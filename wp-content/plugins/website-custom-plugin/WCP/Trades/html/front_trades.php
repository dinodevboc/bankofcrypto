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
   
  .select_section {
    float: right;
    padding: 10px;
    margin: 0 0 -40px;
    z-index: 99999;
    position: relative;
    width: 27%;
  }
  .select_section  label{
        float: left;
            margin: 0 4px;
    line-height: 37px;
  }
  .dataTables_length{
        padding: 0 0 20px 0;
  }
  .dataTables_length select{
         padding: 0;
    width: 30px;
    float: none;
    display: -webkit-inline-box;
    margin: 0 5px;
  }
  .flex_container{
    margin: 0 !important;
  }
  /*#search{
    padding: 3px 10px;
  }*/
  h2.tital{
    color: #436397;
    margin: 10px auto;
    font-family: 'Nunito' !important;
    font-size: 35px !important;
    font-weight: 700 !important;
    text-transform: capitalize !important;
    letter-spacing: 0.5px !important;
    display: block;
    width: 100%;
    line-height: 50px;
    text-align: center;
  }
 </style>   


<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
      <h2 class="tital">Cypto Currency Trades</h2>
        <hr style="text-align: center;width: 10%;border-color: #436397;">
        <div class="table-responsive">
          <div class="select_section">
            <label><b>Filter :</b></label>
            <select class="js-example-basic-single" name="state">
              <option value="w">Last 7 Days</option>
              <option value="cm">This Month</option>
              <option value="lm">Last Month</option>
              <option value="cy">This Year</option>
              <option value="ly">Last Year</option>
            </select>
        
          </div>
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                        
                        <th class="all">Date/Time</th>
                        <th class="all">Crypto Currency Traded</th>
                        <th class="all">Buy Or Sell</th>
                        <th class="all">Number Of BVIP (Decimal)</th>
                        <th class="all">Price</th>
                        <th class="all">Total</th>
 
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
        $ = jQuery;
        reload_table();
        var error_count=0;
         setInterval(function() {
                  window.location.reload();
                }, 300000); 
      $('.js-example-basic-single').on('change', function() {
        
              var data = $(".js-example-basic-single option:selected").val();
              if(data != 0){
                reload_table(data);

              }

            });
        

  
    });


     function reload_table(name="") {
       var sone =  moment.tz.guess();
       var timezone = moment.tz(sone).zoneAbbr();
       var timedifference = moment.tz.guess();
            $('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                     "bFilter": false,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "WCP_Trades_Controller::get_user_trades_front","name":name,"timezone":timedifference}

                    },
                    "aoColumns": [
                      
                        {mData: 'date_time'},
                        {mData: 'crypto_name'},
                        {mData: 'is_buy_sell'},
                        {mData: 'no_of_coins'},
                        {mData: 'price'},
                        {mData: 'total'},
                        
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
    
 
</script>