<?php

// Make sure we don't expose any info if called directly
if (!defined('WPINC')) {
	die;
}


include_once(dirname(__FILE__) . "/View.php");
include_once(dirname(__FILE__) . "/Model.php");

//add_action( 'admin_init', 'redirect_non_logged_users_to_specific_page' );
class WCP_Register_Controller {

	public function render_view_front_screen() {
			print WCP_Register_View::build_html();
	}
	public function is_valid_post_request() {

		$valid_post_fields = array('action', 'uname', 'email', 'password');

		foreach ($valid_post_fields as $key) {
			if (!array_key_exists($key, $_POST) || $_POST[$key] == '') {
				return false;
				exit();
			}
		}

		return true;
	}

	public function registration_save() {
		$post = $_POST;
		global $wpdb;

		$phone_no = esc_sql($post['phone']);
		// $address = $post['address'];
		$email = esc_sql($post['email']);
		$user_name = isset($post['uname'])? esc_sql($post['uname']) : esc_sql($post['email']);
		$pass = sha1(esc_sql($post['password']));
		
			$userdata = array(
				'user_login'  =>  $user_name,
				'user_pass'   =>  $pass, // When creating a new user, `user_pass` is expected.
				'user_email'   =>  $email  // When creating a new user, `user_pass` is expected.
			);

			$user_id = $wpdb->prepare(wp_insert_user( $userdata ) );
			$data['phone_no'] = isset($post['phone_no']) ? esc_sql($post['phone_no']) : '';
			// $data['address'] = isset($post['address']) ? esc_sql($post['address']) : '';
			$data['password'] =  sha1(esc_sql($post['password']));
			$data['email'] = isset($post['email']) ? esc_sql($post['email']) : '';
			$data['name'] = isset($user_name) ? esc_sql($user_name) : '';
			$data['user_id'] = esc_sql($user_id);
			$data['gm_created'] = gmdate("Y-m-d H:i:s", strtotime('today'));


			$result = $wpdb->prepare($wpdb->insert("user_profile", $data));
			if ($user_id) {

				// auto login 

				$credentials = array('user_login' => $user_name, 'user_password' => $pass, 'remember' => true);

				$secure_cookie = is_ssl();

				$secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, $credentials);
				add_filter('authenticate', 'wp_authenticate_cookie', 30, 3);

				$user = wp_authenticate($credentials['user_login'], $credentials['user_password']);
				wp_set_auth_cookie($user->ID, $credentials["remember"], $secure_cookie);
				do_action('wp_login', $user->user_login, $user);
				$response = array("insert_id" => $user_id, "is_ok" => $result, "msg" => "User registered Sucessfully");
			}else{
				$response = array('is_ok' => 'no', 'error' => 'Registration problem. Please contact administrator.');
			}

		echo json_encode($response);
		wp_die();
	}
	
}
$wcp_register_controller = new WCP_Register_Controller();

/// Shortcodes
//old Shortcodes[wppb-register]
add_shortcode('wcp_register', array($wcp_register_controller, 'render_view_front_screen'));
/// Ajax

add_action( 'wp_ajax_nopriv_WCP_Register_Controller::registration_save', Array($wcp_register_controller,'registration_save'));
add_action( 'wp_ajax_WCP_Register_Controller::registration_save', Array($wcp_register_controller,'registration_save'));