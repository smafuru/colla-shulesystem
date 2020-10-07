<?php
include('class/School.php');
$school = new School();
$school->adminLoginStatus();
include('inc/header.php');
?>
<html>
    <head>
        <title>PHP User Registration Form</title>
         <?php include('include_files.php'); ?>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>		
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
        <script src="js/user.js"></script>
        <?php include('inc/container.php'); ?>
        


        <link href="style.css" type="text/css" rel="stylesheet" />
    </head>
    <body>

        <div class="container">	
            <?php include('side-menu.php'); ?>
            <div class="content">
                <div class="container-fluid">
                    <div>   
                        <a href="#"><strong><span class="ti-crown"></span>System Users</strong></a>

                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3 class="panel-title">jjjj</h3>
                                </div>
                                <div class="col-md-2" align="right">
                                    <button type="submit" name="add" id="addUser" class="btn btn-success btn-xs">Register user</button>
                                </div>
                            </div>
                        </div>

                        <table id="userList" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>First name</th>
                                    <th>Last name</th>	
                                    <th>Email</th>
                                    <th>Gender</th>	
                                    <th>Mobile No.</th>
                                    <th>Designation</th>
                                     <th>Image</th>
                                    <th>Type</th>	
                                    <th>Status</th>
                                    <th>Token</th>
                                    
                                    <th colspan-2></th>
				     <th></th>
                                </tr>
                            </thead>
                        </table>

                    </div>		
                </div>	
            </div>	
        </div>	

       <div id="userModal" class="modal fade">
            <div class="modal-dialog">

                <form name="frmRegistration" method="POST" id="userForm" enctype = "multipart/form-data" action="">
 
                    <div class="modal-content">
                        <div class="modal-body">

                            <table border="0" width="30" align="center" class="demo-table">

                                    
                                <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <p class="modal-title thead"><i class="fa fa-plus"></i>Register User</p>
                        </div>                                    
                                        
                                <tr>
                                    <td><input type="text" class="demoInputBox" placeholder="First name" id="first_name" name="first_name"  required></td>

                                    <td><input type="text" class="demoInputBox" placeholder="Last name" id="last_name" name="last_name" required></td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="demoInputBox" placeholder="Email address" id="email" name="email"  required></td>

                                </tr>
                                <tr>
                                    <td><input type="password" class="demoInputBox" placeholder="Password" name="password" required id = "password" onkeyup='check();'/></td>

                                    <td><input type="password" class="demoInputBox" placeholder="Confirm password"  name="confirm_password"  required id="confirm_password"  onkeyup='check();'  />
                                   <span id='message'>  </span>
                                </tr>
                                <tr>
                                <td><script>
            
            $('#password').on('blur', function(){
    if(this.value.length < 4){ // checks the password value length
       alert('Please enter atleast 4 characters for password');
       $(this).focus(); // focuses the current field.
       return false; // stops the execution.
    }
});
                     var check = function() {               

  if (document.getElementById('password').value ==
    document.getElementById('confirm_password').value) {
    document.getElementById('message').style.color = 'green';
    document.getElementById('message').innerHTML = 'matching';
  } else {
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'not matching';
  }
}     
  
                                    </script></td></tr>
                                <tr>
                                    <td>Gender<i class="ti-hand-point-right fa-1x"></i></td>
                                    <td> 
                                        <input type="radio" name="gender" id="male" value="male"> Male
                                        <input type="radio" name="gender" id="female" value="female" > Female
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="demoInputBox" placeholder="Mobile Number" id="mobile" name = "mobile"  required></td>

                                    <td><input type="text" class="demoInputBox" placeholder="Designation" id="designation" name="designation" required></td>
                                </tr>
                                <tr>
                                    <td width="10">Upload(passport size)<i class="ti-hand-point-right fa-1x"></i></td>
                                    <td>  <div class="form-group">
						<input type="file" class="form-control fa-1x" id="image" name="image" placeholder="User photo" required >				
					</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><div class="form-group">
                                            <select class="md-select md-form" id="type" name="type" size="1">
                                                <option>Choose Type</option>
                                                <option>Student</option>
                                                <option>Teacher</option>
                                                <option>Board member</option>
                                                <option>Partner</option>
                                                <option>Committee</option>
                                                <option>Administrator</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div> 
                                    </td>
                                
                                    
                                  
                                    <td> <div class="form-group">
                                            <select class="md-select md-form" id="status" name="status" size="1">
                                                <option>Specify Status</option>
                                                <option>Active</option>
                                                <option>Pending</option>
                                                <option>Deleted</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div> 
                                    </td>
                                    
                                </tr>
                              
                                <tr>
                                    <td width="15%"><input type="text" class="demoInputBox" placeholder="Authentication Token" id="authtoken" name="authtoken" required></td>
                                   </tr>
                                       <tr>
                                    <td>
                                        
                                        <div class="modal-footer">
					<input type="hidden" name ="userid" id ="userid" />
					<input type="hidden" name ="action" id ="action" value="updateUser" />
					<input type="submit" name ="save" id="save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				          </div>
                                    </td>
                                </tr> 
                            </table>
                        </div>

                
            </div>
           </form>
        </div>
    </div>

<?php include('inc/footer.php'); ?>
</body>
</html>