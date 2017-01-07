<?php get_header(); ?>
<?php global $woo_options; ?>
              
	<?php if(get_option("woo_property_single_layout") == "Without Sidebar" ) {
		
		get_template_part('single-full-estate');
		
	} else { 
		
		get_template_part('single-sidebar-estate');
		
	} ?>
		
<?php get_footer(); ?>