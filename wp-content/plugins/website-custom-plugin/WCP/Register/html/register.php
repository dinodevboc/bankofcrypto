<style type="text/css">
	.error{
 
border-color: red !important;
}
.error::placeholder {
  color: red !important;
  
}

</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">


<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js?ver=4.9.9"></script>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<form action="" class="regiterFrm" name="form_register" id="form_register" method="POST">
			<input type="hidden" name="action" value="WCP_Register_Controller::registration_save" />
			<div class="form-group">
		    	<label>Name <em style="color:#f00">*</em></label>
		      <input type="text" class="form-control" id="uname" name="uname" placeholder="">
		    </div>
		    <div class="form-group">
		    	<label>Email</label>
		      <input type="email" class="form-control" id="email"  name="email" placeholder="">
		    </div>		    
			<div class="form-group">
				<label>Password</label>
			<input type="password" id="password" name="password" class="form-control"  placeholder="">
			</div>
			<div class="form-group">
				<label>Repeat Password</label>
				<input type="password" name="repeat_password" id="repeat_password" class="form-control"   placeholder="">
			</div>
		 <!--    <div class="form-group">
		    	<label>Address</label>
		      <input type="text" class="form-control" id="address" name="address">
		    </div> -->
		    <div class="form-group">
				<label>Phone Number</label>
		      <input type="text" class="form-control" id="phone" name="phone_no"  placeholder="" >
		    </div>
		    
			<p id="err_msg">&nbsp;</p>
		    <input type="submit" class="btn btn-default btnSubmit" value="Register"  placeholder="">
	  </form>
	</div>
	
</div>
<script>
	$ = jQuery;

	jQuery(document).ready(function ($) {


		jQuery("#form_register").validate({

      
    errorPlacement: function(error, element) {
        
        // name attrib of the field
    var n = element.attr("name");
    
    if (n == "uname")
      element.attr("placeholder", "User Name is required");
     else if (n == "password")
      element.attr("placeholder", "Password is required");
    else if (n == "repeat_password")
      element.attr("placeholder", "Repeat Password is required"); 
    else if (n == "phone_no")
      element.attr("placeholder", "User Phone is required");
    else if (n == "email")
      element.attr("placeholder", "Email is required"); 


            
    },
    rules: {
        uname: {
            minlength: 2,
            required: true
        },
       
        password: {
            minlength: 6,
            required: true
           
        },
        repeat_password: {
            minlength: 6,
            required: true,
            equalTo: "#password"
        },
        phone_no: {
          required:true,
           minlength: 10
             
        },
        email: {
            minlength: 6,
            required: true,
            email: true
        },
        school_name: {
            minlength: 1,
            required: true
        }
    },
    highlight: function(element) {
    
        // add a class "has_error" to the element 
        jQuery(element).addClass('error');
    },
    unhighlight: function(element) {
    
        // remove the class "has_error" from the element 
        jQuery(element).removeClass('error');
    },
    submitHandler: function(form) {
        
    return false;
    }
});



	var register_form = $('#form_register'),
			err_msg = $('#err_msg'),
			phone = $('#phone'),
			password = $('#password'),
			repeat_password = $('#repeat_password'),
			email = $('#email'),
			address = $('#address'),
			uname = $('#uname');
			register_form.on('submit', function (e) {
			e.preventDefault();




			
			if (is_valid_request()) {

			if (password.val() == repeat_password.val()) {

			/// Create data for form submission
			fdata = {};
			fdata.email = email.val();
			fdata.password = password.val();
			fdata.repeat_password = repeat_password.val();
			fdata.uname = $("#uname").val();
			err_msg.html('Please wait...');
			$.ajax({
			//url: ajaxurl,
			url: ajaxurl,
					method: "POST",
					dataType: "json",
					data: $('#form_register').serialize()
			}).always(function (response) {

			console.log(response);
			if (response.is_ok == '1') {

			err_msg.css('color', "green");
			err_msg.html(response.msg);
			signup_form[0].reset();
			window.location.replace('/');
			} else {

			err_msg.css('color', "red");
			err_msg.html(response.error);
			//alert(response.error);
			}

			});
			} else {

			err_msg.css('color', "red");
			err_msg.html('Please confirm your password.');
			}

			} else {

			err_msg.css('color', "red");
			err_msg.html('All fields are required.');
			}

			});
	function is_valid_request() {
//		alert($('#uemail').val());
	var valid_inputs = [email.val(), password.val()];
//	alert(valid_inputs);
	for (i = 0; i < valid_inputs.length; i++) {

	if (valid_inputs[i] === '') {
	return false;
	}
	}

	return true;
	}

	
	});
</script>