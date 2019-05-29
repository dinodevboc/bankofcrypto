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
            <h3>Platform Update</h3>
        </div>
        <div class="col-sm-12"  style="margin:0 0 20px 0; ">
            <input type="button" value="Update Platform" name="btn_add_user" id="btn_add_user" class="btn btn-info btn-sm" onclick="add_book_btn()"  />
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
                        <th class="all">First Name</th>
                        <th class="all">Last Name</th>
                        <th class="all">Number Of BVIP (show % ownership of total pool)</th>
                        <th class="all">Number Of BVIP Before</th>
                        <th class="all">Number Of BVIP Increase/Decrease</th>
                        <th class="all">Number Of BVIP After</th>
                        <th class="all">Investment Pool Before</th>
                        <th class="all">Investment Pool Increase/Decrease</th>
                        <th class="all">Investment Pool After</th>
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
                }, 600000); 

    
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
                        data: {"action": "WCP_Trades_Controller::get_user_platform","timezone":timedifference}

                    },
                    "aoColumns": [
                      
                        {mData: 'date_time'},
                        {mData: 'first_name'},
                        {mData: 'last_name'},
                        {mData: 'coin_amount_onwership'},
                        {mData: 'last_coin_amount'},
                        {mData: 'after_coin_add'},
                        {mData: 'coin_amount'},
                        {mData: 'new_coin_amount'},
                        {mData: 'new_coin_amount_pool'},
                        {mData: 'old_coin_amount_pool'}
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

    

      function add_book_btn(){
      	 // var name=wp_get_current_user();
      	 var name='';
      	 var user_name='';
         jQuery('#loading').show();
      	 $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {"action": "WCP_Trades_Controller::calculation_without_api_view"},
                    success: function (data) {

                         var res = JSON.parse(data);
                         jQuery('#loading').hide();
                         if(res.status==1){

                          alert("Platform Update Sucessfully.");
                          reload_table();
                         }else{
                            alert("Platform Not Update");
                         }

                        
                    }
                });
      
    }
   
 
</script>