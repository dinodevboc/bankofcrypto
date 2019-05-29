<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'Register/Model.php';

class WCP_Register_View {

	function build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/register.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	
}