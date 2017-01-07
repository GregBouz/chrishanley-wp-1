<?php global $woo_options; ?>
<div id="content" class="single col-full">

<?php if (have_posts()) : $count = 0; ?>
<?php while (have_posts()) : the_post(); $count++; ?>
        
	<?php 
	global $post;
	$post_type = $post->post_type;
		
	//Custom meta boxes
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
	if (get_option('woo_clickable_additional_features') == 'true') {
		$features_list = get_the_term_list( $post->ID, 'propertyfeatures', '' , '|' , ''  );
	} else {
		$features_list = strip_tags(get_the_term_list( $post->ID, 'propertyfeatures', '' , '|' , ''  ));
	}
	$features_array = explode('|', $features_list);
	//setup locations array
	$locations_list = get_the_term_list( $post->ID, 'location', '' , '|' , ''  );
	$locations_list = strip_tags($locations_list);
	$locations_array = explode('|', $locations_list);
	$location_results = '';
	foreach ($locations_array as $location_item) {
	    $location_id = get_term_by( 'name', $location_item, 'location' );
	    $location_results = $location_id->slug.',';
	}
	?>      
	
    <?php if(get_option('woo_displaysearch_single') == 'true') { get_template_part( 'includes/property-search' ); } ?>
	
	<!-- START TITLE -->
		
	<div class="title-block">
	  	<h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
	   	<?php if ( ($post_type == 'woo_estate') && ($property_address != '') ) { ?><span class="sub-title"><?php echo $property_address; ?></span><?php } ?>
	   	<?php if($property_onshow == 'true') { ?><span class="on-show-text"><?php echo $woo_options['woo_label_property_details_on_show']; ?></span><?php } ?>
	   	<span class="webref"><?php _e('Web Reference','woothemes'); ?>: <?php echo $woo_options['woo_estate_property_prefix'].$post->ID; ?></span>
	</div><!-- /.title-block -->
	
	<!-- //END TITLE -->
	
	<!-- START META BAR  (PRICE, FEATURES, CONTACT) -->
	       
	<?php if ($post_type == 'woo_estate') { ?>
		
		<div id="single-meta">
	    	
	    <?php if ( ($post_type == 'woo_estate') && ($property_price > 0) ) { ?>	       
        	<div class="price">
	    		<?php echo $woo_options['woo_estate_currency'].''.$property_price.$property_sale_metric; ?>
	    	</div><!-- /.price -->
	    <?php } ?>
	    
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
            	
	        </div><!-- /.features -->
		
		<?php } ?>
	    	
	    	<div class="contact">
	    		
	    		<span class="button"><?php echo $woo_options['woo_label_contact_agent_button']; ?></span>
	    		
	    		<div class="agent-popup">
	    			
	    			<?php the_commenter_avatar(array('avatar_size'=>'56')) ?>
	    			
	    			<div class="details">
	    				<?php $first_name = esc_attr( get_the_author_meta( 'first_name' ) ); ?>
	    				<?php $last_name = esc_attr( get_the_author_meta( 'last_name' ) ); ?>
	    				<?php if ( ($first_name != '') && ($last_name != '') ) { $display_name = $first_name.' '.$last_name; } else { $display_name = esc_attr( get_the_author_meta( 'display_name' ) ); } ?>
	    				<span class="name"><?php echo $display_name; ?></span>
	    				<span>
	    					<?php echo esc_attr( get_the_author_meta( 'contact_number' ) ); ?>
	    				</span>
	    				<span>
	    					<?php $author_email = esc_attr( get_the_author_meta( 'user_email' ) ); ?>
	    					<input type="text" style="display:none;" value="<?php echo $author_email; ?>" id="email" name="email" />
	    					<?php if ($woo_options['woo_contact_form_link'] == 'true') { ?>
	    					<a href="<?php bloginfo('url'); ?>/<?php echo $woo_options['woo_contact_form_page']; ?>/?propertyid=<?php echo $post->ID; ?>&agentid=<?php echo get_the_author_meta( 'ID' ); ?>" title="<?php echo $author_email; ?>"><?php echo $woo_options['woo_label_agent_email_link']; ?></a><?php } else { ?>
	    					<a href="mailto:<?php echo $author_email; ?>" title="<?php echo $author_email; ?>"><?php echo $woo_options['woo_label_agent_email_link']; ?></a><?php } ?>
	    				</span>
	    				
	    			</div>
	    			
	    			<div class="fix"></div>
	    			
	    		</div><!-- /.agent-popup -->
	    		
	    	</div><!-- ./contact -->
	    	
	    	<div class="fix"></div>
	    	
	    </div><!-- /#single-meta -->
	    
	<?php } ?>
	
	<!-- //END META BAR -->
	    		
	<!-- START PROPERTY CONTENT -->
			
	<div id="single-property" class="fl">
            
		<div <?php post_class(); ?>>
			
			<div class="entry <?php if(!$gallery){ echo 'no-gallery';  }?>">
            	<?php the_content(); ?>
			</div><!-- /.entry -->
			
			<?php if ( $post_type == 'woo_estate' ) { ?>
			
			<?php if ($features_list != '') { ?>
				
				<div class="sub-features">
				    
				    <h2 class="heading"><?php echo $woo_options['woo_label_additional_features']; ?></h2>
            		
            		<ul>
            			<?php foreach ($features_array as $feature_item) { ?>
            			<li><?php echo $feature_item; ?></li>
            			<?php } ?>
            		</ul>
            		
            		<div class="fix"></div>
            		
				</div><!-- /.sub-features -->
				
			<?php } ?>
			
			<?php $gallery = do_shortcode('[gallery size="thumbnail" columns="4"]'); ?>
            
            <?php if($gallery){ ?>
	
				<div class="photo">
			    	<h2 class="heading"><?php echo $woo_options['woo_label_gallery']; ?></h2>
			    	<?php include('includes/gallery.php'); // Photo gallery  ?>
				</div><!-- /.photo -->
	
			<?php } ?>
			
			<div class="fix"></div>
			
			<?php 
			    $maps_active = get_post_meta($post->ID,'woo_maps_enable',true);
			    $src = get_post_meta($post->ID,'image',true);
			?>
			<?php if($maps_active) { $video = woo_embed('width=600&height=243'); } else { $video = woo_embed('width=940&height=243'); } ?>
			                    
            <?php if($maps_active == 'on') { ?>
            
            	<div class="map">
            	
            		<h2 class="heading"><?php echo $woo_options['woo_label_property_map']; ?></h2>
            		
            		<?php 
			    		if($maps_active == 'on'){
			    			$mode = get_post_meta($post->ID,'woo_maps_mode',true);
							$streetview = get_post_meta($post->ID,'woo_maps_streetview',true);
                        	$address = get_post_meta($post->ID,'woo_maps_address',true);
                        	$long = get_post_meta($post->ID,'woo_maps_long',true);
                        	$lat = get_post_meta($post->ID,'woo_maps_lat',true);
							$pov = get_post_meta($post->ID,'woo_maps_pov',true);
                        	$from = get_post_meta($post->ID,'woo_maps_from',true);
                        	$to = get_post_meta($post->ID,'woo_maps_to',true);
                        	$zoom = get_post_meta($post->ID,'woo_maps_zoom',true);
                        	$type = get_post_meta($post->ID,'woo_maps_type',true);
                        	$yaw = get_post_meta($post->ID,'woo_maps_pov_yaw',true);
							$pitch = get_post_meta($post->ID,'woo_maps_pov_pitch',true);
                        	if(!empty($lat) OR !empty($from)){
                            	woo_maps_single_output("mode=$mode&streetview=$streetview&address=$address&long=$long&lat=$lat&pov=$pov&from=$from&to=$to&zoom=$zoom&type=$type&yaw=$yaw&pitch=$pitch"); 
                        	}
			    		}
			    	?>
            		                    	
            	</div><!-- /.map -->
            
            <?php } ?>
			
			<?php if (!empty($video)){ ?>                    
            
	            <div class="video">
            
    	        	<h2 class="heading"><?php echo $woo_options['woo_label_virtual_tour']; ?></h2>
            
			    	<div class="video">
			    		<?php echo $video; ?>
			    	</div><!-- /.video -->
	
	            </div><!-- /.video -->
	            
	        <?php } ?>
	        
            <?php } ?>
            
			<?php 
	    	    $location_results = chop($location_results,',');
	    	    $query_args = array(	'post_type' 		=> 'woo_estate',
	    	        					'post__not_in'		=> array($post->ID),
	    	        					'location'			=> $location_results,
	    	        					'posts_per_page' 	=> 4,
	    	        					'orderby' 			=> 'rand'
	    	        					);
	    	    $related_query = new WP_Query($query_args);
	    	    if ($related_query->have_posts()) : $count = 0; ?>
	    	    
	    	    	<div class="related-properties">
        	    	
        	    	    <h2 class="heading"><?php echo $woo_options['woo_label_related_properties']; ?></h2>
	    	    	
	    	    	        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
	    	    	        	
	    	    	        	<div class="related-post">
	    	    	        		
	    	    	        		<a href="<?php the_permalink() ?>"><?php woo_image('width=150&height=150&class=thumbnail&link=img'); ?></a>
	    	    	              	
	    	    	            </div><!-- /.related-post- -->
	    	    	        
	    	    	        <?php endwhile; ?>
	    	    	        		
	    	    	</div><!-- /.related-properties -->
	    	            	
			<?php else: endif; ?>
						
		</div><!-- /.post -->
		
	</div><!-- /#single-property -->
	
	<!-- //END PROPERTY CONTENT -->
                                                    
<?php endwhile; ?>         
<?php endif; ?>  
        
<?php get_sidebar(); ?>

<div class="fix"></div>