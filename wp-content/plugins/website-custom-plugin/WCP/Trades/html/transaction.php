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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.js"></script>

<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>User Transaction</h3>
        </div>
 <style type="text/css">
 	.select2-container{
 		width:150px !important;
 	}
 	.select_section{
 		float: right;
	    right: 0;
	    padding: 0;
	    margin: 0 0 -30px;
	    z-index: 99999;
	    position: relative;
 	}
 	#search{
 		padding: 3px 10px;
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
 <div id="loading">
  <img src="<?php echo plugin_dir_url( _FILE_ ) ?>website-custom-plugin/WCP/Trades/images/bitcoin.gif">
</div>

        <hr style="background-color:#000000; height:2px; width: 100%;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
        	<div class="select_section">

        		<select class="js-example-basic-single" name="state">
				</select>
				<button  class="btn btn-info search" id="search">Search User</button>
        	</div>
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                        <th class="all">Date/Time</th>
                        <th class="all">Transaction Type</th>
                        <th class="all">First Name</th>
                        <th class="all">Last Name</th>
                        <th class="all">Amount</th>
                       
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    
    $(document).ready(function () {
        jQuery('#loading').hide();
        $ = jQuery;
         setInterval(function() {
                  window.location.reload();
                }, 300000); 
        reload_table();
        jQuery('#loading').show();
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "WCP_Trades_Controller::get_select_data"},
            success: function (data) {
                jQuery('#loading').hide();
                var result = JSON.parse(data)
              		var html="<option value='All Users'>All Users</option>";
                 for (var i = 0; i < result['user_details'].length; i++) {
                 	  
                      var user_details=result['user_details'];
                      html=html+'<option value="'+user_details[i]["ID"]+'">'+user_details[i]["display_name"]+'</option>'
                  }
                  $('.js-example-basic-single').html(html);
				    
            }
        });

        $('.js-example-basic-single').select2({
        	 placeholder: "Select a User"
        });

         $('.search').on('click', function() {
	      var data = $(".js-example-basic-single option:selected").text();
	      select2_table(data);
	    });
      
  
    });

    function select2_table(name) {
        var sone =  moment.tz.guess();
        var timezone = moment.tz(sone).zoneAbbr();
        var timedifference = moment.tz.guess();
    	$('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bFilter": false,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "WCP_Trades_Controller::get_transaction_detail","name":name,"timezone":timedifference}

                    },
                    "aoColumns": [
                        {mData: 'gm_created_date_time'},
                        {mData: 'transaction_type'},
                        {mData: 'first_name'},
                        {mData: 'last_name'},
                        {mData: 'amount'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [2],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                     $(".timezone123").html(timezone);  
                  }
            });
            
            
    }
     
     function reload_table() {
            var sone =  moment.tz.guess();
        var timezone = moment.tz(sone).zoneAbbr();
         var timedifference = moment.tz.guess();
            $('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bFilter": false,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "WCP_Trades_Controller::get_transaction_detail","timezone":timedifference}

                    },
                    "aoColumns": [
                        {mData: 'gm_created_date_time'},
                        {mData: 'transaction_type'},
                        {mData: 'first_name'},
                        {mData: 'last_name'},
                        {mData: 'amount'}
                       
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [2],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                     $(".timezone123").html(timezone);  
                  }
            });
             
            
            
        }
    
 
</script>