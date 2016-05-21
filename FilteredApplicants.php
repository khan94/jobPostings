<h3>Filtered Applicants</h3>

<?php
	$db_conn = oci_connect("ora_r8z8", "a35028125","ug");
	if(!$db_conn){
		echo "Cannot connect to the database";
	}

	if($db_conn){
		if(array_key_exists('filter',$_POST)){
			echo "check";

			$skill = $_POST['skill'];

			$query = "select * 
					  from Applicants Ap, applicant_has_skills A
					  where Ap.app_login = A.login AND skill = '" . $skill . "'";
			$stid = oci_parse($db_conn, $query);
			$success = oci_execute($stid);
			if(!$success){
				echo "Could not execute the query";
			}

			echo "<table border = 1>";
        	echo "<tr><th>Login</th><th>Name</th><th>Phone #</th><th>Email</th><th>Address</th></tr>";
        	while ($row = oci_fetch_ASSOC($stid)) {
          		$app_login = $row['APP_LOGIN'];
          		$name = $row['NAME'];
          		$phone_number = $row['PHONE_NUMBER'];
          		$email = $row['EMAIL'];
          		$address = $row['ADDRESS'];

          		$app_info = "<tr><td>" . $app_login . "</td><td>" . $name . "</td><td>" . $phone_number . "</td><td>" . $email . "</td><td>" . $address . "</td></tr>";
          		echo "".$app_info."";
			}
        	echo "</table>";
		}
	}
?>