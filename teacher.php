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
<script src="js/teacher.js"></script>
<?php include('inc/container.php');?>
<link href="style.css" type="text/css" rel="stylesheet" />

<div class="container">	
	<?php include('side-menu.php');	?>
	<div class="content">
		<div class="container-fluid">
			<div>   
				<a href="#"><strong><span class="ti-crown"></span> Teachers Section</strong></a>
				<hr>		
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-10">
							<h3 class="panel-title"></h3>
						</div>
						<div class="col-md-2" align="right">
							<button type="button" name="add" id="addTeacher" class="btn btn-success btn-xs">Add New Teacher</button>
						</div>
					</div>
				</div>
				<table id="teacherList" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>	
                                                        <th>Birth date</th>
                                                        <th>Appointment date</th>
							<th>Confirmation date</th>
                                                        <th>Check NO.</th>
							<th>File No.</th>
							<th>Mobile</th>
                                                        <th>Ed.Level</th>	
								
							<th>Specialization</th>
                                                        <th>Teach Subjects</th>
                                                         <th>Experience</th>	
							<th>Subject</th>
                                                        <th>Class</th>
                                                         <th>Section</th>	
                                                       
                                                        
                                                        <th colspan-2></th>
				                          <th></th>
							
														
						</tr>
					</thead>
				</table>
				
			</div>			
		</div>		
	</div>	
</div>	
<div id="teacherModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post"  name="frmRegistration" id="teacherForm">
			<div class="modal-content">
				<div class="modal-header">
                                    <div class="row">
                                        
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Teacher</h4>
                                    </div>
				</div>
				<div class="modal-body">
                                    <div class="row">
                                        
					
                                            <div class="col-md-5">
                                               <div class="form-group"> 
						<label for="teacher" class="control-label">Teacher Name*</label>
						<input type="text" class="form-control" id="teacher_name" name="teacher_name" placeholder="Teacher Name" required>
                                               </div>
					</div>	
                                        
                                    
                                    
                                        <div class="col-md-5">
                                            <div class="form-group">
						<label for="dob" class="control-label">Date of Birth*</label>
						<input type="text" class="form-control" id="dob" name="dob" placeholder="Date of birth" required>
                                                
					</div>	
                                        </div>
                                   
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-5">
                                    <div class="form-group">
						<label for="appointment" class="control-label">Appointment*</label>
						<input type="text" class="form-control" id="appointment" name="appointment" placeholder="Appointment" required>
                                                
					</div>	
                                        </div>
                                    
                                    <div class="col-md-5">
                                    <div class="form-group">
						<label for="confirmation" class="control-label">Confirmation*</label>
						<input type="text" class="form-control" id="confirmation" name="confirmation" placeholder="Confirmation" required>
                                                
					</div>	
                                    </div>
                                    </div>
                                    
                                    
                                    <div class="row">
                                        <div class="col-md-5">
                                    <div class="form-group">
						<label for="checkNumber" class="control-label">Check Number*</label>
						<input type="text" class="form-control" id="checkNo" name="checkNumber" placeholder="Check Number" required>
                                                
					</div>	
                                        </div>
                                    
                                        <div class="col-md-5">
                                    <div class="form-group">
						<label for="fileNumber" class="control-label">File Number*</label>
						<input type="text" class="form-control" id="fileNumber" name="fileNumber" placeholder="File Number" required>
                                                
					</div>	
                                        </div>
                                    </div>
                                    
                                     <div class="row">
                                        <div class="col-md-5">
                                         <div class="form-group">
						<label for="mobile" class="control-label">Mobile*</label>
						<input type="text" class="form-control" id="edLevel" name="mobile" placeholder="Mobile" required>
                                                
					</div>	
                                        </div>
                                         <div class="col-md-5">
                                    <div class="form-group">
						<label for="edLevel" class="control-label">Education Level*</label>
						<input type="text" class="form-control" id="edLevel" name="edLevel" placeholder="Highest Level" required>
                                                
					</div>	
                                         </div>
                                     </div>
                                    
                                    
                                     <div class="row">
                                        <div class="col-md-5">
                                    <div class="form-group">
						<label for="specialization" class="control-label">Specialization*</label>
						<input type="text" class="form-control" id="specialization" name="specialization" placeholder="specialization" required>
                                                
					</div>	
                                    
                                        </div>
                                         <div class="col-md-5">
                                    <div class="form-group">
						<label for="teachSubject" class="control-label">Teach Subjects*</label>
						<input type="text" class="form-control" id="teachSubject" name="teachSubject" placeholder="Teaching Subject" required>
                                                
					</div>	
                                         </div>
                                     </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2">
                                    <div class="form-group">
						<label for="teachExperience" class="control-label">Teaching Experience*</label>
						<input type="text" class="form-control" id="teachExperience" name="teachExperience" placeholder="in years" required>
                                                
					</div>	
                                        </div>
                                    
                                 <div class="col-md-5">
                                     <div class="form-group">
                                        <label for="mname" class="control-label">Assign Department*</label>	
                                           <select name="dept_name" id="dept_name" class="form-control" required>
							<option value="dept_id">Select</option>
                                                       
							<?php echo $school->getDepartmentList(); ?>
						</select>
                                            <span class="text-danger"></span>
                                        </div>
                                 </div>
                                    
			
                                        <div class="col-md-5">
				<div class="modal-footer">
					<input type="hidden" name="teacherid" id="teacherid" />
					<input type="hidden" name="action" id="action" value="updateTeacher" />
					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
                                   
		</form>
	</div>
</div>