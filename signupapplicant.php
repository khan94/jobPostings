<html>
<head>
  <style>
  .error {color: #FF0000;}
  </style>
  <title>Applicant Signup</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>

<?php
// ================CONNECT===================
// connect to oracle db
$conn = oci_connect("ora_r8z8", "a35028125", "ug");
// ==========================================

// ================APPLICANT=================
// set default value to variables
$applogin = $aname = $apassword = $aemail = $aaddress = "";
$aregistrationerr = $apploginerr = $asinerr = $anameerr = $aphonenumbererr = $apassworderr = $aconfirmpassworderr = $aemailerr = "";
$asin = $aphonenumber = 0;
$isvalid = 0;
$isregistered = 0;
$hastried = 0;

// check if form was submitted, then check the format of each input
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // isvalid is set to 0 if any input is in the wrong format
  $isvalid = 1;

  // hastried is set to 1 to begin displaying error messages
  $hastried = 1;

  // check format of applogin (required)
  if (empty($_POST['applogin'])) {
    $apploginerr = "Login is required.";
    $isvalid = 0;
  } else {
    $applogin = check_input($_POST['applogin']);
    // check if applogin uses valid characters
    if (!preg_match("/^[-.,:;!?#=%&$+@a-z_A-Z0-9 ]*$/",$applogin)) {
      $apploginerr = "Only letters, numbers, white space, and common symbols(-.,:;!?#=%&$+@) are allowed";
      $isvalid = 0;
    }
  }

  // check format of asin (optional, but if not empty has to be all numbers)
  // no input on asin is valid
  if (empty($_POST['asin'])) {
    $asin = 0;
  } elseif (!ctype_digit($_POST['asin'])) {
    // input was not empty, but was not all digits
    $asinerr = "Invalid SIN.";
    $isvalid = 0;
  } else {
    // input was all digits
    $asin = check_input($_POST['asin']);
  }

  // check format of aname (required)
  if (empty($_POST['aname'])) {
    $anameerr = "Name is required.";
    $isvalid = 0;
  } else {
      $checkaname = check_input($_POST['aname']);
      // check if name only has letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$checkaname)) {
      $anameerr = "Only letters and white space allowed";
      $isvalid = 0;
      } else {
          $aname = check_input($_POST['aname']);
        }
  }

  // check format of aphonenumber (optional, but if not empty has to be all numbers)
  // no input on aphonenumber is valid
  if (empty($_POST['aphonenumber'])) {
    $aphonenumber = 0;
  } elseif (!ctype_digit($_POST['aphonenumber'])) {
    // input was not empty, but was not all digits
    $aphonenumbererr = "Invalid phone number.";
    $isvalid = 0;
  } else {
      // input was all digits
      $aphonenumber = check_input($_POST['aphonenumber']);
  }

  // check format of apassword (required)
  if (empty($_POST['apassword'])) {
    $apassworderr = "Password is required.";
    $isvalid = 0;
  } else {
    $apassword = check_input($_POST['apassword']);
    // check if apassword uses valid characters
    if (!preg_match("/^[-.,:;!?#=%&$+@a-z_A-Z0-9 ]*$/",$apassword)) {
      $apassworderr = "Only letters, numbers, white space, and common symbols(-.,:;!?#=%&$+@) are allowed";
      $isvalid = 0;
    }
  }

  // check if aconfirmpassword (required) matches password
  $aconfirmpassword = check_input($_POST['aconfirmpassword']);
  if (strcmp($apassword, $aconfirmpassword) !== 0) {
    $isvalid = 0;
    $apassworderr = "Passwords did not match.";
    $aconfirmpassworderr = "Passwords did not match.";
  }

  // check format of aemail (required)
  if (empty($_POST["aemail"])) {
  $aemailerr = "Email is required.";
  $isvalid = 0;
  } else {
     $checkaemail = check_input($_POST["aemail"]);
     // check if e-mail address is well-formed
     if (!filter_var($checkaemail, FILTER_VALIDATE_EMAIL)) {
       $aemailerr = "Invalid email format.";
       $isvalid = 0;
     } else {
         $aemail = check_input($_POST["aemail"]);
       }
  }
  
  // aaddress is optional, no restrictions on input
  $aaddress = check_input($_POST['aaddress']);
}

