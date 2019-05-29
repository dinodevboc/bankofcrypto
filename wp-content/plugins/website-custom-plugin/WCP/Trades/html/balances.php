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
<style type="text/css">
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
<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>Crypto Currency Balances</h3>
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
                        <th class="all">Crypto  Currency</th>
                        <th class="all">Amount</th>
                        <th class="all">Price</th>
                        <th class="all">USD Value</th>

                       
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot align="right">
                    <tr>
                        <th colspan="3"></th><th></th> 
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script>
    
    $(document).ready(function () {
        jQuery('#loading').hide();
        $ = jQuery;
        reload_table();
       setInterval(function() {
                  window.location.reload();
                }, 300000); 
      
  
    });

 
     
     function reload_table() {
        jQuery('#loading').show();
         var sone =  moment.tz.guess();
        var timezone = moment.tz(sone).zoneAbbr();
        var timedifference = moment.tz.guess();
            $('#service-table').dataTable({
                    "footerCallback": function ( row, data, start, end, display ) {
                         var api = this.api(), data;
 
                            // converting to interger to find total
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                 
                            // computing column Total of the complete result 
                            var monTotal = api
                                .column( 0 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                
                            // var tueTotal = api
                            //     .column( 4 )
                            //     .data()
                            //     .reduce( function (a, b) {
                            //         return intVal(a) + intVal(b);
                            //     }, 0 );
                            var usdTotal = api
                                .column( 3 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                // var total_value=monTotal+tueTotal;
                                
                          
                        // Update footer
                        $( api.column( 0 ).footer() ).html('Total');
                        $( api.column( 3 ).footer() ).html('$'+usdTotal);
                        // $( api.column( 2 ).footer() ).html(tueTotal);
                        // $( api.column( 3 ).footer() ).html(usdTotal);
                       
                    },
                    
                    "paging": true,
                    "pageLength": 50,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "WCP_Trades_Controller::get_currency_balances_detail","timezone":timedifference}

                    },
                    "aoColumns": [
                        {mData: 'date_time'},
                        {mData: 'display_name'},
                        {mData: 'balance_usd'},
                        {mData: 'coins_price'},
                        {mData: 'price'}

                    ],
                    "order": [[ 0, "desc" ]],        
                    "searching": false,
                    "columnDefs": [{
                        "targets": [1],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                     $(".timezone123").html(timezone);  
                  }
            });
           
            jQuery('#loading').hide();
            
        }
    
 
</script>