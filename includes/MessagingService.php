<?php
/**
 * MessagingService
 * Handles sending payment confirmation notifications (email) for car rentals.
 *
 * Usage:
 *   $messaging = new MessagingService();
 *   $messaging->sendPaymentConfirmation($data);
 */

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MessagingService
{
    /**
     * Send email using PHPMailer
     */
    private function sendEmail($to, $subject, $body, $plainText = '') {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'infofonepo@gmail.com';
            $mail->Password = 'zaoxwuezfjpglwjb';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            // Recipients
            $mail->setFrom('infofonepo@gmail.com', 'Car Rental Management System');
            $mail->addAddress($to);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            
            if (!empty($plainText)) {
                $mail->AltBody = $plainText;
            }
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    /**
     * Generate payment confirmation email template for car rental (Customer)
     */
    private function getPaymentConfirmationEmailTemplate($clientName, $amount, $transactionId, $paymentMethod, $rentalId) {
        $formattedAmount = number_format($amount, 2);
        $currentDate = date('F j, Y \a\t g:i A');
        $currentYear = date('Y');
        
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Car Rental Payment Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                }
                .header {
                    background-color: #3b82f6;
                    padding: 30px 20px;
                    color: white;
                    text-align: center;
                    border-radius: 8px 8px 0 0;
                }
                .header h2 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: bold;
                }
                .content {
                    padding: 30px 20px;
                    background-color: #ffffff;
                    border: 1px solid #e0e0e0;
                    border-top: none;
                }
                .success-icon {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .success-icon .checkmark {
                    display: inline-block;
                    width: 60px;
                    height: 60px;
                    background-color: #10b981;
                    border-radius: 50%;
                    position: relative;
                    margin-bottom: 15px;
                }
                .success-icon .checkmark::after {
                    content: '✓';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    color: white;
                    font-size: 30px;
                    font-weight: bold;
                }
                .message {
                    background-color: #f0f9ff;
                    padding: 20px;
                    border-left: 4px solid #3b82f6;
                    margin-bottom: 25px;
                    border-radius: 0 4px 4px 0;
                }
                .message h3 {
                    margin-top: 0;
                    color: #3b82f6;
                    font-size: 24px;
                }
                .payment-details {
                    background-color: #f0fdf4;
                    padding: 20px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                    border: 1px solid #bbf7d0;
                }
                .payment-details h4 {
                    margin-top: 0;
                    color: #059669;
                    font-size: 18px;
                    border-bottom: 2px solid #059669;
                    padding-bottom: 10px;
                }
                .detail-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 12px;
                    padding: 8px 0;
                    border-bottom: 1px solid #dcfce7;
                }
                .detail-row:last-child {
                    border-bottom: none;
                    margin-bottom: 0;
                }
                .detail-label {
                    font-weight: bold;
                    color: #374151;
                    flex: 1;
                }
                .detail-value {
                    color: #111827;
                    font-weight: 500;
                    text-align: right;
                    flex: 1;
                }
                .amount {
                    font-size: 28px;
                    font-weight: bold;
                    color: #059669;
                }
                .status-completed {
                    color: #059669;
                    font-weight: bold;
                    background-color: #dcfce7;
                    padding: 4px 12px;
                    border-radius: 20px;
                    font-size: 12px;
                    text-transform: uppercase;
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 2px solid #e5e7eb;
                    font-size: 12px;
                    color: #6b7280;
                    line-height: 1.4;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>🚗 Car Rental Payment Confirmation</h2>
                    <p>Smart Car Rental Management System</p>
                </div>
                
                <div class="content">
                    <div class="success-icon">
                        <div class="checkmark"></div>
                    </div>
                    
                    <div class="message">
                        <h3>Payment Successful!</h3>
                        <p>Dear <strong>{$clientName}</strong>,</p>
                        <p>We are pleased to confirm that your car rental payment has been processed successfully. Your booking is now confirmed!</p>
                    </div>
                    
                    <div class="payment-details">
                        <h4>🧾 Transaction Details</h4>
                        <div class="detail-row">
                            <span class="detail-label">Amount Paid:</span>
                            <span class="detail-value amount">RWF {$formattedAmount}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Payment Method:</span>
                            <span class="detail-value">{$paymentMethod}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Transaction ID:</span>
                            <span class="detail-value">{$transactionId}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Rental ID:</span>
                            <span class="detail-value">#{$rentalId}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Date & Time:</span>
                            <span class="detail-value">{$currentDate}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value"><span class="status-completed">Confirmed</span></span>
                        </div>
                    </div>
                    
                    <p style="text-align: center; font-size: 16px; color: #059669; font-weight: bold;">
                        🎉 Thank you for choosing our car rental service!
                    </p>
                    
                    <p style="text-align: center; font-size: 14px; color: #6b7280; margin-top: 20px;">
                        You will receive pickup instructions and car details shortly. Safe travels!
                    </p>
                </div>
                
                <div class="footer">
                    <p><strong>This is an automated message from your Car Rental Management System.</strong></p>
                    <p>&copy; {$currentYear} Smart Car Rental Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
HTML;
    }

    /**
     * Generate admin notification email template for car rental payment
     */
    private function getAdminNotificationEmailTemplate($clientName, $clientEmail, $amount, $transactionId, $paymentMethod, $rentalId) {
        $formattedAmount = number_format($amount, 2);
        $currentDate = date('F j, Y \a\t g:i A');
        $currentYear = date('Y');
        
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>New Car Rental Payment Received</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                }
                .header {
                    background-color: #059669;
                    padding: 30px 20px;
                    color: white;
                    text-align: center;
                    border-radius: 8px 8px 0 0;
                }
                .header h2 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: bold;
                }
                .content {
                    padding: 30px 20px;
                    background-color: #ffffff;
                    border: 1px solid #e0e0e0;
                    border-top: none;
                }
                .alert-icon {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .alert-icon .notification {
                    display: inline-block;
                    width: 60px;
                    height: 60px;
                    background-color: #f59e0b;
                    border-radius: 50%;
                    position: relative;
                    margin-bottom: 15px;
                }
                .alert-icon .notification::after {
                    content: '💰';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 24px;
                }
                .message {
                    background-color: #fef3c7;
                    padding: 20px;
                    border-left: 4px solid #f59e0b;
                    margin-bottom: 25px;
                    border-radius: 0 4px 4px 0;
                }
                .message h3 {
                    margin-top: 0;
                    color: #92400e;
                    font-size: 24px;
                }
                .payment-details {
                    background-color: #f0fdf4;
                    padding: 20px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                    border: 1px solid #bbf7d0;
                }
                .payment-details h4 {
                    margin-top: 0;
                    color: #059669;
                    font-size: 18px;
                    border-bottom: 2px solid #059669;
                    padding-bottom: 10px;
                }
                .detail-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 12px;
                    padding: 8px 0;
                    border-bottom: 1px solid #dcfce7;
                }
                .detail-row:last-child {
                    border-bottom: none;
                    margin-bottom: 0;
                }
                .detail-label {
                    font-weight: bold;
                    color: #374151;
                    flex: 1;
                }
                .detail-value {
                    color: #111827;
                    font-weight: 500;
                    text-align: right;
                    flex: 1;
                }
                .amount {
                    font-size: 28px;
                    font-weight: bold;
                    color: #059669;
                }
                .status-completed {
                    color: #059669;
                    font-weight: bold;
                    background-color: #dcfce7;
                    padding: 4px 12px;
                    border-radius: 20px;
                    font-size: 12px;
                    text-transform: uppercase;
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 2px solid #e5e7eb;
                    font-size: 12px;
                    color: #6b7280;
                    line-height: 1.4;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>💰 New Payment Received</h2>
                    <p>Car Rental Management System - Admin Alert</p>
                </div>
                
                                <div class="content">
                    <div class="alert-icon">
                        <div class="notification"></div>
                    </div>
                    
                    <div class="message">
                        <h3>New Payment Received!</h3>
                        <p>Hello Admin,</p>
                        <p>A new car rental payment has been successfully processed. Please review the details below:</p>
                    </div>
                    
                    <div class="payment-details">
                        <h4>🧾 Payment Details</h4>
                        <div class="detail-row">
                            <span class="detail-label">Customer Name:</span>
                            <span class="detail-value">{$clientName}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Customer Email:</span>
                            <span class="detail-value">{$clientEmail}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Amount Received:</span>
                            <span class="detail-value amount">RWF {$formattedAmount}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Payment Method:</span>
                            <span class="detail-value">{$paymentMethod}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Transaction ID:</span>
                            <span class="detail-value">{$transactionId}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Rental ID:</span>
                            <span class="detail-value">#{$rentalId}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Date & Time:</span>
                            <span class="detail-value">{$currentDate}</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value"><span class="status-completed">Confirmed</span></span>
                        </div>
                    </div>
                    
                    <p style="text-align: center; font-size: 16px; color: #059669; font-weight: bold;">
                        📊 Please process the rental booking accordingly.
                    </p>
                </div>
                
                <div class="footer">
                    <p><strong>This is an automated admin notification from your Car Rental Management System.</strong></p>
                    <p>&copy; {$currentYear} Smart Car Rental Management System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
HTML;
    }

    /**
     * Send payment confirmation via email to customer and admin
     * @param array $data [email, phone, name, amount, payment_method, transaction_id, rental_id, admin_email]
     * @return array
     */
    public function sendPaymentConfirmation($data)
    {
        try {
            error_log("=== SENDING CAR RENTAL PAYMENT CONFIRMATION ===");
            error_log("Payment data: " . json_encode($data));
            
            $clientName = $data['name'] ?? 'Customer';
            $clientEmail = $data['email'] ?? '';
            $adminEmail = $data['admin_email'] ?? '';
            $amount = $data['amount'] ?? 0;
            $paymentMethod = $data['payment_method'] ?? 'Card';
            $transactionId = $data['transaction_id'] ?? '';
            $rentalId = $data['rental_id'] ?? '';
            
            $customerEmailSent = false;
            $adminEmailSent = false;
            
            // Send Email to Customer
            if (!empty($clientEmail)) {
                error_log("Sending customer email to: " . $clientEmail);
                
                $subject = "Car Rental Payment Confirmation - Booking #{$rentalId}";
                $htmlMessage = $this->getPaymentConfirmationEmailTemplate(
                    $clientName, 
                    $amount, 
                    $transactionId, 
                    $paymentMethod,
                    $rentalId
                );
                
                $plainTextMessage = "
Car Rental Payment Confirmation

Dear {$clientName},

Your car rental payment has been processed successfully!

Transaction Details:
- Amount: RWF " . number_format($amount, 2) . "
- Payment Method: {$paymentMethod}
- Transaction ID: {$transactionId}
- Rental ID: #{$rentalId}
- Date: " . date('F j, Y \a\t g:i A') . "
- Status: CONFIRMED

Thank you for choosing our car rental service!

Best regards,
Smart Car Rental Management System
                ";
                
                $customerEmailSent = $this->sendEmail($clientEmail, $subject, $htmlMessage, $plainTextMessage);
                error_log("Customer email sent: " . ($customerEmailSent ? 'YES' : 'NO'));
            }
            
            // Send Email to Admin
            if (!empty($adminEmail)) {
                error_log("Sending admin notification email to: " . $adminEmail);
                
                $adminSubject = "New Car Rental Payment Received - Booking #{$rentalId}";
                $adminHtmlMessage = $this->getAdminNotificationEmailTemplate(
                    $clientName,
                    $clientEmail,
                    $amount, 
                    $transactionId, 
                    $paymentMethod,
                    $rentalId
                );
                
                $adminPlainTextMessage = "
New Car Rental Payment Received

Hello Admin,

A new car rental payment has been successfully processed.

Payment Details:
- Customer: {$clientName} ({$clientEmail})
- Amount: RWF " . number_format($amount, 2) . "
- Payment Method: {$paymentMethod}
- Transaction ID: {$transactionId}
- Rental ID: #{$rentalId}
- Date: " . date('F j, Y \a\t g:i A') . "
- Status: CONFIRMED

Please process the rental booking accordingly.

Best regards,
Car Rental Management System
                ";
                
                $adminEmailSent = $this->sendEmail($adminEmail, $adminSubject, $adminHtmlMessage, $adminPlainTextMessage);
                error_log("Admin email sent: " . ($adminEmailSent ? 'YES' : 'NO'));
            }
            
            return [
                'success' => true,
                'customer_email_sent' => $customerEmailSent,
                'admin_email_sent' => $adminEmailSent,
                'sms_sent' => false, // SMS disabled
                'message' => 'Payment confirmation emails processed successfully'
            ];
            
        } catch (Exception $e) {
            error_log("Error sending payment confirmation: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error sending email notifications: ' . $e->getMessage()
            ];
        }
    }
}
