if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
	
$(document).ready(function(){  
	$('#amount').on("keyup keydown change",function(){  
	   var expenseCategory = $('#expenseCategory').val();  
	   var amount = $(this).val();
	   $.ajax({  
			url:"/expense/check-limit",  
			method:"POST",  
			data:{
				expenseCategory:expenseCategory,
				amount:amount
			},  
			success:function(data){  
				 $('#categoryLimit').html(data);  
			}  
	   });  
	});		  
	  
	$('#expenseCategory').change(function(){  
	   var expenseCategory = $(this).val();  
	   var amount = $('#amount').val();
	   $.ajax({  
			url:"/expense/check-limit",  
			method:"POST",  
			data:{
				expenseCategory:expenseCategory,
				amount:amount
			},  
			success:function(data){  
				 $('#categoryLimit').html(data); 
			}  
	   });  
	}); 

	$('#amount').on("keyup keydown change",function(){  
	   var amount = $(this).val();
	   var expenseCategory = $('#expenseCategory').val();  
	   $.ajax({  
			url:"/expense/get-final-value",  
			method:"POST",  
			data:{
				expenseCategory:expenseCategory,
				amount:amount
			},  
			success:function(data){  						 	
					if (!data) {
						$('#categoryLimit').removeClass('alert-danger');
						$('#categoryLimit').addClass('alert-success');
						
					} else {
						$('#categoryLimit').removeClass('alert-success');
						$('#categoryLimit').addClass('alert-danger');
					}
			}  
	   });  
	}); 			  
	  
	$('#expenseCategory').change(function(){  
	   var expenseCategory = $(this).val();  
	   var amount = $('#amount').val(); 
	   $.ajax({  
			url:"/expense/get-final-value",  
			method:"POST",  
			data:{
				expenseCategory:expenseCategory,
				amount:amount
			},  
			success:function(data){  						 	
					if (!data) {
						$('#categoryLimit').removeClass('alert-danger');
						$('#categoryLimit').addClass('alert-success');

						
					} else {
						$('#categoryLimit').removeClass('alert-success');
						$('#categoryLimit').addClass('alert-danger');
					}
			}  
	   });  
	}); 		  
});	
	

