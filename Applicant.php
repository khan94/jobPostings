<head>
  <title>Applicant</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>

<!-- Connect to the database and initialize the current user! -->

<?php
  $current_login = $_COOKIE['login'];

  $db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");
        if ($db_connec == false){
          echo "cannot connect to database!";
        }
?>

<div class="container">
  
  <h1>Find some jobs ya bish!</h1>
  
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#jobs">Jobs</a></li>
    <li><a data-toggle="tab" href="#interviews">Interviews</a></li>
    <li><a data-toggle="tab" href="#my_skills">My Skills</a></li>
    <li><a data-toggle="tab" href="#add_skill">Add/Update Skill</a></li>
    <li><a data-toggle="tab" href="#job_offers">Job Offers</a></li>
    <li><a data-toggle="tab" href="#interview_offers">Interview Offers</a></li>
    <li><a data-toggle="tab" href="#applied_jobs">Applied Jobs</a></li>
  </ul>
  
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                    VIEW ALL JOBS TAB                                               -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->

  <div class="tab-content">
    <div id="jobs" class="tab-pane fade in active">  
      <h3>Jobs</h3>
      
      <h4>Apply for a Job Now!</h4>

      <!-- Form to apply to a specified job ID ... Database logic implemented below! -->
      <form method="POST" action="Applicant.php" id="apply_to_job">  
        <p>Job ID: <input type="text" name="applied_job_id" size="8">
          <input type="submit" value="Apply Now!" name="apply_to_job"></p>
      </form>
      
      <p>List of jobs</p>
      <!-- PHP code and queries to display all jobs -->
      <?php
        
        $db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");
        if ($db_connec == false){
          echo "cannot connect to database!";
        }

        $success = True;

        $get_jobs = "select * from Job_Postings";        
        $stid = oci_parse($db_connec, $get_jobs);
        $success = oci_execute($stid);

        if ($success) {
          echo "<table border=1>";
          echo "<tr><th>Title</th><th>ID</th><th>Date</th><th>Salary</th><th>Required Skills</th><th>Description</th></tr>";
          while ($row = oci_fetch_ASSOC($stid)) {
            $job_id = $row['JOB_ID'];
            $job_title = $row['JOB_TITLE'];
            //$company = $row['COMPANY_NAME'];
            $posting_date = $row['POSTING_DATE'];
            $salary = $row['SALARY'];
            $desc = $row['DESCRIPTION'];

            // Get the skills associated to this job            
            $get_skills_for_job = "select skill from job_requires_skills where jobid = " . $job_id;
            $statement = oci_parse($db_connec, $get_skills_for_job);
            $success1 = oci_execute($statement);

            $skills = "";
            $count = 0;
            if ($success1) {
              while ($row1 = oci_fetch_assoc($statement)) {
                $current_skill = $row1['SKILL'];
                if (count == 0) {
                  $skills = $skills . $current_skill;
                }
                else {
                  $skills = $skills . ", " . $current_skill;
                }

                $count = $count + 1;

              }
            }
            else {
              echo "Error when accessing database!";
              $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
              echo htmlentities($e['message']);
            }

            
            $job_info = "<tr><td>" . $job_title . "</td><td>" . $job_id .  "</td><td>" . $posting_date . "</td><td>" . $salary . "</td><td>" . $skills . "</td><td>" . $desc . "</td></tr>";
            echo "" . $job_info . "";
  
            }
          echo "</table>";
        } 
        else {
          echo "Error when accessing database!";
          $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
          echo htmlentities($e['message']);
        }

        $get_num_jobs = "select count(*) as num_jobs from job_postings";
        $statement2 = oci_parse($db_connec, $get_num_jobs);
        $success - oci_execute($statement2);

        if ($success) {
          while ($row = oci_fetch_assoc($statement2)) {
            $num_jobs = $row['NUM_JOBS'];
          }
  
          echo "<p>" . $num_jobs . " total jobs</p>";
        }
        else {
          echo "Error when accessing database!";
          $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
          echo htmlentities($e['message']);
        }
      ?> 

      <!-- Form to filter jobs based on skill and salary! -->

      <h5>Filter jobs!</h5>
      <p><font size="2"> Salary&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Skill</font></p>
      <form method="POST" action="http://www.ugrad.cs.ubc.ca/~r8z8/FilteredJobs.php" id="filtered_jobs">  
        <p><input type="number" name="filter_salary" size="18">
            <select name = "filter_skill" form="filtered_jobs">
              <?php
                $sql_skills = "select skill from Skills";
                $stid_skills = oci_parse($db_connec, $sql_skills);
                $success = oci_execute($stid_skills);
                while($row = oci_fetch_assoc($stid_skills)){
                  $skill = $row['SKILL'];
                  echo " <option value='". $skill ."''>". $skill ."</option>";
                }
              ?>
          </select>
          <input type="submit" value="Filter Jobs" name="filter_jobs"> 
        </p>
      </form>

      <h5>Find MAX or MIN number required skills for each type of Job Title!</h5>
      <!-- Form to allow applicant to find max or min number of skills required for each job title (Job title analagous to a type of job)-->
      <form method="POST" action="http://www.ugrad.cs.ubc.ca/~r8z8/MaxMinSkills.php" id="max_min_skills">       
        <p> 
          <input type="submit" value="Get MAX!" name="max_skills"> 
          <input type="submit" value="Get MIN!" name="min_skills"> 
        </p>
      </form>
    </div>

  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                              VIEW ALL INTERVIEWS TAB                                               -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->

    <div id="interviews" class="tab-pane fade">
      <h3>Interviews</h3>
      <p>List of Scheduled Interviews</p>
      <?php
        $db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");
        if ($db_connec == false){
          echo "cannot connect";
        }

        // Accepted interviews denoted with 'y' in accepted column
        $get_all_my_interviews =  "select a.job_id, i.interview_id, i.interview_date, i.interview_time, i.address "
                . "from Interviews i, Apply A "
                . "where i.interview_id in ("
                                          . "select iv_id "
                                          . "from Apply "
                                          . "where login = " . "'" . $current_login . "'"  
                                          . ") AND i.interview_id = a.iv_id AND "
                                          . "i.accepted = 'y'";
        $statement = oci_parse($db_connec, $get_all_my_interviews);
        $success = oci_execute($statement);

        // Display all interviews from result of query
        if ($success) {
          echo "<table border=1>";
          echo "<tr><th>Interview ID</th><th>Job ID</th><th>Date</th><th>Time</th><th>Address</th></tr>";
          while ($row = oci_fetch_ASSOC($statement)) {
            $interview_id = $row['INTERVIEW_ID'];
            $job_id = $row['JOB_ID'];
            $date = $row['INTERVIEW_DATE'];
            $time = $row['INTERVIEW_TIME'];
            $address = $row['ADDRESS'];
            
            
            $interview_info = "<tr><td>" . $interview_id . "</td><td>" . $job_id 
                                . "</td><td>" . $date . "</td><td>" . $time . "</td><td>" . $address . "</td></tr>";
            echo "" . $interview_info . "";
  
            }
          echo "</table>";
        }
        else {
          echo "Error when accessing database!";
          $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
          echo htmlentities($e['message']);
        }
      ?>
    </div>

  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   VIEW MY SKILLS TAB                                               -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->    
    <div id="my_skills" class="tab-pane fade">
      <h3>My Skills</h3>
      <p>List of my skills</p>
      <?php
        $db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");
        if ($db_connec == false){
          echo "cannot connect";
        }

        $get_my_skills = "select * from Applicant_Has_Skills where login = " . "'" . $current_login . "'" ;

        $stid = oci_parse($db_connec, $get_my_skills);

        $success = oci_execute($stid);

        if ($success) {
          echo "<table border =1>";
          echo "<tr><th>Skill</th><th>Proficiency</th></tr>";
          while ($row = oci_fetch_ASSOC($stid)) {
            $skill = $row['SKILL'];
            $proficiency = $row['PROFICIENCY'];
            
            $skill_info = "<tr><td>" . $skill . "</td><td>" . $proficiency . "</td></tr>";
            echo "" . $skill_info . "";
  
            }
          echo "</table>";
        }
        else {
          echo "Error when accessing database!";
          $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
          echo htmlentities($e['message']);
        }
      ?> 
    </div>

  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   ADD/UPDATE MY SKILLS TAB                                         -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ --> 

    <div id="add_skill" class="tab-pane fade">
      <h3>Add/Update Skill</h3>
    
      <!-- Form that allows applicant to either add a new skill or update proficiency of existing skill -->  
      <form method="POST" action="Applicant.php" id="add_update_skill">  
        <p>Proficiency level (1 to 7) <input type="number" name="skill_proficiency" size="3"></p>
        <p>Skill: 
        <select name="skill_list" form="add_update_skill">
          <?php
            $sql_skills = "select skill from Skills";
            $stid_skills = oci_parse($db_connec, $sql_skills);
            $success = oci_execute($stid_skills);
            while($row = oci_fetch_assoc($stid_skills)){
              $skill = $row['SKILL'];
              echo " <option value='". $skill ."''>". $skill ."</option>";
            }
          ?>
        </select>
        </p>
        <input type="submit" value="Add/Update Skill" name="add_update_skill">
      </form>
    </div>
    
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   VIEW/ACCEPT/REJECT MY JOB OFFERS TAB                                           -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ --> 

    <div id="job_offers" class ="tab-pane fade">
      <h3>Job Offers</h3>

      <!-- Form to allow applicant to accept/reject one of their specified job offers -->
      <p>Accept Job Now!</p>
      <form method="POST" action="Applicant.php" id="accept_job">  
        <p>Job ID: <input type="text" name="accepted_job_id" size="8">
          <input type="submit" value="Accept Job Now!" name="accept_job">
          <input type="submit" value="Reject Job Now!" name="reject_job"></p>
      </form>
      
      <p>List of job offers</p>
      <?php
        $db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");
        if ($db_connec == false){
          echo "cannot connect";
        }

        $get_my_job_offers =  "select jp.job_id, jp.salary, jp.posting_date, jp.job_title, jp.description, c.company_name "
                . "from Job_Postings jp, Job_Offers jo, employer_runs e, companies c "
                . "where   jp.job_id = jo.job_id AND "
                . "jo.applicant_login = " . "'" . $current_login . "'" . " AND "
                . "e.emp_login = jp.login AND "
                . "e.c_id = c.c_id";


        $stid = oci_parse($db_connec, $get_my_job_offers);

        $success = oci_execute($stid);

        // Display on a table the result of querying database for all my job offers
        if($success) {
          echo "<table border=1>";
          echo "<tr><th>CompanyName</th><th>Title</th><th>ID</th><th>Date</th><th>Salary</th><th>Description</th></tr>";
          while ($row = oci_fetch_ASSOC($stid)) {
            $company_name = $row['COMPANY_NAME'];
            $job_id = $row['JOB_ID'];
            $job_title = $row['JOB_TITLE'];
            $posting_date = $row['POSTING_DATE'];
            $salary = $row['SALARY'];
            $desc = $row['DESCRIPTION'];
            
            $job_info = "<tr><td>" . $company_name . "</td><td>" . $job_title . "</td><td>" . $job_id . "</td><td>" 
                        . $posting_date . "</td><td>" . $salary . "</td><td>" . $desc . "</td></tr>";
            echo "" . $job_info . "";
          }
          echo "</table>";
        }
        else {
          echo "Error when accessing database!";
          $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
          echo htmlentities($e['message']);
        }
      ?>
    </div>

  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   VIEW/ACCEPT/REJECT MY INTERVIEWS OFFERS TAB                      -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ --> 

    <div id="interview_offers" class ="tab-pane fade">
      <h3>Interview Offers</h3>
      <p>Accept Inteview Now!</p>
      
      <!-- This form will allow an applicant to either accept or reject an invitation to a specified interview offer -->
      <form method="POST" action="Applicant.php" id="accept_interview">  
        <p>Interview ID: <input type="text" name="accepted_interview_id" size="8">
          <input type="submit" value="Accept Interview Now!" name="accept_interview">
          <input type="submit" value="Reject Interview Now!" name="reject_interview"></p>
      </form>

      <p>List of Pending Interview Offers</p>

      <?php
        $db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");
        if ($db_connec == false){
          echo "cannot connect";
        }

        // Interview offers are identified with a 'n' in
        $my_interview_offers =  "select  i.interview_id, a.job_id, i.interview_date, i.interview_time, i.address "
                . "from  interviews i, apply a " 
                . "where   a.iv_id = i.interview_id AND "
                . "a.login = " . "'" . $current_login ."'" .  " AND "
                . "i.accepted = 'n'";


        $stid = oci_parse($db_connec, $my_interview_offers);

        $success = oci_execute($stid);

        // Display table showing resultant interview offers
        if ($success) {
          echo "<table border=1>";
          echo "<tr><th>Interview ID</th><th>Job ID</th><th>Interview Date</th><th>Time</th><th>Address</th></tr>";
          while ($row = oci_fetch_ASSOC($stid)) {
            $interview_id = $row['INTERVIEW_ID'];
            $job_id = $row['JOB_ID'];
            $interview_date = $row['INTERVIEW_DATE'];
            $interview_time = $row['INTERVIEW_TIME'];
            $address = $row['ADDRESS'];
            
            
            $interview_info = "<tr><td>" . $interview_id . "</td><td>" . $job_id . "</td><td>" 
            . $interview_date . "</td><td>" . $interview_time . "</td><td>" . $address . "</td></tr>";
            echo "" . $interview_info . "";
          }
          echo "</table>";
        }
        else {
          echo "Error when accessing database!";
          $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
          echo htmlentities($e['message']);
        }
      ?>
    </div>

  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   VIEW JOBS I'VE APPLIED TO                                        -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ --> 

    <div id="applied_jobs" class ="tab-pane fade">
      <h3>Applied Jobs</h3>
      <p>List of applied jobs</p>
      <?php

        $db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");

        if ($db_connec == false){
          echo "cannot connect";
        }

        $applied_jobs =  "select * "
                      . "from job_postings j "
                      . "where j.job_id in ("
                                          . "select a.job_id "
                                          . "from Apply a "
                                          . "where login = " . "'" . $current_login . "'"
                                        . ")";

        $stid = oci_parse($db_connec, $applied_jobs);

        $success = oci_execute($stid);

        if ($success) {
          echo "<table border=1>";
          echo "<tr><th>Title</th><th>ID</th><th>Date</th><th>Salary</th><th>Description</th></tr>";
          while ($row = oci_fetch_ASSOC($stid)) {
            $job_id = $row['JOB_ID'];
            $job_title = $row['JOB_TITLE'];
            $posting_date = $row['POSTING_DATE'];
            $salary = $row['SALARY'];
            $desc = $row['DESCRIPTION'];
            
            
            $job_info = "<tr><td>" . $job_title . "</td><td>" . $job_id . "</td><td>" 
            . $posting_date . "</td><td>" . $salary . "</td><td>" . $desc . "</td></tr>";
            echo "" . $job_info . "";
          }
          echo "</table>";
        }
        else{
          echo "Error when accessing database!";
          $e = OCI_Error($db_connec); // For OCILogon errors pass no handle
          echo htmlentities($e['message']);
        }
      ?>
    </div>
  </div>
