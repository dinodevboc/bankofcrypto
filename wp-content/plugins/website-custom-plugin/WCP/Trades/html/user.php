<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" > -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>../css/jquery-ui-timepicker-addon.css">

<!-- Latest compiled and minified JavaScript -->
<!-- <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.23/moment-timezone-with-data.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) ?>../js/jquery-ui-timepicker-addon.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>

<style type="text/css">
	.f-r{
		float: right;
	}
	.f-l{
		float: left;
	}
	.t-c{
		text-align: center;
	}
	.lh-30{
		 line-height: 30px;
	}
  .paddingLeft0{
    padding-left: 0;
  }
  .onoffswitch {
    position: relative; width: 70px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}

.onoffswitch-checkbox {
    display: none !important;
}

.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 20px;
}

.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    -moz-transition: margin 0.3s ease-in 0s; -webkit-transition: margin 0.3s ease-in 0s;
    -o-transition: margin 0.3s ease-in 0s; transition: margin 0.3s ease-in 0s;
}

.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 25px; padding: 0; line-height: 25px;
    font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
}

.onoffswitch-inner:before {
    content: "YES";
    padding-left: 10px;
    background-color: #2FCCFF; color: #FFFFFF;
}

.onoffswitch-inner:after {
    content: "NO";
    padding-right: 10px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
}

.onoffswitch-switch {
    display: block; width: 22px; margin: 6px;
    background: #FFFFFF;
    border: 2px solid #999999; border-radius: 20px;
    position: absolute; top: 0; bottom: 0; right: 36px;
    -moz-transition: all 0.3s ease-in 0s; -webkit-transition: all 0.3s ease-in 0s;
    -o-transition: all 0.3s ease-in 0s; transition: all 0.3s ease-in 0s; 
}

.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}

.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
}
#loading {
    position: absolute;
    z-index: 9999;
    right: 50%;
    top:350px;
}
#loading:before
{
   content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.3);
  z-index: -1;
}
</style>



<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>User</h3>
        </div>
        <div class="col-sm-12"  style="margin:0 0 20px 0; ">
            <input type="button" value="Add User" name="btn_add_user" id="btn_add_user" class="btn btn-info btn-sm" onclick="add_user_btn()"  />
        </div>


        <hr style="background-color:#000000; height:2px; width: 100%;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                        <th class="all">Date/Time</th>
                        <th class="all">User Name</th>
                        <th class="all">First Name</th>
                        <th class="all">Last Name</th>
                        <th class="all">BVIP</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="loading">
  <img src="<?php echo plugin_dir_url( _FILE_ ) ?>website-custom-plugin/WCP/Trades/images/bitcoin.gif">
</div>
<!--  Model Popup -->

<div class="modal fade" id="AddUserModal" role="dialog">
    <div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
	    <div class="modal-header">
		
		<h4 class="modal-title f-l"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
	    	<div class="modal-body">
                    
		</div>
		<div class="modal-footer">
		    <input type="submit" name="btnAddUser" id="btnAddUser" class="btn btn-info" value="Save" />
                    <button type="cancel" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	    </form>
	</div>
    </div>
