<?php
    session_start();
    ob_start();
    $link = mysqli_connect("shareddb-w.hosting.stackcp.net", "secret_diary-313439ead8", "4rhxvorftw", "secret_diary-313439ead8");
    if(mysqli_connect_errno()){
        print_r(mysqli_connect_error());
        exit();
    }
    $contactMessage = "";
    $mssg = "";
    $pass = "jkbjf78686fd8yfbdf867f868nfdd786";
    if($_COOKIE && $_COOKIE['name']){
        $_SESSION['name'] = $_COOKIE['name'];
        $_SESSION['email'] = $_COOKIE['email'];
        
    }
    if($_SESSION && $_SESSION['name']){
        $name = $_SESSION['name'];
        $mssg = '<p style="color:white;font-size:20px;margin-top:7px;margin-right:20px">Hi ' . $name . '</p>';
    } else{
        $_SESSION = Array();
        header('Location: index.php');
    }
    if($_SESSION && ($_SESSION['cpage'] || $_SESSION['dpage'] || $_SESSION['chpage'])){
        $_SESSION['cpage'] = "";
        $_SESSION['dpage'] = "";
        $_SESSION['chpage'] = "";
        unset($_POST);
        header("Location: ".$_SERVER['PHP_SELF']);
    }
    $query = "SELECT diary FROM users WHERE email ='" . mysqli_escape_string($link, $_SESSION['email']) . "'";
    if($result = mysqli_query($link, $query)){
        if($row = mysqli_fetch_array($result)){
            $diaryHave = $row[0];
        }else{
            $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Could not load Diary!! Contact admin</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
        }
    } else{
            $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Could not load Diary!! Contact admin</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';

    }
    if ($_POST && $_POST['contactSubject'] && $_POST['contactText'] && $_POST['contactEmail']){
        $emailTo = 'harshsaini6979@gmail.com';
        $subject = $_POST['contactSubject'];
        $body = $_POST['contactText'];
        $headers = 'From: '. $_POST['contactEmail'];
        
        $query = "SELECT email FROM users WHERE email = '" . mysqli_real_escape_string($link, $_POST['contactEmail']) . "'";
        
        if($result = mysqli_query($link, $query)){
            $row = mysqli_fetch_array($result);
            //echo $row[0];
            //print_r($row);
            if($row[0]){
                if (mail($emailTo, $subject, $body, $headers)){
                    $contactMessage = '<div class="alert alert-dismissible alert-success" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Thanks for your time, We\'ll reply ASAP</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
                } else{
                    $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Could not send Email</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
                }
            }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Email doesn\'t exits in our records</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Email doesn\'t exits in our records</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        $_SESSION['cpage'] = '1';
    }
    if($_POST && $_POST['deleteEmail'] && $_POST['deletePassword']){
        
        $query = "SELECT password, email FROM users WHERE email = '" . mysqli_real_escape_string($link, $_POST['deleteEmail']) . "'";
        
        if($result = mysqli_query($link, $query)){
            if($row = mysqli_fetch_array($result)){
            if($row[0] == md5($pass . $_POST['deletePassword'])){
                $query = "DELETE FROM users WHERE email='" . mysqli_real_escape_string($link, $_POST['deleteEmail']) ."'";
                if(mysqli_query($link, $query)){
                    $_SESSION = Array();
                    setcookie('name', '', time() - 60*60*24*365);
                    $_COOKIE['name'] = '';
                    $_COOKIE['email'] = '';
                    header("Location: index.php");
                
                $contactMessage = '<div class="alert alert-dismissible alert-success" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Your all secrets has been deleted. Refresh to exit!</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
                }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Could not delete the Diary</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
                }
            }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Oops! You entered the wrong password</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Email doesn\'t exits in our records</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Email doesn\'t exits in our records</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        $_SESSION['dpage'] = '1';
    }
    print_r($_POST);
    if($_POST && $_POST['email'] && $_POST['password'] && $_POST['newPassword']){
        
        $query = "SELECT password, email FROM users WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "'";
        
        if($result = mysqli_query($link, $query)){
            if($row = mysqli_fetch_array($result)){
            if($row[0] == md5($pass . $_POST['password'])){
                $query = "UPDATE users SET password = '" . mysqli_real_escape_string($link, md5($pass.$_POST['newPassword'])) . "' WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "'";
                if(mysqli_query($link, $query)){
                $contactMessage = '<div class="alert alert-dismissible alert-success" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Your password is successfully changed!</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
                } else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Oops! could not reset the password. Contact admin</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
                }
            }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Oops! You entered the wrong password</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Email doesn\'t exits in our records</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        }else{
                $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Email doesn\'t exits in our records</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
            }
        $_SESSION['chpage'] = '1';
    } 
    if($_POST && $_POST['text']){
        $query = "UPDATE users SET diary ='" . mysqli_escape_string($link, $_POST['text']) . "' WHERE email = '" . mysqli_escape_string($link, $_SESSION['email']) ."'";
        if(mysqli_query($link, $query)){
                $contactMessage = '<div class="alert alert-dismissible alert-success" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Diary saved!!</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
        } else{
                $contactMessage = '<div class="alert alert-dismissible alert-primary" role="alert" style="width: 40%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Could not save!! Try contacting admin</p><p><center><a href="diary.php">Refresh</a></center><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></p></div>';
        }
    }
    
    
?>
<html>
    <head>
        <title>Diary</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="diarystyle.css">
    </head>
    <body>
        <div class="fixed-top">
            
            <nav class="navbar navbar-dark bg-dark">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class=" my-2 my-sm-0" style="float: right"><? echo $mssg; ?></div>
            </nav>
            <div class="collapse " id="navbarToggleExternalContent">
                <div class="bg-dark p-4" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                          <a href="#" class="nav-link" style="color: white;font-size:22px;" id="diary">Diary</a>
                        </li>
                        <li class="nav-item active">
                          <a href="#" class="nav-link" style="color: white;font-size:22px;" id="changePassword">Change Password</a>
                        </li>
                        <li class="nav-item active">
                          <a href="#" class="nav-link" style="color: white;font-size:22px;" id="about">About</a>
                        </li>
                        <li class="nav-item active">
                          <a href="#" class="nav-link" style="color: white;font-size:22px;" id="contact">Contact</a>
                        </li>
                        <li class="nav-item active">
                          <a href="#" class="nav-link" style="color: white;font-size:22px;" id="deleteDiary">Delete Diary</a>
                        </li>
                        <li class="nav-item active">
                          <a href="index.php?logout=1" class="nav-link" style="color: white;font-size:22px;" name="logout">Log Out</a>
                        </li>
                      </ul>
                  <span  style="position: fixed;bottom: 40px;margin: 0 auto;color:grey;"><em>Created By - </em>Harsh Saini</span>
                </div>
              </div>
              
            <div id="contactMessage"> <? echo $contactMessage ?></div>
          </div>
          
        <div style="margin:95px 35px 35px 35px" class="hide show" id="diaryPane">
            <form id="form" method="post" name="diaryform" id="diaryform">
                <textarea class="form-control" cols="60" rows="5" style="overflow:hidden" name="text" id="text"><? echo $diaryHave?></textarea>
                <button type="submit" class="btn btn-success btn-lg" id="diaryButton" name="diaryButton" style="position:fixed;top:90%;left:46%;border:1px grey solid">Save</button>
            </form>
        </div>
            <div class="hide show" id="changePasswordPane">
                <div class="container menuContainer">
                <h2>Change Password</h2>
                <p>It's great to see you here!</p>
                <hr style="color: black; width:80%">
                <p>Please enter your registered Email</p>
                <form id="form" method="post">
                    <div class="form-group">
                      <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email" data-toggle="tooltip" data-placement="top" title="We require Email to check your authenticity">
                    </div>
                    <p>Please enter your current Password</p>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control" id="password" aria-describedby="emailHelp" placeholder="Current Password" data-toggle="tooltip" data-placement="top" title="We require Password to check your authenticity">
                    </div>
                    <br><br>
                    <p>Please enter New Password</p>
                    <div class="form-group">
                      <input type="password" name="newPassword" class="form-control" id="newPassword" aria-describedby="emailHelp" placeholder="New Password" data-toggle="tooltip" data-placement="top" title="We suggest to create the strong password">
                    </div>
                    <button type="submit" class="btn btn-success" id="changePasswordButton">Change Password</button>
                </form>
                </div>
                
        </div>
        <div class="hide show" id="aboutPane">
            <div class="container menuContainer">
                <h2>About</h2>
                <p>Even owner has the secrets!</p>
                <hr style="color: black; width:80%">
                <p style="text-align:justify;">This is created by <strong>Harsh Saini</strong> and it became possible due to his instructor <strong>Mr Rob Percival</strong>. </p>
                <p style="text-align:justify;">This is a learning project whose motive was to learn how to create a functional Website. In the Diary section, after writing the text you'll need to click Save, otherwise no data will be saved. Also remember to add atleast one letter to the Diary.</p>
                <p style="text-align:justify;">All the information you'll gonna store on this website will be secure forever. We will never share your information with anyone, moreover, all the information is saved with the encryption so even the owner can't access your information. </p>
                <p style="text-align:justify;">If you face any problem, refresh twice. It may solve your problem. Also don't forget to refresh the page on each alert.</p>
            </div>
        </div>
        <div class="hide show" id="contactPane">
            <div class="container menuContainer">
            <h2>Get in Touch!</h2>
            <p>Drop a suggestion or appreciation or a message!</p>
            <hr style="color: black; width:80%">
            <form method = "post">
                <div class="form-group">
                  <label for="email">Please enter your Email address</label>
                  <input type="email" name="contactEmail" class="form-control" id="contactEmail" aria-describedby="emailHelp" placeholder="Your Email" data-toggle="tooltip" data-placement="top" title="We require Email to reply back to you">
                </div>
                <div class="form-group">
                  <label for="subject">Please enter Subject of the Email</label>
                  <input type="text" name="contactSubject" class="form-control" id="ContactSubject" placeholder="Subject" data-toggle="tooltip" data-placement="top" title="We require Subject to filter out the Emails">
                </div>
                <div class="form-group">
                    <label for="contactText">What would you like to ask us?</label>
                    <textarea class="form-control" name="contactText" id="contactText" rows="3" style="overflow:hidden"></textarea>
                </div>
                <button type="submit" class="btn btn-success" id="contactButton">Send</button>
              </form>
            </div>
        </div>
        <div class="hide show" id="deleteDiaryPane">
            <div class="container menuContainer">
                <h2>Delete Diary</h2>
                <p>It's Always hard to delete own secrets!</p>
                <hr style="color: black; width:80%">
                <p>Please enter your registered Email</p>
                <form id="form" method="post">
                    <div class="form-group">
                      <input type="email" name="deleteEmail" class="form-control" id="deleteEmail" aria-describedby="emailHelp" placeholder="Email" data-toggle="tooltip" data-placement="top" title="We require Email to check your authenticity">
                    </div>
                    <p>Please enter your current Password</p>
                    <div class="form-group">
                      <input type="password" name="deletePassword" class="form-control" id="deletePassword" aria-describedby="emailHelp" placeholder="Current Password" data-toggle="tooltip" data-placement="top" title="We require Password to check your authenticity">
                    </div>
                    <br><br>

                    <button type="submit" class="btn btn-success" id="deleteButton">Vanish your Secrets</button>
                </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        
        <script type="text/javascript" src="diaryscript.js">
            
        </script>
    </body>

</html>