</div>


  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   PHP LOGIC FOR HANDLING FORMS                                     -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ --> 
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   PHP LOGIC FOR HANDLING FORMS                                     -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ --> 
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ -->
  <!--                                   PHP LOGIC FOR HANDLING FORMS                                     -->
  <!-- __________________________________________________________________________________________________ -->
  <!-- __________________________________________________________________________________________________ --> 




<?php
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_connec = OCI_connect("ora_r8z8", "a35028125", "ug");

if ($db_connec) {

  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //
  //                                    HANDLE APPLY TO JOB                                                //
  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //
  if(array_key_exists('apply_to_job', $_POST)) {
    $job_id = $_POST['applied_job_id'];

    // Insert into apply table with no interview (dont get interview right after application)

    $sql = "insert into Apply values ('" . $current_login . "','" . $job_id . "',null)"; 
    $stid = oci_parse($db_connec, $sql);
    $success = oci_execute($stid);
    oci_commit($db_connec);
  }

  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //
  //                                    HANDLE ACCEPT/REJECT JOB                                           //
  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //

  if(array_key_exists('accept_job', $_POST) or array_key_exists('reject_job', $_POST)) {
    $job_id = $_POST['accepted_job_id'];


    // Check if this applicant has a scheduled interview in interview table, we will have to delete this if so
    $sql = "select iv_id from apply where job_id = " . $job_id . " AND login = '" . $current_login ."'";
    $stid = oci_parse($db_connec, $sql);
    $success = oci_execute($stid);

    $interview_id = null;
    while ($row = oci_fetch_assoc($stid)) {
      $interview_id = $row['IV_ID'];
    }

    // Remove this job offer since it was either accpeted or rejected
    $sql1 = "delete from job_offers where job_id = " . $job_id . " AND applicant_login = '" . $current_login ."'";
    $stid1 = oci_parse($db_connec, $sql1);
    $success = oci_execute($stid1);
    oci_commit($db_connec);

    // Removed the application from Apply table because job was accepted or rejected
    $sql2 = "delete from apply where job_id = " . $job_id . " AND login = '" . $current_login ."'";
    $stid2 = oci_parse($db_connec, $sql2);
    $success = oci_execute($stid2);
    oci_commit($db_connec);

    // If there was an associated interview, delete that interview from interview table
    if (!is_null($interview_id)) {
      $sql3 = "delete from interviews where interview_id = " . $interview_id;
      $stid3 = oci_parse($db_connec, $sql3);
      $success = oci_execute($stid3);
      oci_commit($db_connec);
    } 

    // If this job was accepted, then this job is no longer available, delete the job, it will
    // cascade down to job_requires_skills table
    if (array_key_exists('accept_job', $_POST)) {
      $sql4 = "delete from job_postings where job_id = " . $job_id;
      $stid4 = oci_parse($db_connec, $sql4);
      $success = oci_execute($stid4);
      oci_commit($db_connec);
    }

    //unset($_POST['accept_job']);
    //unset($_POST['reject_job']);
  }

  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //
  //                                        ACCEPT INTERIVEWS                                              //
  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //


  if (array_key_exists('accept_interview', $_POST)) {
    // When accepting interview, update the corresponding entry in the interview table to have a 'y' in 
    // the accepted column to differentiate between interview offers and scheduled interviews
    $interview_id = $_POST['accepted_interview_id'];
    $sql = "update interviews set accepted = 'y' where interview_id = " . $interview_id;
    $stid = oci_parse($db_connec, $sql);
    $success = oci_execute($stid);
    oci_commit($db_connec);
    //unset('accept_interview');
  }

  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //
  //                                        REJECT INTERIVEWS                                              //
  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //


  if (array_key_exists('reject_interview', $_POST)) {
    // When rejecting an interview delete the interview from the interviews table, this will cascade to the 
    // apply table because an interview rejection means that the applicant is not interested in the job anymore
    $job_id = $_POST['accepted_interview_id'];
    $sql = "delete from interviews where interview_id = " . $interview_id;
    $stid = oci_parse($db_connec, $sql);
    $success = oci_execute($stid);
    oci_commit($db_connec);
    //unset('reject_interview');
  }

  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //
  //                                        ADD/UPDATE SKILLS                                              //
  // ----------------------------------------------------------------------------------------------------- //
  // ----------------------------------------------------------------------------------------------------- //


  if (array_key_exists('add_update_skill', $_POST)) {
    $skill = $_POST['skill_list'];
    $proficiency = $_POST['skill_proficiency'];


    // Check if the inputted skill already exists for the current applicant
    $sql = "select * from applicant_has_skills a where skill = '" . $skill . "' AND login = '" . $current_login ."'";
    $stid = oci_parse($db_connec, $sql);
    $success = oci_execute($stid);

    $count = 0;
    
    while ($row = oci_fetch_assoc($stid)) {
      $count = $count + 1;
    }

    // If it doeesnt exist, simply insert into applicant has skills table with the skill name and proficiency
    if ($count == 0) {
      $sql1 = "insert into applicant_has_skills values ('" . $current_login . "','" . $skill ."'," . $proficiency . ")";
      $stid1 = oci_parse($db_connec, $sql1);
      $success = oci_execute($stid1);
      oci_commit($db_connec);
    }

    // If it already exists, update the applicant has skills table to set the given skill to the updated proficiency for the 
    // current user
    else {
      $sql2 = "update applicant_has_skills set proficiency = " . $proficiency . " where skill = '" . $skill . "' AND login = '" . $current_login ."'";
      $stid2 = oci_parse($db_connec, $sql2);
      $success = oci_execute($stid2);
      oci_commit($db_connec); 
    }

  }

}else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}

oci_close($db_connec);
?>