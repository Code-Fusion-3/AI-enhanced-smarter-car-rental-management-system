<?php
require_once 'models/User.php';

class UserController {
    private $db;
    private $user;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->user = new User($conn);
    }
    
    public function handle() {
        if (!isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit();
        }
        
        $action = isset($_GET['action']) ? $_GET['action'] : 'view';
        
        switch($action) {
            case 'view':
                $this->viewProfile();
                break;
            case 'edit':
                $this->editProfile();
                break;
            case 'update':
                $this->updateProfile();
                break;
            case 'password':
                $this->changePassword();
                break;
            case 'favorites':
                $this->viewFavorites();
                break;
            default:
                $this->viewProfile();
        }
    }
    
    private function viewProfile() {
        $userId = $_SESSION['user_id'];
        $userData = $this->user->getUserById($userId);
        
        // Get rental history
        $rentalQuery = "SELECT r.*, c.make, c.model, c.image_url 
                        FROM rentals r 
                        JOIN cars c ON r.car_id = c.car_id 
                        WHERE r.user_id = ? 
                        ORDER BY r.created_at DESC 
                        LIMIT 5";
        $stmt = $this->db->prepare($rentalQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $recentRentals = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Get upcoming rentals
        $upcomingQuery = "SELECT r.*, c.make, c.model, c.image_url 
                          FROM rentals r 
                          JOIN cars c ON r.car_id = c.car_id 
                          WHERE r.user_id = ? AND r.status IN ('pending', 'approved') 
                          AND r.start_date >= CURDATE() 
                          ORDER BY r.start_date ASC";
        $stmt = $this->db->prepare($upcomingQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $upcomingRentals = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Get notifications
        $notifQuery = "SELECT * FROM notifications 
                       WHERE user_id = ? 
                       ORDER BY created_at DESC 
                       LIMIT 10";
        $stmt = $this->db->prepare($notifQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        require 'views/profile/view.php';
        exit();
    }
    
    private function editProfile() {
        $userId = $_SESSION['user_id'];
        $userData = $this->user->getUserById($userId);
        
        require 'views/profile/edit.php';
        exit();
    }
    
    private function updateProfile() {
        $userId = $_SESSION['user_id'];
        
        // Validate and sanitize input
        $fullName = trim($_POST['full_name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $driverLicense = trim($_POST['driver_license']);
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format";
            header('Location: index.php?page=profile&action=edit');
            exit();
        }
        
        // Check if email is already in use by another user
        $checkEmailQuery = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
        $stmt = $this->db->prepare($checkEmailQuery);
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['error'] = "Email is already in use by another account";
            header('Location: index.php?page=profile&action=edit');
            exit();
        }
        
        // Handle profile image upload
        $profileImage = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_image']['name'];
            $fileTmpName = $_FILES['profile_image']['tmp_name'];
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            // Validate file extension
            if (in_array($fileExt, $allowed)) {
                // Generate unique filename
                $newFilename = 'profile_' . $userId . '_' . time() . '.' . $fileExt;
                $uploadPath = 'assets/images/profiles/' . $newFilename;
                
                // Create directory if it doesn't exist
                if (!file_exists('assets/images/profiles/')) {
                    mkdir('assets/images/profiles/', 0777, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($fileTmpName, $uploadPath)) {
                    $profileImage = $uploadPath;
                }
            }
        }
        
        // Update user data
        $updateQuery = "UPDATE users SET 
                        full_name = ?, 
                        email = ?, 
                        phone = ?, 
                        address = ?, 
                        driver_license = ?";
        
        $params = [$fullName, $email, $phone, $address, $driverLicense];
        $types = "sssss";
        
        if ($profileImage) {
            $updateQuery .= ", profile_image = ?";
            $params[] = $profileImage;
            $types .= "s";
        }
        
        $updateQuery .= " WHERE user_id = ?";
        $params[] = $userId;
        $types .= "i";
        
        $stmt = $this->db->prepare($updateQuery);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update profile: " . $this->db->error;
        }
        
        header('Location: index.php?page=profile');
        exit();
    }
    
    private function changePassword() {
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Validate input
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = "All password fields are required";
                require 'views/profile/password.php';
                return;
            }
            
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "New passwords do not match";
                require 'views/profile/password.php';
                return;
            }
            
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters long";
                require 'views/profile/password.php';
                return;
            }
            
            // Verify current password
            $query = "SELECT password FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($currentPassword, $user['password'])) {
                    // Update password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE users SET password = ? WHERE user_id = ?";
                    $stmt = $this->db->prepare($updateQuery);
                    $stmt->bind_param("si", $hashedPassword, $userId);
                    
                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Password updated successfully";
                        header('Location: index.php?page=profile');
                        exit();
                    } else {
                        $_SESSION['error'] = "Failed to update password: " . $this->db->error;
                    }
                } else {
                    $_SESSION['error'] = "Current password is incorrect";
                }
            } else {
                $_SESSION['error'] = "User not found";
            }
        }
        
        require 'views/profile/password.php';
    }
    
    private function viewFavorites() {
        $userId = $_SESSION['user_id'];
        
        // Get user's favorite cars
        $query = "SELECT c.*, cc.name as category_name, uf.favorite_id 
                  FROM user_favorites uf 
                  JOIN cars c ON uf.car_id = c.car_id 
                  LEFT JOIN car_categories cc ON c.category_id = cc.category_id 
                  WHERE uf.user_id = ? 
                  ORDER BY uf.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $favorites = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        require 'views/profile/favorites.php';
    }
}