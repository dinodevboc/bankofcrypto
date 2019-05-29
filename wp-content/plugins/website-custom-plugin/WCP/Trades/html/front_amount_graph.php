<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>../css/jquery-ui-timepicker-addon.css">
 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.23/moment-timezone-with-data.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) ?>../js/jquery-ui-timepicker-addon.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.js"></script>
 <style type="text/css">
   .amount_detail{
        padding: 135px 0 !important;
    background: #436397;
    border: 1px solid;
    border-color: #436397;
    border-radius: 11px;
    box-shadow: 0 0 25px -5px rgb(67, 99, 151);
    float: left;
    margin: 0;
    -webkit-transition: 0.2s !important;
    -moz-transition: 0.2s !important;
    transition: 0.2s !important;
    font-family: 'Nunito' !important;
    font-size: 32px !important;
    font-weight: 700 !important;
    text-transform: capitalize !important;
    letter-spacing: 0.5px !important;
    color: #FFF !important;
    display: inline !important;
    width: 100%;
    line-height: 50px;
    text-align: center;
}
.canvasjs-chart-credit {
  display: none;
}
.graph_showing_sections{
      border: 1px solid;
    border-radius: 11px;
    box-shadow: 0 0 25px -5px rgb(67, 99, 151);
    background: #fff;
        padding: 0 10px;
}
.title_profit{
      color: #436397;
    margin: 10px auto;
    font-family: 'Nunito' !important;
    font-size: 25px !important;
    font-weight: 700 !important;
    text-transform: capitalize !important;
    letter-spacing: 0.5px !important;
    display: block;
    width: 100%;
    line-height: 50px;
    text-align: center;
}
canvas{
  width: -webkit-fill-available;
}
.padding{
  padding: 0 !important;
}
.p_l_40{
 padding: 0 40px 0 0 !important;
}
.p_r_40{
  padding: 0 0 0 40px !important;
}
.flex_container{
        padding-top: 20px;
    margin: 50px 0 !important;
    display: block;
    width: 100%;
    float: left;
  }
 </style>

<div class="flex_container" style="padding-top:20px;">
    <div class="col-lg-12 col-mg-12 col-sm-12 col-xs-12 padding">
        <div class="col-lg-6 col-mg-6 col-sm-6 col-xs-6 p_l_40 ">
          <h3 class="amount_detail"><?php  
          if(count($result)>0){
            for ($i=0; $i <count($result) ; $i++) { 
                echo "Crypto Currency Amount  <br> $ ".number_format($result[$i]['coin_amount'],0,'',',');
            }
            
         }else{
            echo "Crypto Currency Amount  <br> $0";
         }
         ?>
        </h3>
        </div>
        <div class="col-lg-6 col-mg-6 col-sm-6 col-xs-6 p_r_40 ">
          <div class="graph_showing_sections">
            <h2 class="title_profit"><?php
              for ($i=0; $i <count($result) ; $i++) { 
               echo '$ '.number_format($result[$i]['coin_amount'],0,'',','); 
             } ?></h2>
            <div id="chartContainer" style="height: 300px; width: 100%;"></div>
          </div>
        </div>
    </div>
</div>

 
<script>
    
    $(document).ready(function () {
        $ = jQuery;

  
    });
    window.onload = function () {
         setInterval(function() {
                  window.location.reload();
                }, 300000); 
var chart = new CanvasJS.Chart("chartContainer", {
  animationEnabled: true,
  theme: "light2",
  axisY:{
    includeZero: false
  },
  data: [{        
    type: "line",       
    dataPoints: <?php echo json_encode($user_profits_result, JSON_NUMERIC_CHECK); ?>
  }]
});
chart.render();

}

 
          
    
 
</script>