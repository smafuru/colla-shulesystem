<?php 
include('class/School.php');
$school = new School();
$school->adminLoginStatus();
include('inc/header.php');
?>
<title>webdamn.com : Demo School Management System with PHP & MySQL</title>
<?php include('include_files.php');?>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/subjects.js"></script>
<?php include('inc/container.php');?>

 <link href="style.css" type="text/css" rel="stylesheet" />
    </head>
    <body>


<div class="container">	
	<?php include('side-menu.php');	?>
	<div class="content">
		<div class="container-fluid">
			<div>   
				<a href="#"><strong><span class="ti-crown"></span>Subjects Section</strong></a>
				<hr>		
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-10">
							<h3 class="panel-title"></h3>
						</div>
						<div class="col-md-2" align="right">
							<button type="button" name="add" id="addSubject" class="btn btn-success btn-xs">Add New Subject</button>
						</div>
					</div>
				</div>
				<table id="subjectList" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Subject</th>	
							<th>Code</th>
							<th>Subject Type</th>	
                                                        <th>Department</th>	
                                                        
                                                        
							<th></th>
							<th></th>							
						</tr>
					</thead>
				</table>
				
			</div>			
		</div>		
	</div>	
</div>	
<div id="subjectModal" class="modal fade">
	<div class="modal-dialog">
            <style>
select {
background-color:#A2AB58;
color:#ff0;
font-size:20px;
-webkit-appearance:none;
outline:none
}
select:hover {
color:#00ff7f
}
            </style>
            <form method="POST" action="" name= "frmRegistration" id="subjectForm" >
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Subject</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="subject_name" class="control-label">Subject Name*</label>
						<input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Subject Name" required>					
					</div>	
					<div class="form-group">
						<label for="s_type" class="control-label">Subject Type</label><br>							
						<label class="radio-inline">
							<input type="radio" name="subject_type" id="theory" value="Theory" required>Theory
						</label>;
						<label class="radio-inline">
							<input type="radio" name="subject_type" id="practical" value="Practical" required>Practical
						</label>							
					</div>	
					<div class="form-group">
						<label for="code" class="control-label">Code*</label>
						<input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="code..." required>					
					</div>
                                    
                                    
                                    <div class="form-group">
                                        <label for="mname" class="control-label">Assign Department*</label>	
                                           <select name="dept_id" id="dept_id" class="form-control" required>
							<option value="">Select</option>
                                                       
							<?php echo $school->getDepartmentList(); ?>
						</select>
                                            <span class="text-danger"></span>
                                        </div> 
                                    
				</div>
				<div class="modal-footer">
					<input type="hidden" name="subject_id" id="subject_id" />
					<input type="hidden" name="action" id="action" value="updateSubject" />
					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php include('inc/footer.php'); ?>
</body>
</html>