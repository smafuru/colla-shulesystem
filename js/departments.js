$(document).ready(function(){
	var departmentData = $('#departmentList').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"action.php",
			type:"POST",
			data:{action:'listDepartments'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 2, 3],
				"orderable":false,
			},
		],
		"pageLength": 10
	});	

	$('#addDepartment').click(function(){
		$('#departmentModal').modal('show');
		$('#departmentForm')[0].reset();		
		$('.modal-title').html("<i class='fa fa-plus'></i>Add Department");
		$('#action').val('addDepartment');
		$('#save').val('Save');
	});	
	
	$(document).on('submit','#departmentForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#departmentForm')[0].reset();
				$('#departmentModal').modal('hide');				
				$('#save').attr('disabled', false);
				departmentData.ajax.reload();
			}
		})
	});	
	
	$(document).on('click', '.update', function(){
		var dept_id = $(this).attr("dept_id");
		var action = "getDepartmentsDetails";
		$.ajax({
			url:'action.php',
			method:"POST",
			data:{dept_id:dept_id, action:action},
			dataType:"json",
			success:function(data){
				$('#departmentModal').modal('show');
				$('#dept_id').val(data.dept_id);
				$('#dept_name').val(data.dept_name);
                                $('#dept_head').val(data.dept_head);
				$('.modal-title').html("<i class='fa fa-plus'></i> Edit Section");
				$('#action').val('updateDepartment');
				$('#save').val('Save');
			}
		})
	});	
	
        
        
       $(document).on('click', '.delete', function(){
		var dept_id = $(this).attr("dept_id");		
		var action = "deleteDepartment";
		if(confirm("Are you sure you want to delete this Department?")) {
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{dept_id:dept_id, action:action},
				success:function(data) {					
					departmentData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});
      
	
});