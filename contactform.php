<?php
    session_start();
    ob_start();
    $link = mysqli_connect("shareddb-w.hosting.stackcp.net", "secret_diary-313439ead8", "4rhxvorftw", "secret_diary-313439ead8");
    $contactMessage = "";
    $pass = "jkbjf78686fd8yfbdf867f868nfdd786";
    if ($_POST && $_POST['contactSubject'] && $_POST['contactText'] && $_POST['contactEmail']){
        $emailTo = 'harshsaini6979@gmail.com';
        $subject = $_POST['contactSubject'];
        $body = $_POST['contactText'];
        $headers = 'From: '. $_POST['contactEmail'];
        
        if (mail($emailTo, $subject, $body, $headers)){
                    $contactMessage = '<div class="alert alert-dismissible alert-success" role="alert" style="width: 100%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Thanks for your time, We\'ll reply ASAP</p><p><center><a href="index.php" style="color:grey">Back</a></center></p></div>';
                } else{
                    $contactMessage = '<div class="alert alert-dismissible alert-warning" role="alert" style="width: 100%;margin: auto; margin-bottom: 20px; margin-top: 50px;"><p>Could not send Email</p><p><center><a href="index.php" style="color:grey">Back</a></center></p></div>';
                }
    }
?>
<html>
    <head>
        <title>Contact Form</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="diarystyle.css">
    </head>
    <body>
        <div id="contactPane">
            <div class="container menuContainer">
            <h2>Get in Touch!</h2>
            <p>Drop a suggestion or appreciation or a message!</p>
            <hr style="color: black; width:80%">
            <div><? echo $contactMessage ?></div>
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
        
        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $('[data-toggle="tooltip"]').tooltip();
    </body>

</html>