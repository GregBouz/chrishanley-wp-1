<?php
/*
Template Name: Home Page
*/
?>

<?php if (isset($_GET['property-search-submit'])) { ?>
<?php include ( TEMPLATEPATH . '/includes/property-search-results.php' ); ?>
<?php } else { ?>
<?php get_header(); ?>
<?php global $woo_options; ?>
<?php $search_all_properties_page = get_bloginfo('url').'/?s='.$woo_options['woo_search_keyword_text'].'&property-search-submit=Search';?>
<?php $search_all_properties_page = get_bloginfo('url').'/?s=View More';?>
    <div id="content" class="col-full">
    
    	<?php /*
		 <?php include ( TEMPLATEPATH . '/includes/property-search.php' ); ?>
		 */ ?>
    	
    	<?php $showfeatured = get_option('woo_featured'); if ($showfeatured <> "true") { if (get_option('woo_exclude')) update_option("woo_exclude", ""); } ?>
    	<?php if ( !$paged && $showfeatured == "true" ) include ( TEMPLATEPATH . '/includes/featured.php' ); ?>
    	
    	<?php if(get_option('woo_home_layout') == "With sidebar") { ?>
    	
    	
    		<div id="listings" class="with-sidebar">
    		
    			<?php if(get_option('woo_more_header')) { ?><h2 class="heading"><?php echo stripslashes(get_option('woo_more_header')); ?></h2><?php } ?>
    			<?php $exclude_posts = get_option('woo_exclude'); ?>
    			<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
    			$args = array( 'post__not_in' => $exclude_posts, 'post_type' => 'woo_estate', 'paged' => $paged, 'showposts' => get_option('woo_more_entries') );
    			query_posts($args); ?>
       		 	<?php if (have_posts()) : $count = 0; ?>
        		<?php while (have_posts()) : the_post(); $count++; ?>
        	    
        	    <?php 
        	    global $post;
        	    $post_type = $post->post_type;
        	    //Custom Meta Data
        	    $property_onshow = get_post_meta($post->ID,'on_show',true);
        	    $property_address = get_post_meta($post->ID,'address',true);
    			$property_garages = get_post_meta($post->ID,'garages',true);
    			$property_beds = get_post_meta($post->ID,'beds',true);
    			$property_baths = get_post_meta($post->ID,'bathrooms',true);
    			$property_size = get_post_meta($post->ID,'size',true);
    			$property_price = get_post_meta($post->ID,'price',true);
				$property_sale_type = get_post_meta($post->ID,'sale_type',true);
				if ($property_sale_type == 'rent') {
					$property_sale_metric = get_post_meta($post->ID,'sale_metric',true);
					switch ($property_sale_metric) {
						case "Per Week":
							$property_sale_metric = '/week';
							break;
						case "Per Month":
							$property_sale_metric = '/month';
							break;
					}
				} else {
					$property_sale_metric = '';
				}
				//format price				
    			$property_price = number_format($property_price , 0 , '.', ',');
        	    ?>                                                        
        	    <div class="property <?php if($count == 3){ echo 'last'; $count = 0; }?>" >
	    	        	       
        	   		<?php if ( ($post_type == 'woo_estate') && ($property_price > 0) ) { ?>	       
        	   		<a class="price top button fl" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo $woo_options['woo_estate_currency'].''.$property_price.$property_sale_metric; ?></a>
					<?php } ?>
					
					<?php if($property_onshow == 'true') { ?>
					
						<span class="on-show"><?php echo $woo_options['woo_label_on_show']; ?></span>
					
					<?php } ?>
					
        	        <?php woo_image('width=614&height=380&class=thumbnail'); ?>
        	        
        	        <div class="title-block">
	    	        	<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	    	        	<?php if ( ($post_type == 'woo_estate') && ($property_address != '') ) { ?><span class="sub-title"><?php echo $property_address; ?></span><?php } ?>
	    	        </div><!-- /.title-block -->
	    	        <?php if ( ($post_type == 'woo_estate') && ( ($property_size != '') || ($property_garages != '') || ($property_beds != '') || ($property_baths != '') ) ) { ?>	
	    	        <div class="features">
	            		
	            		<ul>
	            			<?php if ( ($property_size != '') || ($property_size > 0) ) { ?>
	            		    <li class="size">
	            		    	<span><img src="<?php echo $woo_options['woo_size_logo_big']; ?>" alt="Property size" /></span>
	            		    	<span><?php echo $property_size; ?> <?php echo $woo_options['woo_label_size_metric']; ?></span>
	            		    </li>
	            		    <?php } ?>
	            		    <?php if ( ($property_garages != '') || ($property_garages > 0) ) { ?>
	            		    <li class="garage">
		        		        <span><img src="<?php echo $woo_options['woo_garage_logo_big']; ?>" alt="Property size" /></span>
	            		    	<span><?php echo $property_garages; ?> <?php if ($property_garages <= 1) { echo $woo_options['woo_label_garage']; } else { echo $woo_options['woo_label_garages']; } ?></span>
	            		    </li>
	            		    <?php } ?>
	            		    <?php if ( ($property_beds != '') || ($property_beds > 0) ) { ?>
	            		    <li class="bed">
	            		    	<span><img src="<?php echo $woo_options['woo_bed_logo_big']; ?>" alt="Property size" /></span>
	            		    	<span><?php echo $property_beds; ?> <?php if ($property_beds <= 1) { echo $woo_options['woo_label_bed']; } else { echo $woo_options['woo_label_beds']; } ?></span>
	            		    </li>
	            		    <?php } ?>
	            		    <?php if ( ($property_baths != '') || ($property_baths > 0) ) { ?>
	            		    <li class="bath">
	            		    	<span><img src="<?php echo $woo_options['woo_bath_logo_big']; ?>" alt="Property size" /></span>
	            		    	<span><?php echo $property_baths; ?> <?php if ($property_baths <= 1) { echo $woo_options['woo_label_bath']; } else { echo $woo_options['woo_label_baths']; } ?></span>
	            		    </li>
	            		    <?php } ?>
	            		</ul>
	            		
	            		<div class="fix"></div>
	            			
	            	</div><!-- /.features -->
        	        <?php } ?>
        	        
        	        <div class="entry">
        	            <p><?php echo woo_excerpt( get_the_excerpt(), '185'); ?></p>
        	        </div>
			
        	        <div class="bottom">
	    	       	    	
	    	        	<span class="more-info fr"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('More info', 'woothemes') ?></a></span>
        	        	        
        	        	<div class="fix"></div>
        	        	        
        	       	</div>                    
        	       	
        	    </div><!-- /.property --> 
        	    
        	     <?php endwhile; endif; ?>
        	     
        	     <?php if(get_option('woo_archives_link') == 'true') { ?><a class="archives-link" href="<?php echo $search_all_properties_page; ?>" title="View All Properties">&middot; <?php _e('View all listed properties', 'woothemes') ?> &middot;</a><?php } ?>
        	     
    		</div><!-- /#listings -->
    		
    		<?php get_sidebar('sidebar'); ?>
    		
    		<div class="fix"></div>    	
    	
    	<?php } else { ?>
    	
    	
    	<div id="listings" class="no-sidebar">
    		
    		<?php if(get_option('woo_more_header')) { ?><h2 class="heading"><?php echo stripslashes(get_option('woo_more_header')); ?></h2><?php } ?>
    		<?php $exclude_posts = get_option('woo_exclude'); ?>
    		<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
    		$args = array( 'post__not_in' => $exclude_posts, 'post_type' => 'woo_estate', 'paged' => $paged, 'showposts' => get_option('woo_more_entries') );
    		query_posts($args); ?>
       	 	<?php if (have_posts()) : $count = 0; ?>
        	<?php while (have_posts()) : the_post(); $count++; ?>
            <?php 
        	    global $post;
        	    $post_type = $post->post_type;
        	    //Custom Meta Data
        	    $property_onshow = get_post_meta($post->ID,'on_show',true);
        	    $property_address = get_post_meta($post->ID,'address',true);
    			$property_garages = get_post_meta($post->ID,'garages',true);
    			$property_beds = get_post_meta($post->ID,'beds',true);
    			$property_baths = get_post_meta($post->ID,'bathrooms',true);
    			$property_size = get_post_meta($post->ID,'size',true);
    			$property_price = get_post_meta($post->ID,'price',true);
				$property_sale_type = get_post_meta($post->ID,'sale_type',true);
				if ($property_sale_type == 'rent') {
					$property_sale_metric = get_post_meta($post->ID,'sale_metric',true);
					switch ($property_sale_metric) {
						case "Per Week":
							$property_sale_metric = '/week';
							break;
						case "Per Month":
							$property_sale_metric = '/month';
							break;
					}
				} else {
					$property_sale_metric = '';
				}
				//format price
    			$property_price = number_format($property_price , 0 , '.', ',');
        	?>                                                        
            <div class="property <?php if($count == 3){ echo 'last'; $count = 0; }?>" >
	            	       
				<?php if ( ($post_type == 'woo_estate') && ($property_price > 0) ) { ?>	       
        	   		<a class="price top button fl" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo $woo_options['woo_estate_currency'].''.$property_price.$property_sale_metric; ?></a>
				<?php } ?>
				
				<?php if($property_onshow == 'true') { ?>
					
					<span class="on-show small"><?php echo $woo_options['woo_label_on_show']; ?></span>
					
				<?php } ?>
				
                <?php woo_image('width=294&height=150&class=thumbnail'); ?>
                
                <div class="title-block">
	            	<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	            	<?php if ( ($post_type == 'woo_estate') && ($property_address != '') ) { ?><span class="sub-title"><?php echo $property_address; ?></span><?php } ?>
	            </div><!-- /.title-block -->
                
                <?php if ( ($post_type == 'woo_estate') && ( ($property_size != '') || ($property_garages != '') || ($property_beds != '') || ($property_baths != '') ) ) { ?>	
	    	        <div class="features">
	            		
	            		<ul>
	            			<?php if ( ($property_size != '') || ($property_size > 0) ) { ?>
	            		    <li class="size">
	            		    	<img src="<?php echo $woo_options['woo_size_logo_small']; ?>" alt="Property size" />
	            		    	<?php echo $property_size; ?> <?php echo $woo_options['woo_label_size_metric']; ?>
	            		    </li>
	            		    <?php } ?>
	            		    <?php if ( ($property_garages != '') || ($property_garages > 0) ) { ?>
	            		    <li class="garage">
		        		        <img src="<?php echo $woo_options['woo_garage_logo_small']; ?>" alt="Property size" />
	            		    	<?php echo $property_garages; ?> <?php if ($property_garages <= 1) { echo $woo_options['woo_label_garage']; } else { echo $woo_options['woo_label_garages']; } ?>
	            		    </li>
	            		    <?php } ?>
	            		    <?php if ( ($property_beds != '') || ($property_beds > 0) ) { ?>
	            		    <li class="bed">
	            		    	<img src="<?php echo $woo_options['woo_bed_logo_small']; ?>" alt="Property size" />
	            		    	<?php echo $property_beds; ?> <?php if ($property_beds <= 1) { echo $woo_options['woo_label_bed']; } else { echo $woo_options['woo_label_beds']; } ?>
	            		    </li>
	            		    <?php } ?>
	            		    <?php if ( ($property_baths != '') || ($property_baths > 0) ) { ?>
	            		    <li class="bath">
	            		    	<img src="<?php echo $woo_options['woo_bath_logo_small']; ?>" alt="Property size" />
	            		    	<?php echo $property_baths; ?> <?php if ($property_baths <= 1) { echo $woo_options['woo_label_bath']; } else { echo $woo_options['woo_label_baths']; } ?>
	            		    </li>
	            		    <?php } ?>
	            		</ul>
	            		
	            		<div class="fix"></div>
	            			
	            	</div><!-- /.features -->
        	    <?php } ?>
                
                <div class="entry">
                    <p><?php echo woo_excerpt( get_the_excerpt(), '185'); ?></p>
                </div>

                <div class="bottom">
	           	    	
	            	<span class="more-info fr"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('More info', 'woothemes') ?></a></span>
                	        
                	<div class="fix"></div>
                	        
               	</div>                    
               	
            </div><!-- /.property --> 
            
             <?php endwhile; endif; ?>
             
             <?php if(get_option('woo_archives_link') == 'true') { ?><a class="archives-link" href="<?php echo $search_all_properties_page; ?>" title="View All Properties">&middot; <?php _e('View all listed properties', 'woothemes') ?> &middot;</a><?php } ?>
             
             <div class="fix"></div>
             
    	</div><!-- /#listings -->
    	
    	<?php } ?>
		
<?php get_footer(); ?>
<?php } ?>