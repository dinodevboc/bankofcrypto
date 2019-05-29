<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'Trades/Model.php';

class WCP_Trades_View {

	function build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/trades.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	function trades_build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/front_trades.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	function user_build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/user.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	function user_transaction_build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/transaction.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}

	function user_balances_build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/user_balances.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	function currency_balances_build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/balances.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	function render_view_front_trades_amount_html($result,$user_profits_result) {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/front_amount_graph.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	function render_view_front_transaction_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/front_transaction.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	
	function user_platform_update_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/platform_update.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
}