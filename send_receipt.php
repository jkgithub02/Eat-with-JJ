<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Add error handling, input validation, etc.

// Retrieve order data (adjust to your method)
$orderData = json_decode($_POST['orderData'], true); 

// Generate Receipt HTML (similar logic from JavaScript)
$receiptHTML = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; } 
        table { width: 60%; margin: 20px auto; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; } 
    </style>
</head>
<body>
    <h1>Thank you for your order from Eat with JJ!</h1>
    <p><strong>Order ID:</strong>  <?php echo $orderData['orderId']; ?></p>
    <p><strong>Customer Name:</strong> <?php echo $orderData['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $orderData['email']; ?></p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach ($orderData['cartItems'] as $item) { 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $item['foodname']; ?></td>
                <td>RM<?php echo $item['price']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>RM<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Total:</td>
                <td>RM<?php echo number_format($total, 2); ?></td> 
            </tr>
        </tfoot>
    </table>

    <p>If you have any questions, please contact us at support@yourdomain.com.</p>
</body>
</html>
'; // Your HTML receipt structure here

// Email Configuration
$recipientEmail = $orderData['email'];
$subject = "Your Order Receipt from Eat with JJ";
$headers = "From: Eat with JJ Orders <joshuatan.kaihooi@gmail.com>\r\n";
$headers .= "MIME-Version: 1.0\r\n"; 
$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 

$mail = new PHPMailer(true); 

try {
    // Configure your mail server settings (SMTP)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'eatwithjj.italian@gmail.com';
    $mail->Password = 'eatwithjj123';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('eatwithjj.italian@gmail.com', 'Eat with JJ Orders'); 
    $mail->addAddress($recipientEmail); 

    $mail->isHTML(true); 
    $mail->Subject = $subject; 
    $mail->Body = $receiptHTML;

    $mail->send();
    header('Content-Type: application/json'); 
    echo json_encode(array('success' => true)); 
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => "Error sending email: {$mail->ErrorInfo}")); 
}
?>