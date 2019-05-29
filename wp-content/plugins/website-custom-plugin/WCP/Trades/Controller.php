<?php
session_start();
// Make sure we don't expose any info if called directly
if (!defined('WPINC')) {
  die;
}


include_once(dirname(__FILE__) . "/View.php");
include_once(dirname(__FILE__) . "/Model.php");

//add_action( 'admin_init', 'redirect_non_logged_users_to_specific_page' );
class WCP_Trades_Controller {

    //Backend trade page
    public function render_view_front_screen() {
        print WCP_Trades_View::build_html();
    }
  
    //Frontend trade page
    public function render_view_front_trades_screen() {
            print WCP_Trades_View::trades_build_html();
    }

    //Backend User Page
    public function render_view_user_front_screen() {
            print WCP_Trades_View::user_build_html();
    }

    //Backend Transaction page
    public function render_view_user_transaction_front_screen() {
            print WCP_Trades_View::user_transaction_build_html();
    }
    //Backend Curremcy balances page
    public function render_view_currency_balances_screen()
    {
          print WCP_Trades_View::currency_balances_build_html();
    }

    //Backend user balances page
    public function render_view_user_balances_screen() {
            print WCP_Trades_View::user_balances_build_html();
    }

    public function view_platform_update()
    {
            print WCP_Trades_View::user_platform_update_html();
    }
    //in backend show plugin and set name in admin menu
    public function wcp_tenant_screen() {


        add_menu_page('Bank Of Crypto', 'Bank Of Crypto', 'manage_options', 'wcp-trades' );
        add_submenu_page('wcp-trades', 'Trades', 'Trades', 'manage_options', 'wcp-trades',array('WCP_Trades_Controller', 'render_view_front_screen') );
        add_submenu_page('wcp-trades', 'Users', 'Users', 'manage_options', 'user-trades',array('WCP_Trades_Controller', 'render_view_user_front_screen') );
        add_submenu_page('wcp-trades', 'Transactions', 'Transactions', 'manage_options', 'user-transactions',array('WCP_Trades_Controller', 'render_view_user_transaction_front_screen') );

        add_submenu_page('wcp-trades', 'Currency Balances', 'Currency Balances', 'manage_options', 'crypto-currency-balances',array('WCP_Trades_Controller', 'render_view_currency_balances_screen') );

        add_submenu_page('wcp-trades', 'User Balances', 'User Balances', 'manage_options', 'user-balances',array('WCP_Trades_Controller', 'render_view_user_balances_screen') );
        add_submenu_page('wcp-trades', 'Platform Update', 'Platform Update', 'manage_options', 'platform-update',array('WCP_Trades_Controller', 'view_platform_update') );

    }

    public function render_view_front_trades_amount_screen()
    {
        $user_id=get_current_user_id();
        global $wpdb,$wp;
        date_default_timezone_set($_SESSION["timezone"]);
        $sql = "SELECT * FROM users_data   where user_id={$user_id}";
        $result=$wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");

        $user_profits_sql = "SELECT amounts as y FROM user_profits   where user_id={$user_id}";
        $user_profits_result=$wpdb->get_results($wpdb->prepare($user_profits_sql), "ARRAY_A");
         
        print WCP_Trades_View::render_view_front_trades_amount_html($result,$user_profits_result);  
         
    }

    public function render_view_front_transaction_screen()
    {
        print WCP_Trades_View::render_view_front_transaction_html();  
         
    }
    
    // Get all trades data 
    public function get_user_platform(){

        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        // $data = array();

         $_SESSION["timezone"] = $_POST['timezone'];
         date_default_timezone_set($_SESSION["timezone"]);

        $sql = "SELECT * FROM users_data as bc";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (bc.first_name LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
            $sql .= " AND (bc.last_name LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
           
       
        }

        $result=$wpdb->get_results($wpdb->prepare($sql), OBJECT);
        
        $totalData = 0;
        $totalFiltered = 0;
        $total_amount=0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
            for ($i=0; $i < count($result) ; $i++) { 
        		$total_amount=$total_amount+$result[$i]->coin_amount;
            }
        }

        // print_r($result);die();
        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= "  LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        $old_pool=0;
        $pool_add_remove=0;

         foreach ($service_price_list as $row) { 
         	$pool_add_remove=$pool_add_remove+$row->pool_add_remove;
         
         } 
        	$old_pool=$total_amount+$pool_add_remove;
        foreach ($service_price_list as $row) { 

            $temp['date_time'] = date("m/d/Y g:i A",strtotime($row->update_date))." <span class='timezone123'></span>";
            $temp['first_name'] = $row->first_name;
            $temp['last_name'] = $row->last_name;
            $temp['coin_amount'] = $row->coin_amount;
            $temp['coin_amount_onwership'] = round($row->coin_amount/$total_amount*100)."%";
            $temp['after_coin_add'] = ($row->pool_add_remove=="")?"0" :sprintf("%.2f", $row->pool_add_remove);
            $temp['last_coin_amount'] = ($row->last_coin_amount=="")?"0" :sprintf("%.2f",$row->last_coin_amount);
            $temp['new_coin_amount'] = sprintf("%.2f",$total_amount);
            $temp['new_coin_amount_pool'] = ($row->pool_add_remove=="")?"0" :sprintf("%.2f", $row->pool_add_remove);
            $temp['old_coin_amount_pool'] = sprintf("%.2f",$old_pool);
            $id = $row->trade_id;
            $data[] = $temp;
            $id = "";
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        echo json_encode($json_data);
        exit(0);
    }


    // Get all trades data 
    public function get_user(){

        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();

         $_SESSION["timezone"] = $_POST['timezone'];
         date_default_timezone_set($_SESSION["timezone"]);

        $sql = "SELECT b.*,bc.name,bc.coin_icon FROM client_trades as b JOIN main_cryptocurrency_balances as bc ON b.crypto_traded = bc.id";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (bc.name LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
           
       
        }

        $result=$wpdb->get_results($wpdb->prepare($sql), OBJECT);
        
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY trade_id DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

            $temp['date_time'] = date("m/d/Y g:i A",strtotime($row->date_time))." <span class='timezone123'></span>";
            $temp['crypto_name'] = "<img src='".plugin_dir_url( __FILE__ )."images/".$row->coin_icon."'>".$row->name;
            $temp['is_buy_sell'] = $row->is_buy_sell;
            $temp['no_of_coins'] = $row->no_of_coins;
            $temp['price'] = "$".sprintf("%.2f", $row->price);
            $temp['total'] = "$".sprintf("%.2f", $row->total);
            
            $id = $row->trade_id;
            $action = '<div style="display: flex;">';
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="user_record_update(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='user_record_delete(" . $id . ")'>&nbsp;";
            $action .= '</div>';
            
            $temp['action'] = $action;
            $data[] = $temp;
            $id = "";
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        echo json_encode($json_data);
        exit(0);
    }

