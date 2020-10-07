<?php
//session_start();
include('class/config.php');
require($_SERVER['DOCUMENT_ROOT'].'/matokeo/datagrid/class.datagrid.php');
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
//tunataka kujua kama  huyu mtu anaruhusiwa kufanyia kazi kituo hiki!
$ownership = new UserSchool();

if ($ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo)==$username) {
	//hakuna neno, we endelea tuu
} else {
	echo "<h1 align = 'center'> Kituo Hiki Kinafanyiwa Kazi na:".$ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo)." </h1>";
	echo "<h1 align = 'center'> <a href='chaguaSomo.php?shule=$SchoolNo'>Tafadhali Badili Shule!</a> </h1>";
	exit;
	
}

//kamata somo kutoka post
if (isset($_POST['somo'])) {
	$somo = $_POST['somo'];
	//weka somo kwenye sessio
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
AND	tbldistrict.DistrictNo = tblschool.DistrictNo
AND	tbldistrict.RegionNo = tblschool.RegionNo
AND	tblschool.RegionNo = tblmarksdetail.RegionNo
AND	tblschool.DistrictNo = tblmarksdetail.DistrictNo
AND	tblschool.SchoolNo = tblmarksdetail.SchoolNo
AND	tblregion.RegionNo = '$RegionNo'
AND	tbldistrict.DistrictNo = '$DistrictNo'
AND	tblschool.SchoolNo = '$SchoolNo'
";

$watahiniwaResults = mysql_query($watahiniwa) or die (mysql_error());
//idadi ya watahiniwa wa shule hii
$idadi = mysql_num_rows($watahiniwaResults);

if (isset($_SESSION['index'])){ //kama value ya index inatoka process.php
	$index = $_SESSION['index'];
	if ($index >= $idadi) { //kama index imeshakua incremented kufikia idadi ya records
		$index = 0; //restart to the first record
	}
} else { //kama ndio tunaingia mara ya kwanza kwenye page hii, initialize value ya index
	$index = 0;
}

if ($index < $idadi) { //sogea next record tuu edapo value ya index bado kufikia idadi ya records
	//move one step forward ... kwa index moja.
	mysql_data_seek($watahiniwaResults, $index);
}
//kamata row kwenye hiyo position
/*$row = mysql_fetch_row($watahiniwaResults);
//kamata column moja moja kwenye hiyo row
$ExamNo = $row['2']; //cadidate namba
*/
$row = mysql_fetch_object($watahiniwaResults); 
$ExamNo = $row->ExamNo;
$alama = $row->$somo;
//instatiate grid class
$grid = new display_grid();
//set query
$grid -> set_sql($watahiniwa);
$grid->set_sql_key("ExamNo");
//hide some columns
$grid->set_col_hidden("SchoolNo");
$grid->set_col_hidden("SchoolName");

$grid->set_col_link("HARIRI", "haririAlama.php", "ExamNo");
$grid->set_ok_nl2br(true);
//$grid->set_ok_rowindex(true);
$grid->set_toolbar_enabled(true);

?>
<html>
<head>

<link rel="stylesheet" href="<? echo $rootUrl ?>/grid/grid_style.css" type="text/css" />
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
| <a href="/PSLE/majukumu.php">Home</a> 
| <a href="chaguaMKoa.php">Badili MKoa</a> 
| <a href="chaguaWilaya.php?mkoa=<? echo $RegionNo ?>">Badili Wilaya</a>  
| <a href="chaguaShule.php?wilaya=<? echo $DistrictNo ?>">Badili Shule</a> 
| <a href="chaguaSomo.php?shule=<? echo $SchoolNo ?>">Badili Somo</a>
</div>
<p>
<form name="fomu" method="post" action="processNext.php">
<table align = "center">
	<tr>
		<td>Namba |</td>
		<td>Alama</td>
	</tr>
	<tr>
		<td><? echo $ExamNo ?></td>
		<td><input type="text" name="alama" value="<? //echo $alama ?>" /></td>
		<td><input type="submit" name="submit" id="submit" value="Submit"></td>
	</tr>
</table>
		<input type="hidden" name="ExamNo" value="<? echo $ExamNo ?>" />
		<input type="hidden" name="index" value="<? echo $index ?>" />
		<!--<input type="hidden" name="idadi" value="<? echo $idadi ?>" />
		--><input type="hidden" name="somo" value="<? echo $somo ?>" />
		

</form>
<div align = "center"> <!--Hii ni muhimu kwa ajili ya MSIE 7, FIREFOX INAFANYA KAZI VIZURI BILA HII -->
<?
// Print the table
$grid -> display();

?>
</div>
</body>
</html>
