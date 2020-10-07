<?php session_start();
     if ($_SESSION['loggedin']!=true){
	header ("location:index.php");
	}
include 'connect/connect.php';
$user=$_SESSION['username'];
$select=mysql_query("select * from student where student_reg='$user'");
$dis=mysql_fetch_array($select);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<head profile="http://gmpg.org/xfn/11">
<title>ESMS | Result - <?=$_SESSION['username'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="scripts/jquery.slidepanel.setup.js"></script>
<script type="text/javascript" src="scripts/jquery.cycle.min.js"></script>
<script type="text/javascript" src="scripts/jquery.cycle.setup.js"></script>
</head>
<body>
<?php include 'headerin.php'; ?>
<!-- ####################################################################################################### -->
<div class="wrapper col2">
  <div id="breadcrumb">
    <ul>
       <li class="first">You Are Here</li>
      <li>&#187;</li>
      <li><a href="student.php">Home</a></li>
      <li>&#187;</li>
      <li>Result</li>
    </ul>
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col3">
  <div id="container">
    <div id="content">
      <h1><?=$dis['firstname']." ".$dis['lastname'];?> &#187;&#187; Result</h1>
      <div id="respond">
<?php
$sqll=mysql_query("select * from class_term where student_reg = '$user'");
echo "<ul>";
while($showl=mysql_fetch_array($sqll)){
	$take=mysql_query("select class_name from class where class_id='$showl[class_id]'");
	$row=mysql_fetch_array($take);
echo '<li><a href="result-view.php?year='.$showl['year'].'&&term='.$showl['term'].'&&class='.$showl['class_id'].'"><font size="3">Result for '.$row['class_name'].' '.$showl['year'].' '.$showl['term'].'</font></a></li>';
}
echo '</ul>';
?>
<h2></h2>
      </div>
    </div>
    <div id="column">
      <div class="subnav">
        <h2>School Navigation</h2>
        <ul>
          <li><a href="student.php">Home</a></li>
          <li><a href="subject.php">View Subject</a></li>
          <li><a href="result.php">Result</a></li>
          <li><a href="communication.php">Communication</a></li>
        </ul>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- ####################################################################################################### -->
<?php include 'footer.php'; ?>
</body>
</html>
