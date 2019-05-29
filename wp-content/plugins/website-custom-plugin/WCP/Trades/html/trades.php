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
input[type=number],.wp-admin select
{
  height:34px;
}
</style>
<div id="loading">
  <img src="<?php echo plugin_dir_url( _FILE_ ) ?>website-custom-plugin/WCP/Trades/images/bitcoin.gif">
</div>
<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>Trades</h3>
        </div>
        <div class="col-sm-12"  style="margin:0 0 20px 0; ">
            <input type="button" value="Add Trades" name="btn_add_user" id="btn_add_user" class="btn btn-info btn-sm" onclick="add_book_btn()"  />
        </div>

        <hr style="background-color:#000000; height:2px; width: 100%;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th class="all">Client Name</th> -->
                        <th class="all">Date/Time</th>
                        <th class="all">Crypto Currency Traded</th>
                        <th class="all">Buy Or Sell</th>
                        <th class="all">Number Of BVIP (Decimal)</th>
                        <th class="all">Price</th>
                        <th class="all">Total</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
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

        

     
      $('#btnAddUser').on('click', function () {
              var user_id = jQuery('#user_id').val();
              var regex = /^\d+(?:\.\d{1,2})?$/;
              var data = jQuery('#action').val().trim();
              if(jQuery('#action').val().trim() == 'WCP_Trades_Controller::AddUserDetails'){
                user_id = 1;
              }
              $('#test').hasClass('divhover'); // returns true
              if($('#crypto_traded').hasClass('has-error')){
                  jQuery('#crypto_traded').css('border','1px solid red');
              }else{
                  if(jQuery('#crypto_traded').val().trim() == ''){
                    jQuery('#crypto_traded').css('border','1px solid red');
                    error_count++;
                  }else{
                    jQuery('#crypto_traded').css('border','1px solid #bbb');
                  }
              }
              if(jQuery('#price').val().trim() == ''){
                jQuery('#price').css('border','1px solid red');
                error_count++;
              }else{
                jQuery('#price').css('border','1px solid #bbb');
              }


              if(jQuery('#no_of_coins').val().trim() == ''){
                jQuery('#no_of_coins').css('border','1px solid red');
                error_count++;
              }else if( !regex.test(jQuery('#no_of_coins').val())){
                jQuery('#no_of_coins').css('border','1px solid red');
                error_count++;
              }
              else{
                jQuery('#no_of_coins').css('border','1px solid #bbb');
              }

         
              if(jQuery("#date_time").val().trim()==""){
                                  var    date=moment();
                                  var expires = moment(date).utc().format("YYYY-MM-DD H:m:s");
                                   $(".utc_datetime").val(expires);
                                   $("#date_time").val(moment(date).format("YYYY-MM-DD H:m:s"));
                                    }
              
              if(user_id > 0 && error_count == 0){
                jQuery('#loading').show();
                  $.ajax({
                      type: 'POST',
                      url: '<?php echo admin_url('admin-ajax.php'); ?>',
                      data: jQuery('#UserUpdateform').serialize(),
                      success: function (data) {
                          var result = JSON.parse(data);
                          if (result.status == 1) {
                            jQuery('#loading').hide();
                              jQuery("#AddUserModal").modal('hide');
                              jQuery('#UserUpdateform')[0].reset();
                              alert(result.msg);
                              reload_table();
                          }else{
                              alert(result.msg);
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
                        data: {"action": "WCP_Trades_Controller::get_user","timezone":timedifference}

                    },
                    "aoColumns": [
                      
                        {mData: 'date_time'},
                        {mData: 'crypto_name'},
                        {mData: 'is_buy_sell'},
                        {mData: 'no_of_coins'},
                        {mData: 'price'},
                        {mData: 'total'},
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [5],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                     $(".timezone123").html(timezone);  
                  }
            });	 
    }

    function user_record_delete(id) {
      if (confirm("Are you sure?")) {
          var user_id = id;
        jQuery('#loading').show();
          $.ajax({
              type: 'POST',
              url: '<?php echo admin_url('admin-ajax.php'); ?>',
              data: {"action": "WCP_Trades_Controller::delete_user_record", user_id: user_id},
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
    function user_record_update(id) {
    $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "WCP_Trades_Controller::get_user_detail_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {
                              user_name=result['user_details'][i]['display_name'];
                             var user_details =result['user_details'];

                             var coin_details=result['coin_details'];
                              var opation="";
                               var price="";
                               for (var j = 0; j <= coin_details.length - 1; j++) {
                                    if(coin_details[j]['id']==user_details[i]['crypto_traded']){
                                         opation+=" <option value='"+coin_details[j]['id']+"' selected>"+coin_details[j]['name']+"</option>";
                                         
                                    }else{
                                        opation+=" <option value='"+coin_details[j]['id']+"' >"+coin_details[j]['name']+"</option>";
                                    }
                               }
                               var timezone_offset_minutes = new Date().getTimezoneOffset();
                              timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
                    $(".modal-title").html("Edit Trade Detail");
                    $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data" style="display: inline-block;"><input type="hidden" name="timezone_offset_minutes" value="'+timezone_offset_minutes+'">   <input type="hidden" id="action" name="action" value="WCP_Trades_Controller::UpdateUserDetails">  <input type="hidden" id="user_id" ref="user_id" name="user_id" value="'+user_details[i]["trade_id"]+'">    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="crypto_traded" class="col-form-label">Crypto Currency Traded:<em style="color:#f00">*</em></label>   <select name="crypto_traded" class="form-control" id="crypto_traded">  '+opation+'      </select>      </div>  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="price" class="col-form-label">Price:<em style="color:#f00">*</em></label>        <input type="number" class="form-control" id="price" name="price" value="'+user_details[i]['price']+'">     </div>  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="no_of_coins" class="col-form-label">Number Of BVIP:<em style="color:#f00">*</em></label>        <input type="number" class="form-control" id="no_of_coins" name="no_of_coins" value="'+user_details[i]['no_of_coins']+'">     </div>  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="is_buy_sell" class="col-form-label">Buy Or Sell:<em style="color:#f00">*</em></label>        <select name="is_buy_sell" class="form-control" id="is_buy_sell">           <option value="buy" '+result["buy"]+'>Buy</option>            <option value="sell" '+result["sell"]+'>Sell</option>      </select>   </div>   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12"> <label for="date_time" class="col-form-label">Date / Time:</label><div class="form-group " ><input type="text" class="form-control" name="date_time" id="date_time" value="'+user_details[i]['date_time']+'"/> <input type="hidden" name="utc_datetime" class="utc_datetime" > </div> </div></form>');
                                $('#AddUserModal').modal('show');
                                $('#date_time').datetimepicker({hours12: "true"});
                        $('#AddUserModal').modal('show');
                        $('#date_time').change(function () {
                                  date=$(this).val();
                                  
                                 var expires = moment(date).utc().format("YYYY-MM-DD H:m:s");
                                 $(".utc_datetime").val(expires);
                                    console.log(expires);
                                    console.log(date);
                                 });

                       
                    }
                    
                }
            }
        });
       
    }
      function add_book_btn(){
      	 // var name=wp_get_current_user();
      	 var name='';
      	 var user_name='';
      	 $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {"action": "WCP_Trades_Controller::check_username"},
                    success: function (data) {
                         var res = JSON.parse(data);
                         // console.log(data);

                         for (var i = res['user_details'].length - 1; i >= 0; i--) {
                            user_name=res['user_details'][i]['display_name'];
                              var coin_details=res['coin_details'];
                              var opation="";
                               for (var j = 0; j <= coin_details.length - 1; j++) {
                                 opation+=" <option value='"+coin_details[j]['id']+"'>"+coin_details[j]['name']+"</option>";
                               }
                              var timezone_offset_minutes = new Date().getTimezoneOffset();
                              timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;


                            $(".modal-title").html("Add Trade Detail");
                            $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;"> <input type="hidden" name="timezone_offset_minutes" value="'+timezone_offset_minutes+'"> <input type="hidden" id="action" name="action" value="WCP_Trades_Controller::AddUserDetails">       </div>  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="crypto_traded" class="col-form-label">Crypto Currency Traded:<em style="color:#f00">*</em></label>   <select name="crypto_traded" class="form-control" id="crypto_traded">    '+opation+' </select>      </div>  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="price" class="col-form-label">Price:<em style="color:#f00">*</em></label>        <input type="number" class="form-control" id="price" name="price" value="">     </div>  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="no_of_coins" class="col-form-label">Number Of BVIP:<em style="color:#f00">*</em></label>        <input type="number" class="form-control" id="no_of_coins" name="no_of_coins" value="">     </div>  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">        <label for="is_buy_sell" class="col-form-label">Buy Or Sell:<em style="color:#f00">*</em></label>        <select name="is_buy_sell" class="form-control" id="is_buy_sell">           <option value="buy" selected>Buy</option>            <option value="sell">Sell</option>      </select>   </div>   <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">                  <label for="date_time" class="col-form-label">Date / Time:</label>                  <div class="form-group " >                     <input type="text" class="form-control" name="date_time" id="date_time" /><input type="hidden" name="utc_datetime" class="utc_datetime" > </div> </div></form>');
                                $('#AddUserModal').modal('show');
                                $('#date_time').datetimepicker();
                                 $('#date_time').change(function () {
                                  date=$(this).val();
                                  
                                 var expires = moment(date).utc().format("YYYY-MM-DD H:m:s");
                                 $(".utc_datetime").val(expires);
                                    console.log(expires);
                                    console.log(date);
                                 });
                         }
                         
                        
                    }
                });
        //clearvalue();
      
    }
   
 
</script>