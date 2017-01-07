<?php
/*-----------------------------------------------------------------------------------

- Loads all the .php files found in /includes/widgets/ directory

----------------------------------------------------------------------------------- */

include( TEMPLATEPATH . '/includes/widgets/widget-woo-adspace.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-blogauthor.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-campaignmonitor.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-custom-taxonomy-cloud.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-custom-taxonomy-list.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-customer-feedback.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-embed.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-flickr.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-property-search.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-search.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-tabs.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-twitter.php' );	
include( TEMPLATEPATH . '/includes/widgets/widget-woo-subscribe.php' );	
	
	
/*---------------------------------------------------------------------------------*/
/* Deregister Default Widgets */
/*---------------------------------------------------------------------------------*/
function woo_deregister_widgets(){
    unregister_widget('WP_Widget_Search');         
}
add_action('widgets_init', 'woo_deregister_widgets');  


?>