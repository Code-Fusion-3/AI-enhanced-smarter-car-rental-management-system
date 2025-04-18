<?php

require_once 'models/User.php';
class AuthController {
    private $db;
    private $user;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->user = new User($conn);
    }
    
    public function handle() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'login';
        
        switch($action) {
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->processLogin();
                } else {
                    require 'views/auth/login.php';
                }
                break;
            case 'register':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->processRegistration();
                } else {
                    require 'views/auth/register.php';
                }
                break;
            case 'logout':
                $this->logout();
                break;
        }
    }

    private function processLogin() {
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        
        if ($this->user->login($username, $password)) {
            header('Location: index.php');
        } else {
            header('Location: index.php?page=auth&action=login&error=1');
        }
        exit();
    }
    
    private function processRegistration() {
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        $email = sanitize($_POST['email']);
        $fullName = sanitize($_POST['full_name']);
        
        if ($this->user->register($username, $password, $email, $fullName)) {
            header('Location: index.php?page=auth&action=login&registered=1');
        } else {
            header('Location: index.php?page=auth&action=register&error=1');
        }
        exit();
    }
    
    private function logout() {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
