<?php 
  $name = $_POST['name'];
  $visitor_email = $_POST['email'];
  $visitor_subject = $_POST['subject'];
  $message = $_POST['message'];

  $to = "test@gmail.com";
  $email_subject = "New Form Submission";
  $email_body = "User name: $name\n".
                "User Email: $visitor_email\n".
                "Visitor Subject: $visitor_subject\n".
                "User Message: $message\n";
  $headers = "From: $visitor_email\r\n";
  $headers .= "Reply-To: $visitor_email\r\n";

  if(mail($to, $email_subject, $email_body, $headers)){
    echo "<script>alert('Your message has been sent successfully');</script>";
    echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
    exit(); // Stop further execution of the script
  } else {
    echo "Error: Your message could not be sent at this time. Please try again later.";
  }
?>


