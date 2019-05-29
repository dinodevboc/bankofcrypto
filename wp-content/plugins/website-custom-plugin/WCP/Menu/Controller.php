<?php

function add_loginout_link($items, $args) {

	if (is_user_logged_in() && $args->theme_location == 'Main Menu | depth 5 (Overlay | depth 1)') {	
	
		
		$items .= '<li><a href="' . wp_logout_url(home_url()) . '">LOG OUT</a></li>';
//		$items .= '<li><a class="get_info_exchange" id="get_info_exchange" data-toggle="modal"   title = "Click Here to tell us more about your exchange requirements" style="color:#fff; border:1px solid;border-radius:5px;cursor:pointer;padding: 5px 5px; background-color:#ff6e05;" data-target="#details_exchange">1031 Exchange/Immediate Need</a></li>';
		
		
	} elseif (!is_user_logged_in() && $args->theme_location == 'Main Menu | depth 5 (Overlay | depth 1)') {
		global $post;
		$post_slug = $post->post_name;		
		$items .= '<li><a href="' . site_url() . '/login">LOGIN</a></li>';
	
//		$items .= '<li></li>';				
		
	
	}
	
	//$items .= '<li>'.the_custom_logo(). '</li>';
	return $items;
}

add_filter('wp_nav_menu_items', 'add_loginout_link', 10, 2);

//add_filter( 'wp_nav_menu_items', 'wpsites_add_logo_nav_menu', 10, 2 );

//function wpsites_add_logo_nav_menu( $menu, stdClass $args ){
//
//if ($args->theme_location == 'Main Menu | depth 5 (Overlay | depth 1)' )
//    return $menu;
//
//
//$menu .= '<li>'.the_custom_logo().'</li>';
//
//return $menu;

//}

?>