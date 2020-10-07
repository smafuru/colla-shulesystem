<?php
include('class/School.php');
$school = new School();

if(!empty($_POST['action']) && $_POST['action'] == 'listClasses') {
	$school->listClasses();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addClass') {
	$school->addClass();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getClass') {
	$school->getClassesDetails();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateClass') {
	$school->updateClass();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteClass') {
	$school->deleteClass();
}
if(!empty($_POST['action']) && $_POST['action'] == 'listStudent') {
	$school->listStudent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addStudent') {
	$school->addStudent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getStudentDetails') {
	$school->getStudentDetails();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateStudent') {
	$school->updateStudent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteStudent') {
	$school->deleteStudent();
}
/********sections********/
if(!empty($_POST['action']) && $_POST['action'] == 'listDepartments') {
	$school->listDepartments();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addDepartment') {
	$school->addDepartment();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getDepartmentsDetails') {
	$school->getDepartmentsDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getDepartmentList') {
	$school->getDepartmentList();
}


if(!empty($_POST['action']) && $_POST['action'] == 'updateDepartment') {
	$school->updateDepartment();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteDepartment') {
	$school->deleteDepartment();
}

/********user********/
if(!empty($_POST['action']) && $_POST['action'] == 'listUser') {
	$school->listUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addUser') {
	$school->addUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getUserDetails') {
	$school->getUserDetails();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateUser') {
	$school->updateUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteUser') {
	$school->deleteUser();
}
/********user********/


/********results*******/
if(!empty($_POST['action']) && $_POST['action'] == 'listResults') {
	$school->listResults();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addResults') {
	$school->addResults();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getResults') {
	$school->getResults();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateResults') {
	$school->updateResults();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteResults') {
	$school->deleteResults();
}

/********results*******/




if(!empty($_POST['action']) && $_POST['action'] == 'listTeacher') {
	$school->listTeacher();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addTeacher') {
	$school->addTeacher();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getTeacher') {
	$school->getTeacher();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateTeacher') {
	$school->updateTeacher();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteTeacher') {
	$school->deleteTeacher();
}
/********Subject********/
if(!empty($_POST['action']) && $_POST['action'] == 'listSubject') {
	$school->listSubject();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addSubject') {
	$school->addSubject();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getSubject') {
	$school->getSubject();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateSubject') {
	$school->updateSubject();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteSubject') {
	$school->deleteSubject();
}
/********attendance********/
if(!empty($_POST['action']) && $_POST['action'] == 'getStudents') {
	$school->getStudents();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateAttendance') {
	$school->updateAttendance();
}
if(!empty($_POST['action']) && $_POST['action'] == 'attendanceStatus') {
	$school->attendanceStatus();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getStudentsAttendance') {
	$school->getStudentsAttendance();
}
?>