     // Get data for Backend User page
    public function get_user_detail(){
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
        
        global $wpdb,$wp;
        $data = array();
         $_SESSION["timezone"] = $_POST['timezone'];
         date_default_timezone_set($_SESSION["timezone"]);

        $sql2 = "SELECT b.*,u.display_name FROM users_data as b LEFT JOIN wp_users as u  ON b.user_id = u.ID ";

         

        $result2=$wpdb->get_results($wpdb->prepare($sql2), "ARRAY_A");
        for ($i=0; $i <count($result2) ; $i++) { 
             if(date("Y-m-d") == date("Y-m-d",$result2[$i]["approved_date"])){
             $user_conis_data['is_approved'] =  "on";
          
             $user_result2 = $wpdb->prepare($wpdb->update('users_data', $user_conis_data, array('user_id' => $result2[$i]["user_id"])));
             }
        }
         $sql = "SELECT b.*,u.display_name FROM users_data as b LEFT JOIN wp_users as u  ON b.user_id = u.ID ";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (u.display_name LIKE '%" . esc_sql($requestData['search']['value']) . "%')  OR  (b.first_name LIKE '%" . esc_sql($requestData['search']['value']) . "%')  OR (b.last_name LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
       
        }

        $result=$wpdb->get_results($wpdb->prepare($sql), OBJECT);
        // print_r($result);
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;
        // print_r($service_price_list);
        foreach ($service_price_list as $row) {
        //     $id = $row->id;
           

            
            $temp['sign_date_time'] = date("m/d/Y g:i A",strtotime($row->sign_date_time));
            $temp['display_name'] = $row->display_name;
            $temp['first_name'] = $row->first_name;
            $temp['last_name'] = $row->last_name;
            $temp['coins'] = $row->coin_amount;
          
            $user_id = $row->user_id;
             $id = $row->id;
            $action = '<div style="display: flex;">';
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="user_update(' . $user_id . ')">&nbsp; &nbsp;';
            $action .= '<input type="button" value="Add Coin(s)" class="btn btn-primary f-l"  onclick="add_coins(' . $id . ')">&nbsp; &nbsp;';
            $action .= '<input type="button" value="Remove Coin(s)" class="btn btn-warning f-r" onclick="remove_coins('. $id .')" >&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='user_delete(" . $user_id . ")'>&nbsp;";
            $action .= '</div>';
            
            $temp['action'] = $action;
            $data[] = $temp;
            $id = "";
            $user_id = "";
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        echo json_encode($json_data);
        exit(0);

    }

    // Get data for Backend Transaction page 
    public function get_transaction_detail(){
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
        
        global $wpdb,$wp;
        $data = array();
         $_SESSION["timezone"] = $_POST['timezone'];
         date_default_timezone_set($_SESSION["timezone"]);
        $sql = "SELECT b.*,u.* FROM user_coin_transactions as b LEFT JOIN users_data as u  ON b.user_id = u.user_id  ";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (u.display_name LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";       
        }

        if (isset($_POST['name']) && $_POST['name'] != '') {
            if($_POST['name']!="All Users"){
            $sql .= " WHERE (u.display_name LIKE '%" . esc_sql($_POST['name']) . "%') ";
            }
        }

        $result=$wpdb->get_results($wpdb->prepare($sql), OBJECT);
        
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }



        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY transaction_id DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) {

            $temp['ID'] = $row->user_id;
            $transaction_type="";

            if($row->transaction_type_id == 1){

                $transaction_type="DEPOSIT";

            }else if ($row->transaction_type_id == 2) {

                $transaction_type="WITHDRAW";

            }else if ($row->transaction_type_id == 3) {

                $transaction_type="PLATFORM UPDATE";

            }       
            $temp['gm_created_date_time'] = date("m/d/Y g:i A",strtotime($row->gm_created_date_time))." <span class='timezone123'></span>";
            $temp['transaction_type'] = $transaction_type;
            $temp['first_name'] = $row->first_name;
            $temp['last_name'] = $row->last_name;
            $temp['amount'] = $row->amount;
            $data[] = $temp;         
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );

        echo json_encode($json_data);
        exit(0);

    }

    // Get data for Backend currency Balances page 
    public function get_currency_balances_detail(){
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
        
        global $wpdb,$wp;
        $data = array();


        $_SESSION["timezone"] = $_POST['timezone'];
        date_default_timezone_set($_SESSION["timezone"]);

        // $mcb_sql = "SELECT  * FROM  main_cryptocurrency_balances  " ;

        // $coin_symbol = $wpdb->get_results($wpdb->prepare($mcb_sql), "ARRAY_A");

        // $coins_data=[];

   

        // for ($i=0; $i < count($coin_symbol); $i++) {    

        //    $link = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=0962e57a7dd5e6daa6c7d217665bc70ac18f823f08958b8f35deee8715a148bf";

        //     $price_data = json_decode(file_get_contents($link),true);

        //         if($price_data['Response']=="Error"){
          
        //   $link2 = "https://chasing-coins.com/api/v1/convert/".$coin_symbol[$i]['coin_symbol']."/USD";
        //   $data2 = json_decode(file_get_contents($link2),true);
        //   $update_sql = "UPDATE  main_cryptocurrency_balances SET price = ".$data2['result']." where coin_symbol = '".$coin_symbol[$i]['coin_symbol']."'";
        // }else{
        //         $update_sql = "UPDATE  main_cryptocurrency_balances SET price = ".$price_data[$coin_symbol[$i]['coin_symbol']]['USD']." where coin_symbol = '".$coin_symbol[$i]['coin_symbol']."'";
        // }

        //     $c_result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");

        // }



            $usd=0; 
            $usd_sql = "SELECT *  FROM trades_usd_price   ";
            $usd_result = $wpdb->get_results($wpdb->prepare($usd_sql), "ARRAY_A");
            if(count($usd_result)>0){
                for ($k=0; $k < count($usd_result); $k++) { 
                    $usd=$usd_result[$k]['usd_price'];
                    $date=$usd_result[$k]['date'];
                }
            }
           
             $temp['date_time'] = date("m/d/Y g:i A",strtotime($date))." <span class='timezone123'></span>";
            $temp['display_name'] = "USD";
            $temp['balance_usd'] = $usd;
            $temp['usd'] = $usd;
            $temp['price'] = "$".$usd ;
            $temp['coins_price'] = "$1" ;
            $data[] = $temp;   
      
        $mcb_sql = "SELECT  * FROM  main_cryptocurrency_balances  " ;

        $coin_symbol = $wpdb->get_results($wpdb->prepare($mcb_sql), "ARRAY_A");

        $totalData = 0;
        $totalFiltered = 0;
        if (count($coin_symbol > 0)) {
            $totalData = count($coin_symbol);
            $totalFiltered = count($coin_symbol);
        }

        $balance=0;
        $price=0;
        for ($i=0; $i < count($coin_symbol); $i++) {



            $c_balance=0;
            $c_balance2=0;
          $usd=0;   
            $users_data_sql = "SELECT ud.*  FROM users_data as ud  where crypto_traded=".$coin_symbol[$i]['id'];
            $ud_result = $wpdb->get_results($wpdb->prepare($users_data_sql), "ARRAY_A");

            $client_trades_sql = "SELECT  ct.* FROM  client_trades as ct   where crypto_traded=".$coin_symbol[$i]['id'];
            $ct_result = $wpdb->get_results($wpdb->prepare($client_trades_sql), "ARRAY_A");
            if(count($ud_result)>0){
                for ($k=0; $k < count($ud_result); $k++) { 
                    $balance=$balance+$ud_result[$k]['coin_amount'];
                    $c_balance=$c_balance+$ud_result[$k]['coin_amount'];
                   $usd=$ud_result[$k]['balance_usd'];
                }
            }
            if (count($ct_result)) {
               for ($l=0; $l < count($ct_result); $l++) { 
                    $balance=$balance+$ct_result[$l]['no_of_coins'];
                    if($ct_result[$l]['is_buy_sell'] == "buy"){
                       
                       $c_balance2=$c_balance2+$ct_result[$l]['no_of_coins']; 
                    }else{
                       
                             $c_balance2=$c_balance2-$ct_result[$l]['no_of_coins']; 
                    }
                     
                     
                }
            } 
                $price=$coin_symbol[$i]['price']*$c_balance2;
    		$temp['date_time'] = date("m/d/Y g:i A",strtotime($coin_symbol[$i]['date']))." <span class='timezone123'></span>";
            $temp['display_name'] = $coin_symbol[$i]['name'];
              $temp['balance_usd'] = $c_balance2;
                $temp['usd'] = $c_balance;
              $temp['price'] = "$".$price ;
                 $temp['coins_price'] = "$".$coin_symbol[$i]['price'] ;
              $data[] = $temp;   
        
        }

        

       
        $arr_data = Array();
        $arr_data = $coin_symbol;

   
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $c_sql
        );

        echo json_encode($json_data);
        exit(0);

    }

    // Get data for Backend user Balances page 
    public function get_user_balances_detail(){
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
        
        global $wpdb,$wp;
        $data = array();


         $_SESSION["timezone"] = $_POST['timezone'];
     
         date_default_timezone_set($_SESSION["timezone"]);


        $coins_data=[];

    
        
             
      
        $sql = "SELECT  ud.*,wu.* FROM  users_data as ud  LEFT JOIN wp_users as wu  ON ud.user_id = wu.ID  " ;

          if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (wu.display_name LIKE '%" . esc_sql($requestData['search']['value']) . "%')   ";
       
        }
       

        $users_data = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");

        $totalData = 0;
        $totalFiltered = 0;
        if (count($users_data > 0)) {
            $totalData = count($users_data);
            $totalFiltered = count($users_data);
        }

        $balance=0;
        $price=0;

         $user_sql = "SELECT  ud.*,wu.* FROM  users_data as ud  LEFT JOIN wp_users as wu  ON ud.user_id = wu.ID  " ;

          if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $user_sql .= " WHERE (wu.display_name LIKE '%" . esc_sql($requestData['search']['value']) . "%')   ";
       
        }
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $user_sql .= " LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }
        
        $user_data = $wpdb->get_results($wpdb->prepare($user_sql), "ARRAY_A");
        if(count($user_data)>0){
        for ($i=0; $i < count($user_data); $i++) {



            $c_balance=0;
            $c_balance2=0;
            $usd=0;   
            $name="";
        //     $users_data_sql = "SELECT ud.*,wu.*  FROM users_data as ud LEFT JOIN wp_users as wu  ON ud.user_id = wu.ID  where ud.user_id=".$user_data[$i]['user_id'];

        //     if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
        //     $users_data_sql .= " AND (wu.display_name LIKE '%" . esc_sql($requestData['search']['value']) . "%')   ";
       
        // }
        //     $ud_result = $wpdb->get_results($wpdb->prepare($users_data_sql), "ARRAY_A");
            

            // $client_trades_sql = "SELECT  ct.* FROM  client_trades as ct   where crypto_traded=".$user_data[$i]['id'];
            // $ct_result = $wpdb->get_results($wpdb->prepare($client_trades_sql), "ARRAY_A");

             
                    $balance=$balance+$user_data[$i]['coin_amount'];
                    $c_balance=$c_balance+$user_data[$i]['coin_amount'];
                   // $usd=$ud_result[$k]['balance_usd'];
                   $name=$user_data[$i]['display_name'];

                 
            // if (count($ct_result)) {
            //    for ($l=0; $l < count($ct_result); $l++) { 
            //         $balance=$balance+$ct_result[$l]['no_of_coins'];
            //         $c_balance2=$c_balance2+$ct_result[$l]['no_of_coins'];   
                     
            //     }
            // } 
                // $price=$user_data[$i]['price']*$c_balance;
    
                // $temp['display_name'] = $user_data[$i]['name'];
                $temp['balance_usd'] = $c_balance;
                $temp['user_name'] = $name;
                $temp['price'] = "$".$c_balance ;
                 // $temp['coins_price'] = "$".$user_data[$i]['price'] ;
                $data[] = $temp;   
          
        }

        }

       
        $arr_data = Array();
        $arr_data = $user_data;

   
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $user_sql
        );

        echo json_encode($json_data);
        exit(0);

    }
    
    // Get data for Front trades tabel
    public function get_user_trades_front(){
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
        
        global $wpdb,$wp;
        $data = array();

         $_SESSION["timezone"] = $_POST['timezone'];
         date_default_timezone_set($_SESSION["timezone"]);

        $sql = "SELECT b.*,u.display_name,bc.name,bc.coin_icon FROM client_trades as b LEFT JOIN wp_users as u  ON b.user_id = u.ID JOIN main_cryptocurrency_balances as bc ON b.crypto_traded = bc.id";

        

        if (isset($_POST['name']) && $_POST['name'] != '') {
           
            if($_POST['name']=="w"){
              $sql .= " WHERE date_time >= DATE(NOW()) - INTERVAL 7 DAY  AND date_time < DATE(NOW())  ";
            }
            if($_POST['name']=="cm"){
              $sql .= " WHERE date_time >DATE_SUB(NOW(), INTERVAL 1 MONTH) ";
            }
            if($_POST['name']=="lm"){
              $sql .= " WHERE date_time >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and date_time <= DATE_SUB(NOW(), INTERVAL 1 MONTH)  ";
            }
             if($_POST['name']=="cy"){
              $sql .= " WHERE date_time >=  DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
            }
            if($_POST['name']=="ly"){
              $sql .= " WHERE date_time >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 YEAR)), INTERVAL 1 DAY) and date_time <= DATE_SUB(NOW(), INTERVAL 1 YEAR)   ";
            }
           
        }else{
             $sql .= " WHERE date_time >= DATE(NOW()) - INTERVAL 7 DAY  AND date_time < DATE(NOW()) ";
        }

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql), "OBJECT");
        $result=$service_price_list;
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) {
            $temp['ID'] = $row->trade_id;
            $temp['name'] = $row->display_name;
            $temp['crypto_traded'] = $row->crypto_traded;
            $temp['price'] =  "$".sprintf("%.2f", $row->price);
            $temp['no_of_coins'] = $row->no_of_coins;
            $temp['is_buy_sell'] = $row->is_buy_sell;
            $temp['total'] = "$".sprintf("%.2f", $row->total);
            $temp['date_time'] = date("m/d/Y g:i A",strtotime($row->date_time))." <span class='timezone123'></span>";
            $temp['crypto_name'] = "<img src='".plugin_dir_url( __FILE__ )."images/".$row->coin_icon."'> ".$row->name;     
            $data[] = $temp;
            $id = "";
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        echo json_encode($json_data);
        exit(0);

    }


    // Get data for Front trades tabel
    public function get_user_transaction_front(){
    
        $user = wp_get_current_user();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;
        
        global $wpdb,$wp;
        $data = array();
        $user_id=get_current_user_id();

         $_SESSION["timezone"] = $_POST['timezone'];
         date_default_timezone_set($_SESSION["timezone"]);

        $sql = "SELECT b.*,u.* FROM user_coin_transactions as b LEFT JOIN users_data as u  ON b.user_id = u.user_id   ";

        

        if (isset($_POST['name']) && $_POST['name'] != '') {
           
            if($_POST['name']=="w"){
              $sql .= " WHERE b.gm_created_date_time  >= DATE(NOW()) - INTERVAL 7 DAY AND b.gm_created_date_time < DATE(NOW()) ";
            }
            if($_POST['name']=="cm"){
              $sql .= " WHERE b.gm_created_date_time >DATE_SUB(NOW(), INTERVAL 1 MONTH) ";
            }
            if($_POST['name']=="lm"){
              $sql .= " WHERE b.gm_created_date_time >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and b.gm_created_date_time <= DATE_SUB(NOW(), INTERVAL 1 MONTH)  ";
            }
             if($_POST['name']=="cy"){
              $sql .= " WHERE b.gm_created_date_time >=  DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
            }
            if($_POST['name']=="ly"){
              $sql .= " WHERE b.gm_created_date_time >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 YEAR)), INTERVAL 1 DAY) and b.gm_created_date_time <= DATE_SUB(NOW(), INTERVAL 1 YEAR)   ";
            }
           
        }else{
             $sql .= " WHERE b.gm_created_date_time  >= DATE(NOW()) - INTERVAL 7 DAY AND b.gm_created_date_time < DATE(NOW()) ";
        }

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " AND u.user_id='{$user_id}'  ORDER BY transaction_id DESC  LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql), "OBJECT");
        $result=$service_price_list;
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result > 0)) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) {
          $temp['ID'] = $row->user_id;
            $transaction_type="";

            if($row->transaction_type_id == 1){

                $transaction_type="DEPOSIT";

            }else if ($row->transaction_type_id == 2) {

                $transaction_type="WITHDRAW";

            }else if ($row->transaction_type_id == 3) {

                $transaction_type="PLATFORM UPDATE";

            }       
            $temp['gm_created_date_time'] = date("m/d/Y g:i A",strtotime($row->gm_created_date_time))." <span class='timezone123'></span>";
            $temp['transaction_type'] = $transaction_type;
            $temp['first_name'] = $row->first_name;
            $temp['last_name'] = $row->last_name;
            $temp['amount'] = $row->amount;
            $data[] = $temp;         
     $id = "";
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        echo json_encode($json_data);
        exit(0);

    }  

    // This is calcaulation of user get pertange  
  //   function calculation(){
  //   	global $wpdb,$wp;
  //       $count=0;
  //       $coin_amount=0;
  //       date_default_timezone_set($_SESSION["timezone"]);
            
  //       $user_sql = "SELECT  usd_price FROM  trades_usd_price " ;
  //       $user_usd = $wpdb->get_results($wpdb->prepare($user_sql), "ARRAY_A");
  //       $usd=0;

  //       for ($i=0; $i < count($user_usd); $i++) { 
  //           $usd=$usd+$user_usd[$i]['usd_price'];

  //       }

  //       $user_id_sql = "SELECT  * FROM users_data ORDER BY id DESC LIMIT 1 " ;
  //       $user_id = $wpdb->get_results($wpdb->prepare($user_id_sql), "ARRAY_A");

		// if(count($user_id)>0){
  //       $percentage_sql = "SELECT  mcb.coin_symbol,ud.coin_amount,ud.id,ud.user_id,ud.balance_usd,ct.no_of_coins  FROM users_data as ud LEFT JOIN main_cryptocurrency_balances as mcb  ON ud.crypto_traded = mcb.id JOIN client_trades as ct ON ct.crypto_traded = ud.crypto_traded  where ud.id !=".$user_id[0]['id'] ;

  //       $coin_symbol = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");
  //       for ($i=0; $i < count($coin_symbol); $i++) { 

  //           $link = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=0962e57a7dd5e6daa6c7d217665bc70ac18f823f08958b8f35deee8715a148bf";

  //           $data = json_decode(file_get_contents($link),true);
  //           if($data['Response']=="Error"){
              
  //               $link2 = "https://chasing-coins.com/api/v1/convert/".$coin_symbol[$i]['coin_symbol']."/USD";
  //               $data2 = json_decode(file_get_contents($link2),true);
  //               $count=$count+($data2['result']*$coin_symbol[$i]['no_of_coins']);
  //           }else{
  //               $count=$count+($data[$coin_symbol[$i]['coin_symbol']]['USD']*$coin_symbol[$i]['no_of_coins']);
  //           }
  //           $coin_amount=$coin_amount+$coin_symbol[$i]['coin_amount'];
  //       }
  //       $add_new_price=0;
  //       $count=$count+$usd;
  //       $all_diff=$count-$coin_amount;
  //       for ($i=0; $i < count($coin_symbol); $i++) { 
  //           $add_new_price= (($coin_symbol[$i]['coin_amount']/$coin_amount) *  $all_diff);
  //           $add_new_price=$add_new_price + $coin_symbol[$i]['balance_usd'];
  //           $user_profits_data = array(
  //               'user_id' => esc_sql($coin_symbol[$i]['user_id']),
  //               'amounts' =>  esc_sql($add_new_price),
  //               'gm_created_date_time' => date("Y-m-d H:i:s"));

  //               $user_profits = $wpdb->prepare($wpdb->insert('user_profits', $user_profits_data));

  //               $percentage_sql = "UPDATE  users_data SET coin_amount = '{$add_new_price}',update_date='{date('Y-m-d H:i:s')}' where id = ".$coin_symbol[$i]['id'];
  //               $percentage_result = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");
  //           }
  //       }
  //   }

      // This is calcaulation of user get pertange  
    function calculation_without_api_view(){

    	global $wpdb,$wp;
        $count=0;
        $coin_amount=0;
        date_default_timezone_set($_SESSION["timezone"]);



        $mcb_sql = "SELECT  * FROM  main_cryptocurrency_balances  " ;

        $coin_symbol = $wpdb->get_results($wpdb->prepare($mcb_sql), "ARRAY_A");

        $coins_data=[];

   

        for ($i=0; $i < count($coin_symbol); $i++) {    

           $link = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=0962e57a7dd5e6daa6c7d217665bc70ac18f823f08958b8f35deee8715a148bf";

            $price_data = json_decode(file_get_contents($link),true);

            if($price_data['Response']=="Error"){
    
                $link2 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=c581f9c597253a4a09671e1616d3b9cd3b6c3aebb9e1cac4b2fd61fb394278a5";

                $price_data2 = json_decode(file_get_contents($link2),true);
                if($price_data2['Response']=="Error"){

                    $link3 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=e417a2869aaf5bf8500d95d44f098d8b11af0b82c114ff4344fada6993bb3328";

                    $price_data3 = json_decode(file_get_contents($link3),true);

                    $update_sql = "UPDATE  main_cryptocurrency_balances SET price = ".$price_data3[$coin_symbol[$i]['coin_symbol']]['USD']." where coin_symbol = '".$coin_symbol[$i]['coin_symbol']."'";

            }else{
                $update_sql = "UPDATE  main_cryptocurrency_balances SET price = ".$price_data2[$coin_symbol[$i]['coin_symbol']]['USD']." where coin_symbol = '".$coin_symbol[$i]['coin_symbol']."'";

            }


        }else{
                $update_sql = "UPDATE  main_cryptocurrency_balances SET price = ".$price_data[$coin_symbol[$i]['coin_symbol']]['USD']." where coin_symbol = '".$coin_symbol[$i]['coin_symbol']."'";
        }

            $c_result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");

        }



            
        $user_sql = "SELECT  usd_price FROM  trades_usd_price " ;
        $user_usd = $wpdb->get_results($wpdb->prepare($user_sql), "ARRAY_A");
        $usd=0;

        for ($i=0; $i < count($user_usd); $i++) { 
            $usd=$usd+$user_usd[$i]['usd_price'];

        }

        $user_id_sql = "SELECT  * FROM users_data " ;
        $user_id = $wpdb->get_results($wpdb->prepare($user_id_sql), "ARRAY_A");
        if(count($user_id) > 0){
         
        $percentage_sql = "SELECT  mcb.*,ct.no_of_coins  FROM client_trades  as ct LEFT JOIN main_cryptocurrency_balances as mcb ON  mcb.id =ct.crypto_traded  ";

        $coin_symbol = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");


        $user_sql_data = "SELECT  *  FROM users_data as ud ";

        $user_data_fesult = $wpdb->get_results($wpdb->prepare($user_sql_data), "ARRAY_A");



        for ($i=0; $i < count($coin_symbol); $i++) { 

                $count=$count+($coin_symbol[$i]['price']*$coin_symbol[$i]['balance']);
          
        }
         for ($i=0; $i < count($user_data_fesult); $i++) { 

                $coin_amount=$coin_amount+$user_data_fesult[$i]['coin_amount'];
          
        }

            
        $add_new_price=0;
        $count=$count+$usd;
        $all_diff=$count-$coin_amount;
        for ($i=0; $i < count($user_data_fesult); $i++) { 
            $add_new_price2= (($user_data_fesult[$i]['coin_amount']/$coin_amount) *  $all_diff)/100;
            $add_new_price= $add_new_price2 + $user_data_fesult[$i]['coin_amount'];
         
            $user_profits_data = array(
                'user_id' => esc_sql($user_data_fesult[$i]['user_id']),
                'amounts' =>  esc_sql($add_new_price),
                'gm_created_date_time' => date("Y-m-d H:i:s"));

                $user_profits = $wpdb->prepare($wpdb->insert('user_profits', $user_profits_data));

                $percentage_sql = "UPDATE  users_data SET coin_amount = '{$add_new_price}',last_coin_amount='{$user_data_fesult[$i]['coin_amount']}',pool_add_remove='{$add_new_price2}' ,update_date='".date('Y-m-d H:i:s')."' where id = ".$user_data_fesult[$i]['id'];
                $percentage_result = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");
            }
        }else{
        	echo $user_id;die();
        }
                 $result_array['status'] = 1;
        echo json_encode($result_array);exit;
    }


     // This is calcaulation of user get pertange  
    function calculation_without_api(){
        global $wpdb,$wp;
        $count=0;
        $coin_amount=0;
        date_default_timezone_set($_SESSION["timezone"]);

            
        $user_sql = "SELECT  usd_price FROM  trades_usd_price " ;
        $user_usd = $wpdb->get_results($wpdb->prepare($user_sql), "ARRAY_A");
        $usd=0;

        for ($i=0; $i < count($user_usd); $i++) { 
            $usd=$usd+$user_usd[$i]['usd_price'];

        }

        $user_id_sql = "SELECT  * FROM users_data  " ;
        $user_id = $wpdb->get_results($wpdb->prepare($user_id_sql), "ARRAY_A");
        if(count($user_id) > 0){
         
         $percentage_sql = "SELECT  mcb.*,ct.no_of_coins  FROM client_trades  as ct LEFT JOIN main_cryptocurrency_balances as mcb ON  mcb.id =ct.crypto_traded  ";

        $coin_symbol = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");


        $user_sql_data = "SELECT  *  FROM users_data as ud ";

        $user_data_fesult = $wpdb->get_results($wpdb->prepare($user_sql_data), "ARRAY_A");



        for ($i=0; $i < count($coin_symbol); $i++) { 

                $count=$count+($coin_symbol[$i]['price']*$coin_symbol[$i]['balance']);
          
        }
         for ($i=0; $i < count($user_data_fesult); $i++) { 

                $coin_amount=$coin_amount+$user_data_fesult[$i]['coin_amount'];
          
        }

            
        $add_new_price=0;
        $count=$count+$usd;
        $all_diff=$count-$coin_amount;
        for ($i=0; $i < count($user_data_fesult); $i++) { 
            $add_new_price2= (($user_data_fesult[$i]['coin_amount']/$coin_amount) *  $all_diff)/100;
            $add_new_price= $add_new_price2 + $user_data_fesult[$i]['coin_amount'];
         
            $user_profits_data = array(
                'user_id' => esc_sql($user_data_fesult[$i]['user_id']),
                'amounts' =>  esc_sql($add_new_price),
                'gm_created_date_time' => date("Y-m-d H:i:s"));

                $user_profits = $wpdb->prepare($wpdb->insert('user_profits', $user_profits_data));

                $percentage_sql = "UPDATE  users_data SET coin_amount = '{$add_new_price}',last_coin_amount='{$user_data_fesult[$i]['coin_amount']}',pool_add_remove='{$add_new_price2}' ,update_date='".date('Y-m-d H:i:s')."' where id = ".$user_data_fesult[$i]['id'];
                $percentage_result = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");
            }
        }else{
        	echo $user_id;die();
        }
    }

    // This is calcaulation of update all user coins balances
    function update_all_user_coin_balances($utc_datetime = null){
    	global $wpdb,$wp;
        date_default_timezone_set($_SESSION["timezone"]);
        if($utc_datetime == null){
            $utc_datetime = gmdate("Y-m-d H:i:s");
            $utc_strtotime = strtotime(gmdate("Y-m-d H:i:s"));
        }else{
            $utc_strtotime = strtotime($utc_datetime);
        }


        $count=0;
        $coin_amount=0;
            
        $user_sql = "SELECT  usd_price FROM  trades_usd_price " ;
        $user_usd = $wpdb->get_results($wpdb->prepare($user_sql), "ARRAY_A");
        $usd=0;

        for ($i=0; $i < count($user_usd); $i++) { 
            $usd=$usd+$user_usd[$i]['usd_price'];
        }

        $user_id_sql = "SELECT  * FROM users_data  " ;
        $user_id = $wpdb->get_results($wpdb->prepare($user_id_sql), "ARRAY_A");
         if(count($user_id)>0){
        $percentage_sql = "SELECT  mcb.coin_symbol,ud.coin_amount,ud.id,ud.user_id,ud.balance_usd,ct.no_of_coins  FROM users_data as ud LEFT JOIN main_cryptocurrency_balances as mcb  ON ud.crypto_traded = mcb.id JOIN client_trades as ct ON ct.crypto_traded = ud.crypto_traded  " ;

        $coin_symbol = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");
        for ($i=0; $i < count($coin_symbol); $i++) { 
          

            // if($utc_datetime == null){
            //     $link2 = "https://chasing-coins.com/api/v1/convert/".$coin_symbol[$i]['coin_symbol']."/USD";
            //     $data2 = json_decode(file_get_contents($link2),true);
            //     $count=$count+($data2['result']*$coin_symbol[$i]['no_of_coins']);
            // }else{
            //     $link = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&ts=".$utc_strtotime."&tsyms=USD&api_key=606b1869b83a593b6ad8d84ecbcc81acfbb0e5658d7af37c697db70e932daa32";
            //     $data = json_decode(file_get_contents($link),true);
            //     $count=$count+($data[$coin_symbol[$i]['coin_symbol']]['USD']*$coin_symbol[$i]['no_of_coins']);
            // }



            $link = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=0962e57a7dd5e6daa6c7d217665bc70ac18f823f08958b8f35deee8715a148bf";

            $price_data = json_decode(file_get_contents($link),true);

            if($price_data['Response']=="Error"){
    
                $link2 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=c581f9c597253a4a09671e1616d3b9cd3b6c3aebb9e1cac4b2fd61fb394278a5";

                $price_data2 = json_decode(file_get_contents($link2),true);
                if($price_data2['Response']=="Error"){

                    $link3 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol[$i]['coin_symbol']."&tsyms=USD&api_key=e417a2869aaf5bf8500d95d44f098d8b11af0b82c114ff4344fada6993bb3328";

                    $price_data3 = json_decode(file_get_contents($link3),true);

                  $count=$count+($price_data3[$coin_symbol[$i]['coin_symbol']]['USD']*$coin_symbol[$i]['no_of_coins']);

                }else{
                     $count=$count+($price_data2[$coin_symbol[$i]['coin_symbol']]['USD']*$coin_symbol[$i]['no_of_coins']);

                }


            }else{
                    $count=$count+($price_data[$coin_symbol[$i]['coin_symbol']]['USD']*$coin_symbol[$i]['no_of_coins']);
            }


            $coin_amount=$coin_amount+$coin_symbol[$i]['coin_amount'];
        }
        $add_new_price=0;
        $count=$count+$usd;

        $all_diff=$count-$coin_amount;

        for ($i=0; $i < count($coin_symbol); $i++) { 

            $add_new_price= (($coin_symbol[$i]['coin_amount']/$coin_amount) *  $all_diff);
            $add_new_price=$add_new_price + $coin_symbol[$i]['balance_usd'];

            $sql = "INSERT INTO user_coin_transactions (transaction_type_id, user_id,amount, gm_created_date_time)
            VALUES ('3','".$coin_symbol[$i]['user_id']."', '".$add_new_price."', '".$utc_datetime."')";

             $percentage_result = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");       
        }
    }
    }
 
    public function coins_add(){
        $result_array = array(); 
        date_default_timezone_set('"'.$_SESSION["timezone"].'"');
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            $id = esc_sql($_POST['user_id']);
            $user_coins = esc_sql($_POST['coins']);
            $user_id=get_current_user_id();

          
            print WCP_Trades_Controller::calculation_without_api();
            // print WCP_Trades_Controller::calculation();
            // print WCP_Trades_Controller::update_all_user_coin_balances();




            $sql = "SELECT * FROM users_data where id = {$id}";
            $coins = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
             
            if(count($coins)>0){

                 $usd_sql = "SELECT * FROM trades_usd_price ";
                $usd_result = $wpdb->get_results($wpdb->prepare($usd_sql), "ARRAY_A");
                    $number_of_coin= $user_coins;
                if(count($usd_result)>0){

                    for ($u=0; $u <count($usd_result) ; $u++) { 
                     
                            $usd_amount=$usd_result[$u]['usd_price']+$user_coins;
                     
                    }

                    $update_sql = "UPDATE  trades_usd_price SET usd_price = '{$usd_amount}',coins = '{$number_of_coin}',`date`='".date("Y-m-d H:i:s", time())."'  where id = '1'";
                    $usd_update_result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");
              
                }else{

                    $usd_data= array(                        
                        'usd_price' =>  $user_coins,
                        'coins' => $user_coins,
                        'date'=>date("Y-m-d H:i:s", time())
                    );
                    $usd_update_result = $wpdb->prepare($wpdb->insert('trades_usd_price', $usd_data));
                }

                $add=$coins[0]['coin_amount']+$user_coins;
                $balance_add=$coins[0]['balance_usd']+$user_coins;
                $update_sql = "UPDATE  users_data SET coin_amount = '{$add}',balance_usd = '{$balance_add}'  where id = ".$id;
                $result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");

                $data2 = array(
                    'transaction_type_id' => 1,
                    'user_id' => $coins[0]['user_id'],
                    'amount' => $user_coins,
                    'gm_created_date_time' => date("Y-m-d H:i:s"));
                $result = $wpdb->prepare($wpdb->insert('user_coin_transactions', $data2));
                $response = array(  "is_ok" => 1, "msg" => "Coins Added Sucessfully");
            
            }
        }
        echo json_encode($response);exit;
    }

    public function coins_remove(){
        date_default_timezone_set($_SESSION["timezone"]);
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
           $id = esc_sql($_POST['user_id']);
           $user_coins = esc_sql($_POST['coins']);

            print WCP_Trades_Controller::calculation_without_api();
            // print WCP_Trades_Controller::calculation();
            // print WCP_Trades_Controller::update_all_user_coin_balances();


            $sql = "SELECT coin_amount,user_id FROM users_data where id = {$id}";
            $coins = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
            // print_r($user_coins);die();
            if(count($coins)>0){

                $usd_sql = "SELECT * FROM trades_usd_price ";
                $usd_result = $wpdb->get_results($wpdb->prepare($usd_sql), "ARRAY_A");
                    $number_of_coin= $user_coins;
                if(count($usd_result)>0){

                    for ($u=0; $u <count($usd_result) ; $u++) { 
                     
                            $usd_amount=$usd_result[$u]['usd_price']-$user_coins;
                     
                    }

                    $update_sql = "UPDATE  trades_usd_price SET usd_price = '{$usd_amount}',coins = '{$number_of_coin}',`date`='".date("Y-m-d H:i:s", time())."'  where id = '1'";
                    $usd_update_result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");
              
                }else{

                    $usd_data= array(                        
                        'usd_price' =>  $user_coins,
                        'coins' => $user_coins,
                        'date'=>date("Y-m-d H:i:s", time())
                    );
                    $usd_update_result = $wpdb->prepare($wpdb->insert('trades_usd_price', $usd_data));
                }
                $remove=$coins[0]['coin_amount']-$user_coins;

        
                 $balance_add=$coins[0]['balance_usd']-$user_coins;
                $remove_sql = "UPDATE  users_data SET coin_amount = '{$remove}',balance_usd = '{$balance_add}'  where id = ".$id;

                // $remove_sql = "UPDATE  users_data SET coin_amount = '{$remove}' where id = ".$id;

                $result = $wpdb->get_results($wpdb->prepare($remove_sql), "ARRAY_A");

                 $data2 = array(
                    'transaction_type_id' => 2,
                    'user_id' => $coins[0]['user_id'],
                    'amount' =>  $user_coins,
                    'gm_created_date_time' => date("Y-m-d H:i:s"));

                 $result2 = $wpdb->prepare($wpdb->insert('user_coin_transactions', $data2));

                $response = array(  "is_ok" => 1, "msg" => "Coins remove Sucessfully");
            }
           
        }
        echo json_encode($response);exit;
    }

    public function delete_user_record(){
        date_default_timezone_set($_SESSION["timezone"]);
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            $id = esc_sql($_POST['user_id']);
              $sql = "DELETE FROM client_trades WHERE trade_id=".$id;
              $result=$wpdb->get_results($wpdb->prepare($sql), OBJECT);
           
            $result_array['status'] = 1;
        }
        echo json_encode($result_array);exit;
    }

    public function delete_user(){
        date_default_timezone_set($_SESSION["timezone"]);
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            $id = esc_sql($_POST['user_id']);

           
            $sql = "DELETE FROM user_profits WHERE user_id=".$id;
            $result=$wpdb->get_results($wpdb->prepare($sql), OBJECT);

            $sql = "DELETE FROM users_data WHERE user_id=".$id;
            $result=$wpdb->get_results($wpdb->prepare($sql), OBJECT);

             $sql2 = "DELETE FROM user_profile WHERE user_id=".$id;
            $res=$wpdb->get_results($wpdb->prepare($sql2), OBJECT);


            wp_delete_user( $id );
           
            $result_array['status'] = 1;
        }
        echo json_encode($result_array);exit;
    }

    public function check_username(){
        date_default_timezone_set($_SESSION["timezone"]);
        $result_array['status'] = 0;
        $user_id=get_current_user_id();
        
        if (!empty($user_id)) {
                   
            global $wpdb;
            $sql = "SELECT display_name FROM wp_users where ID = {$user_id}";
            $result['user_details'] = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");



            $sql1 = "SELECT id,name FROM main_cryptocurrency_balances";
            $result['coin_details'] = $wpdb->get_results($wpdb->prepare($sql1), "ARRAY_A");
           
        }
        echo json_encode($result);
        exit;
    }
    
    public function get_select_data(){
        date_default_timezone_set($_SESSION["timezone"]);
        global $wpdb;
        $sql = "SELECT u.display_name,u.ID FROM users_data as b LEFT JOIN wp_users as u  ON b.user_id = u.ID";
        $result['user_details'] = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
        echo json_encode($result);
        exit;
    }

    public function get_user_detail_by_id(){
        date_default_timezone_set($_SESSION["timezone"]);
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = esc_sql($_POST['id']);

            
            $sql = "SELECT b.*,u.display_name FROM client_trades as b LEFT JOIN wp_users as u ON b.user_id = u.ID  where b.trade_id ={$id}";
            $user_details = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
            $buy="";
            $sell="";
            if($user_details[0]["is_buy_sell"]=="buy"){  $buy='selected'; }
            if($user_details[0]["is_buy_sell"]=="sell"){ $sell='selected'; } 
            // print_r($user_details);
          

            $main_cryptocurrency_balances = "SELECT id,name FROM main_cryptocurrency_balances";
            $result['coin_details'] = $wpdb->get_results($wpdb->prepare($main_cryptocurrency_balances), "ARRAY_A");
            $result['status'] = 1;
            $result['user_details'] = $user_details;
            $result['buy'] = $buy;
            $result['sell'] = $sell;
        }
        echo json_encode($result);
        exit;
    }

    public function get_user_coin_data(){
        date_default_timezone_set($_SESSION["timezone"]);
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = esc_sql($_POST['id']);

            
            $sql = "SELECT b.*,u.user_login,up.email,up.phone_no FROM users_data as b LEFT JOIN wp_users as u ON b.user_id = u.ID JOIN user_profile as up ON up.user_id = u.ID  where b.user_id ={$id}";
            $user_details = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
           
            // print_r($user_details);
           
          
            $result['status'] = 1;
            $result['user_details'] = $user_details;
            $result['buy'] = $buy;
            $result['sell'] = $sell;
        }
        echo json_encode($result);
        exit;
    }

    function UpdateUserDetails() {
        date_default_timezone_set($_SESSION["timezone"]);
        $current_user = wp_get_current_user();
        $is_admin = 0;
        if (user_can( $current_user, 'administrator' )) {
            $is_admin = 1;
        }
        if($is_admin == 1){
            $result_array['status'] = 0;
            if (isset($_POST)) {
                global $wpdb;
               $user_id=get_current_user_id();
               print WCP_Trades_Controller::calculation_without_api();
              // print WCP_Trades_Controller::calculation();

              if(esc_sql($_POST['date_time'])==""){
                $date = date() . time();
                 
              }else{
                  $date = strtotime(esc_sql($_POST['date_time']));
              }

                   $utc_date = strtotime(esc_sql($_POST['utc_datetime']));
              // print_r($date);

           
             
                $output = date('Y-m-d H:i:s',$utc_date);
        
                $newformatdate = date('Y-m-d H:i:s',$date);
           
                print WCP_Trades_Controller::update_all_user_coin_balances($output);

                date_default_timezone_set($timezone_name);

                 
                $data = array(  
                    'user_id' => esc_sql($user_id),
                    'date_time' => $newformatdate,
                    'utc_date_time' => $output,
                    'price' => esc_sql($_POST['price']),
                    'no_of_coins' => esc_sql($_POST['no_of_coins']),
                    'total' =>esc_sql($_POST['price'])*esc_sql($_POST['no_of_coins']),
                    'crypto_traded' => esc_sql($_POST['crypto_traded']),
                    'is_buy_sell'=>esc_sql($_POST['is_buy_sell'])
                    
                );
                  $total_price=esc_sql($_POST['price'])*esc_sql($_POST['no_of_coins']);
               
               
                $sql = "SELECT balance as no_of_coins FROM main_cryptocurrency_balances where id = ".esc_sql($_POST['crypto_traded']);
              $cal = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
              $number_of_coin="";
              for ($i=0; $i < count($cal); $i++) { 
                if(esc_sql($_POST['is_buy_sell'])=="buy"){
                  $number_of_coin=$cal[$i]['no_of_coins'] + esc_sql($_POST['no_of_coins']);
                   $trades_usd_price=$cal[$i]['no_of_coins'] - esc_sql($_POST['no_of_coins']);
                }else{
                    $number_of_coin=$cal[$i]['no_of_coins'] - esc_sql($_POST['no_of_coins']);
                    $trades_usd_price=$cal[$i]['no_of_coins'] + esc_sql($_POST['no_of_coins']);
                }
                
              }
                $usd_sql = "SELECT * FROM trades_usd_price ";
                $usd_result = $wpdb->get_results($wpdb->prepare($usd_sql), "ARRAY_A");
                if(count($usd_result)>0){

                    for ($u=0; $u <count($usd_result) ; $u++) { 
                        if(esc_sql($_POST['is_buy_sell'])=="buy"){
                            $usd_amount=$usd_result[$u]['usd_price']-$total_price;
                        }else{
                            $usd_amount=$usd_result[$u]['usd_price']+$total_price;
                        }
                    }
                  

                    $update_sql = "UPDATE  trades_usd_price SET usd_price = '{$usd_amount}',coins = '{$trades_usd_price}',`date`='".date("Y-m-d H:i:s", time())."'  where id = '1'";
                    $usd_update_result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");
              
                }else{

                    $usd_data= array( 
                       
                        'usd_price' =>  $total_price,
                        'coins' => esc_sql($_POST['no_of_coins']),
                        'date'=>date("Y-m-d H:i:s", time())
                    );

                    $usd_update_result = $wpdb->prepare($wpdb->insert('trades_usd_price', $usd_data));
                  
                }
             //  if(esc_sql($_POST['is_buy_sell'])=="buy"){
             //  	$data2 = array(
             //        'transaction_type_id' => 1,
             //        'user_id' => $user_id,
             //        'amount' =>  esc_sql($_POST['no_of_coins']),
             //        'gm_created_date_time' => date("Y-m-d H:i:s"));
             //     $result = $wpdb->prepare($wpdb->insert('user_coin_transactions', $data2));
             //  }else{
             //  $data2 = array(
             //        'transaction_type_id' => 2,
             //        'user_id' => $user_id,
             //        'amount' =>  esc_sql($_POST['no_of_coins']),
             //        'gm_created_date_time' => date("Y-m-d H:i:s"));
             //     $result = $wpdb->prepare($wpdb->insert('user_coin_transactions', $data2));
             // }
             
               
      // $update_sql = "UPDATE  main_cryptocurrency_balances SET balance = '{$number_of_coin}' where id = ".esc_sql($_POST['crypto_traded']);
      $update_sql = "UPDATE  main_cryptocurrency_balances SET balance = '{$number_of_coin}'  where id = ".esc_sql($_POST['crypto_traded']);
              $data_result2 = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");
                
                $data_result = $wpdb->update('client_trades', $data, array('trade_id' => $_POST['user_id']));

                if ($data_result) {
                    $result_array['status'] = 1;
                    $result_array['msg'] = 'Record Update Succefully.';
                } else {
                    $result_array['msg'] = 'Please try again.';
                }
            }
        }else{
                $result_array['status'] = 0;
                $result_array['msg'] = 'Sorry, We are not allow to update';
        }
        echo json_encode($result_array);
        exit;
    }

    function AddUserDetails(){
        date_default_timezone_set($_SESSION["timezone"]);
        $result_array['status'] = 0;
        if (isset($_POST)) {
            global $wpdb;

            $user_id=get_current_user_id();

            print WCP_Trades_Controller::calculation_without_api();
            // print WCP_Trades_Controller::calculation();
            // print WCP_Trades_Controller::update_all_user_coin_balances();
            if(esc_sql($_POST['date_time'])==""){
                $date = date() . time();
            }else{
                $date = strtotime(esc_sql($_POST['date_time']));
            }
                $utc_date = strtotime(esc_sql($_POST['utc_datetime']));
              // print_r($date);
       
            $newformat = date('Y-m-d H:i:s',$date);
            $output = date('Y-m-d H:i:s',$utc_date);
             
            print WCP_Trades_Controller::update_all_user_coin_balances($output);
              
            $data = array(  
                'user_id' => esc_sql($user_id),
                'date_time' => $newformat,
                'utc_date_time' => $output,
                'price' => esc_sql($_POST['price']),
                'no_of_coins' => esc_sql($_POST['no_of_coins']),
                'total' =>esc_sql($_POST['price'])*esc_sql($_POST['no_of_coins']),
                'crypto_traded' => esc_sql($_POST['crypto_traded']),
                'is_buy_sell'=>esc_sql($_POST['is_buy_sell'])
            );
            $total_price=esc_sql($_POST['price'])*esc_sql($_POST['no_of_coins']);
            $timezone_offset_minutes = esc_sql($_POST['timezone_offset_minutes']); 
            $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);

            $sql = "SELECT balance as no_of_coins FROM main_cryptocurrency_balances where id = ".esc_sql($_POST['crypto_traded']);
            $cal = $wpdb->get_results($wpdb->prepare($sql), "ARRAY_A");
            $number_of_coin="";
            for ($i=0; $i <= count($cal); $i++) { 
                if(esc_sql($_POST['is_buy_sell'])=="buy"){
                    $number_of_coin=$cal[$i]['no_of_coins']+esc_sql($_POST['no_of_coins']);
                }else{
                    $number_of_coin=$cal[$i]['no_of_coins']-esc_sql($_POST['no_of_coins']);
                }
            }
        
            $usd_sql = "SELECT * FROM trades_usd_price ";
            $usd_result = $wpdb->get_results($wpdb->prepare($usd_sql), "ARRAY_A");
            if(count($usd_result)>0){
                for ($u=0; $u <count($usd_result) ; $u++) { 
                    if(esc_sql($_POST['is_buy_sell'])=="buy"){
                        $usd_amount=$usd_result[$u]['usd_price']-$total_price;
                    }else{
                        $usd_amount=$usd_result[$u]['usd_price']+$total_price;
                    }
                }
                $update_sql = "UPDATE  trades_usd_price SET usd_price = '{$usd_amount}',coins = '{$number_of_coin}',`date`='".date("Y-m-d H:i:s", time())."'  where id = '1'";
                $usd_update_result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");
              
            }else{
                $usd_data= array( 
                    'usd_price' =>  $total_price,
                    'coins' => esc_sql($_POST['no_of_coins']),
                    'date'=>date("Y-m-d H:i:s", time())
                );
                $usd_update_result = $wpdb->prepare($wpdb->insert('trades_usd_price', $usd_data));
            }


            // if(esc_sql($_POST['is_buy_sell'])=="buy"){
            //   	$data2 = array(
            //         'transaction_type_id' => 1,
            //         'user_id' => $user_id,
            //         'amount' =>  esc_sql($_POST['no_of_coins']),
            //         'gm_created_date_time' => date("Y-m-d H:i:s"));
            //      $result = $wpdb->prepare($wpdb->insert('user_coin_transactions', $data2));
            // }else{
            //     $data2 = array(
            //         'transaction_type_id' => 2,
            //         'user_id' => $user_id,
            //         'amount' =>  esc_sql($_POST['no_of_coins']),
            //         'gm_created_date_time' => date("Y-m-d H:i:s"));
            //     $result = $wpdb->prepare($wpdb->insert('user_coin_transactions', $data2));
            // }
             
            $update_sql = "UPDATE  main_cryptocurrency_balances SET balance = '{$number_of_coin}'  where id = ".esc_sql($_POST['crypto_traded']);
            $data_result2 = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");
             
            $data_result = $wpdb->prepare($wpdb->insert('client_trades', $data));
            $lastid = $wpdb->insert_id;
            if ($lastid) {
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Add Succefully.';
            } else {
                $result_array['msg'] = 'Please try again.';
            }
        }
        
        echo json_encode($result_array);
        exit;
    }

    public function registration_save() {

        date_default_timezone_set($_SESSION["timezone"]);
        $post = $_POST;
        global $wpdb;

        $phone_no = esc_sql($post['phone']);
        // $address = $post['address'];
        $email = esc_sql($post['email']);
        $user_name = isset($post['uname'])? esc_sql($post['uname']) : esc_sql($post['email']);
        $pass = sha1(esc_sql($post['password']));
        

            print WCP_Trades_Controller::calculation_without_api();
          // print WCP_Trades_Controller::calculation(); error



            $userdata = array(
                'user_login'  =>  $user_name,
                'user_pass'   =>  $pass, // When creating a new user, `user_pass` is expected.
                'user_email'   =>  $email  // When creating a new user, `user_pass` is expected.
            );

            $user_id = $wpdb->prepare(wp_insert_user( $userdata ) );
            $user_profile_data['phone_no'] = isset($post['phone_no']) ? esc_sql($post['phone_no']) : '';
            // $data['address'] = isset($post['address']) ? esc_sql($post['address']) : '';
            $user_profile_data['password'] =  sha1(esc_sql($post['password']));
            $user_profile_data['email'] = isset($post['email']) ? esc_sql($post['email']) : '';
            $user_profile_data['name'] = isset($user_name) ? esc_sql($user_name) : '';
            $user_profile_data['user_id'] = esc_sql($user_id);
            $user_profile_data['gm_created'] = gmdate("Y-m-d H:i:s");


            $result = $wpdb->prepare($wpdb->insert("user_profile", $user_profile_data));

            $user_conis_data['user_id'] = esc_sql($user_id);
            $user_conis_data['sign_date_time'] = gmdate("Y-m-d H:i:s");
            // $user_conis_data['crypto_traded'] = esc_sql($post['crypto_traded']);
            $user_conis_data['crypto_traded'] = 0;
            $user_conis_data['coin_amount'] = esc_sql($post['coins']);
            $user_conis_data['first_name'] = esc_sql($post['fname']);
            $user_conis_data['last_name'] = esc_sql($post['lname']);
            $user_conis_data['balance_usd'] = esc_sql($post['coins']);
            $user_conis_data['is_approved'] =  esc_sql($_POST['approved']);
            if($user_conis_data['is_approved'] == "on"){
              $user_conis_data['approved_date'] =date("Y-m-d H:i:s");
            }else{
                if(esc_sql($_POST['approved_date'])==""){
                    $user_conis_data['approved_date'] =date("Y-m-d H:i:s");
                }else{
                    $user_conis_data['approved_date'] =date("Y-m-d H:i:s",strtotime(esc_sql($_POST['approved_date'])));
                }
            }
            // print WCP_Trades_Controller::update_all_user_coin_balances($user_conis_data['approved_date']);

             $data2 = array(
                'transaction_type_id' => 1,
                'user_id' => esc_sql($user_id),
                'amount' =>  esc_sql($post['coins']),
                'gm_created_date_time' => date("Y-m-d H:i:s"));
             $result = $wpdb->prepare($wpdb->insert('user_coin_transactions', $data2));

                $usd_sql = "SELECT * FROM trades_usd_price ";
                $usd_result = $wpdb->get_results($wpdb->prepare($usd_sql), "ARRAY_A");
                    $number_of_coin= esc_sql($post['coins']);
                if(count($usd_result)>0){

                    for ($u=0; $u <count($usd_result) ; $u++) { 
                     
                            $usd_amount=$usd_result[$u]['usd_price']+$post['coins'];
                     
                    }

                    $update_sql = "UPDATE  trades_usd_price SET usd_price = '{$usd_amount}',coins = '{$number_of_coin}',`date`='".date("Y-m-d H:i:s", time())."'  where id = '1'";
                    $usd_update_result = $wpdb->get_results($wpdb->prepare($update_sql), "ARRAY_A");
              
                }else{

                    $usd_data= array( 
                       
                        'usd_price' =>  $number_of_coin,
                        'coins' => $number_of_coin,
                        'date'=>date("Y-m-d H:i:s", time())
                    );

                    $usd_update_result = $wpdb->prepare($wpdb->insert('trades_usd_price', $usd_data));
                  
                }
           
             $result = $wpdb->prepare($wpdb->insert("users_data", $user_conis_data));

             
        if ($user_id) {
            $response = array("insert_id" => $user_id, "is_ok" => $result, "msg" => "User registered Sucessfully");
        }else{
            $response = array('is_ok' => 'no', 'error' => 'Registration problem. Please contact administrator.');
        }

        echo json_encode($response);
        wp_die();
    }

    public function update_user_data() {
        date_default_timezone_set($_SESSION["timezone"]);
        $post = $_POST;
        global $wpdb;
      

        $phone_no = esc_sql($post['phone']);
        // $address = $post['address'];
        $email = esc_sql($post['email']);
        $user_name = isset($post['uname'])? esc_sql($post['uname']) : esc_sql($post['email']);
        
            $user_id=(int) esc_sql($post['user_id']);
            $userdata = array(
                'user_login'  =>  $user_name,
                'display_name'  =>  $user_name, // When creating a new user, `user_pass` is expected.
                'user_email'   =>  $email  // When creating a new user, `user_pass` is expected.
            );

            $user_update_data = $wpdb->prepare($wpdb->update("wp_users",$userdata , array('ID' => esc_sql($user_id))));

            $data['phone_no'] = isset($post['phone_no']) ? esc_sql($post['phone_no']) : '';
            $data['email'] = isset($post['email']) ? esc_sql($post['email']) : '';
            $data['name'] = isset($user_name) ? esc_sql($user_name) : '';
            $data['user_id'] = esc_sql($user_id);

            $data_result = $wpdb->prepare($wpdb->update('user_profile', $data, array('user_id' => esc_sql($user_id))));

             // $user_conis_data['crypto_traded'] = esc_sql($post['crypto_traded']);
            $user_conis_data['crypto_traded'] = 0;
             $user_conis_data['first_name'] = esc_sql($post['fname']);
            $user_conis_data['last_name'] = esc_sql($post['lname']);
              $user_conis_data['is_approved'] =  esc_sql($_POST['approved']);
            if($user_conis_data['is_approved'] == "on"){
              $user_conis_data['approved_date'] =date("Y-m-d H:i:s");
            }else{
                if(esc_sql($_POST['approved_date'])==""){
                    $user_conis_data['approved_date'] =date("Y-m-d H:i:s");
                }else{
                    $user_conis_data['approved_date'] =date("Y-m-d H:i:s",strtotime(esc_sql($_POST['approved_date'])));
                }
                // $user_conis_data['approved_date'] =date("Y-m-d H:i:s",strtotime(esc_sql($_POST['approved_date'])));
            }
            // $user_conis_data['balance_usd'] =  esc_sql($_POST['usd']);



            
             $data_result2 = $wpdb->prepare($wpdb->update('users_data', $user_conis_data, array('user_id' => esc_sql($user_id))));
          
            if ($user_id) {  
                $response = array("insert_id" => $user_id, "is_ok" => $data_result2, "msg" => "User Record Update Sucessfully");
            }else{
                $response = array('is_ok' => 'no', 'error' => 'Update problem. Please try sometime leter.');
            }

        echo json_encode($response);
        wp_die();
    }




}