</div>
<!-- Close Model Popup -->
<script>
    
    $(document).ready(function () {
      jQuery('#loading').hide();
        $ = jQuery;
        reload_table();
        var error_count=0;
         setInterval(function() {
                  window.location.reload();
                }, 300000); 
        $('#app_date').hide();
        

     
    $('#btnAddUser').on('click', function () {
            var user_id = jQuery('#user_id').val();
             var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              var regex2 = /^([0-9_\.\+])+$/;
            // var email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
            var data = jQuery('#action').val().trim();

            if(jQuery('#action').val() == 'WCP_Trades_Controller::registration_save'){
              	user_id = 1;

		 			if(jQuery('#password').val().trim() == ''){

		              jQuery('#password').css('border','1px solid red');
		              error_count++;
		             }else if(jQuery('#password').val().length<6){
		             	 jQuery('#password').css('border','1px solid red');
		              	 error_count++;
		            }else{
		            	if(jQuery('#repeat_password').val() != jQuery('#password').val()){
		            	jQuery('#password').css('border','1px solid red');
		            	 
		              error_count++;
		            }else{
		           		jQuery('#password').css('border','1px solid #bbb');
		               
		            }
		               
		            }

		            if(jQuery('#repeat_password').val().trim() == ''){
		              jQuery('#repeat_password').css('border','1px solid red');
		              error_count++;
		              }else if(jQuery('#repeat_password').val().length<6){
		             	 jQuery('#repeat_password').css('border','1px solid red');
		              	 error_count++;
		            
		            }else{
		            	if(jQuery('#repeat_password').val() != jQuery('#password').val()){
		            	 
		            	jQuery('#repeat_password').css('border','1px solid red');
		              error_count++;
		            }else{
		           		 
		              	jQuery('#repeat_password').css('border','1px solid #bbb');
		            }
		               
		            }

                 if(jQuery('#usd').val() == ''){
                  jQuery('#usd').css('border','1px solid red');
                  error_count++;
                }else{
                  jQuery('#usd').css('border','1px solid #bbb');
                } 
                 if(jQuery('#fname').val().trim() == ''){
                  jQuery('#fname').css('border','1px solid red');
                  error_count++;
                }else{
                  jQuery('#fname').css('border','1px solid #bbb');
                }
                if(jQuery('#lname').val().trim() == ''){
                  jQuery('#lname').css('border','1px solid red');
                  error_count++;
                }else{
                  jQuery('#lname').css('border','1px solid #bbb');
                }
            }else if(jQuery('#action').val() == 'WCP_Trades_Controller::update_user_data'){
            $('#test').hasClass('divhover'); // returns true

          
                if(jQuery('#uname').val().trim() == ''){
                  jQuery('#uname').css('border','1px solid red');
                  error_count++;
                }else{
                  jQuery('#uname').css('border','1px solid #bbb');
                }
            

                if(jQuery('#phone').val().trim() == ''){
                  jQuery('#phone').css('border','1px solid red');
                  error_count++;
                }else if( !regex2.test(jQuery('#phone').val())){
                  	 jQuery('#phone').css('border','1px solid red');
                    error_count++;
                }else{
                  jQuery('#phone').css('border','1px solid #bbb');
                }
               
 				
                if(jQuery('#email').val().trim() == ''){
                    jQuery('#email').css('border','1px solid red');
                    error_count++;
                  }else if( !regex.test(jQuery('#email').val())){
                  	 jQuery('#email').css('border','1px solid red');
                    error_count++;
                  }else{
                    jQuery('#email').css('border','1px solid #bbb');
                  }
              }else{
                

            if(jQuery('#coins').val().trim() == ''){
              jQuery('#coins').css('border','1px solid red');
              error_count++;
            }else if( !regex2.test(jQuery('#coins').val())){
                     jQuery('#coins').css('border','1px solid red');
                    error_count++;
             }else{
              jQuery('#coins').css('border','1px solid #bbb');
            }

             if(jQuery('#usd').val() == ''){
              jQuery('#usd').css('border','1px solid red');
              error_count++;
            }else{
              jQuery('#usd').css('border','1px solid #bbb');
            } 

            
              }
           
          
            
            
                
             
             
            
            if(user_id > 0 && error_count == 0){
              jQuery('#loading').show();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: jQuery('#userFrm').serialize(),
                    success: function (data) {
                        var result = JSON.parse(data);
                        if (result.is_ok == 1) {
                          jQuery('#loading').hide();
                            jQuery("#AddUserModal").modal('hide');
                            jQuery('#userFrm')[0].reset();
                            alert(result.msg);
                            reload_table();

                        }
                    }
                });
            }
        });
    });
     
     function reload_table() {
            var sone =  moment.tz.guess();
        var timezone = moment.tz(sone).zoneAbbr();
        var timedifference = moment.tz.guess();
            $('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "WCP_Trades_Controller::get_user_detail","timezone":timedifference}

                    },
                    "aoColumns": [
                        {mData: 'sign_date_time'},
                        {mData: 'display_name'},
                        {mData: 'first_name'},
                        {mData: 'last_name'},
                        {mData: 'coins'},
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [1],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                     $(".timezone123").html(timezone);  
                  }
            });
            
        }
      function user_delete(id) {
            if (confirm("Are you sure?")) {
                var user_id = id;
                jQuery('#loading').show();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {"action": "WCP_Trades_Controller::delete_user", user_id: user_id},
                    success: function (data) {
                        var result = JSON.parse(data)
                        if (result.status == 1) {
                          jQuery('#loading').hide();
                            reload_table();
                        }
                    }
                });
            }
            return false;
    }
    

 
             
     function add_coins(id) {

    	 
                    $(".modal-title").html("Add BVIP");
                    $(".modal-body").html('<form action="" class="userFrm" name="userFrm" id="userFrm" method="POST"><input type="hidden" id="action" name="action" value="WCP_Trades_Controller::coins_add" /><input type="hidden" id="user_id" ref="user_id" name="user_id" value="'+id+'"> <div class="form-group"><label>BVIP <em style="color:#f00">*</em></label><input type="text" class="form-control" id="coins"  name="coins" placeholder=""   ></div> </form>');
                                $('#AddUserModal').modal('show');
                                
                  
       
    }
    function remove_coins(id) {

    	 
                    $(".modal-title").html("Remove BVIP");
                    $(".modal-body").html('<form action="" class="userFrm" name="userFrm" id="userFrm" method="POST"><input type="hidden" id="action" name="action" value="WCP_Trades_Controller::coins_remove" /><input type="hidden" id="user_id" ref="user_id" name="user_id" value="'+id+'"> <div class="form-group"><label>BVIP <em style="color:#f00">*</em></label><input type="text" class="form-control" id="coins"  name="coins" placeholder=""   ></div> </form>');
                                $('#AddUserModal').modal('show');
                                
                  
       
    }

    function user_update(id) {

    	 $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {"action": "WCP_Trades_Controller::get_user_coin_data", id: id},
                    success: function (data) {
                         var res = JSON.parse(data);
                         // console.log(data);

                         for (var i = res['user_details'].length - 1; i >= 0; i--) {
                         	  
                              var user_details=res['user_details'];
                              
                             
                               var check="";
                               if(user_details[i]['is_approved'] == "on"){
                                check="checked";
                               } 
                    $(".modal-title").html("Edit User");
                    $(".modal-body").html('<form action="" class="userFrm" name="userFrm" id="userFrm" method="POST" style="display:inline-block"><input type="hidden" id="action" name="action" value="WCP_Trades_Controller::update_user_data" /><input type="hidden" id="user_id" ref="user_id" name="user_id" value="'+user_details[i]["user_id"]+'"> <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>User Name <em style="color:#f00">*</em></label><input type="text" class="form-control" id="uname" name="uname" placeholder="" value="'+user_details[i]["user_login"]+'"></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Frist Name <em style="color:#f00">*</em></label><input type="text" class="form-control" value="'+user_details[i]["first_name"]+'" id="fname" name="fname" placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Last Name <em style="color:#f00">*</em></label><input type="text" class="form-control" value="'+user_details[i]["last_name"]+'" id="lname" name="lname" placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>BVIP <em style="color:#f00">*</em></label><input type="text" class="form-control" value="'+user_details[i]["coin_amount"]+'" id="coin_amount" name="coin_amount" placeholder="" disabled></div><div class="form-group  col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Email <em style="color:#f00">*</em></label><input type="email" class="form-control" id="email"  name="email" placeholder="" value="'+user_details[i]["email"]+'"></div>   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Phone Number <em style="color:#f00">*</em></label><input type="text" class="form-control" id="phone" name="phone_no"  placeholder="" value="'+user_details[i]["phone_no"]+'"></div><div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12"><label>Is Approved?<em style="color:#f00">*</em></label><div class="onoffswitch"><input type="checkbox" name="approved" class="onoffswitch-checkbox" id="myonoffswitch" '+check+'><label class="onoffswitch-label" for="myonoffswitch"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></div><div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 app_date paddingLeft0"> <label for="date_time" class="col-form-label">Approved Date</label><div class="form-group " ><input type="text" class="form-control" name="approved_date" id="approved_date" value="'+user_details[i]['approved_date']+'"/> </div> </div><p id="err_msg">&nbsp;</p> </form>');
                                $('#AddUserModal').modal('show');
                                $('#approved_date').datetimepicker({hours12: "true",});
                                if(user_details[i]['is_approved'] == "on"){
                                  $('.app_date').show();
                                  $("#approved_date").attr("disabled","true");
                               }else{
                                  $('.app_date').show();
                              }
                                  $("#myonoffswitch").click(function(){
                                    if($(this).prop( "checked")){
                                     $('.app_date').hide();
                                    }else{
                                      $("#approved_date").prop("disabled", false);
                                      $('.app_date').show();
                                    }
                                  });
                                
                        }
                         
                        
                    }
                });
                  
       
    }
      function add_user_btn(){
       
 

                         	  $(".modal-title").html("Add User");
						        $(".modal-body").html('<form action="" class="userFrm" name="userFrm" id="userFrm" method="POST" style="display:inline-block"><input type="hidden" id="action" name="action" value="WCP_Trades_Controller::registration_save" /><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>User Name <em style="color:#f00">*</em></label><input type="text" class="form-control" id="uname" name="uname" placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Frist Name <em style="color:#f00">*</em></label><input type="text" class="form-control" id="fname" name="fname" placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Last Name <em style="color:#f00">*</em></label><input type="text" class="form-control" id="lname" name="lname" placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Email <em style="color:#f00">*</em></label><input type="email" class="form-control" id="email"  name="email" placeholder=""></div></div> <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>BVIP <em style="color:#f00">*</em></label><input type="text" class="form-control" id="coins"  name="coins" placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Password <em style="color:#f00">*</em></label><input type="password" id="password" name="password" class="form-control"  placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Repeat Password <em style="color:#f00">*</em></label><input type="password" name="repeat_password" id="repeat_password" class="form-control"   placeholder=""></div><div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Phone Number <em style="color:#f00">*</em></label><input type="text" class="form-control" id="phone" name="phone_no"  placeholder="" ></div><div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12"><label>Is Approved?<em style="color:#f00">*</em></label><div class="onoffswitch"><input type="checkbox" name="approved" class="onoffswitch-checkbox" id="myonoffswitch"><label class="onoffswitch-label" for="myonoffswitch"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></div><div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 app_date paddingLeft0"> <label for="date_time" class="col-form-label">Approved Date</label><div class="form-group " ><input type="text" class="form-control" name="approved_date" id="approved_date" /> </div> </div><p id="err_msg">&nbsp;</p> </form>');
                                $('#AddUserModal').modal('show');
                                $('#approved_date').datetimepicker({hours12: "true"});

                                $("#myonoffswitch").click(function(){
                                  
                                  if($(this).prop( "checked")){
                                   $('.app_date').hide();
                                  }else{
                                    $('.app_date').show();
                                  }
                                });
                         
                        
                    }
              
</script>
