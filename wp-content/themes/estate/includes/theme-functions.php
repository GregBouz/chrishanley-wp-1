<?php 

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Dynamic Search Filter
- Page / Post / Property navigation
- WooTabs - Popular Posts
- WooTabs - Latest Posts
- WooTabs - Latest Comments
- Misc
- Woo Google Mapping
- Thickbox Styles
- WordPress 3.0 New Features Support
- GetGravatar Inclusion on single pages
- Custom Array Functions
- Custom RSS Feed Output
- Subscribe / Connect

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Dynamic Search Filter */
/*-----------------------------------------------------------------------------------*/

add_action( 'pre_get_posts', 'woo_dynamic_search_results_filter' );

/**
 * woo_dynamic_search_results_filter function.
 * 
 * Since WordPress 3.4
 *
 * Filter on the default search 
 * Replaces custom search in search.php to fix pagination issues since 3.4
 *
 * @access public
 * @param mixed $query
 * @return void
 */
function woo_dynamic_search_results_filter( $query ) {
	
	// Exit if not main query
	if ( ! $query->is_main_query() )
		return;
	
	// Apply Filter only if on frontend and when search is running	
	if ($query->is_search) {
	
		ob_start();
	
		global $woo_options;
		
		//get variables
		if (isset($_GET['price_min'])) { $min_price = $_GET['price_min']; } else { $min_price = '';  }
		if (isset($_GET['price_max'])) { $max_price = $_GET['price_max']; } else { $max_price = '';  }
		if (isset($_GET['size_min'])) { $min_size = $_GET['size_min']; } else { $min_size = '';  }
		if (isset($_GET['size_max'])) { $max_size = $_GET['size_max']; } else { $max_size = '';  }
		if (isset($_GET['no_garages'])) { $no_garages = $_GET['no_garages']; } else { $no_garages = '';  }
		if (isset($_GET['no_beds'])) { $no_beds = $_GET['no_beds']; } else { $no_beds = '';  }
		if (isset($_GET['no_baths'])) { $no_baths = $_GET['no_baths']; } else { $no_baths = '';  }
		if (isset($_GET['location_names'])) { $location_id = $_GET['location_names']; } else { $location_id = '';  }
		if (isset($_GET['property_types'])) { $propertytypes_id = $_GET['property_types']; } else { $propertytypes_id = '';  }
		if (isset($_GET['sale_type'])) { $sale_type = $_GET['sale_type']; } else { $sale_type = '';  }
		//checks for empty
		if ( $sale_type == '' ) {
			$sale_type = 'all';
		}
		if ( $no_garages == '' ) {
			$no_garages = 'all';
		}
		if ( $no_beds == '' ) {
			$no_beds = 'all';
		}
		if ( $no_baths == '' ) {
			$no_baths = 'all';
		}
		if ( $location_id == '' ) {
			$location_id = 0;
		}
		if ( $propertytypes_id == ''  ) {
			$propertytypes_id = 0;
		}
		if ( $min_price == '' ) {
			$min_price = 0;
		}
		if ( $max_price == ''  ) {
			$max_price = 0;
		}
		if ( ($no_garages != '') || ($no_beds != '') || ($no_baths != '') ) {
			$array_advanced_search = array(	'garages'	=>	$no_garages,
											'beds'		=>	$no_beds,
											'baths'		=>	$no_baths
											);
		}
		
		$array_advanced_search['price_min'] = $min_price;
		$array_advanced_search['price_max'] = $max_price;
		
		if ( $min_size == '' ) {
			$min_size = 0;
		}
		if ( $max_size == ''  ) {
			$max_size = 0;
		}
		
		$array_advanced_search['size_min'] = $min_size;
		$array_advanced_search['size_max'] = $max_size;
		
		$keyword_to_search_raw = get_query_var('s');
		if ( $keyword_to_search_raw == 'View More' ) { $search_results_amount = $woo_options['woo_more_entries']; } else { $search_results_amount = $woo_options['woo_property_search_results']; }
		if ( $search_results_amount != '' ) { } else { $search_results_amount = 3; } 
		
		if ( ($keyword_to_search_raw == $woo_options['woo_search_keyword_text']) || ($keyword_to_search_raw == 'View More') ) { $keyword_to_search = ''; } else { $keyword_to_search = $keyword_to_search_raw; }
		
		if ( $location_id > 0 ) { $location_slug = get_term($location_id,'location'); } else { $location_slug = ''; }
		if ( $propertytypes_id > 0 ) { $propertytype_slug = get_term($propertytypes_id,'propertytype'); } else { $propertytype_slug = ''; }
		
		//setup query string
		$query_args = array();
		$query_args['post_type'] = 'woo_estate';
		    			
		if ($location_id > 0) { $query_args['location'] = $location_slug->slug; } else {} 
		if ($propertytypes_id > 0) { $query_args['propertytype'] = $propertytype_slug->slug; } else {}
		if ($sale_type == 'rent') { $query_args['meta_key'] = 'sale_type'; $query_args['meta_value'] = 'rent'; } else if ($sale_type == 'sale') { $query_args['meta_key'] = 'sale_type'; $query_args['meta_value'] = 'sale'; } else {  }
		
		$has_results = false;
		
		//Get Search Results posts
		//Check if general search or webref search
		if (isset($_GET['property-search-webref-submit'])) {
			$webref_array['post_type'] = 'woo_estate';
			$keyword_to_search_sanitized = strtolower($keyword_to_search);
			// CUSTOMIZATION
			if (strpos($keyword_to_search_sanitized, strtolower($woo_options['woo_estate_property_prefix'])) === 0) {
				$keyword_to_search_sanitized = substr($keyword_to_search_sanitized, strlen($woo_options['woo_estate_property_prefix']));
			}
			//$keyword_to_search_sanitized = str_replace( strtolower($woo_options['woo_estate_property_prefix']), '', $keyword_to_search_sanitized );
			$webref_array['post__in'] = array($keyword_to_search_sanitized);
			$posts_array = woo_estate_search_result_set($webref_array, $keyword_to_search_sanitized, $location_id, $propertytypes_id, $array_advanced_search, 'webref');
		} else {
			$posts_array = woo_estate_search_result_set($query_args, $keyword_to_search, $location_id, $propertytypes_id, $array_advanced_search);
		}
		
		//Add Paging variables
		$query_args['posts_per_page'] = $search_results_amount;
		$array_counter = count($posts_array);
		if ( $array_counter > 0 ) {
			$query_args['post__in'] = $posts_array;
			$has_results = true;
		
		} else {
			$has_results = false;
		}
		
		// Modified Settings for Query
		
		// Handle WebRef and redirect to exact match post
		if ( $array_counter == 1 && isset($_GET['property-search-webref-submit']) && isset($posts_array[0]) && ( $posts_array[0] > 0 ) ) { 
			wp_redirect( get_permalink( intval($posts_array[0]) ) ); 
			exit;
		} // End If Statement
		
		// Set Query Variables	
		$query->set( 'post__in', $posts_array );
		$query->set( 'posts_per_page', $search_results_amount );
		
		// Build the Meta Query array
		
			$meta_query_args = array();
			$cmb_counter = 0;
			
			if ( get_option('woo_feature_matching_method') == 'exact' ) {
				$meta_operator = '==';
			} else {
				$meta_operator = '>=';
			}
			
			if ( $min_price != '' ) {
				
				array_push( $meta_query_args, array(
																'key' => 'price',
																'value' => $min_price,
																'compare' => '>=',
																'type' => 'DECIMAL'
															)
										); 
				$cmb_counter++;
				
			} // End If Statement
			
			if ( $max_price != '' ) {
				
				array_push( $meta_query_args, array(
																'key' => 'price',
																'value' => $max_price,
																'compare' => '<=',
																'type' => 'DECIMAL'
															)
										); 
				$cmb_counter++;
				
			} // End If Statement
			
			if ( $min_size != '' ) {
				
				array_push( $meta_query_args, array(
																'key' => 'size',
																'value' => $min_size,
																'compare' => '>=',
																'type' => 'DECIMAL'
															)
										); 
				$cmb_counter++;
				
			} // End If Statement
			
			if ( $max_size != '' ) {
				
				array_push( $meta_query_args, array(
																'key' => 'size',
																'value' => $max_size,
																'compare' => '<=',
																'type' => 'DECIMAL'
															)
										); 
				$cmb_counter++;
				
			} // End If Statement
			
			if ( $no_garages != '' && $no_garages > 0 ) {
				
				array_push( $meta_query_args, array(
																'key' => 'garages',
																'value' => $no_garages,
																'compare' => $meta_operator,
																'type' => 'NUMERIC'
															)
										); 
				$cmb_counter++;
				
			} // End If Statement
			
			if ( $no_beds != '' && $no_beds > 0 ) {
				
				array_push( $meta_query_args, array(
																'key' => 'beds',
																'value' => $no_beds,
																'compare' => $meta_operator,
																'type' => 'NUMERIC'
															)
										); 
				$cmb_counter++;
				
			} // End If Statement
			
			if ( $no_baths != '' && $no_baths > 0 ) {
				
				array_push( $meta_query_args, array(
																'key' => 'bathrooms',
																'value' => $no_baths,
																'compare' => $meta_operator,
																'type' => 'NUMERIC'
															)
										); 
				$cmb_counter++;
				
			} // End If Statement
			
			if ( $sale_type != '' && $sale_type != 'all' ) {
				
				array_push( $meta_query_args, array(
																'key' => 'sale_type',
																'value' => $sale_type,
																'compare' => '=='
															)
										); 
										
			} // End If Statement
			
			// Handle more than 1 custom field query		
			if ($cmb_counter > 0) {
				$meta_query_args['relation'] = 'AND';
			} // End If Statement
						
		
		// Build the Tax Query array
			$tax_query_args = array();
			$ctx_counter = 0;
			if ( $location_id != '' && $location_id > 0 ) {
				
				array_push( $tax_query_args, array(
																'taxonomy' => 'location',
																'field' => 'id',
																'terms' => $location_id,
																'operator' => 'IN'
																)
										); 
				$ctx_counter++;
				
			} // End If Statement
			
			if ( $propertytypes_id != '' && $propertytypes_id > 0 ) {
			
				array_push( $tax_query_args, array(
																'taxonomy' => 'propertytype',
																'field' => 'id',
																'terms' => $propertytypes_id,
																'operator' => 'IN'
																)
										); 
				
				// Handle more than 1 taxonomy query			
				if ($ctx_counter == 1) {
					$tax_query_args['relation'] = 'AND';
				} // End If Statement
										
			} // End If Statement
		
		// Setup Meta and Tax Queries if available
		if ( count($meta_query_args) > 0 ) { 
			$query->set('meta_query', $meta_query_args); 
		} // End If Statement
		if ( count($tax_query_args) > 0 ) { 
			$query->set('tax_query', $tax_query_args); 
		} // End If Statement
		//die(print_r($query));
		// Handle Search Query var for searches for 'All' items
		if ( ( get_query_var('s') == stripslashes( $woo_options['woo_search_keyword_text'] ) ) || ( $array_counter > 0 ) ) {
			$query->set('s', '');
		} // End If Statement
		
		ob_flush();
		
	} // End If Statement
	
} // End woo_dynamic_search_results_filter()


