<?php
  session_start();

  if(!isset($_SESSION['user_id'])) {
    echo '<script>windows: location="index.php"</script>';
  }
?>

<?php
  $session_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<title>pdf</title>
	<link rel="stylesheet" href="style.css" />
</head>

<body>
<?php
	if (isset($_GET['msg'])) {
	  echo "<div id = 'res' class='alert alert-info' role='alert'> $_GET[msg] </div>";
	}
?>

<div id="wrap">
<form method="post" action="create_receipt.php">
	<fieldset>
		<br>
		<h1><center>Metropolitan Naga Water District</center></h1><br>
		<center>B I L L I N G  N O T I C E</center>
		<center>-----------------------------------</center><br><br>

		<?php
			require_once ('../../mobile_connect.php');
			//if ($SERVER['REQUEST_METHOD'] == 'POST') {
				//$userid = $_POST['userid'];
				//$accountid = $_POST['accountid'];

				#HARDCODED INPUT
				$accountid = 1;
				$userid = 2;

				#JOIN: account and reading
				$query = "SELECT * FROM `reading` INNER JOIN `account` 
							ON `reading`.`accountid` = `account`.`accountid` 
							WHERE `accountid` = $accountid 
							ORDER BY `billingdate` DESC LIMIT 1";

				$query = "SELECT * FROM `reading` WHERE `accountid` = $accountid";
				$result = mysqli_query($dbconn, $query);
				$row = mysqli_fetch_array($result);

				$refno = $row['refno'];
				
			//}
		?>
		<p><label for="refno">Reference #:</label><input type="text" name="refno" value="1701486087" /></p>
		<p><label for="meterno">Meter #:</label><input type="text" name="meterno" value="94044812" /></p>
		<p><label for="accountno">Account #:</label><input type="text" name="accountno" value="486-12-621" /></p>
		<p><label for="concessionaire">Concessionaire</label><input type="text" name="concessionaire" value="Dela Cruz, Juan" /></p>


	</fieldset>
	<fieldset>
		<legend>For the Month of January 2017</legend><br>
		<table>
			
			<th colspan="6">DATE</th>
			<th colspan="6">READING</th>
			<th colspan="6">CUM USED</th></tr>

			<tr><td colspan="6"><label for="presentdate">Present</label><input type="text" name="presentdate" value="01/23/2017" /></td><td colspan="6"><input type="text" name="presentreading" value="2093" /></td><td colspan="6"><input type="text" name="presentcumused" value="69" /></td></tr>
			<td colspan="6"><label for="previousdate">Previous</label><input type="text" name="previousdate" value="12/21/2016" /></td><td colspan="6"><input type="text" name="previousreading" value="2026" /></td><td colspan="6"><input type="text" name="previouscumused" value="" /></td></tr>
		</table>
	</fieldset>

	<table>
		<br>
		<tr><td colspan="6"><label for="currentbill">Current Bill</label><input type="text" name="currentbill" value="1,127.05" /></td></tr>
		<tr><td colspan="6"><label for="balanceorarrears">Balance/Arrears</label><input type="text" name="balanceorarrears" value="0.00" /></td></tr>
		<tr><td colspan="6">----------------------</td></tr>
		<tr><td colspan="6"><label for="totalbill">TOTAL BILL</label><input type="text" name="totalbill" value="1,127.05" /></td></tr>
		</table>

	<table>
		<br><br>
		<tr><td colspan="6"><label for="amount">Amount after due date</label><input type="text" name="amount" value="1,239.75" /></td></tr>
		<tr><td colspan="6"><label for="duedate">Due Date</label><input type="text" name="duedate" value="02//02/2017" /></td></tr>
		<tr><td colspan="6"><label for="notice">Disconnection</label><input type="text" name="notice" value="02//07/2017" /></td></tr>


	</table>

		<p align="center"><button type="submit">Submit</button></p>
 	
</form>
</div>
</body>
</html>

<script type="text/javascript">
  $("#res").show().delay(3500).hide(1);
</script>