<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
include('class/School.php');
$school = new School();
$school->adminLoginStatus();
include('inc/header.php');

//vuta details zote za mkoa, wilaya na shule kutoka kwenye session
$cname = $_SESSION['cname'];
$examType = $_SESSION['examType'];
$email = $_SESSION['email'];


?>
<html>
    <head>
        <title>PHP User Registration Form</title>
         <?php include('include_files.php'); ?>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>		
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
        <script src="js/results.js"></script>
        <?php include('inc/container.php'); ?>
        


        <link href="style.css" type="text/css" rel="stylesheet" />
    </head>
    <body>

        <div class="container">	
            <?php include('side-menu.php'); ?>
            <div class="content">
                <div class="container-fluid">
                    <div>   <div class="row">
                            <div class="col-md-4">
                        <a href="#"><strong><span class="ti-crown fa-2x"></span>RESULTS ANALYSIS</strong></a>
                        </div>
                              <div class="col-md-4">
                        <a href="#"><strong><span class="ti-book fa-2x"></span>RESULTS ANALYSIS</strong></a>
                        </div>
                            
                            <div class="col-md-4">
                                <a href="dashboard.php"><strong><span class="ti-home fa-2x"></span>HOME</strong></a>
                        </div>
                        </div>
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3 class="panel-title">jjjj</h3>
                                </div>
                                <div class="col-md-2" align="right">
                                    <button type="submit" name="add" id="addResults" class="btn btn-success btn-xs">Enter Student Scores</button>
                                </div>
                            </div>
                        </div>

                        <table id="resultsList" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>First name</th>
                                    <th>Last name</th>	
                                    <th>Gender</th>	
                                    <th>Exam Type</th>
                                    <th>Subject</th>
                                    <th>Score</th>
                                      <th>Grade</th>
                                    <th>Position</th>	
                                    <th>Comments</th>
                                    
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

                <form name="frmRegistration" method="post" id="resultsForm" enctype = "multipart/form-data" action="">
 
                    <div class="modal-content">
                        <div class="modal-body">

                         

                                    
                                <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <p class="modal-title thead"><i class="fa fa-plus"></i>Examination/Test Scores Entry(ETSE)</p>
                        </div>                                    
                                     
                                
                                       <label>Examination Type</label> 
                                          <div class="form-group">
                                             
                                            <select class="md-select md-form" id="exam_type" name="exam_type" size="1">
                                                <option>Specify Exam Type</option>
                                                <option>TEST</option>
                                                <option>MIDTERM</option>
                                                <option>TERMINAL</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div> 
                                  
                                    <label>Class</label>
                                    <div class="form-group">
                                            <select class="md-select md-form" id="class" name="class" size="1">
                                                <option>Specify Class</option>
                                                <option>FORM ONE</option>
                                                <option>FORM TWO</option>
                                                <option>THREE</option>
                                                <option>FOUR</option>
                                                <option>FORM FIVE</option>
                                                <option>FORM SIX</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div> 
                                  
                                       <div class="form-group">
						<label for="subject" class="control-label">Subject*</label>	
						<select name="subject_id" id= "subjectid" class="form-control" required>
							<option value="subject">Select Section</option>
							<?php echo $school->getSubjectList(); ?>
						</select>
					</div>
                                   
                                         <input type="text" class="demoInputBox" placeholder="Student Registration Number" id="registerNo" name="registerNo" required>
                                  
                                       <label>Attended</label>
                                        <div class="form-group">
                                              
                                            <select class="md-select md-form" id="attended" name="attended">
                                                <option>YES</option>
                                                <option>NO</option>
                                               
                                            </select>
                                        </div>
                                    
                              
                                    <label>Score</label>
                                        <input type="text" class="demoInputBox" placeholder="Subject Score" id="subject_score" name="subject_score" required>

                                    <label>Grade</label>
                                        <div class="form-group">
                                              
                                            <select class="md-select md-form" id="grade" name="grade" size="1">
                                                <option>Specify Grade</option>
                                                <option>A</option>
                                                <option>B</option>
                                                <option>C</option>
                                                <option>D</option>
                                                <option>E</option>
                                                <option>F</option>
                                            </select>
                                            <span class="text-danger"></span>
                                        </div>
                                   
                                   <span id='message'>  </span>
                               
                               
                                <labe>Gender<i class="ti-hand-point-right fa-1x"></i></labe>
                                    
                                        <div class="form-group">
                                        <label>Comments</label>
                                        
                                        <textarea name="comments" id="comments" rows="2" cols="26">
                                      
                                        </textarea>
                                        </div>
                                 
                        </div>
                                   
                                        
                                        <div class="modal-footer">
					<input type="hidden" name="id" id="id" />
					<input type="hidden" name="action" id="action" value="updateResults" />
					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				          </div>
                              
                        

                
            </div>
           </form>
        </div>
    </div>
     

<?php include('inc/footer.php'); ?>
</body>
</html>