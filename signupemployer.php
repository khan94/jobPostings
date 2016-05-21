<html>
<head>
  <style>
  .error {color: #FF0000;}
  body { 
  background: url(http://i.imgur.com/TwCLaZY.png) no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  }
  </style>
  <title>Employer Signup</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>

<body>
<?php

// ================CONNECT===================
// connect to oracle db
$conn = oci_connect("ora_r8z8", "a35028125", "ug");
// ==========================================

// ================EMPLOYER==================
// set default value to variables
$emplogin = $epassword = $econfirmpassword = $cname = $caddress = $ename = $eemail = $eregistrationerr = "";
$cid = $bid = $esin = $ephonenumber = 0;
$isvalid = 0;
$isregistered = 0;
$hastried = 0;

// check if form was submitted, then check the format of each input
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // isvalid is set to 0 if any input is in the wrong format
  $isvalid = 1;

  // hastried is set to 1 to begin displaying error messages
  $hastried = 1;

  // check format of emplogin (required)
  if (empty($_POST['emplogin'])) {
    $emploginerr = "Login is required.";
    $isvalid = 0;
  } else {
    $emplogin = check_input($_POST['emplogin']);
    // check if emplogin uses valid characters
    if (!preg_match("/^[-.,:;!?#=%&$+@a-z_A-Z0-9 ]*$/",$emplogin)) {
      $emploginerr = "Only letters, numbers, white space, and common symbols(-.,:;!?#=%&$+@) are allowed";
      $isvalid = 0;
    }
  }

  // check format of epassword (required)
  if (empty($_POST['epassword'])) {
    $epassworderr = "Password is required.";
    $isvalid = 0;
  } else {
    $epassword = check_input($_POST['epassword']);
    // check if epassword uses valid characters
    if (!preg_match("/^[-.,:;!?#=%&$+@a-z_A-Z0-9 ]*$/",$epassword)) {
      $epassworderr = "Only letters, numbers, white space, and common symbols(-.,:;!?#=%&$+@) are allowed";
      $isvalid = 0;
    }
  }

/*  // check format of econfirmpassword (required)
  if (empty($_POST['econfirmpassword'])) {
    $econfirmpassworderr = ".";
    $isvalid = 0;
  } else {
    $econfirmpassword = check_input($_POST['econfirmpassword']);
    // check if econfirmpassword uses valid characters
    if (!preg_match("/^[-.,:;!?#=%&$+@a-z_A-Z0-9 ]*$/",$econfirmpassword)) {
      $econfirmpassworderr = "Only letters, numbers, white space, and common symbols(-.,:;!?#=%&$+@) are allowed";
      $isvalid = 0;
    }
  }*/

  // check if econfirmpassword (required) matches password
  $econfirmpassword = check_input($_POST['econfirmpassword']);
  if (strcmp($epassword, $econfirmpassword) !== 0) {
    $isvalid = 0;
    $epassworderr = "Passwords did not match.";
    $econfirmpassworderr = "Passwords did not match.";
  }

  // check format of cname (required)
  if (empty($_POST['cname'])) {
    $cnameerr = "Company name is required.";
    $isvalid = 0;
  } else {
      $cname = check_input($_POST['cname']);
      // check if name uses valid characters
      if (!preg_match("/^[-.,:;!?#=%&$+@a-z_A-Z0-9 ]*$/",$cname)) {
      $cnameerr = "Only letters, numbers, white space, and common symbols(-.,:;!?#=%&$+@) allowed";
      $isvalid = 0;
      }
  }

  // caddress is optional, no restrictions on input
  $caddress = check_input($_POST['caddress']);

  // check format of cid (required)
  if (!ctype_digit($_POST['cid'])) {
    $ciderr = "Invalid Company ID.";
    $isvalid = 0;
  } else {
      $cid = check_input($_POST['cid']);
  }

  // check format of bid (required)
  if (!ctype_digit($_POST['bid'])) {
      $biderr = "Invalid Branch ID.";
      $isvalid = 0;
  } else {
      $bid = check_input($_POST['bid']);
  }

  // check format of esin (optional, but if not empty has to be all numbers)
  // no input on esin is valid
  if (empty($_POST['esin'])) {
    $esin = 0;
  } elseif (!ctype_digit($_POST['esin'])) {
    // input was not empty, but was not all digits
    $esinerr = "Invalid SIN.";
    $isvalid = 0;
  } else {
      // input was all digits
      $esin = check_input($_POST['esin']);
  }

  // check format of ename (required)
  if (empty($_POST['ename'])) {
    $enameerr = "Name is required.";
    $isvalid = 0;
  } else {
      $ename = check_input($_POST['ename']);
      // check if name only has letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$ename)) {
      $enameerr = "Only letters and white space allowed";
      $isvalid = 0;
      }
  }

  // check format of ephonenumber (optional, but if not empty has to be all numbers)
  // no input on phone number is valid
  if (empty($_POST['ephonenumber'])) {
    $ephonenumber = 0;
  } elseif (!ctype_digit($_POST['ephonenumber'])) {
      // input was not empty, but was not all digits
      $ephonenumbererr = "Invalid phone number.";
      $isvalid = 0;
  } else {
      // input was all digits
      $ephonenumber = check_input($_POST['ephonenumber']);
  }

  // check format of eemail (required)
  if (empty($_POST["eemail"])) {
  $eemailerr = "Email is required.";
  $isvalid = 0;
  } else {
     $eemail = check_input($_POST["eemail"]);
     // check if e-mail address is well-formed
     if (!filter_var($eemail, FILTER_VALIDATE_EMAIL)) {
       $eemailerr = "Invalid email format.";
       $isvalid = 0;
     }
  }
}

// removes whitespace, slashes, hsc
function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
// ==========================================

// ============EMPLOYER QUERIES==============
// queries for employer insertions
$companiesquery = "INSERT INTO COMPANIES(c_id,company_name) VALUES('$cid','$cname')";
$companylocationsquery = "INSERT INTO COMPANY_LOCATIONS(b_id,c_id,address) VALUES('$bid','$cid','$caddress')";
$employerrunsquery = "INSERT INTO EMPLOYER_RUNS(emp_login,sin,name,phone_number,password,email,address,b_id,c_id) VALUES('$emplogin','$esin','$ename','$ephonenumber','$epassword','$eemail','$caddress','$bid','$cid')";

// query for check if emp_login already exists
// $emp_logincount > 0 if emp_login already exists
$checkemp_loginquery = "SELECT emp_login FROM EMPLOYER_RUNS WHERE emp_login='$emplogin'";
$checkemplogin = oci_parse($conn,$checkemp_loginquery);
oci_execute($checkemplogin);
$emp_logincount = oci_fetch_all($checkemplogin,$res);

// try to register as an EMPLOYER
// check if emp_login is unique, and if inputs are valid
if (($emp_logincount == 0) && ($isvalid == 1)) {
  // insert to companies
  $companiesinsert = oci_parse($conn,$companiesquery);
  oci_execute($companiesinsert);

  // insert to company_locations
  $companylocationsinsert = oci_parse($conn,$companylocationsquery);
  oci_execute($companylocationsinsert);

  // insert to employer_runs
  $employerrunsinsert = oci_parse($conn,$employerrunsquery);
  oci_execute($employerrunsinsert);
  $isregistered = 1;
} else {
    if ($hastried == 1) {
      $eregistrationerr = "Registration failed, please re-enter your information.";
    }
  }
// ==========================================

// =============CLOSE CONNECTION=============
oci_close($con);

if ($isregistered == 1) {
  header("Location: http://www.ugrad.cs.ubc.ca/~r8z8/index.php");
}
// ==========================================
?>

    <header class="wrapper clearfix">
        <div class="center">
            <h1>
                <img src="http://i.imgur.com/lfS9Gu1.gif" style="width:72px;height:100px;">
                <img src="http://i.imgur.com/g7pWpmE.gif" style="width:72px;height:100px;">
                <img src="http://i.imgur.com/I3EZm6t.gif" style="width:40px;height:93px;">
                <span class="title">DATABASIC BISHES</span>
                <img src="http://i.imgur.com/I3EZm6t.gif" style="width:40px;height:93px;">
                <img src="http://i.imgur.com/g7pWpmE.gif" style="width:72px;height:100px;">
                <img src="http://i.imgur.com/lfS9Gu1.gif" style="width:72px;height:100px;">
            </h1>
        </div>
    </header>
    <div>
      <p>Already have an account?<a class="btn" href="http://www.ugrad.cs.ubc.ca/~r8z8/index.php">Sign In to your account!</a>
      <br>
      Not an employer?<a class="btn" href="http://www.ugrad.cs.ubc.ca/~r8z8/signupapplicant.php">Sign Up as an Applicant!</a></p>
        <div class="left">
            <h1>
                <img src="http://i.imgur.com/FNq86G1.gif" style="width:40px;height:40px;">
                <span class="title">Employer Registration</span>
                <img src="http://i.imgur.com/FNq86G1.gif" style="width:40px;height:40px;">
            </h1>
        </div>
      <p><span class="error"><?php echo $eregistrationerr;?></span><p>
      <p><span class="error">* required field.</span></p>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        Login: <input type="text" name="emplogin">
        <span class="error">* <?php echo $emploginerr;?></span>
        <br><br>
        Password:
        <input type="password" name="epassword">
        <span class="error">* <?php echo $epassworderr;?></span>
        <br><br>
        Confirm Password:
        <input type="password" name="econfirmpassword">
        <span class="error">* <?php echo $econfirmpassworderr;?></span>
        <br><br>
        Company Name:
        <input type="text" name="cname">
        <span class="error">* <?php echo $cnameerr;?></span>
        <br><br>
        Company Address:
        <input type="text" name="caddress">
        <span class="error"><?php echo $caddresserr;?></span>
        <br><br>
        Company ID:
        <input type="text" name="cid">
        <span class="error">* <?php echo $ciderr;?></span>
        <br><br>
        Branch ID:
        <input type="text" name="bid">
        <span class="error">* <?php echo $biderr;?></span>
        <br><br>
        Employer SIN:
        <input type="text" name="esin">
        <span class="error"><?php echo $esinerr;?></span>
        <br><br>
        Employer Name:
        <input type="text" name="ename">
        <span class="error">* <?php echo $enameerr;?></span>
        <br><br>
        Phone Number:
        <input type="text" name="ephonenumber">
        <span class="error"><?php echo $ephonenumbererr;?></span>
        <br><br>
        Email:
        <input type="text" name="eemail">
        <span class="error">* <?php echo $eemailerr;?></span>
        <br><br>
        <input type="submit" name="submit" value="Register">
      </form> 
    </div>

</body>

</html>