/*-----------------------------------------------------------------------------------*/
/* Page / Post / Property navigation */
/*-----------------------------------------------------------------------------------*/
function woo_pagenav() { 

	global $woo_options;

	// If the user has set the option to use simple paging links, display those. By default, display the pagination.
	if ( array_key_exists( 'woo_pagination_type', $woo_options ) && $woo_options[ 'woo_pagination_type' ] == 'simple' ) {
		if ( get_next_posts_link() || get_previous_posts_link() ) {
	?>

        <div class="nav-entries">
    	    <?php next_posts_link( '<div class="nav-prev fl">'. __( '&laquo; Newer Entries ', 'woothemes' ) . '</div>' ); ?>
            <?php previous_posts_link( '<div class="nav-next fr">'. __( ' Older Entries &raquo;', 'woothemes' ) . '</div>' ); ?>
            <div class="fix"></div>
        </div>

	<?php
		} 
	} else {
		woo_pagination();
	}
	   
}                	

function woo_postnav() { 

	global $woo_options;

	// If the user has set the option to use simple paging links, display those. By default, display the pagination.
	if ( array_key_exists( 'woo_pagination_type', $woo_options ) && $woo_options[ 'woo_pagination_type' ] == 'simple' ) {
		if ( get_next_posts_link() || get_previous_posts_link() ) {
	?>

        <div class="post-entries">
    	    <?php next_posts_link( '<div class="post-prev fl">'. '%link', '%title <span class="meta-nav">&raquo;</span>' . '</div>' ); ?>
            <?php previous_posts_link( '<div class="post-next fr">'. '%link', '<span class="meta-nav">&laquo;</span> %title' . '</div>' ); ?>
            <div class="fix"></div>
        </div>

	<?php
		} 
	} else {
		woo_pagination();
	}
	
}                	

