<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
$RegionNo = $_SESSION['mkoa'];
$RegionName = $_SESSION['jinaMkoa'];
$DistrictNo = $_SESSION['wilaya'];
$DistrictName = $_SESSION['jinaWilaya'];
$SchoolNo = $_SESSION['shule'];
$SchoolName = $_SESSION['jinaShule'];
$username = $_SESSION['username'];

?>
<style type="text/css">
body {
	font-family:verdana,arial,sans-serif;
	font-size:10pt;
	margin:10px;
	}
</style>
</head>
<body>

    <div align = "center" >
	| <a href="/PSLE/index.php"  TARGET="_top">Toka</a>
	| <a href="/PSLE/majukumu.php"  TARGET="_top">Home</a>
	| <a href="/PSLE/alama/chaguaMKoa.php" TARGET="_top">Badili MKoa</a>
	| <a href="/PSLE/alama/chaguaWilaya.php?mkoa=<? echo $RegionNo ?>" TARGET="_top">Badili Wilaya</a>
	| <a href="/PSLE/alama/chaguaShule.php?wilaya=<? echo $DistrictNo ?>" TARGET="_top">Badili Shule</a>
	| <a href="/PSLE/alama/chaguaSomo.php?shule=<? echo $SchoolNo ?>" TARGET="_top">Badili Somo</a>
</div>
</body>