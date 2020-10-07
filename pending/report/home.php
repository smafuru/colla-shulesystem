<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$report = $_GET['report'];
if ($report=='m2') {
    $source = 'm2.php';
}
if ($report=='audit') {
    $source = 'audit.php';
}
?>
<frameset rows="100,*" frameborder="0" border="0" framespacing="0">
	<frame name="menu" src="frame_header.php" marginheight="0" marginwidth="0" scrolling="auto" noresize>
	<frame name="content" src="<?echo $source?>" marginheight="0" marginwidth="0" scrolling="auto" noresize>

<noframes>
<p>
    YOU MUST USE A BROWSER THAT SUPPORT FRAMES!
</p>
</noframes>

</frameset>