function woo_propnav() { 
	
	global $woo_options;
		
	$get_string = '';
		
	if ( isset($_GET) ) {
			
			// Build GET string
			foreach ( $_GET as $key => $value ) {
				
				if ( ! in_array( $key, array('paged') ) ) {
				
					$value = str_replace(' ', '+', $value);
					$get_string .= '&'.$key.'='.$value;
					
				} // End If Statement
				
			} // End For Loop
		
		} // End If Statement
		 
	woo_pagination(array('add_fragment' => $get_string));
	
}                	

add_filter('woo_pagination', 'woo_estate_pagination_filter');
/**
 * woo_estate_pagination_filter function.
 *
 * Since WordPress 3.4
 *
 * Filter on the pagination links to remove default search variable
 * Assists in the pagination fix in the search filter
 *
 * @access public
 * @param mixed $page_links
 * @return void
 */
function woo_estate_pagination_filter($page_links) {
	
	$page_links = str_replace('?s=&', '?', $page_links);
	$page_links = str_replace('#038;', '', $page_links);
	
	return $page_links;
	
} // End woo_estate_pagination_filter()


/*-----------------------------------------------------------------------------------*/
/* WooTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('woo_tabs_popular')) {
	function woo_tabs_popular( $posts = 5, $size = 35 ) {
		global $post;
		$popular = get_posts('orderby=comment_count&posts_per_page='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) woo_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<div class="fix"></div>
	</li>
	<?php endforeach;
	}
}



/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Posts */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('woo_tabs_latest')) {
	function woo_tabs_latest( $posts = 5, $size = 35 ) {
		global $post;
		$latest = get_posts('showposts='. $posts .'&orderby=post_date&order=desc');
		foreach($latest as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) woo_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<div class="fix"></div>
	</li>
	<?php endforeach; 
	}
}



/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Comments */
/*-----------------------------------------------------------------------------------*/

function woo_tabs_comments( $posts = 5, $size = 35 ) {
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
	comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
	comment_type,comment_author_url,
	SUBSTRING(comment_content,1,50) AS com_excerpt
	FROM $wpdb->comments
	LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
	$wpdb->posts.ID)
	WHERE comment_approved = '1' AND comment_type = '' AND
	post_password = ''
	ORDER BY comment_date_gmt DESC LIMIT ".$posts;
	
	$comments = $wpdb->get_results($sql);
	
	foreach ($comments as $comment) {
	?>
	<li>
		<?php echo get_avatar( $comment, $size ); ?>
	
		<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php _e('on ', 'woothemes'); ?> <?php echo $comment->post_title; ?>">
			<?php echo strip_tags($comment->comment_author); ?>: <?php echo strip_tags($comment->com_excerpt); ?>...
		</a>
		<div class="fix"></div>
	</li>
	<?php 
	}
}

/*-----------------------------------------------------------------------------------*/
/* Post Meta */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('woo_post_meta')) {
	function woo_post_meta( ) {
?>
<p class="post-meta">
    <span class="post-author"><span class="small"><?php _e('by', 'woothemes') ?></span> <?php the_author_posts_link(); ?></span>
    <span class="post-date"><span class="small"><?php _e('on', 'woothemes') ?></span> <?php the_time( get_option( 'date_format' ) ); ?></span>
    <span class="post-category"><span class="small"><?php _e('in', 'woothemes') ?></span> <?php the_category(', ') ?></span>
    <?php edit_post_link( __('{ Edit }', 'woothemes'), '<span class="small">', '</span>' ); ?>
</p>
<?php 
	}
}

/*-----------------------------------------------------------------------------------*/
/* MISC */
/*-----------------------------------------------------------------------------------*/



// Shorten Excerpt text for use in theme
function woo_excerpt($text, $chars = 120) {
	$text = $text." ";
	$text = substr($text,0,$chars);
	$text = substr($text,0,strrpos($text,' '));
	$text = $text."...";
	return $text;
}


/*-----------------------------------------------------------------------------------*/
/* Woo Google Mapping */
/*-----------------------------------------------------------------------------------*/

