<?php

// This file is the first step in the checkout process.
// It takes and validates the shipping information.
// This script is begun in Chapter 10.
$page_title = 'Register for our newsletter';

// Require the configuration before any PHP code:
require ('./includes/config.inc.php');
include ('./includes/checkout_header.html');

// Require the database connection:
require (MYSQL);

echo "Enter an email for our newsletter!"; 

if ($_SERVER['REQUEST_METHOD']  == 'POST')    {

    // echo $_POST['first_name'];
//    $em = $_POST['Inputemail'];


// Check for an email address:
        if (filter_var($_POST['Inputemail'], FILTER_VALIDATE_EMAIL)) {
                $em = $_POST['Inputemail'];
                //$_SESSION['email'] = $_POST['email'];
         //else {
            //   echo "<span id='errormessage'><br />Your desired EMAIL ADDRESS is not Validated</span>"; 
//$shipping_errors['email'] = 'Please enter a valid email address!';
	//// }
  // echo $_POST['first_name'];
    $em = $_POST['Inputemail'];






    
    
    
     $q ="INSERT INTO `users` (`email`) VALUES ('$em')";
     $r = mysqli_query($dbc, $q);

if($r)
{
    echo "<span ></span>";
    
    
    //*****************************************
    //this puts a redirection on the page for the user to see
    //*****************************************
    
   echo" <p id='errormessage'>
   <br />Registration was successful<br /> 
   You will be redirected to login in 
   <span id='counter'>5</span> second(s).
   </p><script type='text/javascript'>
function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<= 1) {
        location.href = 'index.php';
    }
    i.innerHTML = parseInt(i.innerHTML) - 1;
}
    setInterval(function(){ countdown(); },1000);
    </script>

";

}
else
{
   //nothing

$y ="SELECT * FROM users WHERE email= '$em'";
$z = mysqli_query($dbc, $y);   
    if (  mysqli_num_rows($z) == 1)
{
        echo "<span id='errormessage'><br />Your desired EMAIL ADDRESS is already taken</span>";
}
else
{
    //not issue
}
}
}else {
               echo "<span id='errormessage'><br />Your desired EMAIL ADDRESS is not Validated</span>"; 
//$shipping_errors['email'] = 'Please enter a valid email address!';
         }

}

?>

    <style>
        <?php include 'Styles/RegisterStyle.css';
        ?>

    </style>

    <div id="registerdiv">
        <form id="registerform" action="newsletter.php" method="post">
           
            <label for="lname">Email Address</label>
            <input type="text" name="Inputemail" placeholder="Your Email Address..">

            <input type="submit" name="submit_button" />
        </form>
    </div>


  <?php

// Finish the page:
include ('./includes/footer.html');
?>

