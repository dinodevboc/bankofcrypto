<?php 
session_start();
// print_r($_SESSION['timezone']);
date_default_timezone_set($_SESSION["timezone"]);
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
 
     
        $sql = "SELECT coin_symbol,id FROM main_cryptocurrency_balances";
        $result = $conn->query($sql);

              while($coins = $result->fetch_assoc()) {
              	   $link = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coins['coin_symbol']."&tsyms=USD&api_key=0962e57a7dd5e6daa6c7d217665bc70ac18f823f08958b8f35deee8715a148bf";

				$data = json_decode(file_get_contents($link),true);
 				if($data['Response']=="Error"){
 					
 					
                 $link2 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coins['coin_symbol']."&tsyms=USD&api_key=c581f9c597253a4a09671e1616d3b9cd3b6c3aebb9e1cac4b2fd61fb394278a5";

                $price_data2 = json_decode(file_get_contents($link2),true);
                if($price_data2['Response']=="Error"){

                    $link3 = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=".$coins['coin_symbol']."&tsyms=USD&api_key=e417a2869aaf5bf8500d95d44f098d8b11af0b82c114ff4344fada6993bb3328";

                    $price_data3 = json_decode(file_get_contents($link3),true);

                  $sql = "INSERT INTO cryptocurrency_prices_history (cryptocurrency_id, price, gm_created_date_time)
					VALUES ('".$coins['id']."', '".$price_data3[$coins['coin_symbol']]['USD']."', '".gmdate("Y-m-d H:i:s", time())."')";

				  $update_sql = "UPDATE  main_cryptocurrency_balances SET price = '".$price_data3[$coins['coin_symbol']]['USD']."' date='".date("Y-m-d H:i:s", time())."' where coin_symbol = '".$coins['coin_symbol']."' ";

                }else{
                    $sql = "INSERT INTO cryptocurrency_prices_history (cryptocurrency_id, price, gm_created_date_time)
					VALUES ('".$coins['id']."', '".$price_data2[$coins['coin_symbol']]['USD']."', '".gmdate("Y-m-d H:i:s", time())."')";

				  $update_sql = "UPDATE  main_cryptocurrency_balances SET price = '".$price_data2[$coins['coin_symbol']]['USD']."' date='".date("Y-m-d H:i:s", time())."' where coin_symbol = '".$coins['coin_symbol']."' ";
                    

                }
 				
 				}else{
				 $sql = "INSERT INTO cryptocurrency_prices_history (cryptocurrency_id, price, gm_created_date_time)
					VALUES ('".$coins['id']."', '".$data[$coins['coin_symbol']]['USD']."', '".gmdate("Y-m-d H:i:s", time())."')";

				  $update_sql = "UPDATE  main_cryptocurrency_balances SET price = '".$data[$coins['coin_symbol']]['USD']."' date='".date("Y-m-d H:i:s", time())."' where coin_symbol = '".$coins['coin_symbol']."' ";
 					
 				}
 				 
 				

					if ($conn->query($sql) === TRUE ){
					    echo "New record created successfully <br>";
					} else {
					    echo "Error: " . $sql . "<br>" . $conn->error;
					}

					if($conn->query($update_sql) === TRUE) {
 						echo "New record created successfully <br>";
					} else {
					    echo "Error: " . $sql . "<br>" . $conn->error;
					}
                
            
        }



         