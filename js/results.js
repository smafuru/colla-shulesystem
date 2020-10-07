$(document).ready(function(){
	var resultsData = $('#resultsList').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"action.php",
			type:"POST",
			data:{action:'listResults'},
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

	$('#addResults').click(function(){
		$('#resultsModal').modal('show');
		$('#resultsForm')[0].reset();		
		$('.modal-title-thead').html("<i class='fa fa-plus'></i> Register Results");
		$('#action').val('addResults');
		$('#save').val('Save');
	});	
	
	$(document).on('submit','#resultsForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');		
		$.ajax({
			url:"action.php",
			method:"POST",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success:function(data){				
				$('#resultsForm')[0].reset();
				$('#resultsModal').modal('hide');				
				$('#save').attr('disabled', false);
				 resultsData.ajax.reload();
			}
		})
	});	
	
	$(document).on('click', '.update', function(){
		var id = $(this).attr("results_id");
		var action = 'getResultsDetails';
		$.ajax({
			url:'action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(data){
				$('#resultsModal').modal('show');
				$('#id').val(data.results_id);
				$('#exam_type').val(data.exam_type);
                                $('#subject_score').val(data.subject_score);
				$('#subject_grade').val(data.subject_grade);
                                $('#class_position').val(data.class_position);
                                
                                $('#subject_comments').val(data.subject_comments);
				$('#subject_id').val(data.subject_id);
                                $('#student_id').val(data.student_id);
				$('#subject_id').val(data.subject_id);
                                $('#student_id').val(data.student_id);
                                
                                
				$('.modal-title-thead').html("<i class='fa fa-plus'></i> Edit User");
				$('#action').val('updateResults');
				$('#save').val('Save');
			}
		})
	});	
	
	$(document).on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deleteResults";
		if(confirm("Are you sure you want to delete this Result?")) {
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					resultsData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	

});

