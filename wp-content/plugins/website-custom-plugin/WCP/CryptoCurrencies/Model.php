<?php

class WCP_CryptoCurrencies_Model {

    public function __construct() {
        $table_name = 'wcp_cryptocurrencies';
        $this->table_name =  $table_name;
        add_action('wp_ajax_nopriv_wcp_add_crypCurr', array($this, 'wcp_add_crypCurr'));
        add_action('wp_ajax_wcp_add_crypCurr', array($this, 'wcp_add_crypCurr'));
        
        add_action('wp_ajax_nopriv_wcp_edit_crypCurr', array($this, 'wcp_edit_crypCurr'));
        add_action('wp_ajax_wcp_edit_crypCurr', array($this, 'wcp_edit_crypCurr'));
        
        add_action('wp_ajax_nopriv_wcp_delete_crypCurr', array($this, 'wcp_delete_crypCurr'));
        add_action('wp_ajax_wcp_delete_crypCurr', array($this, 'wcp_delete_crypCurr'));

        add_action('wp_ajax_wcp_get_crypCurrs', Array($this, 'get_crypCurrs'));
        add_action('wp_ajax_nopriv_wcp_get_crypCurrs', array($this, 'get_crypCurrs'));

        add_action('wp_ajax_wcp_get_crypCurr_by_id', Array($this, 'get_crypCurr_by_id'));
        add_action('wp_ajax_nopriv_wcp_get_crypCurr_by_id', array($this, 'get_crypCurr_by_id'));
       
    }
    public function wcp_add_crypCurr() {
        global $wpdb;
        $table_name = $this->table_name;
        $postData = $_POST;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($postData['name']) || trim($postData['name']) == "") {
            $error = array(
                "key" => "name",
                "message" => sprintf($errorMessage, __("Name", "wcp"))
            );
            $errorArray[] = $error;
        }

        if (!isset($postData['price_last_updated']) || trim($postData['price_last_updated']) == "") {
            $error = array(
                "key" => "price_last_updated",
                "message" => sprintf($errorMessage, __("Price Last Updated", "wcp"))
            );
            $errorArray[] = $error;
        }
        
        if (!isset($postData['price']) || trim($postData['price']) == "") {
            $error = array(
                "key" => "price",
                "message" => sprintf($errorMessage, __("Price", "wcp"))
            );
            $errorArray[] = $error;
        }
        
        if (!isset($postData['symbol']) || trim($postData['symbol']) == "") {
            $error = array(
                "key" => "symbol",
                "message" => sprintf($errorMessage, __("Symbol", "wcp"))
            );
            $errorArray[] = $error;
        }

        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {
           
            $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] :"";
            $price = isset($_REQUEST["price"]) ? $_REQUEST["price"] :0;
            $symbol = isset($_REQUEST["symbol"]) ? $_REQUEST["symbol"] :0;
            $price_last_updated = isset($_REQUEST["price_last_updated"]) ? $_REQUEST["price_last_updated"] :"0000-00-00 00:00:00";
            $price_last_updated = date( "Y-m-d H:i:s", strtotime( $price_last_updated ));
            
            $createddate = current_time("Y-m-d H:i:s");
            $updateddate = current_time("Y-m-d H:i:s");

            $wpdb->insert( 
                $table_name, 
                array( 
                    'name' => $name, 
                    'price' => $price,
                    'price_last_updated' => $price_last_updated, 
                    'symbol' => $symbol, 
                    'created_date' => $createddate, 
                    'updated_date' => $updateddate
                )
            );
            
            $id = $wpdb->insert_id;
            if($wpdb->last_error !== '') :

                $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
                $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

                print "<div id='error'>
                <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
                <code>$query</code></p>
                </div>";

            endif;
        
