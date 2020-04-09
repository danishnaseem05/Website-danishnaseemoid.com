<?php

  if (isset($_POST['submit'])) {

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Lf0COgUAAAAAECTfB7n3DZ3yCGtYPX6f9MsHvy5';
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response . '&remoteip=' . $userIP);
    
    $recaptcha = json_decode($recaptcha);

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $message = $_POST["message"];


    $email_from = "mail.danishnaseemoid.com";
    $email_subject = 'New Contact Form Submission';
    $email_body = "First Name: $first_name.\n".
                  "Last Name: $last_name.\n".
                  "Phone: $phone.\n".
                  "Email: $email.\n".
                  "Message: $message.\n"; 

    $to = "danishnaseem05@gmail.com";
    $headers = "From: $email_from \r\n";
    $headers .= "Reply-To: $email \r\n";

    // Take action based on the score returned:
    if($recaptcha->success){
      // Verified - send email
      mail($to,$email_subject,$email_body,$headers);
      header("location: ../../site_pages/success.php");
    } else {
      // Not verfied - show form error
      header("location: ../../site_pages/error.php");
    }
  }
?>