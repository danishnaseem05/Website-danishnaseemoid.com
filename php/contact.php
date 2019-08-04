<?php

    // if(isset($_POST['submit'])){
    //     require 'phpmailer/PHPMailerAutoload.php';
    //     $mail = new PHPMailer;

    //     $mail->Host='lion.truehostdns.com';
    //     $mail->Port=465;
    //     $mail->SMTPAuth=true;
    //     $mail->SMTPSecure='tls';
    //     $mail->Username='danishnaseem05@danishnaseemoid.com';
    //     $mail->Password='063096DanNass';

    //     $mail->setFrom($_POST['email'],$_POST['first_name'],$_POST['last_name']);
    //     $mail->addAddress('danishnaseem05@gmail.com');
    //     $mail->addReplyTo($_POST['email'],$_POST['first_name'].$_POST['last_name']);

    //     $mail->isHTML(true);
    //     $mail->Subject='Contact Form Submission from danishnaseemoid.com';
    //     $mail->Body='<h1 align=center>First Name: '.$_POST['first_name'].'<br>Last Name: '.$_POST['last_name'].'<br>Email: '.$_POST['email'].'<br>Phone: '.$_POST['phone'].'<br>Message: '.$_POST['message'].'</h1>';

    //     if(!$mail->send()){
    //         echo"Something went wrong.Please try again."
    //     }
    //     else{
    //         header("location: ../../site_pages/success.html");
    //     }
    // }

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

    mail($to,$email_subject,$email_body,$headers);

    header("location: ../../site_pages/success.html");

?>