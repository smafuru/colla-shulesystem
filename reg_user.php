<?php





if (count($_POST) > 0) {
    /* Form Required Field Validation */
    foreach ($_POST as $key => $value) {
        if (empty($_POST[$key])) {
            $message = ucwords($key) . " field is required";
            $type = "error";
            break;
        }
    }
    /* Password Matching Validation */
    if ($_POST['password'] != $_POST['confirm_password']) {
        $message = 'Passwords should be same<br>';
        $type = "error";
    }

    /* Email Validation */
    if (!isset($message)) {
        if (!filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid UserEmail";
            $type = "error";
        }
    }

    /* Validation to check if gender is selected */
    if (!isset($message)) {
        if (!isset($_POST["gender"])) {
            $message = " Gender field is required";
            $type = "error";
        }
    }

    /* Validation to check if Terms and Conditions are accepted */
    if (!isset($message)) {
        if (!isset($_POST["terms"])) {
            $message = "Accept Terms and conditions before submit";
            $type = "error";
        }
    }

    if (!isset($message)) {
        require_once("dbcontroller.php");
        $db_handle = new DBController();
        $query = "SELECT * FROM registered_users where email = '" . $_POST["userEmail"] . "'";
        $count = $db_handle->numRows($query);

        if ($count == 0) {
            $query = "INSERT INTO registered_users (user_name, first_name, last_name, password, email, gender) VALUES
			('" . $_POST["userName"] . "', '" . $_POST["firstName"] . "', '" . $_POST["lastName"] . "', '" . md5($_POST["password"]) . "', '" . $_POST["userEmail"] . "', '" . $_POST["gender"] . "')";
            $current_id = $db_handle->insertQuery($query);
            if (!empty($current_id)) {
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "activate.php?id=" . $current_id;
                $toEmail = $_POST["userEmail"];
                $subject = "User Registration Activation Email";
                $content = "Click this link to activate your account. <a href='" . $actual_link . "'>" . $actual_link . "</a>";
                $mailHeaders = "From: Admin\r\n";
                if (mail($toEmail, $subject, $content, $mailHeaders)) {
                    $message = "You have registered and the activation mail is sent to your email. Click the activation link to activate you account.";
                    $type = "success";
                }
                unset($_POST);
            } else {
                $message = "Problem in registration. Try Again!";
            }
        } else {
            $message = "User Email is already in use.";
            $type = "error";
        }
    }
}
?>
<html>
    <head>
        <title>PHP User Registration Form</title>
        
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<?php include('inc/container.php');?>
<?php include('include_files.php'); ?>

        <link href="style.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        
        <div class="container">	
            
            
            <div class="container">	
    <?php include('side-menu.php'); ?>
    <div class="content">
        <div class="container-fluid">
            <div>   
                <p><a href="#"><strong><span class="ti-crown"></span>Student Admission</strong></a>
                    <a href="results.php"><strong><span class="ti-crown"></span>Student Results</strong></a></p>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <h3 class="panel-title">jjjj</h3>
                        </div>
                        <div class="col-md-2" align="right">
                            <button type="button" name="add" id="addStudent" class="btn btn-success btn-xs">Student Admission</button>
                        </div>
                    </div>
                </div>
                <table id="studentList" class="table table-bordered table-striped">
                    <thead colspan-9><h4 class="text-center">Overall Students Ledger</h4></thead>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reg No</th>
                            <th>Roll No</th>	
                            <th>Name</th>
                            <th>Photo</th>	
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
        <form name="frmRegistration" method="post" action="">
            
            <table border="0" width="400" align="center" class="demo-table">
                <?php if (isset($message)) { ?>
                    <div class="message <?php echo $type; ?>"><?php echo $message; ?></div>
                <?php } ?>
                <div class="thead">User Registration Form</div>
                <tr>
                    <td><input type="text" class="demoInputBox" placeholder="First name" name="first_name" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>"></td>

                    <td><input type="text" class="demoInputBox" placeholder="Last name" name="last_name" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>"></td>
                </tr>
                <tr>
                    <td><input type="text" class="demoInputBox" placeholder="Email address" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"></td>

                </tr>
                <tr>
                    <td><input type="password" class="demoInputBox" placeholder="Password" name="password" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"></td>

                    <td><input type="password" class="demoInputBox" placeholder="Confirm password"  name="confirm_password" value="<?php if (isset($_POST['confirm_password'])) echo $_POST['confirm_password']; ?>"></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td> 
                        <input type="radio" name="gender" value="Male" <?php if (isset($_POST['gender']) && $_POST['gender'] == "Male") { ?>checked<?php } ?>> Male
                        <input type="radio" name="gender" value="Female" <?php if (isset($_POST['gender']) && $_POST['gender'] == "Female") { ?>checked<?php } ?>> Female
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="demoInputBox" placeholder="Mobile Number" name="mobile_no" value="<?php if (isset($_POST['mobile_no'])) echo $_POST['mobile_no']; ?>"></td>

                    <td><input type="text" class="demoInputBox" placeholder="Designation" name="designation" value="<?php if (isset($_POST['designation'])) echo $_POST['designation']; ?>"></td>
                </tr>
                <tr>
                    <td width="10">(passport size)</td>
                    <td>  <div class="form-group">
						<label for="firstname" class="control-label">Photo*</label>
						<input type="file" class="form-control" id="photo" name="photo" placeholder="User photo" required>				
					</div>
                    </td>
                </tr>
                <tr>
                    <td width="15%">Type</td>
                    <td> <div class="form-group">
                            <select class="md-select md-form" name="type" size="1">
                                <option>Choose Type</option>
                                <option>Teacher</option>
                                <option>Student</option>
                                <option>Board member</option>
                                <option>Partner</option>
                                <option>Committee</option>
                                <option>Administrator</option>
                            </select>
                            <span class="text-danger"><?php if (isset($type_error)) echo $type_error; ?></span>
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td width="15%">Status</td>
                    <td> <div class="form-group">
                            <select class="md-select md-form" name="status" size="1">
                                <option>Choose status</option>
                                <option>Active</option>
                                <option>Pending</option>
                                <option>Deleted</option>
                            </select>
                            <span class="text-danger"><?php if (isset($status_error)) echo $status_error; ?></span>
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td width="15%"></td>
                    <td><div class="form-group">
                            <input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
                    </td>
                </tr>
            </table>
</form>
        </div>
    

    <?php include('inc/footer.php'); ?>
</body>
</html>