<?php


session_start();
require('config.php');

class School extends Dbconfig {

    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;
    private $userTable = 'sms_user';
    private $studentTable = 'sms_students';
    private $classesTable = 'sms_classes';
    private $resultsTable = 'sms_results';
    private $departmentsTable = 'sms_departments';
    private $teacherTable = 'sms_teacher';
    private $subjectsTable = 'sms_subjects';
    private $attendanceTable = 'sms_attendance';
    private $dbConnect = false;

    public function __construct() {
        if (!$this->dbConnect) {
            $database = new dbConfig();
            $this->hostName = $database->serverName;
            $this->userName = $database->userName;
            $this->password = $database->password;
            $this->dbName = $database->dbName;
            $conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
            if ($conn->connect_error) {
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else {
                $this->dbConnect = $conn;
            }
        }
    }

    private function getData($sqlQuery) {
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    private function getNumRows($sqlQuery) {
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $numRows = mysqli_num_rows($result);
        return $numRows;
    }

    public function adminLoginStatus() {
        if (empty($_SESSION["adminUserid"])) {
            header("Location: index.php");
        }
    }

    public function isLoggedin() {
        if (!empty($_SESSION["adminUserid"])) {
            return true;
        } else {
            return false;
        }
    }

    public function adminLogin() {
        $errorMessage = '';
        if (!empty($_POST["login"]) && $_POST["email"] != '' && $_POST["password"] != '') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sqlQuery = "SELECT * FROM " . $this->userTable . " 
				WHERE email='" . $email . "' AND password='" . md5($password) . "' AND status = 'active' OR type = 'Administrator' OR 'Student' OR 'Teacher' OR 'Committee' OR 'Board member' OR 'Partner'";
            $resultSet = mysqli_query($this->dbConnect, $sqlQuery) or die("error" . mysql_error());
            $isValidLogin = mysqli_num_rows($resultSet);
            if ($isValidLogin) {
                //i have altered the below sessions and also on the status field
                $userDetails = mysqli_fetch_assoc($resultSet);
                $_SESSION["adminUserid"] = $userDetails['id'];
                $_SESSION["user"] = $userDetails['first_name'] . " " . $userDetails['last_name'];
                header("location: dashboard.php");
            } else {
                $errorMessage = "Invalid login!";
            }
        } else if (!empty($_POST["login"])) {
            $errorMessage = "Enter Both user and password!";
        }
        return $errorMessage;
    }

    public function listClasses() {
        $sqlQuery = "SELECT class_id,class_name
			FROM " . $this->classesTable . " ";
        if (!empty($_POST["search"]["value"])) {
            $sqlQuery .= ' WHERE (class_id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR class_name LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (!empty($_POST["order"])) {
            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= 'ORDER BY class_id DESC ';
        }
        if ($_POST["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        $classesData = array();
        while ($classes = mysqli_fetch_assoc($result)) {
            $classesRows = array();
            $classesRows[] = $classes['class_id'];
            $classesRows[] = $classes['class_name'];
            $classesRows[] = '<button type="button" name="update" id="' . $classes["id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $classesRows[] = '<button type="button" name="delete" id="' . $classes["id"] . '" class="btn btn-danger btn-xs delete" >Delete</button>';
            $classesData[] = $classesRows;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $numRows,
            "recordsFiltered" => $numRows,
            "data" => $classesData
        );
        echo json_encode($output);
    }

    public function addClass() {
        if ($_POST["class_name"]) {
            $insertQuery = "INSERT INTO " . $this->classesTable . "(class_name) 
				VALUES ('" . $_POST["class_name"] . "')";
            $userSaved = mysqli_query($this->dbConnect, $insertQuery);
        }
    }

    public function getClassesDetails() {
        $sqlQuery = "SELECT c.id, c.name, s.section, s.section_id, t.teacher_id 
			FROM " . $this->classesTable . " as c
			LEFT JOIN " . $this->sectionsTable . " as s ON c.section = s.section_id 
			LEFT JOIN " . $this->teacherTable . " as t ON c.teacher_id = t.teacher_id
			WHERE c.id = '" . $_POST["classid"] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function updateClass() {
        if ($_POST['classid']) {
            $updateQuery = "UPDATE " . $this->classesTable . " 
			SET Name = '" . $_POST["cname"] . "', section = '" . $_POST["sectionid"] . "', teacher_id = '" . $_POST["teacherid"] . "'
			WHERE id ='" . $_POST["classid"] . "'";
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }

    public function deleteClass() {
        if ($_POST["classid"]) {
            $sqlUpdate = "
				DELETE FROM " . $this->classesTable . "
				WHERE id = '" . $_POST["classid"] . "'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    /*     * ***************Student methods*************** */

    public function listStudent() {
        $sqlQuery = "SELECT s.id, s.name, s.photo, s.gender, s.dob, s.mobile, s.email, s.current_address, s.father_name, s.mother_name,s.admission_no, s.roll_no, s.admission_date, s.academic_year, c.name as class, se.section 
			FROM " . $this->studentTable . " as s
			LEFT JOIN " . $this->classesTable . " as c ON s.class = c.id
			LEFT JOIN " . $this->sectionsTable . " as se ON s.section = se.section_id ";
        if (!empty($_POST["search"]["value"])) {
            $sqlQuery .= ' WHERE (s.id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR s.name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR s.gender LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR s.mobile LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR s.admission_no LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR s.roll_no LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (!empty($_POST["order"])) {
            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= 'ORDER BY s.id DESC ';
        }
        if ($_POST["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        $studentData = array();
        while ($student = mysqli_fetch_assoc($result)) {
            $studentRows = array();
            $studentRows[] = $student['id'];
            $studentRows[] = $student['admission_no'];
            $studentRows[] = $student['roll_no'];
            $studentRows[] = $student['name'];
            $studentRows[] = "<img width='40' height='40' src='upload/" . $student['photo'] . "'>";
            $studentRows[] = $student['class'];
            $studentRows[] = $student['section'];
            $studentRows[] = '<button type="button" name="update" id="' . $student["id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $studentRows[] = '<button type="button" name="delete" id="' . $student["id"] . '" class="btn btn-danger btn-xs delete" >Delete</button>';
            $studentData[] = $studentRows;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $numRows,
            "recordsFiltered" => $numRows,
            "data" => $studentData
        );
        echo json_encode($output);
    }

    public function addStudent() {
        if ($_POST["sname"]) {
            $target_dir = "upload/";
            $fileName = time() . $_FILES["photo"]["name"];
            $targetFile = $target_dir . basename($fileName);
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                echo "The file $fileName has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
            $insertQuery = "INSERT INTO " . $this->studentTable . "(name, email, mobile, gender, current_address, father_name, mother_name, class, section, admission_no, roll_no, academic_year, admission_date, dob, photo) 
				VALUES ('" . $_POST["sname"] . "', '" . $_POST["email"] . "', '" . $_POST["mobile"] . "', '" . $_POST["gender"] . "', '" . $_POST["address"] . "', '" . $_POST["fname"] . "', '" . $_POST["mname"] . "', '" . $_POST["classid"] . "', '" . $_POST["sectionid"] . "', '" . $_POST["registerNo"] . "', '" . $_POST["rollNo"] . "', '" . $_POST["year"] . "', '" . $_POST["admission_date"] . "', '" . $_POST["dob"] . "', '" . $fileName . "')";
            $userSaved = mysqli_query($this->dbConnect, $insertQuery);
        }
    }

    public function getStudentDetails() {
        $sqlQuery = "SELECT s.id, s.name, s.photo, s.gender, s.dob, s.mobile, s.email, s.current_address, s.father_name, s.mother_name,s.admission_no, s.roll_no, s.admission_date, s.academic_year, s.class, s.section 
			FROM " . $this->studentTable . " as s
			LEFT JOIN " . $this->classesTable . " as c ON s.class = c.id 
			WHERE s.id = '" . $_POST["studentid"] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function updateStudent() {
        if ($_POST['studentid']) {
            if ($_FILES["photo"]["name"]) {
                $target_dir = "upload/";
                $fileName = time() . $_FILES["photo"]["name"];
                $targetFile = $target_dir . basename($fileName);
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                    echo "The file $fileName has been uploaded.";
                    $photoUpdate = ", photo = '$fileName'";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            $updateQuery = "UPDATE " . $this->studentTable . " 
			SET name = '" . $_POST["sname"] . "', email = '" . $_POST["email"] . "', mobile = '" . $_POST["mobile"] . "', gender = '" . $_POST["gender"] . "', current_address = '" . $_POST["address"] . "', father_name = '" . $_POST["fname"] . "', mother_name = '" . $_POST["mname"] . "', class = '" . $_POST["classid"] . "', section = '" . $_POST["sectionid"] . "', admission_no = '" . $_POST["registerNo"] . "', roll_no = '" . $_POST["rollNo"] . "', academic_year = '" . $_POST["year"] . "', admission_date = '" . $_POST["admission_date"] . "', dob = '" . $_POST["dob"] . "' $photoUpdate
			WHERE id ='" . $_POST["studentid"] . "'";
            echo $updateQuery;
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }

    public function deleteStudent() {
        if ($_POST["studentid"]) {
            $sqlUpdate = "
				DELETE FROM " . $this->studentTable . "
				WHERE id = '" . $_POST["studentid"] . "'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    public function classList() {
        $sqlQuery = "SELECT * FROM " . $this->classesTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $classHTML = '';
        while ($class = mysqli_fetch_assoc($result)) {
            $classHTML .= '<option value="' . $class["class_id"] . '">' . $class["class_name"] . '</option>';
        }
        return $classHTML;
    }

    /*     * ***************Section methods*************** */

    public function listDepartments() {
        $sqlQuery = "SELECT dept_id,dept_name,dept_head 
			FROM " . $this->departmentsTable . " ";
        if (!empty($_POST["search"]["value"])) {
            $sqlQuery .= ' WHERE (dept_id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR dept_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR dept_head LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (!empty($_POST["order"])) {
            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= 'ORDER BY dept_id DESC ';
        }
        if ($_POST["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        $departmentData = array();
        while ($department = mysqli_fetch_assoc($result)) {
            $departmentRows = array();
            $departmentRows[] = $department['dept_id'];
            $departmentRows[] = $department['dept_name'];
            $departmentRows[] = $department['dept_head'];
            $departmentRows[] = '<button type="button" name="update" id="' . $department["dept_id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $departmentRows[] = '<button type="button" name="delete" id="' . $department["dept_id"] . '" class="btn btn-danger btn-xs delete" >Delete</button>';
            $departmentData[] = $departmentRows;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $numRows,
            "recordsFiltered" => $numRows,
            "data" => $departmentData
        );
        echo json_encode($output);
    }

    public function addDepartment() {
        if ($_POST["dept_name"]) {
            $insertQuery = "INSERT INTO " . $this->departmentsTable . "(dept_name,dept_head) 
				VALUES ('" . $_POST["dept_name"] . "','" . $_POST["dept_head"] . "')";
            $userSaved = mysqli_query($this->dbConnect, $insertQuery);
        }
    }

    
   public function getDepartmentList() {
        $sqlQuery = "SELECT dept_name FROM " . $this->departmentsTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $departmentHTML = '';
        while ($department = mysqli_fetch_assoc($result)) {
            $departmentHTML .= '<option value="' . $department["dept_id"] . '">' . $department["dept_name"] . '</option>';
        }
        return $departmentHTML;
    }   
    
    
    
    public function getDepartmentsDetails() {
      
		  $sqlQuery = "SELECT * FROM " . $this->departmentsTable . " 
			WHERE dept_id = '" . $_POST["dept_id"] . "' ";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function updateDepartment() {
        if ($_POST['dept_id']) {
            $updateQuery = "UPDATE " . $this->departmentsTable . " 
			SET dept_name ='" . $_POST["dept_name"] . "',dept_head =  '" . $_POST["dept_head"] . "'   
			WHERE dept_id ='" . $_POST["dept_id"] . "'";
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }


    public function departmentsList() {
        $sqlQuery = "SELECT * FROM " . $this->departmentsTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $departmentHTML = '';
        while ($department = mysqli_fetch_assoc($result)) {
            $departmentHTML .= '<option value="' . $department["dept_id"] . '">' . $department["dept_name"] . ' . ' . $department["dept_head"] . ' </option>';
        }
        return $departmentHTML;
    }

    
    
    
      public function deleteDepartment() {
        if ($_POST["dept_id"]) {
            $sqlUpdate = "
				DELETE FROM " . $this->departmentsTable . "
				WHERE dept_id = '" . $_POST["dept_id"] . "'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    
    /*     * ***************Section methods*************** */

    public function listTeacher() {
        $sqlQuery = "SELECT t.teacher_id, t.teacher, t.dob, t.appointment, t.confirmation, t.checkNumber, t.fileNumber, t.edLevel, t.specialization, t.teachSubject, t.teachExperience, s.subject, c.name, se.section			
			FROM " . $this->teacherTable . " as t 
			LEFT JOIN " . $this->subjectsTable . " as s ON t.subject_id = s.subject_id
			LEFT JOIN " . $this->classesTable . " as c ON t.teacher_id = c.teacher_id
			LEFT JOIN " . $this->sectionsTable . " as se ON c.section = se.section_id ";
        if (!empty($_POST["search"]["value"])) {
            $sqlQuery .= ' WHERE (t.teacher_id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.teacher LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.dob LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.appointment LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.confirmation LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.checkNumber LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.fileNumber LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.mobile LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.edLevel LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.specialization LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.teachSubject LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR t.teachExperience LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (!empty($_POST["order"])) {
            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= 'ORDER BY t.teacher_id DESC ';
        }
        if ($_POST["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        $teacherData = array();
        while ($teacher = mysqli_fetch_assoc($result)) {
            $teacherRows = array();
            $teacherRows[] = $teacher['teacher_id'];
            $teacherRows[] = $teacher['teacher'];
            $teacherRows[] = $teacher['dob'];
            $teacherRows[] = $teacher['appointment'];
            $teacherRows[] = $teacher['confirmation'];
            $teacherRows[] = $teacher['checkNumber'];
            $teacherRows[] = $teacher['fileNumber'];
            $teacherRows[] = $teacher['mobile'];
            $teacherRows[] = $teacher['edLevel'];
            $teacherRows[] = $teacher['specilaization'];
            $teacherRows[] = $teacher['teachSubject'];
            $teacherRows[] = $teacher['teachExperience'];

            $teacherRows[] = $teacher['subject'];
            $teacherRows[] = $teacher['name'];
            $teacherRows[] = $teacher['section'];
            $teacherRows[] = '<button type="button" name="update" id="' . $teacher["teacher_id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $teacherRows[] = '<button type="button" name="delete" id="' . $teacher["teacher_id"] . '" class="btn btn-danger btn-xs delete" >Delete</button>';
            $teacherData[] = $teacherRows;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $numRows,
            "recordsFiltered" => $numRows,
            "data" => $teacherData
        );
        echo json_encode($output);
    }

    public function addTeacher() {
        if ($_POST["teacher_name"]) {
            $insertQuery = "INSERT INTO " . $this->teacherTable . "(teacher,dob,appointment,confirmation,checkNumber,fileNumber,mobile,edLevel,specialization,teachSubject,teachExperience ) 
				VALUES ('" . $_POST["teacher_name"] . "','" . $_POST["dob"] . "','" . $_POST["appointment"] . "','" . $_POST["confirmation"] . "','" . $_POST["checkNumber"] . "',
                                           '" . $_POST["fileNumber"] . "','" . $_POST["mobile"] . "','" . $_POST["edLevel"] . "',
                                               '" . $_POST["specialization"] . "','" . $_POST["teachSubject"] . "','" . $_POST["teachExperience"] . "')";
                                 $userSaved = mysqli_query($this->dbConnect, $insertQuery);
                       }
                     }

    public function getTeacher() {
        $sqlQuery = "
			SELECT * FROM " . $this->teacherTable . " 
			WHERE teacher_id = '" . $_POST["teacherid"] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function updateTeacher() {
        if ($_POST['teacherid']) {
            $updateQuery = "UPDATE " . $this->teacherTable . " 
			SET teacher = '" . $_POST["teacher"] . "', dob = '" . $_POST["dob"] . "',
                         appointment = '" . $_POST["appointment"] . "',confirmation = '" . $_POST["confirmation"] . "',   
                         checkNumber = '" . $_POST["checkNumber"] . "',  fileNumber = '" . $_POST["fileNumber"] . "', 
                         mobile = '" . $_POST["mobile"] . "', edLevel = '" . $_POST["edLevel"] . "',
                         specialization = '" . $_POST["specialization"] . "',teachSubject = '" . $_POST["teachSubject"] . "',
                         teachExperience = '" . $_POST["teachExperience"] . "'    
			WHERE teacher_id ='" . $_POST["teacherid"] . "'";
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }

    public function deleteTeacher() {
        if ($_POST["teacherid"]) {
            $sqlUpdate = "
				DELETE FROM " . $this->teacherTable . "
				WHERE teacher_id = '" . $_POST["teacherid"] . "'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    /*     * ***************Subject methods*************** */

    public function listSubject() {
        $sqlQuery = "SELECT subject_id, subject_name, subject_type, subject_code 
			FROM " . $this->subjectsTable . " ";
        if (!empty($_POST["search"]["value"])) {
            $sqlQuery .= ' WHERE (subject_id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR subject_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR subject_type LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR subject_code LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR dept_id LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (!empty($_POST["order"])) {
            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= 'ORDER BY subject_id DESC ';
        }
        if ($_POST["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        $subjectData = array();
        while ($subject = mysqli_fetch_assoc($result)) {
            $subjectRows = array();
            $subjectRows[] = $subject['subject_id'];
            $subjectRows[] = $subject['subject_name'];
            $subjectRows[] = $subject['subject_code'];
            $subjectRows[] = $subject['subject_type'];
            $subjectRows[] = $subject['dept_id'];
            $subjectRows[] = '<button type="button" name="update" id="' . $subject["subject_id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $subjectRows[] = '<button type="button" name="delete" id="' . $subject["subject_id"] . '" class="btn btn-danger btn-xs delete" >Delete</button>';
            $subjectData[] = $subjectRows;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $numRows,
            "recordsFiltered" => $numRows,
            "data" => $subjectData
        );
        echo json_encode($output);
    }

    public function addSubject() {
        if ($_POST["subject_name"]) {
            $insertQuery = "INSERT INTO " . $this->subjectsTable ."(subject_id,
                                  subject_name,
                                  subject_type,
                                  subject_code,
                                  dept_id)
				   VALUES
                                   (NULL,
                                       '" . $_POST["subject_name"] . "',
                                       '" . $_POST["subject_type"] . "',
                                       '" . $_POST["subject_code"] . "',
                                       '" . $_POST["dept_id"] . "')";
            $userSaved = mysqli_query($this->dbConnect, $insertQuery);
        }
    }

    public function getSubject() {
                  
                        $sqlQuery ="SELECT s.subject_id,
                         s.subject_name,
                         s.subject_type,
                         s.subject_code,
                         d.dept_id  FROM  
                         " . $this->subjectsTable . " as s
                        LEFT JOIN " . $this->departmentsTable . " as d ON s.dept_id = d.dept_id
			WHERE subject_id = '" . $_POST["subject_id"] . "' ";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function getSubjectList() {
        $sqlQuery = "SELECT s.subject_id,
                        s.subject_name,
                        s.subject_type,
                        s.subject_code,
                        d.dept_id
                      FROM  " . $this->subjectsTable . " as s
			LEFT JOIN ". $this->departmentsTable . " as d ON s.dept_id = d.dept_id";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $subjectHTML = '';
        while ($subject = mysqli_fetch_assoc($result)) {
            $subjectHTML .= '<option value="' . $subject["subject_id"] . '">' . $subject["subject_name"] . '</option>';
        }
        return $subjectHTML;
    }

    public function updateSubject() {
        if ($_POST['subject_id']) {
            $updateQuery = "UPDATE " . $this->subjectsTable . " 
			SET subject_name = '" . $_POST["subject_name"] . "', subject_type = '" . $_POST["subject_type"] . "', subject_code = '" . $_POST["subject_code"] . "',
                            dept_id = '" . $_POST["dept_id"] . "'
			WHERE subject_id ='" . $_POST["subject_id"] . "'";
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }

    public function deleteSubject() {
        if ($_POST["subject_id"]) {
            $sqlUpdate = "
				DELETE FROM " . $this->subjectsTable . "
				WHERE subject_id = '" . $_POST["subject_id"] . "'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    public function getTeacherList() {
        $sqlQuery = "SELECT * FROM " . $this->teacherTable;
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $teacherHTML = '';
        while ($teacher = mysqli_fetch_assoc($result)) {
            $teacherHTML .= '<option value="' . $teacher["teacher_id"] . '">' . $teacher["teacher"] . '</option>';
        }
        return $teacherHTML;
    }

    /* add user method start */

    public function listUser() {
        $sqlQuery = "SELECT id,first_name,last_name,email,gender,mobile,designation,image,type,status,authtoken
                        FROM " . $this->userTable . " as u ";
        if (!empty($_POST["search"]["value"])) {
            //$sqlQuery .= ' WHERE (id LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR last_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR email LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR gender LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR mobile LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR designation LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR image LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR type LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR status LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR authtoken LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (!empty($_POST["order"])) {
            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= 'ORDER BY id DESC ';
        }
        if ($_POST["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        $userData = array();
        while ($user = mysqli_fetch_assoc($result)) {
            $userRows = array();
            //$userRows[] = $user['id'];
            $userRows[] = $user['first_name'];
            $userRows[] = $user['last_name'];
            $userRows[] = $user['email'];
            $userRows[] = $user['gender'];
            $userRows[] = $user['mobile'];
            $userRows[] = $user['designation'];
            $userRows[] = "<img width='40' height='40' src='user/" . $user['image'] . "'>";
            $userRows[] = $user['type'];
            $userRows[] = $user['status'];
            $userRows[] = $user['authtoken'];
            $userRows[] = '<button type="button" name="update" id="' . $user["id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $userRows[] = '<button type="button" name="delete" id="' . $user["id"] . '" class="btn btn-danger btn-xs delete" >Delete</button>';
            $userData[] = $userRows;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $numRows,
            "recordsFiltered" => $numRows,
            "data" => $userData
        );
        echo json_encode($output);
    }

    public function addUser() {
        if ($_POST["first_name"]) {
            
            $_POST["password"]= md5(password); //hapo tunahash passcode kwa MD5 katika db

            $target_dir = "user/";
            $fileName = time() . $_FILES["image"]["first_name"];
            $targetFile = $target_dir . basename($fileName);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "The file $fileName has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
            $insertQuery = "INSERT INTO " . $this->userTable . " (id,first_name, last_name, email, password, gender, mobile, designation,image, type, status,authtoken) 
				VALUES (NULL,'" . $_POST["first_name"] . "', '" . $_POST["last_name"] . "', '" . $_POST["email"] . "','" . $_POST["password"] . "','" . $_POST["gender"] . "', '" . $_POST["mobile"] . "', '" . $_POST["designation"] . "', '" . $_POST["image"] . "', '" . $_POST["type"] . "', '" . $_POST["status"] . "','" . $_POST["authtoken"] . "')";
            $userSaved = mysqli_query($this->dbConnect, $insertQuery);
        }
    }
 
    public function getUserDetails() { 
        $sqlQuery = "SELECT u.id, u.first_name, u.last_name, u.email, u.gender, u.photo, u.mobile, u.designation, u.type, u.status 
			FROM " . $this->userTable . " as u
			LEFT JOIN " . $this->studentTable . " as s ON u.class = u.id 
			WHERE s.id = '" . $_POST["userid"] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function updateUser() {
        if ($_POST['userid']) {
            if ($_FILES["photo"]["name"]) {
                $target_dir = "user/";
                $fileName = time() . $_FILES["photo"]["name"];
                $targetFile = $target_dir . basename($fileName);
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                    echo "The file $fileName has been uploaded.";
                    $photoUpdate = ", photo = '$fileName'";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            $updateQuery = "UPDATE " . $this->userTable . " 
			SET first_name = '" . $_POST["first_name"] . "',last_name = '" . $_POST["last_name"] . "' ,email = '" . $_POST["email"] . "',gender = '" . $_POST["gender"] . "', mobile = '" . $_POST["mobile"] . "',  designation = '" . $_POST["designation"] . "', photo =  '" . $_POST["photo"] . "', type = '" . $_POST["type"] . "', status = '" . $_POST["status"] . "',authtoken = '" . $_POST["authtoken"] . "' $photoUpdate
			WHERE id ='" . $_POST["userid"] . "'";
            echo $updateQuery;
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }

    public function deleteUser() {
        if ($_POST["userid"]) {
            $sqlUpdate = "
				DELETE FROM " . $this->userTable . "
				WHERE id = '" . $_POST["userid"] . "'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    //results

    public function listResults() {
        $sqlQuery = "SELECT clas.name,res.id,stud.id,res.attended,stud.name,res.exam_type,
            sbj.code,sbj.subject,res.subject_score,res.subject_grade,
               res.class_position,res.subject_comments,stud.name,
                  us.email,res.created

               FROM  " . $this->resultsTable . " as res
               INNER JOIN ".$this->classesTable." as clas  ON res.class_id=clas.id    
               INNER JOIN " . $this->subjectsTable . " as sbj ON res.subject_id=sbj.subject_id
               INNER JOIN " . $this->studentsTable. " as stud ON res.student_id=stud.id 
               INNER JOIN " . $this->userTable . " as us  ON res.user_id=us.id";
        if (!empty($_POST["search"]["value"])) {
            $sqlQuery .= ' WHERE (res.id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR clas.name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR res.id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR stud.id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR res.attended LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR stud.name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR res.exam_type LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR sbj.code LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR sbj.subject LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR res.subject_score LIKE "%' . $_POST["search"]["value"] . '%" ';
             $sqlQuery .= ' OR res.subject_grade LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR res.class_position LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR res.subject_comments LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR stud.name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR us.email LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR res.created LIKE "%' . $_POST["search"]["value"] . '%" ';
            
            
            
            
        }
        if (!empty($_POST["order"])) {
            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= 'ORDER BY id DESC ';
        }
        if ($_POST["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        $resultsData = array();
        while ($results = mysqli_fetch_assoc($result)) {
            $resultsRows = array();
            $resultsRows[] = $results['res.id'];
            $resultsRows[] = $results['sbj.subject'];
            $resultsRows[] = $results['sbj.code'];
            $resultsRows[] = $results['clas.name'];
            $resultsRows[] = $results['sud.id'];
            $resultsRows[] = $results['res.attended'];
            $resultsRows[] = $results['stud.name'];
            $resultsRows[] = $results['res.exam_type'];
            
            $resultsRows[] = $results['res.subject_score'];
            $resultsRows[] = $results['res.subject_grade'];
            $resultsRows[] = $results['res.class_position'];
            $resultsRows[] = $results['res.subject_comments'];
            
             $resultsRows[] = $results['us.email'];
            $resultsRows[] = $results['res.created'];
            
            $resultsRows[] = '<button type="button" name="update" id ="' . $results["res.id"] . '" class="btn btn-warning btn-xs update">Update</button>';
            $resultsRows[] = '<button type="button" name="delete" id="' . $results["res.id"] . '" class="btn btn-danger btn-xs delete" >Delete</button>';
            $resultsData[] = $resultsRows;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $numRows,
            "recordsFiltered" => $numRows,
            "data" => $resultsData
        );
        echo json_encode($output);
    }

    public function addResults() {
        if ($_POST["results"]) {
            $insertQuery = "INSERT INTO " . $this->resultsTable . "(clas.name,res.id,stud.id,res.attended,stud.name,res.exam_type,
            sbj.code,sbj.subject,res.subject_score,res.subject_grade,
               res.class_position,res.subject_comments,stud.name,
                  us.email,res.created) 
				VALUES ('" . $_POST["subject"] . "', '" . $_POST["s_type"] . "', '" . $_POST["code"] . "')";
            $userSaved = mysqli_query($this->dbConnect, $insertQuery);
        }
    }

    public function getResultsDetails() {
        $sqlQuery = "SELECT clas.name,res.id,stud.id,res.attended,stud.name,res.exam_type,
            sbj.code,sbj.subject,res.subject_score,res.subject_grade,
               res.class_position,res.subject_comments,stud.name,
                  us.email,res.created

               FROM  " . $this->resultsTable . " as res
               INNER JOIN ".$this->classesTable." as clas  ON res.class_id=clas.id    
               INNER JOIN " . $this->subjectsTable . " as sbj ON res.subject_id=sbj.subject_id
               INNER JOIN " . $this->studentsTable. " as stud ON res.student_id=stud.id 
               INNER JOIN " . $this->userTable . " as us  ON res.user_id=us.id
			WHERE stud.id = '" . $_POST["id"] . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function updateResults() {
        if ($_POST['userid']) {
            if ($_FILES["photo"]["name"]) {
                $target_dir = "user/";
                $fileName = time() . $_FILES["photo"]["name"];
                $targetFile = $target_dir . basename($fileName);
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                    echo "The file $fileName has been uploaded.";
                    $photoUpdate = ", photo = '$fileName'";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            $updateQuery = "UPDATE " . $this->userTable . " 
			SET first_name = '" . $_POST["first_name"] . "',last_name = '" . $_POST["last_name"] . "' ,email = '" . $_POST["email"] . "',gender = '" . $_POST["gender"] . "', mobile = '" . $_POST["mobile"] . "',  designation = '" . $_POST["designation"] . "', photo =  '" . $_POST["photo"] . "', type = '" . $_POST["type"] . "', status = '" . $_POST["status"] . "',authtoken = '" . $_POST["authtoken"] . "' $photoUpdate
			WHERE id ='" . $_POST["userid"] . "'";
            echo $updateQuery;
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }

    public function deleteResults() {
        if ($_POST["userid"]) {
            $sqlUpdate = "
				DELETE FROM " . $this->userTable . "
				WHERE id = '" . $_POST["userid"] . "'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    // user methods  end
    ///Student attendance 
    public function getStudents() {
        if ($_POST["classid"] && $_POST["sectionid"]) {
            $attendanceYear = date('Y');
            $attendanceMonth = date('m');
            $attendanceDay = date('d');
            $attendanceDate = $attendanceYear . "/" . $attendanceMonth . "/" . $attendanceDay;

            $sqlQueryCheck = "SELECT * FROM " . $this->attendanceTable . " 
				WHERE class_id = '" . $_POST["classid"] . "' AND section_id = '" . $_POST["sectionid"] . "' AND attendance_date = '" . $attendanceDate . "'";
            $resultAttendance = mysqli_query($this->dbConnect, $sqlQueryCheck);
            $attendanceDone = mysqli_num_rows($resultAttendance);

            $query = '';
            if ($attendanceDone) {
                $query = "AND a.attendance_date = '" . $attendanceDate . "'";
            }

            $sqlQuery = "SELECT s.id, s.name, s.photo, s.gender, s.dob, s.mobile, s.email, s.current_address, s.father_name, s.mother_name,s.admission_no, s.roll_no, s.admission_date, s.academic_year, a.attendance_status, a.attendance_date
				FROM " . $this->studentTable . " as s
				LEFT JOIN " . $this->attendanceTable . " as a ON s.id = a.student_id
				WHERE s.class = '" . $_POST["classid"] . "' AND s.section='" . $_POST["sectionid"] . "' $query ";
            $sqlQuery .= "GROUP BY a.student_id ";
            if (!empty($_POST["search"]["value"])) {
                $sqlQuery .= ' AND (s.id LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR s.name LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR s.admission_no LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR s.roll_no LIKE "%' . $_POST["search"]["value"] . '%" )';
            }
            if (!empty($_POST["order"])) {
                $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
            } else {
                $sqlQuery .= 'ORDER BY s.id DESC ';
            }
            if ($_POST["length"] != -1) {
                $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            $numRows = mysqli_num_rows($result);

            $studentData = array();

            while ($student = mysqli_fetch_assoc($result)) {
                $checked = array();
                $checked[1] = '';
                $checked[2] = '';
                $checked[3] = '';
                $checked[4] = '';
                if ($student['attendance_date'] == $attendanceDate) {
                    if ($student['attendance_status'] == '1') {
                        $checked[1] = 'checked';
                    } else if ($student['attendance_status'] == '2') {
                        $checked[2] = 'checked';
                    } else if ($student['attendance_status'] == '3') {
                        $checked[3] = 'checked';
                    } else if ($student['attendance_status'] == '4') {
                        $checked[4] = 'checked';
                    }
                }
                $studentRows = array();
                $studentRows[] = $student['id'];
                $studentRows[] = $student['admission_no'];
                $studentRows[] = $student['roll_no'];
                $studentRows[] = $student['name'];
                $studentRows[] = '
				<input type="radio" id="attendencetype1_' . $student['id'] . '" value="1" name="attendencetype_' . $student['id'] . '" autocomplete="off" ' . $checked['1'] . '>
				<label for="attendencetype_' . $student['id'] . '">Present</label>
				<input type="radio" id="attendencetype2_' . $student['id'] . '" value="2" name="attendencetype_' . $student['id'] . '" autocomplete="off" ' . $checked['2'] . '>
				<label for="attendencetype' . $student['id'] . '">Late </label>
				<input type="radio" id="attendencetype3_' . $student['id'] . '" value="3" name="attendencetype_' . $student['id'] . '" autocomplete="off" ' . $checked['3'] . '>
				<label for="attendencetype3_' . $student['id'] . '"> Absent </label>
				<input type="radio" id="attendencetype4_' . $student['id'] . '" value="4" name="attendencetype_' . $student['id'] . '" autocomplete="off" ' . $checked['4'] . '><label for="attendencetype_' . $student['id'] . '"> Half Day </label>';
                $studentData[] = $studentRows;
            }

            $output = array(
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $numRows,
                "recordsFiltered" => $numRows,
                "data" => $studentData
            );
            echo json_encode($output);
        }
    }

    public function updateAttendance() {
        $attendanceYear = date('Y');
        $attendanceMonth = date('m');
        $attendanceDay = date('d');
        $attendanceDate = $attendanceYear . "/" . $attendanceMonth . "/" . $attendanceDay;
        $sqlQuery = "SELECT * FROM " . $this->attendanceTable . " 
			WHERE class_id = '" . $_POST["att_classid"] . "' AND section_id = '" . $_POST["att_sectionid"] . "' AND attendance_date = '" . $attendanceDate . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $attendanceDone = mysqli_num_rows($result);
        if ($attendanceDone) {
            foreach ($_POST as $key => $value) {
                if (strpos($key, "attendencetype_") !== false) {
                    $student_id = str_replace("attendencetype_", "", $key);
                    $attendanceStatus = $value;
                    if ($student_id) {
                        $updateQuery = "UPDATE " . $this->attendanceTable . " SET attendance_status = '" . $attendanceStatus . "'
						WHERE student_id = '" . $student_id . "' AND class_id = '" . $_POST["att_classid"] . "' AND section_id = '" . $_POST["att_sectionid"] . "' AND attendance_date = '" . $attendanceDate . "'";
                        mysqli_query($this->dbConnect, $updateQuery);
                    }
                }
            }
            echo "Attendance updated successfully!";
        } else {
            foreach ($_POST as $key => $value) {
                if (strpos($key, "attendencetype_") !== false) {
                    $student_id = str_replace("attendencetype_", "", $key);
                    $attendanceStatus = $value;
                    if ($student_id) {
                        $insertQuery = "INSERT INTO " . $this->attendanceTable . "(student_id, class_id, section_id, attendance_status, attendance_date) 
						VALUES ('" . $student_id . "', '" . $_POST["att_classid"] . "', '" . $_POST["att_sectionid"] . "', '" . $attendanceStatus . "', '" . $attendanceDate . "')";
                        mysqli_query($this->dbConnect, $insertQuery);
                    }
                }
            }
            echo "Attendance save successfully!";
        }
    }

    public function attendanceStatus() {
        $attendanceYear = date('Y');
        $attendanceMonth = date('m');
        $attendanceDay = date('d');
        $attendanceDate = $attendanceYear . "/" . $attendanceMonth . "/" . $attendanceDay;
        $sqlQuery = "SELECT * FROM " . $this->attendanceTable . " 
			WHERE class_id = '" . $_POST["classid"] . "' AND section_id = '" . $_POST["sectionid"] . "' AND attendance_date = '" . $attendanceDate . "'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $attendanceDone = mysqli_num_rows($result);
        if ($attendanceDone) {
            echo "Attendance already submitted!";
        }
    }

    public function getStudentsAttendance() {
        if ($_POST["classid"] && $_POST["sectionid"] && $_POST["attendanceDate"]) {
            $sqlQuery = "SELECT s.id, s.name, s.photo, s.gender, s.dob, s.mobile, s.email, s.current_address, s.father_name, s.mother_name,s.admission_no, s.roll_no, s.admission_date, s.academic_year, a.attendance_status
				FROM " . $this->studentTable . " as s
				LEFT JOIN " . $this->attendanceTable . " as a ON s.id = a.student_id
				WHERE s.class = '" . $_POST["classid"] . "' AND s.section='" . $_POST["sectionid"] . "' AND a.attendance_date = '" . $_POST["attendanceDate"] . "'";
            if (!empty($_POST["search"]["value"])) {
                $sqlQuery .= ' AND (s.id LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR s.name LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR s.admission_no LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR s.roll_no LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR a.attendance_date LIKE "%' . $_POST["search"]["value"] . '%" )';
            }
            if (!empty($_POST["order"])) {
                $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
            } else {
                $sqlQuery .= 'ORDER BY s.id DESC ';
            }
            if ($_POST["length"] != -1) {
                $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            $numRows = mysqli_num_rows($result);

            $studentData = array();

            while ($student = mysqli_fetch_assoc($result)) {
                $attendance = '';
                if ($student['attendance_status'] == '1') {
                    $attendance = '<small class="label label-success">Present</small>';
                } else if ($student['attendance_status'] == '2') {
                    $attendance = '<small class="label label-warning">Late</small>';
                } else if ($student['attendance_status'] == '3') {
                    $attendance = '<small class="label label-danger">Absent</small>';
                } else if ($student['attendance_status'] == '4') {
                    $attendance = '<small class="label label-info">Half Day</small>';
                }
                $studentRows = array();
                $studentRows[] = $student['id'];
                $studentRows[] = $student['admission_no'];
                $studentRows[] = $student['roll_no'];
                $studentRows[] = $student['name'];
                $studentRows[] = $attendance;
                $studentData[] = $studentRows;
            }

            $output = array(
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $numRows,
                "recordsFiltered" => $numRows,
                "data" => $studentData
            );
            echo json_encode($output);
        }
    }

}

?>