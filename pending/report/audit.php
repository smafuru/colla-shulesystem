<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/connection.php');
include('Jumla.php');
include('PDF.php');
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/alama/UserSchool.php');
//root url

//variables zote hizi ziko kwenye session
$RegionNo = $_SESSION['mkoa'];
$RegionName = $_SESSION['jinaMkoa'];
$DistrictNo = $_SESSION['wilaya'];
$DistrictName = $_SESSION['jinaWilaya'];
$SchoolNo = $_SESSION['shule'];
$SchoolName = $_SESSION['jinaShule'];
//mtumiaji
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$username = $_SESSION['username'];
//code ya kufunga kituo
//$lock = 2;
//check kama hii host iko blocked
if (isBlocked()) {
    echo "<h1 align = 'center'> <a href=/psle/alama/chaguaSomo.php?shule=$SchoolNo>Your are not allowed to print from this computer
! <br> Please, use the laptops provided instead.</a> </h1>"; exit;
}

//tunataka kujua kama  huyu mtu anaruhusiwa kufanyia kazi kituo hiki!
$ownership = new UserSchool();
$owner = $ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo);
if ($owner == $username) {
	//hakuna neno, we endelea tuu
} else {
	echo "<h1 align = 'center'> Kituo Hiki Kinafanyiwa Kazi na:".$ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo)." </h1>";
	echo "<h1 align = 'center'> <a href=/psle/alama/chaguaSomo.php?shule=$SchoolNo>Tafadhali Badili Shule!</a> </h1>";
	exit;	
}

$isLocked = mysql_fetch_row(mysql_query("
	select 	distinct lockStatus 
	from 	tblmarksdetail
	where 	RegionNo = '$RegionNo'
	and		DistrictNo = '$DistrictNo'
	and		SchoolNo = '$SchoolNo'
"));
if ($isLocked[0] != 2) {
	echo "<h1 align = 'center'> Kituo Hiki Bado Hakijafungwa! </h1>";	
	echo "<h1 align = 'center'> <a href=/psle/alama/chaguaSomo.php?shule=$SchoolNo>Tafadhali Kafunge Shule Kwanza!</a> </h1>";
	exit;
} 
	
$audit = mysql_query("
	select 	ExamNo,
			alamaZamani 'AWALI',
			alamaSasa 'SASA',
			somo 'SOMO',
			saa
	from 	audit
	where	RegionNo = '$RegionNo'
	and		DistrictNo = '$DistrictNo'
	and		SchoolNo = '$SchoolNo'
	order by ExamNo
") or die (mysql_error());

$pdf = new PDF();
$pdf->setHeaderValues($RegionNo, $RegionName, $DistrictNo, $DistrictName, $SchoolNo, $SchoolName );
$pdf->setFooterValues($firstName, $lastName);
$pdf->title("zuia");

$pdf->SetLeftMargin(20);
$pdf->SetTopMargin(10);

$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();
$pdf->Link(15, 25, 33,33, $rootUrl."/alama/chaguaSomo.php?shule=$SchoolNo");

$lineNo = 1;

while ($row = mysql_fetch_array($audit) ) {	
	$pdf->Cell(25,5, $row['ExamNo'],1,0, 'C');
	if (empty($row['AWALI']) || is_null($row['AWALI'])) {
		
		$pdf->Cell(25,5, "-",1,0, 'C');
	} else {
		$pdf->Cell(25,5, $row['AWALI'],1,0, 'C');
	}
	if (empty($row['SASA']) || is_null($row['SASA'])) {
		$pdf->Cell(25,5, "-",1,0, 'C');
	} else {
		
		$pdf->Cell(25,5, $row['SASA'],1,0, 'C');
	}
	$pdf->Cell(25,5, strtoupper( $row['SOMO']),1,0, 'L');
	$pdf->Cell(35,5, $row['saa'],1,0, 'C');
		
	//break line
	$lineNo = $lineNo + 1;
	$pdf->Ln();
}

function isBlocked() {
    $thisHost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    list($is_blocked) = mysql_fetch_array(mysql_query("select distinct is_blocked from blocked_ip where ip = '$thisHost'"));

    if ($is_blocked == 1) {

        return true;
    } else {

        return false;
    }
}

$pdf->Output();

?>