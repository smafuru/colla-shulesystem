<?php
session_start();
if (isset($_SESSION['usr_id'])) {
    //header("Location: index.php");
}
//include_once 'dbconnect.php';
//set validation error flag as false
$error = false;
//check if form is submitted with escape string function
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
if (isset($_POST['signup'])) {
    $f_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $m_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $s_name = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $user_group = mysqli_real_escape_string($con, $_POST['gender']);
    $subject_name = mysqli_real_escape_string($con, $_POST['mobile']);
    $user_group = mysqli_real_escape_string($con, $_POST['designation']);
    $user_group = mysqli_real_escape_string($con, $_POST['image']);
    $subject_name = mysqli_real_escape_string($con, $_POST['type']);
    $user_group = mysqli_real_escape_string($con, $_POST['status']);
    $subject_name = mysqli_real_escape_string($con, $_POST['authtoken']);

    //name can contain only alpha characters and space
    if (!preg_match("/^[a-zA-Z ]+$/", $f_name)) {
        $error = true;
        $f_name_error = "Name must contain only alphabets and space";
    }
    if (!filter_var($user_name)) {
        $error = true;
        $user_name_error = "Please Enter Valid User Name ID";
    }
    //Make Password strong is good for security matters
    if (strlen($password) < 6) {
        $error = true;
        $password_error = "Password must be minimum of 6 characters";
    }
    if ($password != $cpassword) {
        $error = true;
        $cpassword_error = "Password and Confirm Password doesn't match";
    }//Insert into Database
    if (!$error) {
        if (mysqli_query($con, "INSERT INTO users(first_name,last_name,email,password,gender,mobile,designation,image,type,status,authtoken)VALUES ('" . $first_name . "','" . $last_name . "','" . $email . "','" . md5($password) . "','" . $gender . "','" . $mobile . "','" . $designation . "','" . $image . "','" . $type . "','" . $status . "','" . $authtoken . "')")) {
            $successmsg = "Successfully Registered! <a href='index.php'>Click here to Login</a>";
//            echo "INSERT INTO users(f_name,m_name,s_name,user_name,password,user_group,subject_name)VALUES ('" . $f_name . " ', ' " . $m_name . " ',' " . $s_name . " ',   ' " . $user_name . " ', '" . md5($password) . "', '" . $user_group . "', '" . $subject_name . "' ";
        } else {
            $errormsg = "Please fill all the mandatory items for registration!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>User Registration Script</title>
        <?php include('include_files.php'); ?>
        <?php include('inc/container.php'); ?>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="fontawesome-free/css/all.min.css"/>
        <link rel="stylesheet" href="fontawesome-free/js/all.min.css"/>
        <link rel="stylesheet" href="mycss/mycss.css"/>

    </head>
    <body class="bg-warning">


        <div class="container-fluid">
            <?php //require_once './includes/nav.php'; ?>

        </div>
        <div class="container">
        <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="signupform">

            <legend>Registration Form</legend>


            <div class="row">
                <div class="col-md-2">

                <input type="text" name="first_name" placeholder="First name" required value="<?php if ($error) echo $first_name; ?>" class="form-control" />
                <span class="text-danger"><?php if (isset($first_name_error)) echo $first_name_error; ?></span>
            </div>
                    <div class="col-md-2">
            <div class="form-group">

                <input type="text" name="last_name" placeholder="Last name" required value="<?php if ($error) echo $last_name; ?>" class="form-control" />
                <span class="text-danger"><?php if (isset($last_name_error)) echo $last_name_error; ?></span>
            </div>
                    </div>

             <div class="col-md-2">
            <div class="form-group">

                <input type="text" name="email" placeholder="E-mail address" required value="<?php if ($error) echo $email; ?>" class="form-control" />
                <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
            </div>
             </div>
            </div> 
               <div class="row"> 
            <div class="col-md-2">
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required class="form-control" />
                <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
            </div>
            </div>
                
                
             <div class="col-md-2">
            <div class="form-group">

                <input type="password" name="cpassword" placeholder="Confirm Password" required class="form-control" />
                <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
            </div>
             </div>

                    
              <div class="col-md-2">
            <div class="form-group">
                <select class="md-select md-form push-right" name="gender" size="1">
                    <option>Choose Gender</option>
                    <option>Male</option>
                    <option>Fale</option>
                </select>
                <span class="text-danger"><?php if (isset($gender_error)) echo $gender_error; ?></span>
            </div>   
                  </div>  
               </div>

            <div class="row">
            <div class="col-md-2">
            <div class="form-group">

                <input type="text" name="mobile" placeholder="Mobile Number" required value="<?php if ($error) echo $mobile; ?>" class="form-control" />
                <span class="text-danger"><?php if (isset($mobile_error)) echo $mobile_error; ?></span>
            </div>
                </div>


            <div class="col-md-2">
            <div class="form-group">

                <input type="text" name="designation" placeholder="Designation" required value="<?php if ($error) echo $designation; ?>" class="form-control" />
                <span class="text-danger"><?php if (isset($designation_error)) echo $designation_error; ?></span>
            </div>
            </div>
                 <div class="col-md-2">
            <div class="form-group">
                <div id="form-label"> Upload image(Passport Size Max = 15MB)</div>
                <input type="file" name="image" placeholder="Upload image"/>
            </div>
                 </div>
            </div>
               
            <div class="row">
             <div class="col-md-2">
            <div class="form-group">
                <select class="md-select md-form" name="type" size="1">
                    <option>Type</option>
                    <option>Teacher</option>
                    <option>Student</option>
                    <option>Board member</option>
                    <option>Partner</option>
                    <option>Committee</option>
                    <option>Administrator</option>
                </select>
                <span class="text-danger"><?php if (isset($type_error)) echo $type_error; ?></span>
            </div>  
             </div>

                <div class="col-md-2">
                
            <div class="form-group">
                <select class="md-select md-form push-right" name="status" size="1">
                    <option>Choose status</option>
                    <option>Active</option>
                    <option>Pending</option>
                    <option>Deleted</option>
                </select>
                <span class="text-danger"><?php if (isset($status_error)) echo $status_error; ?></span>
            </div>  
                </div>

            </div>
            <div class="form-group">
                <input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
            </div>
            </div>
            <span class="text-success">
                <div class="text-center text-primary"><?php
                    if (isset($successmsg)) {
                        echo $successmsg;
                    }
                    ?>
                    <span class="text-danger"><?php
                        if (isset($errormsg)) {
                            echo $errormsg;
                        }
                        ?></div></span>





        </form>




    </div>
    <?php include('inc/footer.php'); ?>
      
</body>
</html>
