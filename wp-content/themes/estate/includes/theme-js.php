<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'woothemes_add_javascript' );
function woothemes_add_javascript( ) {
	wp_enqueue_script('jquery');    
	wp_enqueue_script( 'superfish', get_bloginfo('template_directory').'/includes/js/superfish.js', array( 'jquery' ) );
	wp_enqueue_script( 'wootabs', get_bloginfo('template_directory').'/includes/js/woo_tabs.js', array( 'jquery' ) );
	wp_enqueue_script( 'general', get_bloginfo('template_directory').'/includes/js/general.js', array( 'jquery' ) );
	wp_enqueue_script( 'loopedSlider', get_bloginfo('template_directory').'/includes/js/loopedSlider.js', array( 'jquery' ) );
	wp_enqueue_script('thickbox');
	wp_enqueue_script('carousel', get_bloginfo('template_directory').'/includes/js/jquery.jcarousel.min.js', array( 'jquery' ));
	wp_register_script('woo-autocomplete', get_bloginfo('template_directory').'/includes/js/jquery.autocomplete.js', array( 'jquery' ));
	wp_enqueue_script('woo-autocomplete');
	wp_enqueue_script( 'getgravatar', get_bloginfo('template_directory').'/includes/js/getgravatar.js', array( 'jquery' ) );
}
?>