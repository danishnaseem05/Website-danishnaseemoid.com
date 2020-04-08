<?php

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LdC_ecUAAAAAKypRop9v8pUtRqvLu3yCu7JrM8K';
    $recaptcha_response = $_POST['recaptcha_response'];

    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
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
    if($recaptcha-> score >= 0.5){
      // Verified - send email
      mail($to,$email_subject,$email_body,$headers);
      header("location: ../../site_pages/success.php");
    }
    else{
      // Not verfied - show form error
      echo '<script> alert ("Incorrect reCAPTCHA response")</script>';
      header("location: ../../../index.php");
    }
  }
?>