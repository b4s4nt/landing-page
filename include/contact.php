



<?php

if($_POST)
{
    // check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    
        //exit script outputting json data
        $output = json_encode(
        array(
            'type'=>'error', 
            'text' => 'Request must come from Ajax'
        ));
        
        die($output);
    } 

    //check $_POST vars are set, exit if any missing
    if(!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["phone"]) || !isset($_POST["message"]))
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
        die($output);
    }

    //Sanitize input data using PHP filter_var().
    $user_Name        = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $user_Email       = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $user_Phone       = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $user_Message     = filter_var($_POST["message"], FILTER_SANITIZE_STRING);
    
    //additional php validation
    if(strlen($user_Name)<4) // If length is less than 4 it will throw an HTTP error.
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
    if(!is_numeric($user_Phone)) //check entered data is numbers
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Only numbers allowed in phone field'));
        die($output);
    }
    if(strlen($user_Message)<5) //check emtpy message
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
        die($output);
    }
    
    //proceed with PHP email.

    $result = "";
    $error  = "";

    
if(isset($user_Name))

{
    require 'phpmailer/PHPMailerAutoload.php';
    $subject  = 'You have new inquiry from '.$user_Name; //Subject line for emails
    $mail = new PHPMailer;
   // $mail->SMTPDebug = 4; // this is debug script
    
    //smtp settings
    $mail->isSMTP(); // send as HTML
    $mail->Host = "smtp.gmail.com"; // SMTP servers
    $mail->SMTPAuth = true; // turn on SMTP authentication

    $mail->Username = "bedp701@gmail.com"; // Company or email for hosting , this is just gmail made for tes 
    $mail->Password = 'm3r03xtr4gm4!l@***'; // Your password mail, same email password for company email
    $mail->Port = 587; //specify SMTP Port
    
    $mail->SMTPSecure = 'tls';                               
    $mail->setFrom($user_Email,$user_Name);  //  email and  name of the person sending message
    $mail->addAddress('liam@acordsoftware.tech'); //  email of reception. 
    $mail->addCC('basantakandel10@gmail.com');// this is my email for testing , remove at the time of merging.
    $mail->addReplyTo($user_Email,$user_Name); // to reply back
    $mail->isHTML(true);
    $mail -> Subject = 'You have new inquiry from '.$user_Name; //Subject line for email sender.
   // $mail->Subject='Form Submission:' .$_POST['subject'];
    $mail->Body='<h3> Sender Name :'.$user_Name.'<br>Sender Email: '.$user_Email.'<br>Sender Contact:'.$user_Phone.'</h3>'.'<br> <p>Message: '.$user_Message.'</p>';

    if(!$mail->send())
    
    {
        
        $error = "Something went worng. Please try again.";
        echo $error;
        echo "Something went worng. Please try again.";
        
        
    }
    else 
    {
        $result="Thanks\t" .$_POST['name']. " for contacting us.";
        echo "Thanks\t" .$_POST['name']. " for contacting us." ;
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));

       
    }


    
}


}


?>
