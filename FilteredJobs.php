<?php
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = oci_connect("ora_r8z8", "a35028125", "ug");

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
  //echo "<br>running ".$cmdstr."<br>";
  global $db_conn, $success;
  $statement = oci_parse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

  if (!$statement) {
    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
    $e = OCI_Error($db_conn); // For OCIParse errors pass the       
    // connection handle
    echo htmlentities($e['message']);
    $success = False;
  }

  $r = oci_execute($statement);
  if (!$r) {
    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
    $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
    echo htmlentities($e['message']);
    $success = False;
  } else {

  }
  return $statement;

}

function printResult($result) { //prints results from a select statement
  echo "<h1>Filtered Jobs</h1>";
  echo "<table border = 1>";
  echo "<tr><th>ID</th><th>Title</th><th>Salary</th></tr>";

  while ($row = oci_fetch_assoc($result)) {
    echo "<tr><td>" . $row["JOB_ID"] . "</td><td>" . $row["JOB_TITLE"] . "</td><td>" . $row["SALARY"] . "</td></tr>"; 
  }
  echo "</table>";
}

if ($db_conn) {
  if(array_key_exists('filter_jobs', $_POST)) {
    $salary = $_POST['filter_salary'];
    $skill =  $_POST['filter_skill'];

    $result = executePlainSQL("select j.job_id, j.salary, j.job_title "
                              . "from Job_Postings j, Job_Requires_Skills js "
                              . "where   j.job_id = js.jobid AND " 
                              . "salary >= " . $salary . " AND "
                              . "js.skill = " . "'" . $skill . "'");

    printResult($result);

    //unset($_POST['filter_jobs'];
  }
  oci_close($db_conn);
}else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}

?>
