<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/connection.php');
include('Jumla.php');
include('PDF.php');
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/alama/UserSchool.php');

$users = mysql_query("
	select tblmarksdetail.userid as jina, remarks as MRG, count(*) as idadi
	from tblmarksdetail, users
	where tblmarksdetail.userid = users.userid
	group by remarks, tblmarksdetail.userid
");

$pdf = new FPDF();
//$pdf->setHeaderValues($RegionNo, $RegionName, $DistrictNo, $DistrictName, $SchoolNo, $SchoolName );
//$pdf->setFooterValues($firstName, $lastName);
//$pdf->title("zuia");

$pdf->SetLeftMargin(20);
$pdf->SetTopMargin(10);

$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();
echo "<table>";
while ($row = mysql_fetch_array($users) ) {	
	//$pdf->Cell(25,5, $row['MRG'],1,0, 'C');
	//$pdf->Ln();
	echo "<tr>";
	echo "<td>".$row['MRG']." - ".$row['jina']." - ".$row['idadi'];
	echo "</tr>";
}
echo "</table>"
//$pdf->Output();
?>