            if ($id) {
                    $response['success'] = 1;
                    $response['status'] = 1;
                    $response['urlredirect'] = '';
            } else {
                $response['error'] = 1;
                $response['status'] = 0;
            }
        }
        echo json_encode($response);
        exit();
    }
    
    public function get_crypCurrs() {
        global $wpdb,$wp;
        $data = array();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;

        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name";
        $result = $wpdb->get_results($sql);
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
        $arr_data = array();
        $arr_data = $result;

        foreach ($service_price_list as $row) {
            $temp['ID'] = $row->ID;
            $temp['name'] = $row->name;
            $temp['price'] = $row->price;
            $price_last_updated = date( "m/d/Y h:i a", strtotime( $row->price_last_updated ));
            $temp['price_last_updated'] = $price_last_updated;
            
            $temp['symbol'] = $row->symbol;
            /*$temp['symbol_url'] = wp_get_attachment_url($row->symbol);
            $temp['symbol_img'] = "<img src='".wp_get_attachment_url($row->symbol)."' style='height:50px;width:50px' />";*/
            
            $id = $row->ID;
            $action = '<div style="display: flex;">';
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="wcp_edit_row(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='wcp_delete_row(" . $id . ")'>&nbsp;";
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

        return $rows;
    }

    public function get_crypCurrs_array() {
        global $wpdb;
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    
    public function wcp_edit_crypCurr() {
        global $wpdb;
        $table_name = $this->table_name;
        $postData = $_POST;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($postData['name']) || trim($postData['name']) == "") {
            $error = array(
                "key" => "name",
                "message" => sprintf($errorMessage, __("Name", "wcp"))
            );
            $errorArray[] = $error;
        }

        if (!isset($postData['price_last_updated']) || trim($postData['price_last_updated']) == "") {
            $error = array(
                "key" => "price_last_updated",
                "message" => sprintf($errorMessage, __("Price Last Updated", "wcp"))
            );
            $errorArray[] = $error;
        }
        
        if (!isset($postData['price']) || trim($postData['price']) == "") {
            $error = array(
                "key" => "price",
                "message" => sprintf($errorMessage, __("Price", "wcp"))
            );
            $errorArray[] = $error;
        }
        
        if (!isset($postData['symbol']) || trim($postData['symbol']) == "") {
            $error = array(
                "key" => "symbol",
                "message" => sprintf($errorMessage, __("Symbol", "wcp"))
            );
            $errorArray[] = $error;
        }

        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {
            
            $edit_ID = isset($_REQUEST["edit_ID"]) ? $_REQUEST["edit_ID"] :"";

            $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] :"";
            $price = isset($_REQUEST["price"]) ? $_REQUEST["price"] :0;
            $symbol = isset($_REQUEST["symbol"]) ? $_REQUEST["symbol"] :0;
            $price_last_updated = isset($_REQUEST["price_last_updated"]) ? $_REQUEST["price_last_updated"] :"0000-00-00 00:00:00";
            $price_last_updated = date( "Y-m-d H:i:s", strtotime( $price_last_updated ));
            
            $updateddate = current_time("Y-m-d H:i:s");
        
            $wpdb->update(
                $table_name, 
                array( 
                    'name' => $name, 
                    'price' => $price,
                    'price_last_updated' => $price_last_updated, 
                    'symbol' => $symbol, 
                    'updated_date' => $updateddate
                ),
                array( 'ID' => $edit_ID )
            );
            
            $id = $edit_ID;
            if($wpdb->last_error !== '') :

                $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
                $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

                print "<div id='error'>
                <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
                <code>$query</code></p>
                </div>";

            endif;
        
            if ($id) {
                    $response['success'] = 1;
                    $response['status'] = 1;
                    $response['urlredirect'] = '';
            } else {
                $response['error'] = 1;
                $response['status'] = 0;
            }
        }
        echo json_encode($response);
        exit();
    }
    
    
    public function wcp_delete_crypCurr() {
        global $wpdb;
        $table_name = $this->table_name;
        if(isset($_REQUEST["id"])){
            $wpdb->delete($table_name,
                 [ 'ID' => $_REQUEST['id'] ],
                 [ '%d' ] );
        }
        echo "success";
        exit();
    }

    public function get_crypCurr_by_id() {
        global $wpdb;
        $table_name = $this->table_name;
        $response = array();
        $response['status'] = 0;
        $response['row'] = array();
        if(isset($_REQUEST["id"])){
            $response['status'] = 1;
            $response['row'] = $this->get_crypCurr($_REQUEST['id']);
        }
        echo json_encode($response);
        exit();
    }

    public function get_crypCurr($id) {
        global $wpdb;
        $table_name = $this->table_name;
        $query = "SELECT * FROM {$table_name} WHERE ID = " . esc_sql(trim($id));
        $result = $wpdb->get_row($query);
        return $result;
    }

}

if (class_exists("WCP_CryptoCurrencies_Model")) {
    $WCP_CryptoCurrencies_Model = new WCP_CryptoCurrencies_Model();
}