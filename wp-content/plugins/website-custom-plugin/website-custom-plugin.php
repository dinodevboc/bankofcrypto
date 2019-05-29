<?php
/**
* Plugin Name: Website Custom Plugin
* Plugin URI: 
* Description: 
* Version: 1.0.0
* Author: Dino Bartolome
* License: GPL2
*/
define( 'WCP_PLUGIN_VERSION', '1.0.0' );
define( 'WCP_PLUGIN_DOMAIN', 'website-custom-plugin' );
define( 'WCP_PLUGIN_URL', WP_PLUGIN_URL . '/website-custom-plugin' ); 

include_once(dirname(__FILE__)."/WCP/Login/Controller.php");
include_once(dirname(__FILE__)."/WCP/Register/Controller.php");
include_once(dirname(__FILE__)."/WCP/Trades/Controller.php");
include_once(dirname(__FILE__)."/WCP/CryptoCurrencies/Controller.php");
function myplugin_ajaxurl(){

	echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
add_action('wp_head', 'myplugin_ajaxurl');


global $wcp_db_version;
$wcp_db_version = '1.0';


function wcp_install() {
    global $wpdb;
    global $wcp_db_version;
    $table_cryptocurrencies='wcp_cryptocurrencies';

    $table_users_data = 'wcp_users_data';
    $table_trades='wcp_trades';
    $table_users_trasactions='wcp_users_trasactions';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS `$table_cryptocurrencies` (
        ID bigint(20) NOT NULL AUTO_INCREMENT,
        name text NOT NULL,
        symbol text NOT NULL,
        price text NOT NULL,
        price_last_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (ID)
    ) $charset_collate;";

    $sqlUserData = "CREATE TABLE IF NOT EXISTS `$table_users_data` (
        ID bigint(20) NOT NULL AUTO_INCREMENT,
        invite_name text NOT NULL,
        invite_type text NOT NULL,
        invite_email text NOT NULL,
        invite_status INT(1) DEFAULT '0' NOT NULL,
        created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        school_id bigint( 20 ),
        invite_code text NOT NULL, 
        PRIMARY KEY  (ID)
    ) $charset_collate;"; 


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    //dbDelta( $sqlInvite );
   // dbDelta( $sqlSchool );
	

    add_option( 'wcp_db_version', $wcp_db_version );
}
register_activation_hook( __FILE__, 'wcp_install' );