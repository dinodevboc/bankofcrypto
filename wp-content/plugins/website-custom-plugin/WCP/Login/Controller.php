<?php

// Make sure we don't expose any info if called directly
if ( ! defined( 'WPINC' ) ) {
    die;
}
// include_once(dirname(__FILE__)."/../API/Helper.php");
	
class WCP_Login_Controller {


	public function custom_login(){
		global $wpdb;
		if($this->is_valid_post_request()){
//			$user_login = $_POST['input_email'];
			 
			$user_password = sha1(esc_sql($_POST['input_pass']));
		 
//			$userdata = array(
//				'user_login'  =>  $_POST['input_email'],
//				'user_pass'   =>  $user_password  // When creating a new user, `user_pass` is expected.
//			);
//
//			$user_id = wp_insert_user( $userdata ) ;
			$creds = array(
				'user_login'	=> $_POST['input_email'],
				'user_password'	=> $user_password,
				'remember'		=> true
			);

			$user = wp_signon( $creds, false );
			
			if ( is_wp_error( $user ) ) {

				$response = array('status' => 'no', 'error' => $user->get_error_message());

			} else {

				$response = array('status' => 'ok', 'msg' => 'Logged in, Please wait...');
			}

		} else {

			$response = array('status' => 'no', 'error' => 'Invalid request.');
		}
			
		echo json_encode($response);
		wp_die();
		//alert($response);
		//wp_die();


	}

	/// Shortcode for displaying Login form on the frontend page
	public function shortcode_login_form() {

		ob_start();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'Login/view/login_form.php';
		return ob_get_clean();
	}


	private function is_valid_post_request(){
		
		$valid_post_fields = array('action', 'input_email', 'input_pass');

		foreach ($valid_post_fields as $key) {
			if(!array_key_exists($key, $_POST) || $_POST[$key] == ''){
				return false;
				exit();
			}
		}

		return true;
	}

	public function add_login_script() {

		wp_enqueue_script(
			'wcp_custom_login_script', // name your script so that you can attach other scripts and de-register, etc.
			plugin_dir_url( __FILE__ ). 'js/wcp_login_script.js',
			//array('jquery-ui-core', 'jquery-ui-datepicker', 'jquery-effects-core'), // this array lists the scripts upon which your script depends
			array(),
			'1.0',
			true
		);

		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		//wp_localize_script( 'wcp_custom_login_script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
	}


} /// End Class

$wcp_login_controller = new WCP_Login_Controller();

/// Shortcodes
// old Shortcodes [wppb-login redirect_url="/home/"]
add_shortcode('wcp_login_form', array($wcp_login_controller, 'shortcode_login_form'));

/// Ajax
add_action( 'wp_ajax_nopriv_WCP_Login_Controller::custom_login', Array($wcp_login_controller,'custom_login'));

add_action( 'wp_ajax_WCP_Login_Controller::custom_login', Array($wcp_login_controller,'custom_login'));

/// Add scripts
add_action( 'wp_enqueue_scripts', array($wcp_login_controller, 'add_login_script'));