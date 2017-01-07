<?php global $woo_options; ?>
<?php if(get_option('woo_featured_header')) { ?><h2 class="heading"><?php echo stripslashes(get_option('woo_featured_header')); ?></h2><?php } ?>

<div id="loopedSlider">
    <?php $woo_featured_tags = get_option('woo_featured_tags'); if ( ($woo_featured_tags != '') && (isset($woo_featured_tags)) ) { ?>
    <?php $woo_slider_pos = get_option('woo_slider_image'); ?>
    <?php
		$featposts = get_option('woo_featured_entries'); // Number of featured entries to be shown
		$GLOBALS['feat_tags_array'] = explode(',',get_option('woo_featured_tags')); // Tags to be shown
        foreach ($GLOBALS['feat_tags_array'] as $tags){ 
			$tag = get_term_by( 'name', trim($tags), 'post_tag', 'ARRAY_A' );
			if ( $tag['term_id'] > 0 )
				$tag_array[] = $tag['term_id'];
		}
    ?>
	<?php $saved = $wp_query; query_posts(array('post_type' => 'woo_estate','tag__in' => $tag_array, 'showposts' => $featposts)); ?>
    <?php if (have_posts() && $featposts > 1) : $count = 0; ?>
	
	<ul class="nav-buttons <?php if ($woo_slider_pos == 'Right') { echo 'right'; } ?>">
    	<li id="n"><a href="#" class="next"></a></li>
        <li id="p"><a href="#" class="previous"></a></li>
    </ul>
	        
	<?php endif; $wp_query = $saved; ?>      

	<?php $saved = $wp_query; query_posts(array('post_type' => 'woo_estate','tag__in' => $tag_array, 'showposts' => $featposts)); ?>
	<?php if (have_posts()) : $count = 0; ?>

    <div class="container">
    
        <div class="slides<?php if($featposts == 1) { echo ' single-slide'; } ?>" <?php if($featposts == 1) { echo 'style="display: block;position: relative;"'; }?>>
        
            <?php while (have_posts()) : the_post(); $GLOBALS['shownposts'][$count] = $post->ID; $count++; ?>
            <?php 
        	    global $post;
        	    $post_type = $post->post_type;
        	    //Meta Data
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
    			$property_price = (float) $property_price;		
    			$property_price = number_format($property_price , 0 , '.', ',');
        	?>
            <div id="slide-<?php echo $count; ?>" class="slide">
            
            	<div class="image <?php if ($woo_slider_pos == 'Right') { echo 'fr right'; } else { echo 'fl'; } ?> ">
            	
            		<?php if($property_onshow == 'true') { ?>
					
						<span class="on-show"><?php echo stripslashes( $woo_options['woo_label_on_show'] ); ?></span>
					
					<?php } ?>
            	
            		<?php woo_get_image('image',534,321,' '.get_option('woo_slider_image')); ?>
            	
            	</div>
            	
            	<div class="text <?php if ($woo_slider_pos == 'Right') { echo 'fr right'; } else { echo 'fl'; } ?>">
            	
            		<div class="property">
            		
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
                	                            
                	        <p><?php echo woo_excerpt( get_the_excerpt(), '240'); ?></p>
                	                
                	    </div>
                	    
                	    <div class="fix"></div>
                	    
                	    <div class="bottom">
	            	       	<?php if ( ($post_type == 'woo_estate') && ($property_price > 0) ) { ?>	       
        	   				<a class="price button fl" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo $woo_options['woo_estate_currency'].''.$property_price.$property_sale_metric; ?></a>
							<?php } ?>
							<span class="more-info fr"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('More info', 'woothemes') ?></a></span>
        	        	 
                	        <div class="fix"></div>
                	        
                	    </div>
                	    
                	</div><!-- /.property -->
            	
            	</div>          
				        
            </div>
            
		<?php endwhile; ?> 

        </div><!-- /.slides -->        
    </div><!-- /.container -->
	<div class="fix"></div>
    
    <?php endif; $wp_query = $saved; ?> 
    <?php if (get_option('woo_exclude') <> $GLOBALS['shownposts']) update_option("woo_exclude", $GLOBALS['shownposts']); ?>
    <?php } else { ?>    
	<p class="note"><?php _e('Please setup Featured Panel tag(s) in your options panel. You must setup tags that are used on active posts.','woothemes'); ?></p>
	<?php } ?>
</div><!-- /#loopedSlider -->
