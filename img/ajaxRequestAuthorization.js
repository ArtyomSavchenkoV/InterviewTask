$(document).ready(function(){




	$('#ajaxSubmit').bind('click', function(){
		$.ajax({
		  type: 'POST',
		  url: 'indexAjax.php?action=authorization',
		  data: $('#authorizationForm').serialize(),
		  success: function(data){
			alert(data);
		  }


		});
	});

});
