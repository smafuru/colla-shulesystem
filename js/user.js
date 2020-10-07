$(document).ready(function(){
	var userData = $('#userList').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"action.php",
			type:"POST",
			data:{action:'listUser'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 7, 8],
				"orderable":false,
			},
		],
		"pageLength": 10
	});	

	$('#addUser').click(function(){
		$('#userModal').modal('show');
		$('#userForm')[0].reset();		
		$('.modal-title-thead').html("<i class='fa fa-plus'></i> Register User");
		$('#action').val('addUser');
		$('#save').val('Save');
	});	
	
	$(document).on('submit','#userForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');		
		$.ajax({
			url:"action.php",
			method:"POST",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success:function(data){				
				$('#userForm')[0].reset();
				$('#userModal').modal('hide');				
				$('#save').attr('disabled', false);
				 userData.ajax.reload();
			}
		})
	});	
	
	$(document).on('click', '.update', function(){
		var userid = $(this).attr("id");
		var action = 'getUserDetails';
		$.ajax({
			url:'action.php',
			method:"POST",
			data:{userid:userid, action:action},
			dataType:"json",
			success:function(data){
				$('#userModal').modal('show');
				$('#userid').val(data.id);
				$('#first_name').val(data.first_name);
                                $('#last_name').val(data.last_name);
				$('#email').val(data.email);
                                $('#password').val(data.password);
                               
                               
				if(data.gender == 'male') {
					$('#male').prop("checked", true);
				} else if(data.gender == 'female') {
					$('#female').prop("checked", true);
				}
				$('#mobile').val(data.mobile);
				$('#designation').val(data.designation);
				$('#image').val(data.image);
				$('#type').val(data.type);
				$('#status').val(data.status);	
                                $('#authtoken').val(data.authtoken);	
				$('.modal-title-thead').html("<i class='fa fa-plus'></i> Edit User");
				$('#action').val('updateUser');
				$('#save').val('Save');
			}
		})
	});	
	
	$(document).on('click', '.delete', function(){
		var userid = $(this).attr("id");		
		var action = "deleteUser";
		if(confirm("Are you sure you want to delete this User?")) {
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{userid:userid, action:action},
				success:function(data) {					
					userData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	

});

