jQuery(document).ready(function($){

	var form_login = $('#form_login'), err_msg = $('#err_msg'), input_pass=$('#input_pass'), input_email = $('#input_email');
	
	form_login.on('submit', function(e){
		e.preventDefault();

		if(is_valid_request()){

			/// Create data for form submission
			fdata = {};
			fdata.input_email = input_email.val();
			fdata.input_pass = input_pass.val();
			
			fdata.action = 'WCP_Login_Controller::custom_login';

			err_msg.html('Please wait...');
			
			
			
		jQuery.ajax({
				url: ajaxurl,
				method: "POST",
				dataType:"json",
				data: fdata
			}).always(function(response) {

				console.log(response);

				if(response.status == 'ok'){

		 			err_msg.css('color', "green");
					err_msg.html(response.msg);
					form_login[0].reset();

					//var url = window.location.hostname+'/puppy-listings';
					window.location.replace('/#dashboard');

				} else{

					err_msg.css('color', "red");
					err_msg.html(response.error);
				}

			});

		} else {

			err_msg.css('color', "red");
			err_msg.html('All fields are required.');
		}

	})


	function is_valid_request(){
		valid_inputs = [input_email.val(), input_pass.val()];
         // alert(valid_inputs);
		for (i = 0; i < valid_inputs.length; i++) {
			if(valid_inputs[i] == ''){
			return false;
			}
		}

		return true;
	}


}); /// End document ready