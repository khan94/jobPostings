<html>
<head>
  <title>Employer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<div class="container">
  <h1>DATABASIC BISHES</h1>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#applicants">Applicants</a></li>
    <li><a data-toggle="tab" href="#my_jobs">My Jobs</a></li>
    <li><a data-toggle="tab" href="#add">Post New Job</a></li>
    <li><a data-toggle="tab" href="#my_applicants">Applied</a></li>
    <li><a data-toggle="tab" href="#offer_invite">Offer/Invite for Job</a></li>
    <li><a data-toggle="tab" href="#delete">Delete Job Postings</a></li>
    <li><a data-toggle="tab" href="#perfect">Find Perfect Matches</a></li>
  </ul>

  <div class="tab-content">

    <!-- __________________APPLICANTS_PANEL_____________________START_________________-->

    <div id="applicants" class="tab-pane fade in active">
      <h3>Applicants</h3>
      <p>List of applicants</p>
      <?php
        $current_login = $_COOKIE['login'];

        $db_conn = oci_connect("ora_r8z8", "a35028125", "ug");

        if ($db_conn == false){
          echo "cannot connect";
        }
        $sql = "select * from Applicants";

        $stid = oci_parse($db_conn, $sql);

        $success = oci_execute($stid);
        if(!$success){
          echo "Oops!";
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
        echo "</br>";
      ?>

      <form method = "POST" action = "FilteredApplicants.php">
        Skill: <input type="text" name="skill">
        <input type="submit" value="Filter" name = "filter">
      </form>
    </div>
    

    <!-- __________________APPLICANTS_PANEL_____________________END_________________-->



    <!-- __________________MY_JOBS_PANEL_____________________START_________________-->

    <div id="my_jobs" class="tab-pane fade">
      <h3>My Jobs</h3>
      <p>List of jobs created by me</p>

      <?php
        $sql = "select * from Job_Postings where login = '" . $current_login . "'";

        $stid = oci_parse($db_conn, $sql);
        $success = oci_execute($stid);
        if(!$success){
          echo "where my jobs at?";
        }
        echo "<table border = 1>";
        echo "<tr><th>Job ID</th><th>Title</th><th>Date</th><th>Salary</th><th>Description</th></tr>";
        while ($row = oci_fetch_ASSOC($stid)) {
          $job_id = $row['JOB_ID'];
          $job_title = $row['JOB_TITLE'];
          $posting_date = $row['POSTING_DATE'];
          $salary = $row['SALARY'];
          $desc = $row['DESCRIPTION'];
          $job_info = "<tr><td>" . $job_id . "</td><td>" . $job_title . "</td><td>" . $posting_date . "</td><td>" . $salary . "</td><td>" . $desc . "</td></tr>";
          echo "" . $job_info . "";
        }
        echo "</table>";
      ?>

    </div>
      <!-- __________________MY_JOBS_PANEL_____________________END_________________-->


<!-- __________________ADD PANEL_____________________START_________________-->

    <div id="add" class="tab-pane fade">
      <h3>New Job</h3>
      <form method = "POST" action = "Employer.php" id = "add_job">
        <p>Title: <input type="text" name="job_title"> Salary ($): <input type="text" name="job_salary"></p>
        <p>Description <input type="text" name="job_description" ></p>
        <p>Required Skills</p>
        <select name = "skill1" form = "add_job">
          <option value = "empty">----</option>
          <?php
            $sql = "select skill from Skills";

            $stid = oci_parse($db_conn, $sql);

            $success = oci_execute($stid);

            while($row = oci_fetch_assoc($stid)){
              $skill = $row['SKILL'];
              echo " <option value='". $skill ."''>". $skill ."</option>";
            }
          ?>
        </select>
        <select name = "prof1" form = "add_job">
          <?php
            for($i = 0; $i <= 10; $i++){
              echo " <option value='". $i ."''>". $i ."</option>";
            }
          ?>
        </select>
        </br>
        <select name = "skill2" form = "add_job">
          <option value = "empty">----</option>
          <?php
            $sql = "select skill from Skills";

            $stid = oci_parse($db_conn, $sql);

            $success = oci_execute($stid);

            while($row = oci_fetch_assoc($stid)){
              $skill = $row['SKILL'];
              echo " <option value='". $skill ."''>". $skill ."</option>";
            }
          ?>
        </select>
        <select name = "prof2" form = "add_job">
          <?php
            for($i = 0; $i <= 10; $i++){
              echo " <option value='". $i ."''>". $i ."</option>";
            }
          ?>
        </select>
        </br>
        <select  name = "skill3" form = "add_job">
          <option value = "empty">----</option>
          <?php
            $sql = "select skill from Skills";

            $stid = oci_parse($db_conn, $sql);

            $success = oci_execute($stid);

            while($row = oci_fetch_assoc($stid)){
              $skill = $row['SKILL'];
              echo " <option value='". $skill ."''>". $skill ."</option>";
            }
          ?>
        </select>
        <select name = "prof3" form = "add_job">
          <?php
            for($i = 0; $i <= 10; $i++){
              echo " <option value='". $i ."''>". $i ."</option>";
            }
          ?>
        </select>
        </br>
        </br>
        <input type="submit" value="Post Job" name = "post">
    </form>
    </div>


    <!-- __________________ADD PANEL_____________________END_________________-->



    <!-- __________________MY_APPLICANTS_PANEL_____________________START_________________-->
    <div id="my_applicants" class="tab-pane fade">
      <h3>Applicants applied to my jobs</h3>
      <p>List of Applicants applied to my jobs</p>

      <?php
        $jobs_by_employer = "create view jobs_by_employer as select job_id from job_postings where login = '".$current_login."'";

        $stid = oci_parse($db_conn, $jobs_by_employer);

        $success = oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
        if(!$success){
          echo "error1";
        }

        $yams = "select A.login 
                 from Apply A, jobs_by_employer J
                 where A.job_id = J.job_id";
        $sql = "select Ap.app_login, A2.job_id, Ap.name, Ap.phone_number, Ap.email, Ap.address 
                from Applicants Ap, Apply A2 
                where Ap.app_login in (" . $yams . ") AND Ap.app_login = A2.login";

        $stid = oci_parse($db_conn, $sql);

        $success = oci_execute($stid);
        if(!$success){
          echo "error2";
        }


        echo "<table border = 1>";
        echo "<tr><th>Login</th><th>Job ID</th><th>Name</th><th>Phone #</th><th>Email</th><th>Address</th></tr>";
        while ($row = oci_fetch_ASSOC($stid)) {
          $job_id = $row['JOB_ID'];
          $app_login = $row['APP_LOGIN'];
          $name = $row['NAME'];
          $phone_number = $row['PHONE_NUMBER'];
          $email = $row['EMAIL'];
          $address = $row['ADDRESS'];
          
          
          $app_info = "<tr><td>" . $app_login . "</td><td>" . $job_id  . "</td><td>" . $name . "</td><td>" . $phone_number . "</td><td>" . $email . "</td><td>" . $address . "</td></tr>";
          echo "" . $app_info . "";

        }

        echo "</table>";



        //delete the created view
        $drop = "drop view jobs_by_employer";
        $stid = oci_parse($db_conn, $drop);
        $success = oci_execute($stid,OCI_COMMIT_ON_SUCCESS);

      ?>

    </div>

    <!-- __________________MY_APPLICANTS_PANEL_____________________END_________________-->


    <!-- __________________OFFER/INVITE_PANEL_____________________START_________________-->


    <div id="offer_invite" class="tab-pane fade">
      <h3>Offer Job</h3>
      <form method = "POST" action = "Employer.php">
        <p>Applicant login: <input type="text" name="app_login"></p>
        <p>Job_ID: <input type="text" name="job_id"></p>
        <input type="submit" value="Offer" name="offer">
      </form>
      <h3>Invite to a Job Interview</h3>
      <form method = "POST" action = "Employer.php">
        <p>Applicant login: <input type="text" name="app_login"></p>
        <p>Job_ID: <input type="text" name="job_id"></p>
        <p>Date (YYYY-MON-DD): <input type="text" name="date"></p>
        <p>Time (hh:mm:ss): <input type="text" name="time"></p>
        <p>Address: <input type="text" name="address"></p>
        <input type="submit" value="Invite" name="invite">
      </form>
    </div>

    <!-- __________________OFFER/INVITE_PANEL_____________________END_________________-->




    <!-- __________________DELETE_PANEL_____________________START_________________-->

    <div id="delete" class="tab-pane fade">
      <h3>Delete Job by Job ID</h3>
      <form method = "POST" action = "Employer.php">
        Job ID: <input type="text" name="delete_job_id">
        <input type="submit" value="Delete Job" name="delete">
      </form>
    </div>

    <!-- __________________DELETE_PANEL_____________________START_________________-->


    <!-- __________________PERFECT_PANEL_____________________START_________________-->

    <div id="perfect" class="tab-pane fade">
      <h3>Filter list of applicants to find perfect matches</h3>
      <form method = "POST" action = "perfect.php">
        Job ID: <input type="text" name="perfect_job_id">
        <input type="submit" value="Search" name="search">
      </form>
    </div>
    <!-- __________________PERFECT_PANEL_____________________END_________________-->
  </div>
</div>
</html>





<?php

//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________
//__________________________________________ARRAY_KEY_EXISTS___________________________________________


if ($db_conn){

  //______________POST________________
  if(array_key_exists('post', $_POST)){

    
    $job_title = $_POST['job_title'];

    $salary = $_POST['job_salary'];

    $desc = $_POST['job_description'];

    $date = date("o") ."-". date("M") ."-". date("d");

    $query = "select job_id from job_postings";
    $statement = oci_parse($db_conn, $query);
    $success = oci_execute($statement);
    $random = rand(10000000,99999999);


    // do while loop is needed to check
    // if randomly generated number is not equal to
    // other, already existing job_ids in Job_Postings table
    do {

      $in = false;    // to check if the random value is in job_id array
      while ($row = oci_fetch_assoc($statement)){
        if ($row['JOB_ID'] == $random){  // check, if true, take measures
        $in = true;
        $random = rand(10000000,99999999);
        break; 
        }
      }
      $statement = oci_parse($db_conn, $query); //do the query again
      $success = oci_execute($statement); //do the query again
    }
    while ($in);
    $job_id = $random; //Set the job_id for a job posting


    $date = "TO_DATE('" .$date. "','YYYY/MON/DD')";

    //Add the job_posting into the JOB_POSTINGS
    $statement = "insert into job_postings VALUES ('" . $job_id . "','" . $current_login . "','" . $desc . "'," . $date . ",'" . $job_title . "','" . $salary . "')";

    $stid = oci_parse($db_conn, $statement);
    $success_lol = oci_execute($stid); // execute fucked me over
    oci_commit($db_conn);

    if(!$success_lol){
      echo "Did not add the job\n";
    }

    //Retrieve the skills and proficiencies from input
    $skill1 = $_POST['skill1'];
    $skill2 = $_POST['skill2'];
    $skill3 = $_POST['skill3'];

    $prof1 = $_POST['prof1'];
    $prof2 = $_POST['prof2'];
    $prof3 = $_POST['prof3'];


    //Add the skills to a job_requires_skill
    if($skill1 != 'empty'){    
      $query = "insert into job_requires_skills VALUES ('" . $job_id . "','" . $skill1 . "','" . $prof1 . "')";
      $statement = OCI_parse($db_conn,$query);
      $success = oci_execute($statement);
      oci_commit($db_conn);
      if(!$success){
        echo "Did not add the skill1";
      }
    }

    if(($skill2 != 'empty') && ($skill2 != $skill1)){
      $query = "insert into job_requires_skills VALUES ('" . $job_id . "','" . $skill2 . "','" . $prof2 . "')";
      $statement = OCI_parse($db_conn,$query);
      $success = oci_execute($statement);
      oci_commit($db_conn);
      if(!$success){
        echo "Did not add the skill2";
      }
    }

    if($skill3 != 'empty' && ($skill3 != $skill1) && ($skill3 != $skill2) && ($prof3 != 0)){
      echo $prof3;
      $query = "insert into job_requires_skills VALUES ('" . $job_id . "','" . $skill3 . "','" . $prof3 . "')";
      $statement = OCI_parse($db_conn,$query);
      $success = oci_execute($statement);
      oci_commit($db_conn);
      if(!$success){
        echo "Did not add the skill3";
      }
    }
    oci_commit($db_conn);
  }



  //_____________OFFER______________
    if(array_key_exists('offer', $_POST)){
      $app_login = $_POST['app_login'];
      $job_id = $_POST['job_id'];

      //create a row in job_offers{ employer_login, applicant_login, job_id }
      $check_query = "select * from job_postings where login = '" . $current_login . "' AND job_id = '" . $job_id . "'";
      $check_stid = oci_parse($db_conn, $check_query);
      $check_success = oci_execute($check_stid);
      if (!$check_success){
        echo "WHAT?!";
      }
      $check = 0;

      while($row = oci_fetch_assoc($check_stid)){
        $check = $check + 1;
      }


      $query = "select * from job_offers where applicant_login = '". $app_login ."' AND job_id = '". $job_id ."' AND employer_login = '". $current_login ."'";
        
      $stid = oci_parse($db_conn, $query);
      $success_3 = oci_execute($stid);
      if(!$success_3){
        echo "error3";
      }
      $count = 0;
      while($row = oci_fetch_assoc($stid)){
        $count = $count + 1;
      }

      if(($count == 0) && ($check == 1)){ // check if there is any other job_offer like that
        $query = "insert into job_offers VALUES ('". $current_login ."','". $app_login ."',". $job_id .")";
        
        $stid = oci_parse($db_conn, $query);

        $success_ha = oci_execute($stid);
        oci_commit($db_conn);

        if(!$success_ha){
          echo "Did not add the Job offer";
        }
        else{
          echo "Job Offer Sent";
        }
        $check = 0;
        $count = 0;
      }
      else{
        echo "there is an offer already";
      }

    }

    //_____________INVITE______________
    if(array_key_exists('invite', $_POST)){
      $app_login = $_POST['app_login'];
      $job_id = $_POST['job_id'];
      $date = "TO_DATE('" . $_POST['date'] . "','YYYY/MON/DD')";
      $time = $_POST['time'];
      $address = $_POST['address'];

      //checks if there are even such people in apply table (does not check the interview ID yet)
      $query = "select * from Apply where login = '" . $app_login . "' AND job_id = '" . $job_id . "'";

      $stid = oci_parse($db_conn, $query);
      $success = oci_execute($stid);
      if(!$success){
        echo "error4";
      }
      $count = 0;
      while($row = oci_fetch_assoc($stid)){
        $count = $count + 1;
        $int_id_check = $row['IV_ID'];
      }
      echo $count;

      //No people who applied yet
      if ($count == 0){    //no such person in Apply table

        $query = "select interview_id from interviews";
        $random = rand(10000000,99999999);
        $statement = oci_parse($db_conn, $query);
        $success = oci_execute($statement);

        do {
          $in = false; // to check if the random value is in tinterview_id array
          while ($row = oci_fetch_assoc($statement)){
            if ($row['INTERVIEW_ID'] == $random){ // check, if true, take measures
              $in = true;
              $random = rand(10000000,99999999);
            }
            break;
          }
          $statement = oci_parse($db_conn, $query); //do the query again
          $success = oci_execute($statement); //do the query again
        }
        while ($in);

        //ADD ROW TO INTERVIEWS

        $add_interview_query = "insert into interviews VALUES (" . $random . ",'n'," . $date . ",'" . $time . "','" . $address . "')";
        $add_interview_stid = oci_parse($db_conn, $add_interview_query);
        $success = oci_execute($add_interview_stid);
        if(!$success){
          echo "cannot add an interview";
        }

        
        $insert_query = "insert into Apply VALUES ('" . $app_login . "'," . $job_id . "," . $random . ")";
        $insert_stid = oci_parse($db_conn, $insert_query);
        $success = oci_execute($insert_stid);
        oci_commit($db_conn);
        if(!$success){
          echo "did not insert into Apply table ";
        }

        $count = 0;
      }

      // People who applied to job already (They are in Apply Table)
      else{
        
        if($int_id_check == null){    // There is such person in Apply, but he/she has no interview set up yet
          echo "check";

          $query = "select interview_id from interviews";
          $random = rand(10000000,99999999);
          $statement = oci_parse($db_conn, $query);
          $success = oci_execute($statement);

          do {
            $in = false;    // to check if the random value is in tinterview_id array
            while ($row = oci_fetch_assoc($statement)){
              if ($row['INTERVIEW_ID'] == $random){  // check, if true, take measures
                $in = true;
                $random = rand(10000000,99999999);
              }
            }
            $statement = oci_parse($db_conn, $query); //do the query again
            $success = oci_execute($statement); //do the query again
          }
          while ($in);

          $add_interview_query = "insert into interviews VALUES (" . $random . ",'n'," . $date . ",'" . $time . "','" . $address . "')";
          $add_interview_stid = oci_parse($db_conn, $add_interview_query);
          $success = oci_execute($add_interview_stid);
          if(!$success){
            echo "cannot add an interview";
          } 
          echo $random;
          $final_query = "update Apply set iv_id = " . $random . " where login = '" . $app_login . "' AND job_id = '" . $$job_id . "'";

          $final_stid = oci_parse($db_conn, $final_query);
          $success = oci_execute($final_stid);
          $success = oci_commit($db_conn);

          if (!$success){
            echo "Did not update the Apply table";
          }

        }
        else{    // There is already a set up interview
          echo "In the database already";
        }
      }
      oci_commit($db_conn);
      //check if this data is in Apply, if so, add the interview_id to it, if not, add a row
      //if not added already, add a row in Interviews table
    }

    //________________DELETE___________________
    if(array_key_exists('delete', $_POST)){
      echo "del";
      $job_id = $_POST['delete_job_id'];


      
      // Need to manually delete the interviews that are related to a job_posting being deleted
      $query = "delete iv_id from apply where job_id = '" . $job_id . "'";
      $stid = oci_parse($db_conn, $query);
            $success = oci_execute($stid);
      if(!$success){
        echo "Could not run the delete job query";
      }

      while ($row = oci_fetch_assoc($stid)){
        $delete_query = "delete from interviews
                         where interview_id = '" . $row['IV_ID'] . "'";
        $delete_stid = oci_parse($db_conn, $delete_query);
        $success = oci_execute($delete_stid);
        if(!$success){
          echo "Could not run the delete interview";
        }
      }



      // This part of the code is delete cascade,
      //so when Job_Posting entry is deleted,
      // all the entries that are referencing that Job Posting
      // in Job_Requires_Skills and Apply tables are also deleted
      $delete_query = "delete from job_postings
                       where job_id = '" . $job_id . "'";
      $delete_stid = oci_parse($db_conn, $delete_query);
      $success = oci_execute($delete_stid);
      if(!$success){
        echo "Could not run the delete job query";
      } 

      
      oci_commit($db_conn);

    }
}
?>





