<?php session_start(); ?>

<?php
  include 'connect.php';
  include 'security_check.php';

  $reportid = $_REQUEST['reportid'];
  $query = "SELECT * FROM `incident_report` WHERE `reportid` = $reportid";
  $result = mysqli_query($dbconn, $query);
  $row = mysqli_fetch_array($result);

  if (!$result) {
    die("Error: Data not found..");
  }

  $action_taken = $row['action_taken'];
?>

<p><h1>Action Taken Update</h1></p>

<form method="post" action="confirm-edit-action-taken.php">
  <table width="342" border="0">
    <tr>
      <td>Action:</td>
      <td><input type="text" name="action_taken" value="<?php echo $action_taken; ?>"/></td>
      <td><input type="hidden" name="reportid" value="<?php echo $reportid; ?>"/></td>
      <td>&nbsp;&nbsp;</td>
    </tr>
    
    <tr>
      <td>&nbsp;&nbsp;</td>
      <td><input type="submit" name="Save" value="Save"  /></td>
    </tr>
  </table>
</form>