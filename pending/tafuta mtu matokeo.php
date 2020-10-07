<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/PSLE/usalama/isLogedIn.php');

//$idadi = $_SESSION['idadi']; //haitumiki kwa sasa hivi

if (isset($_POST['namba'])) { //mtumiaji kaingiza namba ya mtahiniwa kwenye fomu hii na kupost
	$index = $_POST['namba']-1; //index ni kubwa kwa moja zaidi ya namba ya mtahiniwa
	header( 'Location: /PSLE/alama/mpunga.php?command=nenda&index='.$index );
}

?>
<br><br>
<h3 align = "center"> Ingiza Namba ya Mtahiniwa Unaemtafuta</h3>
<br>
<form name="fomu" method="post" action="tafuta.php">
<table align = "center">
	<tr>
		<td>Namba ya Mtahiniwa:</td>
		<td><input type="text" name="namba" value="" MAXLENGTH="3"/></td>
		<td><input type="submit" name="submit" id="submit" value="Tafuta"></td>
	</tr>
</table>		
		
</form>