<?php
    
    session_start();
    error_reporting (E_ALL ^ E_NOTICE);
    if($_GET && $_GET['logout']){
        //unset($_SESSION);
        $_SESSION = Array();
        setcookie('name', '', time() - 60*60*24*365);
        $_COOKIE['name'] = '';
        $_COOKIE['email'] = '';
    }
    //echo($_SESSION['name']);
    if(($_SESSION && $_SESSION['name']) || $_COOKIE && $_COOKIE['name']){
        header("Location: diary.php");
    } else{
        
    $link = mysqli_connect("shareddb-w.hosting.stackcp.net", "secret_diary-313439ead8", "4rhxvorftw", "secret_diary-313439ead8");
    if(mysqli_connect_error()){
        die ("SQL connection could not established!!");
    }
    $pass = "jkbjf78686fd8yfbdf867f868nfdd786";
    $error = "";
    
    if ($_POST && ($_POST['name'] || $_POST['email'] || $_POST['password'])){
        //Sign Up
        $error = "";
        if(!$_POST['name'] || !$_POST['email'] || !$_POST['password']){
            $error = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;">Oops!! You filled something wrong</div>';
        } else{
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = md5($pass . $_POST['password']);
            $query = "INSERT INTO users (name, email, password) VALUES('" . mysqli_real_escape_string($link, $name) . "', '" . mysqli_real_escape_string($link, $email) . "', '" . mysqli_real_escape_string($link, $password) . "')";
            if(mysqli_query($link, $query)){
                //$query = "UPDATE users SET password ='" . md5(md5(mysqli_insert_id($link)) . $password) . "' WHERE id ='" . mysqli_insert_id($link) . "'";
                //mqsqli_query($link, $query);

                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                if($_POST['rem'] && $_POST['rem'] == '1'){
                    setcookie('name', $name, time() + 60*60*24*365);
                }
                header("Location: diary.php");
            } else{
                $error = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;">This Email is already registered, Try using Log In</div>';
            }
        }
        
    }
    if($_POST && ($_POST['lemail'] || $_POST['lpassword'])){
        $error = "";
        if(!$_POST['lemail'] || !$_POST['lpassword']){
            $error = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;">Oops!! You filled something wrong</div>';
        } else{

            $query = "SELECT password, name, email FROM users WHERE email = '" . mysqli_real_escape_string($link, $_POST['lemail']) . "'";

            $email = $_POST['lemail'];
            
            if($result = mysqli_query($link, $query)){
                if($row = mysqli_fetch_array($result)){
                $password = md5($pass . $_POST['lpassword']);
                if ($password == $row[0]){
                    $_SESSION['name'] = $row[1];
                    $_SESSION['email'] = $email;
                    if($_POST['rem'] == '1'){
                        setcookie('name', $row[1], time() + 60*60*24*365);                    
                    }
                    header("Location: diary.php");
                } else{
                    $error = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;">You entered incorrect password</div>';
                }
            } else{
                $error = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;">This Email is not registered yet, Try using Sign Up</div>';
            }
            }else{
                $error = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;">This Email is not registered yet, Try using Sign Up</div>';
            }
        }
    }   
}

?>




<html>
    <head>
        <link rel="stylesheet" type="text/css" href="indexstyle.css">        
        <title>Secret Diary</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>

        <div class="container" id="container">
            <h1 id="heading">Secret Diary</h1>
            <p>Store your thoughts personally and securely</p>
            <p>Let's get started!!</p>
            <div><? echo $error; ?></div>
            <div id="signuptab" class="show">
            <form id="form" method="post">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-toggle="tooltip" data-placement="top" title="Enter your Name to Sign Up">
                </div>
                <div class="form-group">
                  <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Your Email" data-toggle="tooltip" data-placement="top" title="Enter your correct Email to Sign Up">
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control" id="password" placeholder="Password" data-toggle="tooltip" data-placement="top" title="Enter your Password to Sign Up">
                </div>
                <div class="form-group form-check">
                  <input type="checkbox" class="form-check-input" name="rem" id="remember" value="1">
                  <label class="form-check-label" for="remember">Stay Logged In</label>
                </div>
                <button type="submit" class="btn btn-success">Sign Up</button>
                <div id="toggle">
                <a id="login">Log In</a>
            </div>
            </form>
        </div>
            <div id="logintab" class="hide">
            <form id="form" method="post">
                <div class="form-group">
                  <input type="email" name="lemail" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Your Email" data-toggle="tooltip" data-placement="top" title="Enter your Email to Log In">
                </div>
                <div class="form-group">
                  <input type="password" name="lpassword" class="form-control" id="password" placeholder="Password" data-toggle="tooltip" data-placement="top" title="Enter your Password to Log In">
                </div>
                <div class="form-group form-check">
                  <input type="checkbox" class="form-check-input" name="lrem" id="remember" value="1">
                  <label class="form-check-label" for="remember">Stay Logged In</label>
                </div>
                <button type="submit" class="btn btn-success">Log In</button>
            </form>
            <a href="forgot.php" style="color:white;margin-top:25px">Forgot Password</a>
            <br>
            <div id="toggle">
                <a id="signup">Sign Up</a>
            </div>
        </div>
    
        </div>
        <div id="me">
            <p><em>Created by - </em>Harsh Saini</p>
        </div>
        <div style="margin-top:0px;" id="me">
            <a href="contactform.php" style="color:green;margin-top:25px">Contact</a>
        </div>

        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script type="text/javascript" src="indexscript.js">
            
        </script>
    </body>
</html>