$wcp_trades_controller = new WCP_Trades_Controller();

/// Shortcodes

add_action('admin_menu', array($wcp_trades_controller, 'wcp_tenant_screen'));
add_shortcode('wcp_trabes', array($wcp_trades_controller, 'render_view_front_trades_screen'));
add_shortcode('wcp_trabes_amount', array($wcp_trades_controller, 'render_view_front_trades_amount_screen'));
add_shortcode('wcp_transaction', array($wcp_trades_controller, 'render_view_front_transaction_screen'));
/// Ajax

add_action( 'wp_ajax_nopriv_WCP_Trades_Controller::registration_save', Array($wcp_trades_controller,'registration_save'));
add_action( 'wp_ajax_WCP_Trades_Controller::registration_save', Array($wcp_trades_controller,'registration_save'));

add_action('wp_ajax_WCP_Trades_Controller::get_user', Array('WCP_Trades_Controller', 'get_user'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user', array('WCP_Trades_Controller', 'get_user'));
// add_action( 'wp_ajax_nopriv_WCP_Signup_Controller::registration_save', Array($wcp_trades_controller,'registration_save'));
// add_action( 'wp_ajax_WCP_Signup_Controller::registration_save', Array($wcp_trades_controller,'registration_save'));

add_action('wp_ajax_WCP_Trades_Controller::delete_user_record', Array('WCP_Trades_Controller', 'delete_user_record'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::delete_user_record', array('WCP_Trades_Controller', 'delete_user_record'));

add_action('wp_ajax_WCP_Trades_Controller::delete_user', Array('WCP_Trades_Controller', 'delete_user'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::delete_user', array('WCP_Trades_Controller', 'delete_user'));

add_action('wp_ajax_WCP_Trades_Controller::AddUserDetails', Array('WCP_Trades_Controller', 'AddUserDetails'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::AddUserDetails', array('WCP_Trades_Controller', 'AddUserDetails'));

add_action('wp_ajax_WCP_Trades_Controller::get_user_coin_data', Array('WCP_Trades_Controller', 'get_user_coin_data'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user_coin_data', array('WCP_Trades_Controller', 'get_user_coin_data'));

add_action('wp_ajax_WCP_Trades_Controller::check_username', Array('WCP_Trades_Controller', 'check_username'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::check_username', array('WCP_Trades_Controller', 'check_username'));

add_action('wp_ajax_WCP_Trades_Controller::get_user_detail_by_id', Array('WCP_Trades_Controller', 'get_user_detail_by_id'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user_detail_by_id', array('WCP_Trades_Controller', 'get_user_detail_by_id'));

add_action('wp_ajax_WCP_Trades_Controller::get_user_detail', Array('WCP_Trades_Controller', 'get_user_detail'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user_detail', array('WCP_Trades_Controller', 'get_user_detail'));

add_action('wp_ajax_WCP_Trades_Controller::UpdateUserDetails', Array('WCP_Trades_Controller', 'UpdateUserDetails'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::UpdateUserDetails', array('WCP_Trades_Controller', 'UpdateUserDetails'));

add_action('wp_ajax_WCP_Trades_Controller::update_user_data', Array('WCP_Trades_Controller', 'update_user_data'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::update_user_data', array('WCP_Trades_Controller', 'update_user_data'));

add_action('wp_ajax_WCP_Trades_Controller::get_transaction_detail', Array('WCP_Trades_Controller', 'get_transaction_detail'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_transaction_detail', array('WCP_Trades_Controller', 'get_transaction_detail'));

add_action('wp_ajax_WCP_Trades_Controller::coins_add', Array('WCP_Trades_Controller', 'coins_add'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::coins_add', array('WCP_Trades_Controller', 'coins_add'));


add_action('wp_ajax_WCP_Trades_Controller::coins_remove', Array('WCP_Trades_Controller', 'coins_remove'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::coins_remove', array('WCP_Trades_Controller', 'coins_remove'));


add_action('wp_ajax_WCP_Trades_Controller::get_select_data', Array('WCP_Trades_Controller', 'get_select_data'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_select_data', array('WCP_Trades_Controller', 'get_select_data'));

 
add_action('wp_ajax_WCP_Trades_Controller::get_user_trades_front', Array('WCP_Trades_Controller', 'get_user_trades_front'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user_trades_front', array('WCP_Trades_Controller', 'get_user_trades_front'));

add_action('wp_ajax_WCP_Trades_Controller::get_user_transaction_front', Array('WCP_Trades_Controller', 'get_user_transaction_front'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user_transaction_front', array('WCP_Trades_Controller', 'get_user_transaction_front'));


add_action('wp_ajax_WCP_Trades_Controller::get_currency_balances_detail', Array('WCP_Trades_Controller', 'get_currency_balances_detail'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_currency_balances_detail', array('WCP_Trades_Controller', 'get_currency_balances_detail'));


add_action('wp_ajax_WCP_Trades_Controller::get_user_balances_detail', Array('WCP_Trades_Controller', 'get_user_balances_detail'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user_balances_detail', array('WCP_Trades_Controller', 'get_user_balances_detail'));

add_action('wp_ajax_WCP_Trades_Controller::view_platform_update', Array('WCP_Trades_Controller', 'view_platform_update'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::view_platform_update', array('WCP_Trades_Controller', 'view_platform_update'));


add_action('wp_ajax_WCP_Trades_Controller::calculation_without_api_view', Array('WCP_Trades_Controller', 'calculation_without_api_view'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::calculation_without_api_view', array('WCP_Trades_Controller', 'calculation_without_api_view'));


add_action('wp_ajax_WCP_Trades_Controller::get_user_platform', Array('WCP_Trades_Controller', 'get_user_platform'));
add_action('wp_ajax_nopriv_WCP_Trades_Controller::get_user_platform', array('WCP_Trades_Controller', 'get_user_platform'));
