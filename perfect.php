<h3>List of applicants That are Perfectly Matched for a Job</h3>

<?php
	$db_conn = oci_connect("ora_r8z8", "a35028125","ug");
	if(!$db_conn){
		echo "Cannot connect to the database";
	}


	if($db_conn){

		if(array_key_exists('search', $_POST)){
	     	$job_id = $_POST['perfect_job_id'];
	     	echo $job_id;
	     	$perfect_query = "select *
	                          from Applicants a
	                          where not exists (select skill
	                                            from job_requires_skills jrs
	                                            where jrs.jobid = " . $job_id . "
	                                            minus (select skill
	                                                   from applicant_has_skills ahs
	                                                   where ahs.login = a.app_login))";
	      	$perfect_stid = oci_parse($db_conn,$perfect_query);
	      	$perfect_success = oci_execute($perfect_stid);
			if(!$perfect_success){
				echo "Could not execute the query";
			}


			echo "<table border = 1>";
	        echo "<tr><th>Login</th><th>Name</th><th>Phone #</th><th>Email</th><th>Address</th></tr>";
	        while ($row = oci_fetch_ASSOC($perfect_stid)) {
	          	$app_login = $row['APP_LOGIN'];
	          	$name = $row['NAME'];
	          	$phone_number = $row['PHONE_NUMBER'];
	          	$email = $row['EMAIL'];
	          	$address = $row['ADDRESS'];

	          	$app_info = "<tr><td>" . $app_login . "</td><td>" . $name . "</td><td>" . $phone_number . "</td><td>" . $email . "</td><td>" . $address . "</td></tr>";
	          	echo "".$app_info."";
			}
	        echo "</table>";




    // THE PERFECT_QUERY DOES NOT WORK, ASK PEOPLE
    	}
	}
?>