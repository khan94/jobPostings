<?php
// set the cookie with the submitted user data
setcookie('login', $_POST['login']);
echo "<b>login:</b>".$_COOKIE['login'];
$APPLICANT_URL = "http://www.ugrad.cs.ubc.ca/~r8z8/Applicant.php";
$EMPLOYER_URL = "http://www.ugrad.cs.ubc.ca/~r8z8/Employer.php";
 if (!empty($_POST)) {
  // set the cookie with the submitted user data
  setcookie('login',$_POST['login']);
  // redirect the user to final landing page so cookie info is available
  header("Location:index.php");
 } else {
 echo "<b>login:</b>".$_COOKIE['login'];
 }


$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_connect = OCI_connect("ora_r8z8", "a35028125", "ug");


if ($db_connect == false){
          echo "cannot connect";
        }

        		else if (array_key_exists('loginsubmit', $_POST)) {


				// Take Login and Password from form
					$bind1 = $_POST['login'];
					$bind2 = $_POST['psw'];
				
				//Query applicant database to return a username
				// and password if it is in database
				$sql = "select APP_LOGIN, PASSWORD 
					from APPLICANTS where APP_LOGIN = '" . $bind1 ."' 
					AND  PASSWORD = '" . $bind2 ."' ";

				$stid = oci_parse($db_connect,$sql);
				oci_execute($stid);

				$numrows = oci_fetch_all($stid, $res);

				//check returned rows to see if contains anything
				if(($numrows) > 0){
				header( "Location: $APPLICANT_URL" );
				//jump to applicant page if username and password were in applicant table

				} else if ($numrows == 0){
					//check employer table since username and password was not 
					// found in applicant database
				$sql = "select EMP_LOGIN, PASSWORD 
					from EMPLOYER_RUNS where EMP_LOGIN = '" . $bind1 ."' 
					AND  PASSWORD = '" . $bind2 ."' ";

				$stid = oci_parse($db_connect,$sql);
				oci_execute($stid);

				$numrows = oci_fetch_all($stid, $res);

				if(($numrows) > 0){
				
				header( "Location: $EMPLOYER_URL" );
				//jump to applicant page if username and password were in applicant table

				}else{
				echo "Incorrect username and password combination";
				}


			}
}
/*
*/

?>

