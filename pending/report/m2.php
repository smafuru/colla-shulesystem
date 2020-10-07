<?php

session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/PSLE/connection.php');
include('Jumla.php');
include('PDF.php');
include($_SERVER['DOCUMENT_ROOT'] . '/PSLE/alama/UserSchool.php');
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
$lock = 2;
//check kama hii host iko blocked
if (isBlocked ()) {
    echo "<h1 align = 'center'> <a href=/psle/alama/chaguaSomo.php?shule=$SchoolNo>Your are not allowed to print from this computer
! <br> Please, use the laptops provided instead.</a> </h1>";
    exit;
}
//tunataka kujua kama  huyu mtu anaruhusiwa kufanyia kazi kituo hiki!
$ownership = new UserSchool();
$owner = $ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo);
if ($owner == $username ) {
    //hakuna neno, we endelea tuu
} else {
    echo "<h1 align = 'center'> Kituo Hiki Kinafanyiwa Kazi na:" . $ownership->getOwner($SchoolNo, $DistrictNo, $RegionNo) . " </h1>";
    echo "<h1 align = 'center'> <a href=/psle/alama/chaguaSomo.php?shule=$SchoolNo>Tafadhali Badili Shule!</a> </h1>";
    exit;
}

//lock kituo
mysql_query("
	UPDATE	tblmarksdetail
	SET	lockStatus = '$lock'
	WHERE	RegionNo = '$RegionNo'
	AND	DistrictNo = '$DistrictNo'
	AND	SchoolNo = '$SchoolNo'
") or die(mysql_error());

$m2 = mysql_query("
SELECT 	
	ExamNo,
	Kiswahili,
	English,
	Maarifa,
	Hisabati,
	Sayansi
FROM	tblmarksdetail
WHERE	RegionNo = '$RegionNo'
AND		DistrictNo = '$DistrictNo'
AND		SchoolNo = '$SchoolNo'
") or die(mysql_error());

$pdf = new PDF();
$pdf->setHeaderValues($RegionNo, $RegionName, $DistrictNo, $DistrictName, $SchoolNo, $SchoolName);
$pdf->setFooterValues($firstName, $lastName);

$pdf->SetLeftMargin(20);
$pdf->SetTopMargin(10);

$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();
$lineNo = 1;
while ($row = mysql_fetch_array($m2)) {
    $jumla = new Jumla();

    $pdf->Cell(25, 5, $row['ExamNo'], 1, 0, 'C');

    //check kama kiswahili kuna alama
    if (empty($row['Kiswahili']) || is_null($row['Kiswahili'])) {
        //output dash kwenye report
        $pdf->Cell(25, 5, '-', 1, 0, 'C');
    } else {
        //weka alama kwenye report
        $pdf->Cell(25, 5, $row['Kiswahili'], 1, 0, 'C');
        //peleka kweye Total.php ili ikajumlishwe
        $jumla->addElement($row['Kiswahili']);
    }
    if (empty($row['English']) || is_null($row['English'])) {
        //output dash kwenye sehemu ya english kwenye report
        $pdf->Cell(25, 5, '-', 1, 0, 'C');
    } else {
        //weka alama ya english kwenye report
        $pdf->Cell(25, 5, $row['English'], 1, 0, 'C');
        //peleka alama ya english kwenye total ili ikajumlishwe
        $jumla->addElement($row['English']);
    }
    if (empty($row['Maarifa']) || is_null($row['Maarifa'])) {
        //weka dash sehemu ya maarifa kwenye report
        $pdf->Cell(25, 5, '-', 1, 0, 'C');
    } else {
        //weka alama za maarifa kwenye report
        $pdf->Cell(25, 5, $row['Maarifa'], 1, 0, 'C');
        //peleka alama za maarifa kwenye Total.php zikajumlishwe
        $jumla->addElement($row['Maarifa']);
    }
    if (empty($row['Hisabati']) || is_null($row['Hisabati'])) {
        //weka dash sehemu ya sayansi kwenyer report
        $pdf->Cell(25, 5, '-', 1, 0, 'C');
    } else {
        //weka alama ya maarifa kwenye report
        $pdf->Cell(25, 5, $row['Hisabati'], 1, 0, 'C');
        //peleka alama za sayansi kweye Total.php zikajumlishwe
        $jumla->addElement($row['Hisabati']);
    }
    if (empty($row['Sayansi']) || is_null($row['Sayansi'])) {
        $pdf->Cell(25, 5, '-', 1, 0, 'C');
    } else {
        $pdf->Cell(25, 5, $row['Sayansi'], 1, 0, 'C');
        $jumla->addElement($row['Sayansi']);
    }
    //tafuta jumla ya alama kwa candidate huyu
    $jumlaKuu = $jumla->getTotal();
    //hakikisha jumla ya alama inakua katika digiti tatu
    if (is_numeric($jumlaKuu)) {
        if (strlen($jumlaKuu) == 1) {
            $jumlaKuu = "00" . $jumlaKuu;
        } elseif (strlen($jumlaKuu) == 2) {
            $jumlaKuu = "0" . $jumlaKuu;
        }
    }
    $pdf->Cell(25, 5, $jumlaKuu, 1, 0, 'C');
    //break line
    $lineNo = $lineNo + 1;
    $pdf->Ln();
}

function isBlocked() {
    $thisHost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$thisHost = 0;
	
    list($is_blocked) = mysql_fetch_array(mysql_query("select distinct is_blocked from blocked_ip where ip = '$thisHost'"));
    }

  

$pdf->Output();
?>