// removes whitespace, slashes, hsc
function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
// ==========================================

// ============APPLICANT QUERIES=============
// queries for applicant insertions
$applicantsquery = "INSERT INTO APPLICANTS(app_login,sin,name,phone_number,password,email,address) VALUES('$applogin','$asin','$aname','$aphonenumber','$apassword','$aemail','$aaddress')";

// query for check if app_login already exists
// $app_logincount > 0 if app_login already exists
$checkapp_loginquery = "SELECT app_login FROM APPLICANTS WHERE app_login='$applogin'";
$checkapplogin = oci_parse($conn,$checkapp_loginquery);
oci_execute($checkapplogin);
$app_logincount = oci_fetch_all($checkapplogin,$res);

// try to register as an APPLICANT
// check if app_login is unique, and if all inputs are valid
if (($app_logincount == 0) && ($isvalid == 1)) {
  // insert into applicants
  $applicantsinsert = oci_parse($conn,$applicantsquery);
  oci_execute($applicantsinsert);
  $isregistered = 1;
} else {
    if ($hastried == 1) {
      $aregistrationerr = "Registration failed, please re-enter your information.";
    }
    $isregistered = 0;
  }
// ==========================================

// =============CLOSE CONNECTION=============
oci_close($con);

if ($isregistered == 1) {
  header("Location: http://www.ugrad.cs.ubc.ca/~r8z8/index.php");
}
//===========================================
?>

    <header class="wrapper clearfix">
        <div class="center">
            <h1>
                <img src="http://i.imgur.com/g7pWpmE.gif" style="width:72px;height:100px;">
                <img src="http://i.imgur.com/I3EZm6t.gif" style="width:40px;height:93px;">
                <span class="title">DATABASIC BISHES</span>
                <img src="http://i.imgur.com/I3EZm6t.gif" style="width:40px;height:93px;">
                <img src="http://i.imgur.com/g7pWpmE.gif" style="width:72px;height:100px;">
            </h1>
        </div>
    </header>
    <p>Already have an account?<a class="btn" href="http://www.ugrad.cs.ubc.ca/~r8z8/index.php">Sign In to your account!</a>
      <br>
      Not an applicant?<a class="btn" href="http://www.ugrad.cs.ubc.ca/~r8z8/signupemployer.php">Sign Up as an Employer!</a></p>
      <div class="left">
            <h1>
                <img src="http://i.imgur.com/lfS9Gu1.gif" style="width:72px;height:100px;">
                <img src="http://i.imgur.com/g7pWpmE.gif" style="width:72px;height:100px;">
                <img src="http://i.imgur.com/FNq86G1.gif" style="width:40px;height:40px;">
                <span class="title">Applicant Registration</span>
                <img src="http://i.imgur.com/FNq86G1.gif" style="width:40px;height:40px;">
                <img src="http://i.imgur.com/lfS9Gu1.gif" style="width:72px;height:100px;">
                <img src="http://i.imgur.com/g7pWpmE.gif" style="width:72px;height:100px;">
            </h1>
        </div>
      <!--<h3>Applicant Registration</h3>-->
    <div>
      <p><span class="error"><?php echo $aregistrationerr;?></span><p>
      <p><span class="error">* required field.</span></p>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Login: <input type="text" name="applogin">
        <span class="error">* <?php echo $apploginerr;?></span>
        <br><br>
        Password:
        <input type="password" name="apassword">
        <span class="error">* <?php echo $apassworderr;?></span>
        <br><br>
        Confirm Password:
        <input type="password" name="aconfirmpassword">
        <span class="error">* <?php echo $aconfirmpassworderr;?></span>
        <br><br>
        Name:
        <input type="text" name="aname">
        <span class="error">* <?php echo $anameerr;?></span>
        <br><br>
        Address:
        <input type="text" name="aaddress">
        <br><br>
        SIN:
        <input type="text" name="asin">
        <span class="error"> <?php echo $asinerr;?></span>
        <br><br>
        Phone Number:
        <input type="text" name="aphonenumber">
        <span class="error"> <?php echo $aphonenumbererr;?></span>
        <br><br>
        Email:
        <input type="text" name="aemail">
        <span class="error">* <?php echo $aemailerr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Register">
      </form> 
    </div>

</html>