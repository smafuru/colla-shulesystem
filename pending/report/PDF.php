<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/fpdf16/fpdf.php');

class PDF extends FPDF {
	var $RegionNo;
	var $RegionName;
	var $DistrictNo;
	var $DistrictName;
	var $SchoolNO;
	var $SchoolName;

	var $firstName;
	var $lastName;

	var $zuia;

	function setHeaderValues($RegionNo, $RegionName, $DistrictNo, $DistrictName, $SchoolNo, $SchoolName ) {
		$this->RegionNo = $RegionNo;
		$this->RegionName = $RegionName;
		$this->DistrictNo = $DistrictNo;
		$this->DistrictName = $DistrictName;
		$this->SchoolNo = $SchoolNo;
		$this->SchoolName = $SchoolName;
	}

	function setFooterValues($firstName, $lastName) {
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}
	//function ya kuzuia baadhi ya headers fulani kutokana na matumizi tofauti
	public function title($zuia) {
		$this->zuia = $zuia;
	}
	
	//Page header
	function Header()
	{

		$this->SetFont('Arial','I',8);
		//Page number
		$this->Cell(165);
		$this->Cell(10,10,'UKURASA '.$this->PageNo().'/{nb}',0,0,'R');
		if($this->zuia==="zuia") { //iwapo titles zimezuiwa
			//$this->Cell(2, 10, "Marekebisho", 0, 0, "L");	
		} else {
			$this->Cell(2, 10, "M2", 0, 0, "L");	
		}
		
		$this->Ln();
		//Title
		$this->SetFont('Arial','B',12);
		//heading ya report
		$this->Cell(0,5, 'BARAZA LA MITIHANI LA TANZANIA',0,1,'C');
		if($this->zuia==="zuia") { //iwapo titles zimezuiwa
			$this->Cell(0,5, 'FOMU YA UHAKIKI WA MAREKEBISHO YA ALAMA',0,2,'C');
		} else {
			$this->Cell(0,5, 'FOMU YA ALAMA ZA WATAHINIWA WA MTIHANI WA CHETI CHA ELIMU YA MSINGI',0,2,'C');
		}
		$yr=date('Y');
		$this->Cell(0,5, 'PSLE '.$yr,0,2,'C');
		$this->Ln();

		$this->SetFont('Arial','B',10);
		$this->Cell(35,5, 'Namba ya Mkoa',0,0);
		$this->Cell(20,5, $this->RegionNo,0,0);
		$this->Cell(35,5, 'Jina la Mkoa',0,0);
		$this->Cell(60,5, $this->RegionName,0,0);
		$this->Ln();
		$this->Cell(35,5, 'Namba ya Wilaya',0,0);
		$this->Cell(20,5, $this->DistrictNo,0,0);
		$this->Cell(35,5, 'Jina la Wilaya',0,0);
		$this->Cell(60,5, $this->DistrictName,0,0);
		$this->Ln();
		$this->Cell(35,5, 'Namba ya Shule',0,0);
		$this->Cell(20,5, $this->SchoolNo,0,0);
		$this->Cell(35,5, 'Jina la Shule',0,0);
		$this->Cell(60,5, $this->SchoolName,0,0);

		$this->Ln();
		$this->Ln();
		if($this->zuia==="zuia") { //iwapo titles zimezuiwa
			$this->Cell(25,5, 'NAMBA ',1,0, 'C');
			$this->Cell(25,5, 'AWALI',1,0, 'C');
			$this->Cell(25,5, 'SASA',1,0, 'C');
			$this->Cell(25,5, 'SOMO',1,0, 'C');
			$this->Cell(35,5, 'SAA',1,0, 'C');
			$this->Ln();
		} else {
			$this->Cell(175,5, 'ALAMA ZA MASOMO',1,2,'C');
			$this->Cell(25,5, 'NAMBA ',1,0, 'C');

			$this->Cell(25,5, '01 KISWAHILI',1,0, 'C');
			$this->Cell(25,5, '02 ENGLISH',1,0, 'C');
			$this->Cell(25,5, '03 MAARIFA',1,0, 'C');
			$this->Cell(25,5, '04 HISABATI',1,0, 'C');
			$this->Cell(25,5, '05 SAYANSI',1,0, 'C');
			$this->Cell(25,5, 'JUMLA',1, 0,'C');
			$this->Ln();
		}

	}

	//Page footer
	function Footer()
	{
		$this->Ln();
		//Position at 2.0 cm from bottom
		$this->SetY(-20);
		$this->SetFont('Arial','B',10);
		$this->Cell(35, 4, "Aliyeingiza Alama:", 0, 0);
		$this->Cell(50, 4, $this->firstName." ".$this->lastName, 0, 0);
		$this->Cell(38, 4, "Sahihi:___________", 0, 0);
		$this->Cell(15, 4, "Tarehe:", 0, 0);
		$this->Cell(10, 4, date("F j, Y"), 0,0);
		$this->Ln();
		$this->Ln();
		if($this->zuia==="zuia") { //iwapo titles zimezuiwa
			//DO NOTHING
		} else {
			$this->Cell(35, 4, "Aliyehakiki Alama:", 0, 0);
			$this->Cell(50, 4, "_____________________", 0, 0);
			$this->Cell(38, 4, "Sahihi:___________", 0, 0);
			$this->Cell(15, 4, "Tarehe:", 0, 0);
			$this->Cell(10, 4, "______________", 0,0);
		}

	}
}

?>