function woo_maps_single_output($args){

	$key = get_option('woo_maps_apikey');
	
	// No More API Key needed
	
	if ( !is_array($args) ) 
		parse_str( $args, $args );
		
	extract($args);	
		
	$map_height = get_option('woo_maps_single_height');
	$featured_w = get_option('woo_home_featured_w');
	$featured_h = get_option('woo_home_featured_h');
	   
	$lang = get_option('woo_maps_directions_locale');
	$locale = '';
	if(!empty($lang)){
		$locale = ',locale :"'.$lang.'"';
	}
	$extra_params = ',{travelMode:G_TRAVEL_MODE_WALKING,avoidHighways:true '.$locale.'}';
	
	if(is_home() OR is_front_page()) { $map_height = get_option('woo_home_featured_h'); }
	if(empty($map_height)) { $map_height = 250;}
	
	if(is_home() && !empty($featured_h) && !empty($featured_w)){
	?>
    <div id="single_map_canvas" style="width:<?php echo $featured_w; ?>px; height: <?php echo $featured_h; ?>px"></div>
    <?php } else { ?> 
    <div id="single_map_canvas" style="width:100%; height: <?php echo $map_height; ?>px"></div>
    <?php } ?>
    <script src="<?php bloginfo('template_url'); ?>/includes/js/markers.js" type="text/javascript"></script>
    <script type="text/javascript">
		jQuery(document).ready(function(){
			function initialize() {
				
				
			<?php if($streetview == 'on'){ ?>

				var location = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
				
				<?php 
				// Set defaults if no value
				if ($yaw == '') { $yaw = 20; }
				if ($pitch == '') { $pitch = -20; }
				?>
				
				var panoramaOptions = {
  					position: location,
  					pov: {
    					heading: <?php echo $yaw; ?>,
    					pitch: <?php echo $pitch; ?>,
    					zoom: 1
  					}
				};
				
				var map = new google.maps.StreetViewPanorama(document.getElementById("single_map_canvas"), panoramaOptions);
				
		  		google.maps.event.addListener(map, 'error', handleNoFlash);
				
				<?php if(get_option('woo_maps_scroll') == 'true'){ ?>
			  	map.scrollwheel = false;
			  	<?php } ?>
				
			<?php } else { ?>
				
			  	<?php switch ($type) {
			  			case 'G_NORMAL_MAP':
			  				$type = 'ROADMAP';
			  				break;
			  			case 'G_SATELLITE_MAP':
			  				$type = 'SATELLITE';
			  				break;
			  			case 'G_HYBRID_MAP':
			  				$type = 'HYBRID';
			  				break;
			  			case 'G_PHYSICAL_MAP':
			  				$type = 'TERRAIN';
			  				break;
			  			default:
			  				$type = 'ROADMAP';
			  				break;
			  	} ?>
			  	
			  	var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
				var myOptions = {
				  zoom: <?php echo $zoom; ?>,
				  center: myLatlng,
				  mapTypeId: google.maps.MapTypeId.<?php echo $type; ?>
				};
			  	var map = new google.maps.Map(document.getElementById("single_map_canvas"),  myOptions);
				<?php if(get_option('woo_maps_scroll') == 'true'){ ?>
			  	map.scrollwheel = false;
			  	<?php } ?>
			  	
				<?php if($mode == 'directions'){ ?>
			  	directionsPanel = document.getElementById("featured-route");
 				directions = new GDirections(map, directionsPanel);
  				directions.load("from: <?php echo $from; ?> to: <?php echo $to; ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);
			  	<?php
			 	} else { ?>
			 
			  		var point = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
	  				var root = "<?php bloginfo('template_url'); ?>";
	  				var the_link = '<?php echo get_permalink(get_the_id()); ?>';
	  				<?php $title = str_replace(array('&#8220;','&#8221;'),'"',get_the_title(get_the_id())); ?>
	  				<?php $title = str_replace('&#8211;','-',$title); ?>
	  				<?php $title = str_replace('&#8217;',"`",$title); ?>
	  				<?php $title = str_replace('&#038;','&',$title); ?>
	  				var the_title = '<?php echo html_entity_decode($title) ?>'; 
	  				
	  			<?php		 	
			 	if(is_page()){ 
			 		$custom = get_option('woo_cat_custom_marker_pages');
					if(!empty($custom)){
						$color = $custom;
					}
					else {
						$color = get_option('woo_cat_colors_pages');
						if (empty($color)) {
							$color = 'red';
						}
					}			 	
			 	?>
			 		var color = '<?php echo $color; ?>';
			 		createMarker(map,point,root,the_link,the_title,color);
			 	<?php } else { ?>
			 		var color = '<?php echo get_option('woo_cat_colors_pages'); ?>';
	  				createMarker(map,point,root,the_link,the_title,color);
				<?php 
				}
					if(isset($_POST['woo_maps_directions_search'])){ ?>
					
					directionsPanel = document.getElementById("featured-route");
 					directions = new GDirections(map, directionsPanel);
  					directions.load("from: <?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?> to: <?php echo $address; ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);
  					
  					
  					
					directionsDisplay = new google.maps.DirectionsRenderer();
					directionsDisplay.setMap(map);
    				directionsDisplay.setPanel(document.getElementById("featured-route"));
					
					<?php if($walking == 'on'){ ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.WALKING;
					<?php } else { ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.DRIVING;
					<?php } ?>
					var start = '<?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?>';
					var end = '<?php echo $address; ?>';
					var request = {
       					origin:start, 
        				destination:end,
        				travelMode: travelmodesetting
    				};
    				directionsService.route(request, function(response, status) {
      					if (status == google.maps.DirectionsStatus.OK) {
        					directionsDisplay.setDirections(response);
      					}
      				});	
      				
  					<?php } ?>			
				<?php } ?>
			<?php } ?>
			

			  }
			  function handleNoFlash(errorCode) {
				  if (errorCode == FLASH_UNAVAILABLE) {
					alert("Error: Flash doesn't appear to be supported by your browser");
					return;
				  }
				 }

			
		
		initialize();
			
		});
	jQuery(window).load(function(){
			
		var newHeight = jQuery('#featured-content').height();
		newHeight = newHeight - 5;
		if(newHeight > 300){
			jQuery('#single_map_canvas').height(newHeight);
		}
		
	});

	</script>

<?php
}

function woothemes_metabox_maps_create() {
    global $post;
	$enable = get_post_meta($post->ID,'woo_maps_enable',true);
	$streetview = get_post_meta($post->ID,'woo_maps_streetview',true);
	$address = get_post_meta($post->ID,'woo_maps_address',true);
	$long = get_post_meta($post->ID,'woo_maps_long',true);
	$lat = get_post_meta($post->ID,'woo_maps_lat',true);
	$zoom = get_post_meta($post->ID,'woo_maps_zoom',true);
	$type = get_post_meta($post->ID,'woo_maps_type',true);
	$walking = get_post_meta($post->ID,'woo_maps_walking',true);
	
	$yaw = get_post_meta($post->ID,'woo_maps_pov_yaw',true);
	$pitch = get_post_meta($post->ID,'woo_maps_pov_pitch',true);
	
	$from = get_post_meta($post->ID,'woo_maps_from',true);
	$to = get_post_meta($post->ID,'woo_maps_to',true);
	
	if(empty($zoom)) $zoom = get_option('woo_maps_default_mapzoom');
	if(empty($type)) $type = get_option('woo_maps_default_maptype');
	if(empty($pov)) $pov = 'yaw:0,pitch:0';


	
	$key = get_option('woo_maps_apikey');
	
	// No More API Key needed
	
	?>

    
    
    <?php
    $mode = get_post_meta($post->ID,'woo_maps_mode',true); 
    if($mode == 'plot'){ $directions = 'not-active'; $plot = 'active'; }
    elseif($mode == 'directions'){ $directions = 'active'; $plot = 'not-active'; }
    else {$directions = 'not-active'; $plot = 'active';}

    ?>


	<table><tr><td><strong>Enable map on this post: </strong></td>
    <td><input class="address_checkbox" type="checkbox" name="woo_maps_enable" id="woo_maps_enable" <?php if($enable == 'on'){ echo 'checked=""';} ?> /></td></tr>
    <tr><td><strong>This map will be in Streetview: </strong></td>
    <td><input class="address_checkbox" type="checkbox" name="woo_maps_streetview" id="woo_maps_streetview" <?php if($streetview == 'on'){ echo 'checked=""';} ?> /></td></tr>
    <tr class="hidden"><td><strong>Outputs directions for walking: </strong></td>
    <td><input class="address_checkbox" type="checkbox" name="woo_maps_walking" id="woo_maps_walking" <?php if($walking == 'on'){ echo 'checked=""';} ?> /></td></tr>
    
    </table>
    
    <div id="map_mode">
    	<ul>
    		<li><a class="<?php echo $plot; ?>" href="#" id="woo_plot_point">Plot Point</a></li>
    		<li class="hidden"><a class="<?php echo $directions; ?>" href="#" id="woo_directions_map">Directions Map</a></li>
    	</ul>
    </div>
   	<div class="woo_maps_search">
    <table><tr><td width="200"><b>Search for an address:</b></td>
    <td><input class="address_input" type="text" size="40" value="" name="woo_maps_search_input" id="woo_maps_search_input"/><span class="button" id="woo_maps_search">Plot</span>
    </td></tr></table>
    </div>
	<div id="woo_maps_holder" class="woo_maps_style" >
    <ul>
    	<li class="woo_plot <?php echo $plot; ?>">
    		<label>Address Name:</label>
    		<input class="address_input" type="text" size="40" name="woo_maps_address" id="woo_maps_address" value="<?php echo $address; ?>" />
    	</li>
    	<li>
    		<label>Latitude: <small class="woo_directions">Center Point</small></label>
    		<input class="address_input" type="text" size="40" name="woo_maps_lat" id="woo_maps_lat" value="<?php echo $lat; ?>"/>
    	</li>
    	<li>
    		<label>Longitude: <small class="woo_directions">Center Point</small></label>
    		<input class="address_input" type="text" size="40" name="woo_maps_long" id="woo_maps_long" value="<?php echo $long; ?>"/>
    	</li>
        <li class="woo_plot <?php echo $plot; ?>">
    		<label>Point of View: Yaw</label>    	
    		<input class="address_input" type="text" name="woo_maps_pov_yaw" id="woo_maps_pov_yaw" size="40" value="<?php echo $yaw;  ?>" />
      		<small>Streetview</small>	
      	</li>
        <li class="woo_plot <?php echo $plot; ?>">
    		<label>Point of View: Pitch</label>    		
    		<input class="address_input" type="text" name="woo_maps_pov_pitch" id="woo_maps_pov_pitch" size="40" value="<?php echo $pitch;  ?>">
      		<small>Streetview</small>
      	</li>
    	<li class="woo_directions <?php echo $directions; ?>">
    		<label>From:</label>
			<input class="address_input current_input" type="text" size="40" name="woo_maps_from" id="woo_maps_from" value="<?php echo $from; ?>"/>
    	</li>
    	<li class="woo_directions <?php echo $directions; ?>">
    		<label>To:</label>
    		<input class="address_input" type="text" size="40" name="woo_maps_to" id="woo_maps_to" value="<?php echo $to; ?>"/>
    	</li>
    	 <li>
    		<label>Zoom Level:</label>
    		<select class="address_select" style="width:120px" name="woo_maps_zoom" id="woo_maps_zoom">
    			<?php 
				for($i = 0; $i < 20; $i++) {
					if($i == $zoom){ $selected = 'selected="selected"';} else { $selected = '';} ?>
		 			<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
    				<?php } ?>
    		</select>
    	</li>
    	<li>
	  		<label>Map Type:</label>
    		<select class="address_select" style="width:120px" name="woo_maps_type" id="woo_maps_type">
   			<?php
			$map_types = array('Normal' => 'G_NORMAL_MAP','Satellite' => 'G_SATELLITE_MAP','Hybrid' => 'G_HYBRID_MAP','Terrain' => 'G_PHYSICAL_MAP',); 
			foreach($map_types as $k => $v) {
				if($type == $v){ $selected = 'selected="selected"';} else { $selected = '';} ?>
				<option value="<?php echo $v; ?>" <?php echo $selected; ?>><?php echo $k; ?></option>
    		<?php } ?>
    		</select>
 		</li>

 	</ul> 
 	<input type="hidden" value="<?php echo $mode; ?>" id="woo_maps_mode" name="woo_maps_mode" />
    </div>
    
    <div id="map_canvas" style="width: 100%; height: 250px"></div>
    <div name="pano" id="pano" style="width: 100%; height:250px"></div>

    <?php
	
}


function woothemes_metabox_maps_header(){
	global $post;  
    $pID = $post->ID; 
	$key = get_option('woo_maps_apikey');
	
	// No More API Key needed
	
	?>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
	jQuery(document).ready(function(){
		var map;
		var geocoder;
		var address;
		var pano;
		var location;
		var markersArray = [];
		
		<?php 
		$mode = get_post_meta($pID,'woo_maps_mode',true);
		if($mode == 'directions'){ ?>
		var mode = 'directions';
		<?php } else { ?>
		var mode = 'plot';
		<?php } ?>
		
		jQuery('#map_mode a').click(function(){
		
			var mode_set = jQuery(this).attr('id');
			if(mode_set == 'woo_directions_map'){
				mode = 'directions';
				jQuery('.woo_plot').hide();
				jQuery('.woo_directions').show();
				jQuery('#woo_maps_mode').val('directions');

			}
			else {
				mode = 'plot';
				jQuery('.woo_plot').show();
				jQuery('.woo_directions').hide();
				jQuery('#woo_maps_mode').val('plot');
			}
			
			jQuery('#map_mode a').removeClass('active');
			jQuery(this).addClass('active');
		
			return false;
		});
		
		jQuery('#woo_maps_to').focus(function(){
			jQuery('#woo_maps_from').removeClass('current_input');
			jQuery(this).addClass('current_input');
		});
		jQuery('#woo_maps_from').focus(function(){
			jQuery('#woo_maps_to').removeClass('current_input');
			jQuery(this).addClass('current_input');
		});
	
		function initialize() {
		  
		  <?php 
		  $lat = get_post_meta($pID,'woo_maps_lat',true);
		  $long = get_post_meta($pID,'woo_maps_long',true);
		  $yaw = get_post_meta($pID,'woo_maps_pov_yaw',true);
		  $pitch = get_post_meta($pID,'woo_maps_pov_pitch',true);
		 
		  if(empty($long) && empty($lat)){
		  	//Defaults...
			$lat = '40.7142691';
			$long = '-74.0059729';
			$zoom = get_option('woo_maps_default_mapzoom');
		  } else { 
		  	$zoom = get_post_meta($pID,'woo_maps_zoom',true); 
		  }
		  if(empty($yaw) OR empty($pitch)){
		  	$pov = 'yaw:20,pitch:-20';
		  } else {
		  	$pov = 'yaw:' . $yaw . ',pitch:' . $pitch;
		  }
		  
		  ?>
		  
		  // Manage API V2 existing data
		  <?php switch ($type) {
				case 'G_NORMAL_MAP':
					$type = 'ROADMAP';
					break;
				case 'G_SATELLITE_MAP':
					$type = 'SATELLITE';
					break;
				case 'G_HYBRID_MAP':
					$type = 'HYBRID';
					break;
				case 'G_PHYSICAL_MAP':
					$type = 'TERRAIN';
					break;
				default:
					$type = 'ROADMAP';
		  			break;
		  } ?>
		  
		  // Create Standard Map
		  location = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
		  var myOptions = {
		  		zoom: <?php echo $zoom; ?>,
		  		center: location,
		  		mapTypeId: google.maps.MapTypeId.<?php echo $type; ?>,
		  		streetViewControl: false
		  };
		  map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		  
      	  <?php
      	  // Set defaults if no value
		  if ($yaw == '') { $yaw = 20; }
		  if ($pitch == '') { $pitch = -20; }
		  ?>
		  
		  // Create StreetView Map		
		  var panoramaOptions = {
  		  	position: location,
  			pov: {
    			heading: <?php echo $yaw; ?>,
    			pitch: <?php echo $pitch; ?>,
    			zoom: 1
  			}
		  };	
		  pano = new google.maps.StreetViewPanorama(document.getElementById("pano"), panoramaOptions);
		  
		  // Set initial Zoom Levels
		  var z = map.getZoom();        
          jQuery('#woo_maps_zoom option').removeAttr('selected');
          jQuery('#woo_maps_zoom option[value="'+z+'"]').attr('selected','selected');
      	  
      	  // Event Listener - StreetView POV Change
      	  google.maps.event.addListener(pano, 'pov_changed', function(){
      	  	var headingCell = document.getElementById('heading_cell');
      		var pitchCell = document.getElementById('pitch_cell');
      	  	jQuery("#woo_maps_pov_yaw").val(pano.getPov().heading);
     	  	jQuery("#woo_maps_pov_pitch").val(pano.getPov().pitch);
     	  	
      	  });
      	  
      	  // Event Listener - Standard Map Zoom Change
      	  google.maps.event.addListener(map, 'zoom_changed', function(){
      	  	var z = map.getZoom();        
        	jQuery('#woo_maps_zoom option').removeAttr('selected');
        	jQuery('#woo_maps_zoom option[value="'+z+'"]').attr('selected','selected');
      	  });
      	  
      	  // Event Listener - Standard Map Click Event
      	  geocoder = new google.maps.Geocoder();
      	  google.maps.event.addListener(map, "click", getAddress);
      	
		} // End initialize() function
		
		// Adds the overlays to the map, and in the array
		function addMarker(location) {
  			marker = new google.maps.Marker({
    			position: location,
    			map: map
  			});
  			markersArray.push(marker);
		} // End addMarker() function
		  
		// Removes the overlays from the map, but keeps them in the array
		function clearOverlays() {
  			if (markersArray) {
    			for (i in markersArray) {
      				markersArray[i].setMap(null);
    			}
  			}
		} // End clearOverlays() function
		
		// Deletes all markers in the array by removing references to them
		function deleteOverlays() {
		 	if (markersArray) {
		    	for (i in markersArray) {
		      		markersArray[i].setMap(null);
		    	}
		    	markersArray.length = 0;
		  	}
		} // End deleteOverlays() function

		// Shows any overlays currently in the array
		function showOverlays() {
  			if (markersArray) {
    			for (i in markersArray) {
      				markersArray[i].setMap(map);
    			}
  			}
		} // End showOverlays() function
		
		// Sets initial marker on centre point
		function setSavedAddress() {
			point = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
		 	addMarker(point);
  		} // End setSavedAddress() function
		
		// Click event for address
		function getAddress(event) {
		  	
		  	clearOverlays();
		  	point = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
		 	addMarker(point);
		  	if(mode == 'directions'){
				jQuery('#woo_maps_lat').attr('value',event.latLng.lat());
				jQuery('#woo_maps_long').attr('value',event.latLng.lng());

			} else {
				jQuery('#woo_maps_lat').attr('value',event.latLng.lat());
				jQuery('#woo_maps_long').attr('value',event.latLng.lng());
			}
			
		  	if (event.latLng != null) {
				address = event.latLng;
				geocoder.geocode( { 'location': address}, showAddress);
		  	}
		  	if (event.latLng) {
		  		pano.setPosition(event.latLng);
		  		pano.setPov({heading:<?php echo $yaw; ?>,pitch:<?php echo $pitch; ?>,zoom:1});
		  	}
		} // End getAddress() function
		
		// Updates fields with address data
		function showAddress(results, status) {
			
			if (status == google.maps.GeocoderStatus.OK) {
        		deleteOverlays();
        		
        		map.setCenter(results[0].geometry.location);
        			
        		addMarker(results[0].geometry.location);
        				
        		place = results[0].formatted_address;
        		latlngplace = results[0].geometry.location;
        				
				if(mode == 'directions'){
					jQuery('.current_input').attr('value',place);
				} else {
					jQuery('#woo_maps_address').attr('value',place);
				}
        					
        	} else {
        		alert("Status Code:" + status);
        		
        	}
		} // End showAddress() function
		
		// addAddressToMap() is called when the geocoder returns an
		// answer.  It adds a marker to the map.
		function addAddressToMap(results, status) {
		  
		  deleteOverlays();
		  if (status != google.maps.GeocoderStatus.OK) {
			alert("Sorry, we were unable to geocode that address");
		  } else {
			place = results[0].formatted_address;
			point = results[0].geometry.location;					
			
			addMarker(point);
	
			map.setCenter(point, <?php echo $zoom; ?>);
			pano.setPosition(point);
		  	pano.setPov({heading:<?php echo $yaw; ?>,pitch:<?php echo $pitch; ?>,zoom:1});
		  					
			if(mode == 'directions'){
				
				jQuery('.current_input').attr('value',place);
				jQuery('#woo_maps_lat').attr('value',point.lat());
				jQuery('#woo_maps_long').attr('value',point.lng());
		
			} else {
				jQuery('#woo_maps_address').attr('value',place);
				jQuery('#woo_maps_lat').attr('value',point.lat());
				jQuery('#woo_maps_long').attr('value',point.lng());
			}
			
		  }
		}
	
		// >> PLOT
		// showLocation() is called when you click on the Search button
		// in the form.  It geocodes the address entered into the form
		// and adds a marker to the map at that location.
		function showLocation() {
		  var address = jQuery('#woo_maps_search_input').attr('value');
		  geocoder.geocode( { 'address': address}, addAddressToMap);
		}
		initialize();
		setSavedAddress();
		
		// >> PLOT
		//Click on the "Plot" button	
		jQuery('#woo_maps_search').click(function(){
		
			showLocation();
	
		})
		
	});
	
    </script>
	<style type="text/css">
		#map_canvas { margin:10px 0}
		.woo_maps_bubble_address { font-size:16px}
		.woo_maps_style { padding: 10px}
		.woo_maps_style ul li label { width: 150px; float:left; display: block}
		.woo_maps_search { border-bottom:1px solid #e1e1e1; padding: 10px}
		
		#woo_maps_holder .not-active{ display:none }
		
		#map_mode { height: 38px; margin: 10px 0; background: #f1f1f1; padding-top: 10px}
		#map_mode ul li { float:left;  margin-bottom: 0;}
		#map_mode ul li a {padding: 10px 15px; display: block;text-decoration: none;   margin-left: 10px }
		#map_mode a.active { color: black;background: #fff;border: solid #e1e1e1; border-width: 1px 1px 0px 1px; }
		.current_input { background: #E9F2FA!important}
		
	</style>
	
	<?php
}



function woothemes_metabox_maps_handle(){   
    
    global $globals;  
    $pID = $_POST['post_ID'];
    $woo_map_input_names = array('woo_maps_enable','woo_maps_streetview','woo_maps_address','woo_maps_from','woo_maps_to','woo_maps_long','woo_maps_lat','woo_maps_zoom','woo_maps_type','woo_maps_mode','woo_maps_pov_pitch','woo_maps_pov_yaw','woo_maps_walking');
	
    
    if ($_POST['action'] == 'editpost'){                                   
        foreach ($woo_map_input_names as $name) { // On Save.. this gets looped in the header response and saves the values submitted
  
				$var = $name;
				if (isset($_POST[$var])) {            
					if( get_post_meta( $pID, $name ) == "" )
						add_post_meta($pID, $name, $_POST[$var], true );
					elseif($_POST[$var] != get_post_meta($pID, $name, true))
						update_post_meta($pID, $name, $_POST[$var]);
					elseif($_POST[$var] == "") {
					   delete_post_meta($pID, $name, get_post_meta($pID, $name, true));
					}
				}
				elseif(!isset($_POST[$var]) && $name == 'woo_maps_enable') { 
					update_post_meta($pID, $name, 'false'); 
				}     
				else {
					  delete_post_meta($pID, $name, get_post_meta($pID, $name, true)); // Deletes check boxes OR no $_POST
				}  
                
            }
        }
}

function woothemes_metabox_maps_add() {
    if ( function_exists('add_meta_box') ) {
        
		$plugin_page_woo_estate = add_meta_box('woothemes-maps',get_option('woo_themename').' Custom Maps','woothemes_metabox_maps_create','woo_estate','normal');

		add_action('admin_head-'. $plugin_page_woo_estate, 'woothemes_metabox_maps_header' );
		
	}
}

add_action('edit_post', 'woothemes_metabox_maps_handle');
add_action('admin_menu', 'woothemes_metabox_maps_add'); // Triggers Woothemes_metabox_create

function woo_maps_enqueue($hook) {
  if ($hook == 'post.php' OR $hook == 'post-new.php' OR $hook == 'page.php' OR $hook == 'page-new.php') {
    if ( get_post_type() == 'woo_estate' || !get_post_type() ) {
    	  add_action('admin_head', 'woothemes_metabox_maps_header');
    }
  }
}
add_action('admin_enqueue_scripts','woo_maps_enqueue',10,1);

/*-----------------------------------------------------------------------------------*/
/* Category to Color matrix */
/*-----------------------------------------------------------------------------------*/

//return the color dependant no the cat passed
function cat_to_color($cat_object){

	$custom = get_option('woo_cat_custom_marker_' . $cat_object[0]->term_id);
	if(!empty($custom)){
		$color = $custom;
	}
	else {
		$color = get_option('woo_cat_colors_' . $cat_object[0]->term_id);
	}
	 
	return $color;
	
}


function custom_markers_admin_head(){
	?>
	<style type="text/css">
		#woo-option-coloredcustommarkers .section-text{ border:none;}
		#woo-option-coloredcustommarkers .section-text h3{ display:none}
		
	</style>
	<?php
}
add_action('admin_head','custom_markers_admin_head');
  
/*-----------------------------------------------------------------------------------*/
/*Thickbox Styles */
/*-----------------------------------------------------------------------------------*/

function thickbox_style() {
    ?>
    <link rel="stylesheet" href="<?php echo get_bloginfo('siteurl'); ?>/wp-includes/js/thickbox/thickbox.css" type="text/css" media="screen" />
    <script type="text/javascript">
    	var tb_pathToImage = "<?php echo get_bloginfo('siteurl'); ?>/wp-includes/js/thickbox/loadingAnimation.gif";
    	var tb_closeImage = "<?php echo get_bloginfo('siteurl'); ?>/wp-includes/js/thickbox/tb-close.png"
    </script>
    <?php
}

add_action('wp_head','thickbox_style');

function woo_get_custom_post_meta_entries($meta) {
	//db class	
	global $wpdb;
	//tables
	$table_1 = $wpdb->prefix . "postmeta";
	//initialize where clause
	$where_clause = '';
	if (sizeof($meta) > 0) {
		foreach ($meta as $key => $meta_item) {
			if ($key == 0) {
				$where_clause = "WHERE ".$table_1.".meta_key = '".$meta_item."'";
			} else {
				$where_clause .= " OR ".$table_1.".meta_key = '".$meta_item."'";
			}
		}
		$woo_result = $wpdb->get_results("SELECT ".$table_1.".meta_value FROM ".$table_1." ".$where_clause);
	} else {
		$woo_result = '';
	}
	return $woo_result;					
}

/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu' ), 'secondary-menu' => __( 'Secondary Menu' ) ) );
}

/*-----------------------------------------------------------------------------------*/
/* GetGravatar Inclusion on single pages */
/*-----------------------------------------------------------------------------------*/

function inc_getgravatar() {

	if ( is_single() ) {
	
	?>
	
		<script type="text/javascript" charset="utf-8">
			jQuery(document).ready(function() {
				jQuery("#email").getGravatar({
					avatarSize: 56,
					url: '<?php bloginfo('template_directory'); ?>/includes/get-gravatar.php',
					avatarContainer: 'img.avatar'
				});
			});
		</script>    	
	
	<?php
	
	}

}

add_action('wp_head','inc_getgravatar');

/*-----------------------------------------------------------------------------------*/
/* Custom Array Functions */
/*-----------------------------------------------------------------------------------*/

function woo_multidimensional_array_unique($array)
{
	$result = array_map("unserialize", array_unique(array_map("serialize", $array)));

	foreach ($result as $key => $value)
	{
		if ( is_array($value) )
		{
			$result[$key] = super_unique($value);
		}
	}

	return $result;
}

/*-----------------------------------------------------------------------------------*/
/* Custom RSS Feed Output */
/*-----------------------------------------------------------------------------------*/

function woo_custom_rss_output($content) {
	global $post;
	//get property image
	$img_src =  woo_image('width=614&height=180&class=center&link=img&return=true');
	$post_type = $post->post_type;
	//Get property details
	if ($post_type == 'woo_estate') {
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
		$property_price = (float) $property_price;
   		$property_price = number_format($property_price , 0 , '.', ',');
	}
	//default content output to nothing
	$temp_content = $content;
	$content = '';
	//if has image - output it
	if ($img_src != '') {
		$content .= '<p>'.$img_src.'</p>';
    }
    //if is a property - output details
	if ($post_type == 'woo_estate') {
		if($property_onshow == 'true') {
			$content .= '<p><strong>'.get_option('woo_label_property_details_on_show').'</strong></p>';
		}
		if ($property_address != '') {
			$content .= '<p><strong>'.__('Address', 'woothemes').':</strong>&nbsp;'.$property_address.'</p>';
		}
		if ( ($property_garages != '') || ($property_beds != '') || ($property_baths != '') || ($property_size != '') ) {
			$content .= '<p><strong>'.get_option('woo_label_garages').':</strong>&nbsp;'.$property_garages.'&nbsp;&nbsp;&nbsp;';
			$content .= '<strong>'.get_option('woo_label_beds').':</strong>&nbsp;'.$property_beds.'&nbsp;&nbsp;&nbsp;';
			$content .= '<strong>'.get_option('woo_label_baths').':</strong>&nbsp;'.$property_baths.'&nbsp;&nbsp;&nbsp;';
			$content .= '<strong>'.__('Size', 'woothemes').':</strong>&nbsp;'.$property_size.'&nbsp;'.get_option('woo_label_size_metric').'</p>';
		}
		if ($property_price != '') {
			$content .= '<p><strong>'.__('Price', 'woothemes').':</strong>&nbsp;'.get_option('woo_estate_currency').$property_price.$property_sale_metric.'</p>';
		}
	}
    //add original content back
    $content .= $temp_content;
    //output the content
    return $content;
}
add_filter('the_excerpt_rss', 'woo_custom_rss_output');
add_filter('the_content_rss', 'woo_custom_rss_output');

/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_subscribe_connect')) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		global $woo_options;

		// Setup title
		if ( $widget != 'true' )
			$title = $woo_options[ 'woo_connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $woo_options[ 'woo_connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $woo_options[ 'woo_connect' ] == "true" OR $widget == 'true' ) : ?>
	<div id="connect">
		<h3><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else _e('Subscribe','woothemes'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ($woo_options[ 'woo_connect_content' ] != '') echo stripslashes($woo_options[ 'woo_connect_content' ]); else _e( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ); ?></p>

			<?php if ( $woo_options[ 'woo_connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'woothemes' ); ?>';}" />
				<input type="hidden" value="<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit button" type="submit" name="submit" value="<?php _e( 'Submit', 'woothemes' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $woo_options['woo_connect_mailchimp_list_url'] != "" AND $form != 'on' AND $woo_options['woo_connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $woo_options[ 'woo_connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $woo_options[ 'woo_connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $woo_options['woo_feed_url'] ) { echo esc_url( $woo_options['woo_feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-rss.png" title="<?php _e('Subscribe to our RSS feed', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_twitter'] ); ?>" class="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-twitter.png" title="<?php _e('Follow us on Twitter', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_facebook'] ); ?>" class="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-facebook.png" title="<?php _e('Connect on Facebook', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_youtube'] ); ?>" class="youtube"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-youtube.png" title="<?php _e('Watch on YouTube', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_flickr'] ); ?>" class="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-flickr.png" title="<?php _e('See photos on Flickr', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_linkedin'] ); ?>" class="linkedin"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-linkedin.png" title="<?php _e('Connect on LinkedIn', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_delicious' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_delicious'] ); ?>" class="delicious"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-delicious.png" title="<?php _e('Discover on Delicious', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_googleplus'] ); ?>" class="googleplus"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-googleplus.png" title="<?php _e('View Google+ profile', 'woothemes'); ?>" alt=""/></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $woo_options[ 'woo_connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Posts:', 'woothemes' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

        <div class="fix"></div>
	</div>
	<?php endif; ?>
<?php
	}
}    

?>