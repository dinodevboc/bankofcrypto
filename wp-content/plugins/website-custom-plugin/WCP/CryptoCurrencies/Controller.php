<?php
include_once(dirname(__FILE__)."/Model.php");
include(ABSPATH . "wp-includes/pluggable.php");
class WCP_CryptoCurrencies_Controller {
    public function __construct() {

        add_action('admin_menu', array($this, 'wcp_cryptocurrencies_page'));

        add_shortcode( 'wcp_cryptocurrencies_options', array($this, 'render_wcp_cryptocurrencies_options') );
        add_shortcode( 'wcp_cryptocurrencies_table', array($this, 'render_wcp_cryptocurrencies_table') );
    }

    public function wcp_cryptocurrencies_page() {
        add_menu_page('Crypto Currencies','Crypto Currencies', 'manage_options', 'wcp-cryptocurrencies', array($this, 'wcp_cryptocurrencies_admin_html') );
    }

    public function wcp_cryptocurrencies_admin_html() {
        echo do_shortcode("[wcp_cryptocurrencies_table]");
    }

    public function add_admin_css_and_js() {

        if(isset($_GET["page"]) && $_GET["page"] == "wcp-cryptocurrencies"){
      wp_enqueue_script('media-upload');
      wp_enqueue_script('thickbox');
      wp_enqueue_style('thickbox');
      wp_enqueue_style('media-upload');
      wp_enqueue_media();

            wp_enqueue_script( 'jquery-ui' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            //and later
            wp_enqueue_script( 'jquery-ui-datepicker' );
            
            // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
            wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
            wp_enqueue_style( 'jquery-ui' );
            wp_register_style( 'wcp-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
            wp_enqueue_style( 'wcp-bootstrap' ); 
            wp_register_style( 'wcp-timepicker', WCP_PLUGIN_URL.'/css/jquery-ui-timepicker-addon.css' );
            wp_enqueue_style( 'wcp-timepicker' );

            wp_enqueue_script( 'wcp-bootstrapcdn-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
            wp_enqueue_script( 'wcp-timepicker-js', WCP_PLUGIN_URL.'/js/jquery-ui-timepicker-addon.js');
            wp_enqueue_script( 'wcp-jquery-validate-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js');

            //wp_enqueue_style( 'wcp-admin-custom-css', WCP_PLUGIN_URL.'assets/admin/css/wcp-custom.css');
            //wp_enqueue_script( 'wcp-admin-custom-js', WCP_PLUGIN_URL.'assets/admin/js/wcp-custom.js');

            /*wp_localize_script( 'wcp-admin-custom-js', 'wcp_obj', array(
                "SITE_URL"             => site_url("/"),
                "SITE_AJAX_URL"        => admin_url("admin-ajax.php")
            ));*/
        }

        
    }

    public function render_wcp_cryptocurrencies_options($atts) {
        $this->add_admin_css_and_js();
        ob_start();
        include_once dirname(__FILE__)."/View/option-list.php";
        $content = ob_get_clean();
        return $content;
    }
    public function render_wcp_cryptocurrencies_table($atts) {
        $this->add_admin_css_and_js();
        ob_start();
        include_once dirname(__FILE__)."/View/table-list.php";
        $content = ob_get_clean();
        return $content;
    }
}

if(class_exists("WCP_CryptoCurrencies_Controller")) {
    $WCP_CryptoCurrencies_Controller = new WCP_CryptoCurrencies_Controller();
}