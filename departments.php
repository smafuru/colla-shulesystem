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
<script src="js/departments.js"></script>
<?php include('inc/container.php');?>
<div class="container">	
	<?php include('side-menu.php');	?>
	<div class="content">
		<div class="container-fluid">
			<div>   
				<a href="#"><strong><span class="ti-crown"></span>Departments</strong></a>
				<hr>		
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-10">
							<h3 class="panel-title"></h3>
						</div>
						<div class="col-md-2" align="right">
							<button type="button" name = "add" id="addDepartment" class="btn btn-success btn-xs">ADD DEPARTMENT</button>
						</div>
					</div>
				</div>
				<table id="departmentList" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>	
                                                        <th>Head</th>	
                                                        
							<th></th>
							<th></th>							
						</tr>
					</thead>
				</table>
				
			</div>			
		</div>		
	</div>	
</div>	
<div id="departmentModal" class="modal fade">
	<div class="modal-dialog">
            <form method = "POST" name = "frmRegistration" id ="departmentForm" enctype = "multipart/form-data" action="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i>Edit Department</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="email" class="control-label">Department*</label>
						<input type="text" class="form-control" id="dept_name" name="dept_name" placeholder="Department name" required>							
					</div>									
                               
                            
                            
					<div class="form-group">
						<label for="email" class="control-label">Department Head*</label>
						<input type="text" class="form-control" id="dept_head" name="dept_head" placeholder="Head of Department" required>							
					</div>									
				</div>
                            
                        
				<div class="modal-footer">
					<input type="hidden" name= "dept_id" id = "dept_id" />
					<input type="hidden" name="action" id="action" value="updateDepartment" />
					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
                        </div>
		</form>
	</div>
</div>

<?php include('inc/footer.php'); ?>
