<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

function handleLogin($conn)
{
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, email, password FROM users WHERE email = :email AND password = :password";

    try {
      $stmt = $conn->prepare($sql);
      $stmt->execute([':email' => $email, ':password' => $password]);

      if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        echo json_encode(['success' => true]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
      }
    } catch (PDOException $e) {
      echo json_encode(['success' => false, 'message' => 'Login error: ' . $e->getMessage()]);
    }
    exit();
  }
}

function handleSignUp($conn)
{
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
      echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
      exit();
    }

    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";

    try {
      $stmt = $conn->prepare($sql);
      $stmt->execute([
        ':email' => $email,
        ':password' => $password
      ]);

      echo json_encode(['success' => true, 'message' => "Registration successful"]);
    } catch (PDOException $e) {
      if ($e->getCode() == '23000' && strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo json_encode(['success' => false, 'message' => 'This email is already registered']);
      } else {
        echo json_encode(['success' => false, 'message' => 'An error occurred during registration']);
      }
    }
    exit();
  }
}

function handleForgotPassword($conn)
{
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $current_password = $row['password'];

      $mail = new PHPMailer(true);

      try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tcgplayes@gmail.com';
        $mail->Password   = 'lome hund houv hidj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('noreply@tcgplayer.com', 'Team TCGplayer');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'TCGplayer.com: Your Password';

        $emailTemplate = file_get_contents('../../views/login/email_template.html');
        $emailTemplate = str_replace('{CURRENT_PASSWORD}', $current_password, $emailTemplate);

        $mail->Body = $emailTemplate;
        $mail->AltBody = "Your current password is: $current_password";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
      } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error sending email']);
      }
    } else {
      echo json_encode(['success' => false, 'message' => 'Email not found in the system']);
    }
    exit();
  }
}
