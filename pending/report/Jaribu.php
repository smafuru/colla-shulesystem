<?php

include('Jumla.php');

$jumla = new Jumla();
$x = 0;
$jumla->addElement($x);
$jumla->addElement("kk");
//$jumla->addElement(5);
$jumla->addElement("kk");
echo $jumla->getTotal();

?>