<?php
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = oci_connect("ora_r8z8", "a35028125", "ug");

function printResultMax($result) { //prints results from a select statement
  echo "<h1>Max Number of Required Skills For Each Job Type</h1>";
  echo "<table border = 1>";
  echo "<tr><th>Title</th><th>Max Num Skills</th></tr>";

  while ($row = oci_fetch_assoc($result)) {
    echo "<tr><td>" . $row["JOB_TITLE"] . "</td><td>" . $row["MAX_NUM_SKILLS"] .  "</td></tr>"; 
  }
  echo "</table>";
}

function printResultMin($result) { //prints results from a select statement
  echo "<h2>Min Number of Required Skills For Each Job Type</h2>";
  echo "<table border = 1>";
  echo "<tr><th>Title</th><th>Min Num Skills</th></tr>";

  while ($row = oci_fetch_assoc($result)) {
    echo "<tr><td>" . $row["JOB_TITLE"] . "</td><td>" . $row["MIN_NUM_SKILLS"] .  "</td></tr>"; 
  }
  echo "</table>";
}

if ($db_conn) {
  if(array_key_exists('max_skills', $_POST)) {

    $create_num_skills_per_job_view = "create view num_skills_per_job as  
      select jp.job_id as job_id, count(distinct jrs.skill) as num_skills 
      from job_postings jp, job_requires_skills jrs 
      where jp.job_id = jrs.jobid 
      group by jp.job_id";
    $stid = oci_parse($db_conn, $create_num_skills_per_job_view);
    $success = oci_execute($stid);
    $success = oci_commit($db_conn);
    

    if ($success) {
      $sql =  "select jp.job_title as job_title, max(num_skills) as max_num_skills 
      from num_skills_per_job nspr, job_postings jp
      where nspr.job_id = jp.job_id
      group by jp.job_title";

      $stid = oci_parse($db_conn, $sql);
      $success = oci_execute($stid); 

      printResultMax($stid);

      $drop_view = "drop view num_skills_per_job";
      $statement = oci_parse($db_conn, $drop_view);
      $success = oci_execute($statement);
      oci_commit($db_conn);

    }

    //unset($_POST['filter_jobs'];
  }
  if(array_key_exists('min_skills', $_POST)) {
    $create_num_skills_per_job_view = "create view num_skills_per_job as " . 
                                      "select jp.job_id as job_id, count(distinct jrs.skill) as num_skills " .
                                      "from job_postings jp, job_requires_skills jrs " .
                                      "where jp.job_id = jrs.jobid " .
                                      "group by jp.job_id";
    $stid = oci_parse($db_conn, $create_num_skills_per_job_view);
    $success = oci_execute($stid);
    $success = oci_commit($db_conn);
    
    if ($success) {
      $sql =  "select jp.job_title as job_title, min(num_skills) as min_num_skills " .
              "from num_skills_per_job nspr, job_postings jp " .
              "where nspr.job_id = jp.job_id " .
              "group by jp.job_title";

      $stid = oci_parse($db_conn, $sql);
      $success = oci_execute($stid); 

      printResultMin($stid);

      $drop_view = "drop view num_skills_per_job";
      $statement = oci_parse($db_conn, $drop_view);
      $success = oci_execute($statement);
      oci_commit($db_conn);
  }
}
oci_close($db_conn);
}
else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}

?>
