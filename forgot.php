<?php
    session_start();
    if($_COOKIE && $_COOKIE['name']){
        $_SESSION['name'] = $_COOKIE['name'];
    }
    if(($_COOKIE && $_COOKIE['name']) || ($_SESSION && $_SESSION['name'])){
        header('Location: index.php');
    }else{
        
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $otp = "";
    function generate_string($input, $strength = 8) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
     
        return $random_string;
    }
    $link = mysqli_connect("shareddb-w.hosting.stackcp.net", "secret_diary-313439ead8", "4rhxvorftw", "secret_diary-313439ead8");
    $errorMess = "";
    if($_POST && $_POST['email'] && !$_POST['otp']){
        $emailTo = $_POST['email'];
        $subject = "Secret Diary | OTP Generator";
        $otp =  generate_string("0123456789", 4);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $emailTo;
        $body = 'We have received a request from your end to send the OTP for the Secret Diary.

Your OTP: "' . $otp . '"';
        $headers = "From: secret-diary-com@stackstaging.com";

        $query = "SELECT name FROM users WHERE email = '" . mysqli_real_escape_string($link, $emailTo) . "'";
        if($result = mysqli_query($link, $query)){
            if($row = mysqli_fetch_array($result)){
                if($row){
                    if (mail($emailTo, $subject, $body, $headers)){
                        $errorMess = '<div class="alert alert-success" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>OTP sent</p></div>';
                    } else{
                        $errorMess = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>OTP could not sent.</p></div>';
                    }
                } 
                
            } else{
                $errorMess = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>Incorrect Email</p></div>';
            }
            
        } 
    }

    if ($_POST && $_POST['otp'] && !$_POST['email']){
        $userotp = $_POST['otp'];
        if($userotp == $_SESSION['otp']){
            $newpassword = generate_string($permitted_chars);
            $pass = "jkbjf78686fd8yfbdf867f868nfdd786";
            $query = "UPDATE users SET password = '" . md5($pass . $newpassword) .  "' WHERE email = '" . mysqli_real_escape_string($link, $_SESSION['email']) . "'";
            if (mysqli_query($link, $query)){
                $emailTo = $_SESSION['email'];
                $subject = "Secret Diary | New Password";
                $body = 'Your new password for Secret Diary is: '. $newpassword;
                $headers = 'From: secret-diary-com@stackstaging.com';
                if (mail($emailTo, $subject, $body, $headers)){
                    $errorMess = '<div class="alert alert-success" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>OTP Verified</p><p>New Password has been sent on your email</p></div>';
                } else{
                    $errorMess = '<div class="alert alert-warning" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>Could not update password, contact Admin</p></div>';
                }
            }else{
                $errorMess = '<div class="alert alert-warning" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>Could not update password, contact Admin</p></div>';
            }




        } else{
            $errorMess = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>Incorrect OTP</p></div>';
        }
        $_SESSION['otp'] = '';
        $_SESSION['email'] = '';
        $_SESSION['page'] = '1';
    }

    }
    unset($_POST);
    unset($_REQUEST);
//header('Location: ...');
    //unset($_POST);
    //header("Location: ".$_SERVER['PHP_SELF']);
?>

<html>
    <head>
        <title>Fogot Password</title>
        <link rel="stylesheet" type="text/css" href="forgotstyle.css">        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <div class="container" id="container">
            <h2>Forgot Password</h2>
            <p>Don't panic, We are here to solve your problem</p>
            <hr style="color: black; width:80%">
            <div><? echo ($errorMess); ?></div>
            <p>Please enter your registered Email</p>
            <form id="form" method="post">
                <div class="form-group">
                  <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter Your Email" data-toggle="tooltip" data-placement="top" title="Enter your Email you used to Sign Up">
                </div>
                <button type="submit" class="btn btn-primary" id="sendotpbutton">Send OTP</button>
            </form>
            <br><br>
            <p>Please enter the OTP</p>
            <form id="form" method="post">
                <div class="form-group">
                  <input type="password" name="otp" class="form-control" id="otp" aria-describedby="emailHelp" placeholder="Enter OTP" data-toggle="tooltip" data-placement="top" title="You would have recieve the OTP on your Email">
                </div>
                <button type="submit" class="btn btn-success" id="verifybutton">Verify</button>
            </form>
        <br>
        <a href="index.php" style="color:white;margin-top:25px">Sign Up/ Log In</a>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $('[data-toggle="tooltip"]').tooltip();
        </script>
    </body>
</html>