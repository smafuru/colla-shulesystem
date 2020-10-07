<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/connection.php');
//require($_SERVER['DOCUMENT_ROOT'].'/matokeo/datagrid/class.datagrid.php');
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/grid/grid.php');
include('UserSchool.php');
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/usalama/isLogedIn.php');
$rootPath = $settings['rootPath'];
$rootUrl = $settings['rootUrl'];
//vuta details zote za mkoa, wilaya na shule kutoka kwenye session
$RegionNo = $_SESSION['mkoa'];
$RegionName = $_SESSION['jinaMkoa'];
$DistrictNo = $_SESSION['wilaya'];
$DistrictName = $_SESSION['jinaWilaya'];
$SchoolNo = $_SESSION['shule'];
$SchoolName = $_SESSION['jinaShule'];
$username = $_SESSION['username'];
//kamata status ya lock
$lock = mysql_fetch_object(mysql_query("
	SELECT	DISTINCT lockStatus
	FROM	tblmarksdetail
	WHERE	RegionNo = '$RegionNo'
	AND		DistrictNo = '$DistrictNo'
	AND		SchoolNo = '$SchoolNo'
"));
$lockStatu = $lock->lockStatus; 
//tunataka kujua kama  huyu mtu anaruhusiwa kufanyia kazi kituo hiki!
$ownership = new UserSchool();
$owner = $ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo);
if ($owner === $username) {
	//hakuna neno, we endelea tuu
} elseif (is_null($owner)) {
	//kwa kua hiki kituo hakina mwenyewe, basi kichukue
	$ownership->chukuaKituo($SchoolNo, $DistrictNo, $RegionNo, $username);
	
} else {
	echo "<h1 align = 'center'> Kituo Hiki Kinafanyiwa Kazi na:".$ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo)." </h1>";
	echo "<h1 align = 'center'> <a href=chaguaSomo.php?shule=$SchoolNo>Tafadhali Badili Shule!</a> </h1>";
	exit;	
}

//Kituo kimefungwa?
if ($lockStatu == 2) {
	echo "<h2 align = 'center'> Kituo Hiki Tayari Kimefungwa. Kama ni Lazima Kukifungua, Wasiliana na Msimamizi! </h2>";
	echo "<h2 align = 'center'> <a href=chaguaSomo.php?shule=$SchoolNo>Tafadhali Badili Shule!</a> </h2>";	
	exit;
}

//kamata somo kutoka post
if (isset($_POST['somo'])) {
	$somo = $_POST['somo'];
	//weka somo kwenye session
	$_SESSION['somo'] = $somo;
} else{
	//kamata somo kutoka kwenye session
	$somo = $_SESSION['somo'];
}  

//kamata watahiniwa wote wa mkoa, wilaya, shule zilizo chaguliwa
$watahiniwa = "
SELECT	
		tblschool.SchoolNo,
		tblschool.SchoolName,
		ExamNo,
		$somo,
		'HARIRI'

FROM	tblregion,
		tbldistrict,
		tblschool,
		tblmarksdetail

WHERE	tblregion.RegionNo = tbldistrict.RegionNo
AND		tbldistrict.DistrictNo = tblschool.DistrictNo
AND		tbldistrict.RegionNo = tblschool.RegionNo
AND		tblschool.RegionNo = tblmarksdetail.RegionNo
AND		tblschool.DistrictNo = tblmarksdetail.DistrictNo
AND		tblschool.SchoolNo = tblmarksdetail.SchoolNo
AND		tblregion.RegionNo = '$RegionNo'
AND		tbldistrict.DistrictNo = '$DistrictNo'
AND		tblschool.SchoolNo = '$SchoolNo'
";

$watahiniwaResults = mysql_query($watahiniwa) or die (mysql_error());
//idadi ya watahiniwa wa shule hii
$idadi = mysql_num_rows($watahiniwaResults);
//weka idadi kwenye session
$_SESSION['idadi'] = $idadi;

if ( isset ($_GET['command'])) {
	$command = $_GET['command'];
	$index = $_GET['index'];
} elseif (isset ($_GET['data'])) {
	$index = $_SESSION['index'];
} else {
	$index = 0;
}

if ($index < $idadi) { //sogea next record tuu edapo value ya index bado kufikia idadi ya records
	//move one step forward ... kwa index moja.
	mysql_data_seek($watahiniwaResults, $index);
}

$row = mysql_fetch_object($watahiniwaResults); 
$ExamNo = $row->ExamNo;
$alama = $row->$somo;

?>
<html>
<head>

<link rel="stylesheet" href="<? echo $rootUrl?>/grid/grid_style.css" type="text/css" />

<script type='text/javascript'>

function isNumeric(){
	var numericExpression = /^[0-9]+$/;
	var str = document.forms[0].alama.value;
	if((str.match(numericExpression) || str == "") && (str<=50 && str >= 0)){		
		return true;
	}else{
		alert("Data Uliyoingiza Ni Ndogo Kuliko 0 Au Kubwa Kuliko > 50 Au Sio Namba!");
		document.forms[0].alama.value=""
		exit;
		exit();
		exit(0);		
		return false;
	}
}

</script>
</head>
<body onLoad = "document.fomu.alama.focus()">
        <div align="center">
            <img src="../images/psleBanner.jpg" width="700" height="82" alt="banner"/>
        </div>
<table border = "1" align = "center" class='table_css'>
	<tr>
		<td><? echo "<h4>" .$RegionNo."-".$RegionName."</h4>"?></td>

		<td><? echo "<h4>" .$DistrictNo."-".$DistrictName."</h4>" ?></td>

		<td><? echo "<h4>" .$SchoolNo."-".$SchoolName."</h4>" ?></td>
	</tr>
</table>
<div align = "center" >
	| <a href="/PSLE/index.php">Toka</a>
	| <a href="/PSLE/majukumu.php">Home</a> 
	| <a href="chaguaMKoa.php">Badili MKoa</a> 
	| <a href="chaguaWilaya.php?mkoa=<? echo $RegionNo ?>">Badili Wilaya</a> 
	| <a href="chaguaShule.php?wilaya=<? echo $DistrictNo ?>">Badili Shule</a> 
	| <a href="chaguaSomo.php?shule=<? echo $SchoolNo ?>">Badili Somo</a>
</div>
<p>
<form name="fomu" method="post" action="processNext.php">
<H3 align="center"> <? echo $somo?></H3>
<H3 align="center"> <? echo " Watahiniwa: ".$idadi?></H3>
<table align = "center">
	<tr>
		<td>Namba | </td>
		<td>Alama</td>
	</tr>
	<tr>
		<td><? echo $ExamNo ?></td>
		<td><input type="text" name="alama" value="<? echo $alama ?>" MAXLENGTH="2" onKeyUp = "return isNumeric()"/></td>
		<td><input type="submit" name="submit" id="submit" value="Submit" onclick = "return isNumeric()" /></td>
	</tr>
</table>
		<input type="hidden" name="ExamNo" value="<? echo $ExamNo ?>" />
		<input type="hidden" name="index" value="<? echo $index ?>" />
		<input type="hidden" name="idadi" value="<? echo $idadi ?>" />
		<input type="hidden" name="somo" value="<? echo $somo ?>" />		

</form>
<div align = "center">   
<a href=processNext.php?command=rudi&index=<? echo $index ?>>Rudi Nyuma</a> |
<a href=processNext.php?command=tafuta&index=<? echo $index ?>>Tafuta</a> |
<a href=processNext.php?command=nenda&index=<? echo $index ?>>Nenda Mbele</a>
</div>

</body>
</html>
