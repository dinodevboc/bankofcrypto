<?php
// Make sure we don't expose any info if called directly
if ( ! defined( 'WPINC' ) ) {
    die;
}

class WCP_Trades_Model {

	public static $ACTIVE_STATUS_ID = 1;
	public static $DELETED_STATUS_ID = 2;
}