<?php 

ini_set('display_errors',1);
error_reporting(E_ALL);

$servername = "bankofcrypto.com-virtual.db2.serverdatahost.com";
$username = "bankofcrypto_c68";
$password = "smcoazwpi";
$dbname = "bankofcrypto_com_virtual";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
 
     
     


         	$count=0;
            $coin_amount=0;
            
            $user_sql = "SELECT  usd_price FROM  trades_usd_price " ;
            $user_usd = $conn->query($user_sql);
            $usd=0;

            while($user_ud = $user_usd->fetch_assoc()) {
				$usd=$usd+$user_ud['usd_price'];
             }

            $user_id_sql = "SELECT  * FROM users_data  " ;
            $user_id = $conn->query($user_id_sql);
            while($user_sid = $user_id->fetch_assoc()) {
				$data_user=$user_sid['id'];
			}
            
            $percentage_sql = "SELECT  mcb.*,ct.no_of_coins  FROM  main_cryptocurrency_balances as mcb LEFT JOIN client_trades as ct ON ct.crypto_traded = mcb.id ";

          

           
            $coin_sb= $conn->query($percentage_sql);
            while($coin_symbol = $coin_sb->fetch_assoc()) {
   

                $link = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol['coin_symbol']."&tsyms=USD&api_key=0962e57a7dd5e6daa6c7d217665bc70ac18f823f08958b8f35deee8715a148bf";

                $data = json_decode(file_get_contents($link),true);
                if($data['Response']=="Error"){
                    
                //     $link2 = "https://chasing-coins.com/api/v1/convert/".$coin_symbol['coin_symbol']."/USD";
                //     $data2 = json_decode(file_get_contents($link2),true);
                // $count=$count+($data2['result']*$coin_symbol['no_of_coins']);


                 $link2 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol['coin_symbol']."&tsyms=USD&api_key=c581f9c597253a4a09671e1616d3b9cd3b6c3aebb9e1cac4b2fd61fb394278a5";

                $price_data2 = json_decode(file_get_contents($link2),true);
                if($price_data2['Response']=="Error"){

                    $link3 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coin_symbol['coin_symbol']."&tsyms=USD&api_key=e417a2869aaf5bf8500d95d44f098d8b11af0b82c114ff4344fada6993bb3328";

                    $price_data3 = json_decode(file_get_contents($link3),true);

                  $count=$count+($price_data3[$coin_symbol['coin_symbol']]['USD']*$coin_symbol['no_of_coins']);

                }else{
                     $count=$count+($price_data2[$coin_symbol['coin_symbol']]['USD']*$coin_symbol['no_of_coins']);
                    

                }






                
                }else{
                $count=$count+($data[$coins['coin_symbol']]['USD']*$coin_symbol['no_of_coins']);
                    
                }





                // print_r($data);


                
            }


              $user_sql_data = "SELECT  *  FROM users_data as ud  "  ;  
          $user_coins_data= $conn->query($user_sql_data);
          while($user_coins = $user_coins_data->fetch_assoc()) {
            $coin_amount=$coin_amount+$user_coins['coin_amount'];
          }
            $add_new_price=0;
            $count=$count+$usd;

            $all_diff=$count-$coin_amount;

            //   $percentage_sql = "SELECT  mcb.coin_symbol,ud.coin_amount,ud.user_id,ud.id,ud.balance_usd,ct.no_of_coins  FROM users_data as ud LEFT JOIN main_cryptocurrency_balances as mcb  ON ud.crypto_traded = mcb.id JOIN client_trades as ct ON ct.crypto_traded = ud.crypto_traded  where ud.id !=".$data_user ;

           
            // $coin_sb= $conn->query($percentage_sql);

              $user_sql_data = "SELECT  *  FROM users_data as ud  "  ;  
          $user_coins_data= $conn->query($user_sql_data);
             while($user_coins = $user_coins_data->fetch_assoc()) {

                    $add_new_price= (($user_coins['coin_amount']/$coin_amount) *  $all_diff);
                    $add_new_price=$add_new_price + $user_coins['coin_amount'];

                    $sql = "INSERT INTO user_coin_transactions (transaction_type_id, user_id,amount, gm_created_date_time)
                    VALUES ('3','".$user_coins['user_id']."', '".$add_new_price."', '".date("Y-m-d H:i:s")."')";
                    if ($conn->query($sql) === TRUE) {
                        echo "New record created successfully <br>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                    

                   $user_profits_sql = "INSERT  user_profits SET user_id = '{$user_coins['user_id']}', amounts = '{$add_new_price}', gm_created_date_time = '{ date('Y-m-d H:i:s')}' ";
                    // $percentage_result = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");

                    $conn->query($user_profits_sql);


                    $percentages_sql = "UPDATE  users_data SET coin_amount = '{$add_new_price}' where id = ".$user_coins['id'];
                    // $percentage_result = $wpdb->get_results($wpdb->prepare($percentage_sql), "ARRAY_A");

                    if ($conn->query($percentages_sql) === TRUE) {
                        echo "New record created successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
            }
         


?>