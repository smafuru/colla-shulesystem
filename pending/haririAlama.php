<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/connection.php');
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/usalama/isLogedIn.php');
$RegionNo = $_SESSION['mkoa'];
$DistrictNo = $_SESSION['wilaya'];
$SchoolNo = $_SESSION['shule'];
$subject = $_SESSION['somo'];
//$alama = $_POST['alama'];
$ExamNo = $_GET['ExamNo'];

//get the specific candidate
$mtahiniwa = mysql_query("
SELECT	ExamNo,
		$subject
FROM	tblmarksdetail
WHERE	RegionNo = '$RegionNo'
AND		DistrictNo = '$DistrictNo'
AND		SchoolNo = '$SchoolNo'
AND		ExamNo = '$ExamNo'
") or die (mysql_error());

$row = mysql_fetch_row($mtahiniwa);
$alama = $row[1];	
	
?>


<html>
<head>
<link href="table.css" rel="stylesheet" type="text/css">
</head>
<body onload="document.alama.alama.focus()">

<form name="alama" method="post" action="processNext.php">
<table align="center">
	<tr>
		<td>Namba ya Mtahiniwa:</td>
		<td><? echo $ExamNo ?></td>
	</tr>

	<tr>
		<td>Alama:</td>
		<td><input type="text" name="alama" value="<? echo $alama ?>" /></td>
	</tr>
	<tr>
		<td><input type="hidden" name="ExamNo" value="<? echo $ExamNo ?>" /></td>
		<td><input type="hidden" name="index" value="<? echo $index ?>" /></td>
		<td><input type="hidden" name="idadi" value="<? echo $idadi ?>" /></td>
		<td><input type="hidden" name="somo" value="<? echo $somo ?>" /></td>
		<td><input type="submit" name="submit" id="submit" value="Sasisha"></td>
	</tr>
</table>
</form>

</body>
</html>
