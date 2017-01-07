<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- WooThemes WooEstate Custom Post Type Class
- WooThemes WooEstate Custom Post Type Filters
- WooThemes WooEstate Custom Post Type Metabox Setup
- WooThemes WooEstate Agent Roles Setup
- WooThemes WooEstate Taxonomy Search Functions
- WooThemes WooEstate Property Search Function 

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* WooThemes WooEstate Custom Post Type Class */
/*-----------------------------------------------------------------------------------*/

class WooEstate {
	
	function WooEstate()
	{
	
		$wf_icon = get_option( 'framework_woo_backend_icon');
		$icon = get_template_directory_uri() . '/functions/images/woo-icon.png';
		if( $wf_icon == '' ) { $icon = $wf_icon; }
	
		// Register custom post types
		register_post_type(	'woo_estate', 
							array(	'label' 			=> __('Real Estate'),
									'labels' 			=> array(	'name' 					=> __('Properties'),
																	'singular_name' 		=> __('Property'),
																	'add_new' 				=> __('Add New'),
																	'add_new_item' 			=> __('Add New Property'),
																	'edit' 					=> __('Edit'),
																	'edit_item' 			=> __('Edit Property'),
																	'new_item' 				=> __('New Property'),
																	'view_item'				=> __('View Property'),
																	'search_items' 			=> __('Search Properties'),
																	'not_found' 			=> __('No Properties found'),
																	'not_found_in_trash' 	=> __('No Properties found in trash')	),
									'public' 			=> true,
									'can_export'		=> true,
									'show_ui' 			=> true, // UI in admin panel
									'_builtin' 			=> false, // It's a custom post type, not built in
									'_edit_link' 		=> 'post.php?post=%d',
									'capability_type' 	=> 'post',
									'menu_icon' 		=> $icon,
									'hierarchical' 		=> false,
									'has_archive' 		=> true,
									'rewrite' 			=> array(	"slug" => "property"	), // Permalinks
									'query_var' 		=> "woo_estate", // This goes to the WP_Query schema
									'supports' 			=> array(	'title',
																	'author', 
																	'excerpt',
																	'thumbnail',																
																	'editor', 
																	'custom-fields'	) ,
									'show_in_nav_menus'	=> true ,
									'taxonomies'		=> array(	'location',
																	'propertytype',
																	'propertyfeatures',
																	'post_tag')
								)
							);
		
		//Custom columns
		add_filter("manage_edit-woo_estate_columns", array(&$this, "woo_estate_edit_columns"));
		add_action("manage_posts_custom_column", array(&$this, "woo_estate_custom_columns"));
		//Add filter to insure the text Property, or property, is displayed when user updates a property
		add_filter('post_updated_messages', array(&$this, "woo_estate_updated_messages"));
		
		// Register custom taxonomy
		register_taxonomy(	"location", 
							array(	"woo_estate"	), 
							array (	"hierarchical" 		=> true, 
									"label" 			=> "Locations", 
									'labels' 			=> array(	'name' 				=> __('Locations'),
																	'singular_name' 	=> __('Location'),
																	'search_items' 		=> __('Search Real Estate'),
																	'popular_items' 	=> __('Popular Locations'),
																	'all_items' 		=> __('All Locations'),
																	'parent_item' 		=> __('Parent Location'),
																	'parent_item_colon' => __('Parent Location:'),
																	'edit_item' 		=> __('Edit Location'),
																	'update_item'		=> __('Update Location'),
																	'add_new_item' 		=> __('Add New Location'),
																	'new_item_name' 	=> __('New Location Name')	), 
									'public' 			=> true,
									'show_ui' 			=> true,
									"rewrite" 			=> array('slug' => 'location', 'hierarchical' => true)	)
							);
		register_taxonomy(	"propertytype", 
							array(	"woo_estate"	), 
							array(	"hierarchical" 		=> false, 
									"label" 			=> "Property Types", 
									'labels' 			=> array(	'name' 				=> __('Property Types'),
																	'singular_name' 	=> __('Property Type'),
																	'search_items' 		=> __('Search Property Types'),
																	'popular_items' 	=> __('Popular Property Types'),
																	'all_items' 		=> __('All Property Types'),
																	'parent_item' 		=> __('Parent Property Type'),
																	'parent_item_colon' => __('Parent Property Type:'),
																	'edit_item' 		=> __('Edit Property Type'),
																	'update_item'		=> __('Update Property Type'),
																	'add_new_item' 		=> __('Add New Property Type'),
																	'new_item_name' 	=> __('New Property Type Name')	),  
									'public' 			=> true,
									'show_ui' 			=> true,
									"rewrite" 			=> true	)
							);
		register_taxonomy(	"propertyfeatures", 
							array(	"woo_estate"	), 
							array(	"hierarchical" 		=> false, 
									"label" 			=> "Additional Features", 
									'labels' 			=> array(	'name' 				=> __('Additional Features'),
																	'singular_name' 	=> __('Additional Feature'),
																	'search_items' 		=> __('Search Additional Features'),
																	'popular_items' 	=> __('Popular Additional Features'),
																	'all_items' 		=> __('All Additional Features'),
																	'parent_item' 		=> __('Parent Additional Feature'),
																	'parent_item_colon' => __('Parent Additional Feature:'),
																	'edit_item' 		=> __('Edit Additional Feature'),
																	'update_item'		=> __('Update Additional Feature'),
																	'add_new_item' 		=> __('Add New Additional Feature'),
																	'new_item_name' 	=> __('New Additional Feature Name')	),  
									'public' 			=> true,
									'show_ui' 			=> true,
									"rewrite" 			=> true	)
							);
		
	}
	
	//custom post type edit headers
	function woo_estate_edit_columns($columns)
	{
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"woo_webref" => "WebRef",
			"title" => "Property Title",
			"woo_estate_description" => "Description",
			"woo_estate_thumbnail" => "Thumbnail",
			"woo_estate_standard_features" => "Standard Features",
			"woo_estate_additional_features" => "Additional Features",
			"woo_estate_locations" => "Locations",
			"woo_estate_type" => "Type",
			"woo_estate_price" => "Price",
		);
		
		return $columns;
	}
	
	//custom post type edit output
	function woo_estate_custom_columns($column)
	{
		global $post;
		switch ($column)
		{
			case "woo_webref":
				echo get_option('woo_estate_property_prefix').$post->ID;
				break;
			case "woo_estate_description":
				the_excerpt();
				break;
			case "woo_estate_thumbnail":
				woo_image('width=100&height=100&class=thumbnail');
				break;
			case "woo_estate_standard_features":
				$custom = get_post_custom();
				if ( isset($custom["garages"][0]) ) { $garages = $custom["garages"][0]; } else { $garages = ''; }
				if ( isset($custom["beds"][0]) ) { $beds = $custom["beds"][0]; } else { $beds = ''; }
				if ( isset($custom["bathrooms"][0]) ) { $bathrooms = $custom["bathrooms"][0]; } else { $bathrooms = ''; }
				if ( isset($custom["size"][0]) ) { $size = $custom["size"][0]; } else { $size = ''; }
				if ( isset($custom["on_show"][0]) ) { $on_show = $custom["on_show"][0]; } else { $on_show = ''; }
				$output = '';
				if ($on_show == 'true') { echo '<strong>'; echo get_option('woo_label_on_show'); echo '</strong><br />'; }
				if (has_tag('featured') ) { echo '<strong>'; _e('Featured Property', 'woothemes'); echo '</strong><br />'; }
				if ($garages != '') { echo '<strong>'; _e('Garages : ', 'woothemes'); echo '</strong>'.$garages.'<br />'; }
				if ($beds != '') { echo '<strong>'; _e('Bedrooms : ', 'woothemes'); echo '</strong>'.$beds.'<br />'; }
				if ($bathrooms != '') { echo '<strong>'; _e('Bathrooms : ', 'woothemes'); echo '</strong>'.$bathrooms.'<br />'; }
				if ($size != '') { echo '<strong>'; _e('Size : ', 'woothemes'); echo '</strong>'.$size.' '.get_option('woo_label_size_metric').'<br />'; }
				
				break;
			case "woo_estate_additional_features":
				$features = get_the_terms( $post->ID, "propertyfeatures");
				$features_html = array();
				if ($features) {
				foreach ($features as $feature)
					array_push($features_html, '<a href="' . get_term_link($feature->slug, "propertyfeatures") . '">' . $feature->name . '</a>');
				echo implode($features_html, ", ");
				} else {
					_e('None', 'woothemes');;
				}
				break;
			case "woo_estate_locations":
				$locations = get_the_terms($post->ID, "location");
				$locations_html = array();
				if ($locations) {
				foreach ($locations as $location)
					array_push($locations_html, '<a href="' . get_term_link($location->slug, "location") . '">' . $location->name . '</a>');
				
				echo implode($locations_html, ", ");
				} else {
					_e('None', 'woothemes');;
				}
				break;
			case "woo_estate_type":
				$propertytypes = get_the_terms($post->ID, "propertytype");
				$propertytypes_html = array();
				if ($propertytypes) {
				foreach ($propertytypes as $propertytype)
					array_push($propertytypes_html, '<a href="' . get_term_link($propertytype->slug, "propertytype") . '">' . $propertytype->name . '</a>');
				
				echo implode($propertytypes_html, ", ");
				} else {
					_e('None', 'woothemes');;
				}
				break;
			case "woo_estate_price":
				$price = get_post_meta($post->ID,'price',true);
				$price = (float) $price;
				$property_price = number_format($price , 0 , '.', ',');
				if ( $property_price > 0 ) {
					echo get_option('woo_estate_currency').$property_price;
				} else {
					echo __('Not captured.', 'woothemes');;
				}
				
				break;
		}
	}
	
	function woo_estate_updated_messages( $messages ) {
		
		global $post;
		$post_ID = $post->ID;
		
  		$messages['woo_estate'] = array(
    			0 => '', // Unused. Messages start at index 1.
    			1 => sprintf( __('Property updated. <a href="%s">View property</a>'), esc_url( get_permalink($post_ID) ) ),
    			2 => __('Custom field updated.'),
    			3 => __('Custom field deleted.'),
    			4 => __('Property updated.'),
    			/* translators: %s: date and time of the revision */
    			5 => isset($_GET['revision']) ? sprintf( __('Property restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    			6 => sprintf( __('Property published. <a href="%s">View Property</a>'), esc_url( get_permalink($post_ID) ) ),
    			7 => __('Property saved.'),
    			8 => sprintf( __('Property submitted. <a target="_blank" href="%s">Preview Property</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    			9 => sprintf( __('Property scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Property</a>'),
    	  			// translators: Publish box date format, see http://php.net/date
     				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    			10 => sprintf( __('Property draft updated. <a target="_blank" href="%s">Preview Property</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  			);

		return $messages;
	}
	
}

// Initiate the plugin
add_action("init", "WooEstateInit");
function WooEstateInit() { global $woowoo_estate; $woowoo_estate = new WooEstate(); }

/*-----------------------------------------------------------------------------------*/
/* WooThemes WooEstate Custom Post Type Filters */
/*-----------------------------------------------------------------------------------*/

// Custom Taxonomy Filters
if ( isset($_GET['post_type']) ) {
	$post_type = $_GET['post_type'];
}
else {
	$post_type = '';
}

if ( $post_type == 'woo_estate' ) {
	add_action('restrict_manage_posts', 'woo_estate_restrict_manage_posts');
	add_filter('posts_where', 'woo_estate_posts_where');
}

// The drop down with filter
function woo_estate_restrict_manage_posts() {
    ?>
        
            <fieldset>
            <?php
				//Locations
				if (isset($_GET['location_names'])) { $category_ID = $_GET['location_names']; } else { $category_ID = 0; }
            	if ($category_ID > 0) {
            		//Do nothing
            	} else {
            		$category_ID = 0;
            	}
            	$dropdown_options = array	(	
            								'show_option_all'	=> __('View all Locations'), 
            								'hide_empty' 		=> 0, 
            								'hierarchical' 		=> 1,
											'show_count' 		=> 0, 
											'orderby' 			=> 'name',
											'name' 				=> 'location_names',
											'id' 				=> 'location_names',
											'taxonomy' 			=> 'location', 
											'selected' 			=> $category_ID
											);
				wp_dropdown_categories($dropdown_options);
				//Property Types
				if (isset($_GET['type_names'])) { $category_ID = $_GET['type_names']; } else { $category_ID = 0; }
            	if ($category_ID > 0) {
            		//Do nothing
            	} else {
            		$category_ID = 0;
            	}
            	$dropdown_options = array	(	
            								'show_option_all'	=> __('View all Property Types'), 
            								'hide_empty' 		=> 0, 
            								'hierarchical' 		=> 1,
											'show_count' 		=> 0, 
											'orderby' 			=> 'name',
											'name' 				=> 'type_names',
											'id' 				=> 'type_names',
											'taxonomy' 			=> 'propertytype', 
											'selected' 			=> $category_ID
											);
				wp_dropdown_categories($dropdown_options);
				//Additional Features
				if (isset($_GET['feature_names'])) { $category_ID = $_GET['feature_names']; } else { $category_ID = 0; }
            	if ($category_ID > 0) {
            		//Do nothing
            	} else {
            		$category_ID = 0;
            	}
            	$dropdown_options = array	(	
            								'show_option_all'	=> __('View all Additional Features'), 
            								'hide_empty' 		=> 0, 
            								'hierarchical' 		=> 0,
											'show_count' 		=> 0, 
											'orderby' 			=> 'name',
											'name' 				=> 'feature_names',
											'id' 				=> 'feature_names',
											'taxonomy' 			=> 'propertyfeatures', 
											'selected' 			=> $category_ID
											);
				wp_dropdown_categories($dropdown_options);
            ?>
            <input type="submit" name="submit" value="<?php _e('Filter') ?>" class="button" />
        </fieldset>
        
    <?php
}

// Custom Query to filter edit grid
function woo_estate_posts_where($where) {
    if( is_admin() ) {
        global $wpdb;
        if (isset($_GET['location_names'])) { $location_ID = $_GET['location_names'];  } else { $location_ID = '';  }
        if (isset($_GET['type_names'])) { $type_ID = $_GET['type_names'];  } else { $type_ID = '';  }
		if (isset($_GET['feature_names'])) { $feature_ID = $_GET['feature_names'];  } else { $feature_ID = '';  }
		if ( ($location_ID > 0) || ($type_ID > 0) || ($feature_ID > 0) ) {

			$location_tax_names =  &get_term( $location_ID, 'location' );
			$type_tax_names =  &get_term( $type_ID, 'propertytype' );
			$feature_tax_names =  &get_term( $feature_ID, 'propertyfeatures' );
			$string_post_ids = '';
 			//locations
			if ($location_ID > 0) {
				$location_tax_name = $location_tax_names->slug;
				$location_myposts = get_posts('nopaging=true&post_type=woo_estate&location='.$location_tax_name);
				foreach($location_myposts as $post) {
					$string_post_ids .= $post->ID.',';
				}
			}
			//property types
			if ($type_ID > 0) {
				$type_tax_name = $type_tax_names->slug;
				$type_myposts = get_posts('nopaging=true&post_type=woo_estate&propertytype='.$type_tax_name);
				foreach($type_myposts as $post) {
					$string_post_ids .= $post->ID.',';
				}
			}
			//additional features
			if ($feature_ID > 0) {
				$feature_tax_name = $feature_tax_names->slug;
				$feature_myposts = get_posts('nopaging=true&post_type=woo_estate&propertyfeatures='.$feature_tax_name);
				foreach($feature_myposts as $post) {
					$string_post_ids .= $post->ID.',';
				}
   			}
 			$string_post_ids = chop($string_post_ids,',');
   			$where .= "AND ID IN (" . $string_post_ids . ")";
		}
    }
    return $where;
}

/*-----------------------------------------------------------------------------------*/
/* WooThemes WooEstate Custom Post Type Metabox Setup */
/*-----------------------------------------------------------------------------------*/

//Add meta boxes to woo_estate post type
function woothemes_woo_estate_metabox_add() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box('woothemes-settings',get_option('woo_themename').' Custom Settings','woothemes_metabox_create','woo_estate','normal');
    }
}
add_action('admin_menu', 'woothemes_woo_estate_metabox_add',1,1);

/*-----------------------------------------------------------------------------------*/
/* WooThemes WooEstate Agent Roles Setup */
/*-----------------------------------------------------------------------------------*/

//add a new role if it doesnt exist
if ( get_option('woo_agent_user_role_enable') == 'true' ) {
	woo_add_agent_role();
} else {
	//remove user role if exists - woo_estate_agent
	$agent_role = 'woo_estate_agent';
	$existing_role = get_role( $agent_role );
	if ($existing_role) {
		$removed_role = remove_role($agent_role);
	} 
}

//adds agent role
function woo_add_agent_role() {
	
	$agent_role = 'woo_estate_agent';
	$agent_name = get_option('woo_agent_role_name');
	$theme_agent_role = get_option('woo_agent_role_default');
	$existing_role = get_role( $agent_role );
	
	if ($existing_role) {
		$role = get_role( $theme_agent_role );
		$agent_capabilities = $role->capabilities;
		$existing_role->capabilities = $agent_capabilities;
		
	} else {
		if ( ($theme_agent_role != '') && ($theme_agent_role != 'Select a Role:') ) {
			//get existing role for theme setting - default is editor
			$role = get_role( $theme_agent_role );
			$agent_capabilities = $role->capabilities;
	
			/* Sanitize the new role, removing any unwanted characters. */
			$new_role = strip_tags( $agent_role );
			$new_role = str_replace( array( '-', ' ', '&nbsp;' ) , '_', $new_role );
			$new_role = preg_replace('/[^A-Za-z0-9_]/', '', $new_role );
			$new_role = strtolower( $new_role );

			/* Sanitize the new role name/label. We just want to strip any tags here. */
			$new_role_name = strip_tags( $agent_name ); // Should we use something like the WP user sanitation method?

			/* Add a new role with the data input. */
			$new_role_added = add_role( $new_role, $new_role_name, $agent_capabilities );
	
		}
	}
}


add_action( 'show_user_profile', 'woo_agent_extra_profile_fields' );
add_action( 'edit_user_profile', 'woo_agent_extra_profile_fields' );

//extra user fields output
function woo_agent_extra_profile_fields( $user ) { ?>

	<h3><?php _e('Additional Contact Information', 'woothemes'); ?></h3>

	<table class="form-table">

		<tr>
			<th><label for="contact-number"><?php _e('Contact Number', 'woothemes'); ?></label></th>

			<td>
				<input type="text" name="contact-number" id="contact-number" value="<?php echo esc_attr( get_the_author_meta( 'contact_number', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter your Contact Number', 'woothemes'); ?></span>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', 'woo_agent_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'woo_agent_save_extra_profile_fields' );

//handle save of extra user fields
function woo_agent_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'contact_number', $_POST['contact-number'] );
}

/*-----------------------------------------------------------------------------------*/
/* WooThemes WooEstate Taxonomy Search Functions */
/*-----------------------------------------------------------------------------------*/

//search taxonomies for a match against a search term and returns array of success count
function woo_taxonomy_matches($term_name, $term_id, $post_id = 0, $keyword_to_search = '') {
	$return_array = array();
	$return_array['success'] = false;
	$return_array['keywordcount'] = 0;
	$terms = get_the_terms( $post_id , $term_name );
	$success = false;
	$keyword_count = 0;
	if ($term_id == 0) {
		$success = true;
	}
	$counter = 0;
	// Loop over each item
	if ($terms) {
		foreach( $terms as $term ) {

			if ($term->term_id == $term_id) {
				$success = true;
			}
			if ( $keyword_to_search != '' ) {
				$keyword_count = substr_count( strtolower( $term->name ) , strtolower( $keyword_to_search ) );
				if ( $keyword_count > 0 ) {
					$success = true;
					$counter++;
				}
			} else {
				//If search term is blank
				$location_tax_names =  get_term_by( 'id', $term_id, 'location' );
 				//locations
				if ($location_tax_names) {
					if (isset($location_tax_names->slug)) { $location_tax_name = $location_tax_names->slug; } else { $location_tax_name = ''; }
					if ($location_tax_name != '') {
						$location_myposts = get_posts('nopaging=true&post_type=woo_estate&location='.$location_tax_name);
						foreach($location_myposts as $location_mypost) {
							if ($location_mypost->ID == $post_id) {
								$success = true;
	        					$counter++;
							} 
						}
					}
				}
			}
		}
	}
	$return_array['success'] = $success;
	if ($counter == 0) {
		$return_array['keywordcount'] = $keyword_count;
	} else { 
		$return_array['keywordcount'] = $counter;
	}
	
	return $return_array;
}



/*-----------------------------------------------------------------------------------*/
/* WooThemes WooEstate Property Search Function 
/*-----------------------------------------------------------------------------------*/

function woo_estate_search_result_set($query_args,$keyword_to_search, $location_id, $propertytypes_id, $advanced_search = null, $search_type = '') {
	
	$search_results = array();
	$query_args['showposts'] = -1;
	$the_query = new WP_Query($query_args);
	
	//Prepare Garages, Beds, Baths variables
	
	if ($advanced_search['beds'] == '10+') { 
		$advanced_beds = 10;
	} else {
		$advanced_beds = $advanced_search['beds'];
	}
	if ($advanced_search['baths'] == '10+') { 
		$advanced_baths = 10;
	} else {
		$advanced_baths = $advanced_search['baths'];
	}
	if ($advanced_search['garages'] == '10+') { 
		$advanced_garages = 10;
	} else {
		$advanced_garages = $advanced_search['garages'];
	}
	
	//Get matching method
	$matching_method = get_option('woo_feature_matching_method');
	
	if ($the_query->have_posts()) : $count = 0;

	while ($the_query->have_posts()) : $the_query->the_post();

		global $post;
        $post_type = $post->post_type;
		
		if ($search_type == 'webref') {
			array_push($search_results,$post->ID);
		} 
		else {
	        //Check Locations for matches
	        $location_terms = woo_taxonomy_matches('location', $location_id, $post->ID, $keyword_to_search);
	        $success_location = $location_terms['success'];
	        $location_keyword_count = $location_terms['keywordcount'];

	        //Secondary Location Check
	        if ( (!$success_location) || ($location_keyword_count == 0) ) {
	        	$location_tax_names =  get_term_by( 'name', $keyword_to_search, 'location' );

 				//locations
				if ($location_tax_names) {
					$location_tax_name = $location_tax_names->slug;
					//echo $location_tax_name.'<br />';
					if ($location_tax_name != '') {
						$location_myposts = get_posts('nopaging=true&post_type=woo_estate&location='.$location_tax_name);
						foreach($location_myposts as $location_mypost) {
							if ($location_mypost->ID == $post->ID) {
								$success_location = true;
	        					$location_keyword_count++;
							} 
						}
					}
				} 
	        }
	        
	        //Check Property Types for matches
	        $propertytypes_terms = woo_taxonomy_matches('propertytype', $propertytypes_id, $post->ID, $keyword_to_search);
	        $success_propertytype = $propertytypes_terms['success'];
	        $propertytype_keyword_count = $propertytypes_terms['keywordcount'];
	        
	        //Secondary Property Type Check
	        if ( (!$success_propertytype) || ($propertytype_keyword_count == 0) ) {
	        	$propertytype_tax_names =  get_term_by( 'name', $keyword_to_search, 'propertytype' );

 				//locations
				if ($propertytype_tax_names) {
					$propertytype_tax_name = $propertytype_tax_names->slug;
					//echo $location_tax_name.'<br />';
					if ($propertytype_tax_name != '') {
						$propertytype_myposts = get_posts('nopaging=true&post_type=woo_estate&propertytype='.$propertytype_tax_name);
						foreach($propertytype_myposts as $propertytype_mypost) {
							if ($propertytype_mypost->ID == $post->ID) {
								$success_propertytype = true;
	        					$propertytype_keyword_count++;
							} 
						}
					}
				} 
	        }
	        
	        //Check Additional Features for matches
	        $propertyfeatures_terms = woo_taxonomy_matches('propertyfeatures', 0, $post->ID, $keyword_to_search);
	        $success_propertyfeatures = $propertyfeatures_terms['success'];
	        $propertyfeatures_keyword_count = $propertyfeatures_terms['keywordcount'];
		    //Do custom meta boxes comparisons here
	    	$property_address = get_post_meta($post->ID,'address',true);
	    	$property_garages = get_post_meta($post->ID,'garages',true);
	    	if ($property_garages == '10+' ) {
	    		$property_garages = 10;
	    	}
			$property_garages_success = false;
			if ($advanced_garages == 'all') {
				$property_garages_success = true;
			} else {
				//Matching Method
				if ($matching_method == 'minimum') {
					//Minimum Value
					if ($property_garages >= $advanced_garages) {
						$property_garages_success = true;
					} else {
						$property_garages_success = false;
					}
				} else {
					//Exact Matching
					if ($property_garages == $advanced_garages) {
						$property_garages_success = true;
					} else {
						$property_garages_success = false;
					}
				}
			}
	    	$property_beds = get_post_meta($post->ID,'beds',true);
	    	if ($property_beds == '10+' ) {
	    		$property_beds = 10;
	    	}
			$property_beds_success = false;
			if ($advanced_beds == 'all') {
				$property_beds_success = true;
			} else {
				//Matching Method
				if ($matching_method == 'minimum') {
					//Minimum Value
					if ($property_beds >= $advanced_beds) {
						$property_beds_success = true;
					} else {
						$property_beds_success = false;
					}
				} else {
					//Exact Matching
					if ($property_beds == $advanced_beds) {
						$property_beds_success = true;
					} else {
						$property_beds_success = false;
					}
				}
			}
	    	$property_baths = get_post_meta($post->ID,'bathrooms',true);
	    	if ($property_baths == '10+' ) {
	    		$property_baths = 10;
	    	}
			$property_baths_success = false;
			if ($advanced_baths == 'all') {
				$property_baths_success = true;
			} else {
				//Matching Method
				if ($matching_method == 'minimum') {
					//Minimum Value
					if ($property_baths >= $advanced_baths) {
						$property_baths_success = true;
					} else {
						$property_baths_success = false;
					}
				} else {
					//Exact Matching
					if ($property_baths == $advanced_baths) {
						$property_baths_success = true;
					} else {
						$property_baths_success = false;
					}
				}
			}
			
			// SIZE COMPARISON SCENARIO(S)
	    	$property_size = get_post_meta($post->ID,'size',true);
			$property_size_success = false;
			//scenario 1 - only size min
			if ( ($advanced_search['size_min'] != '') && ( ($advanced_search['size_max'] == '') || ($advanced_search['size_max'] == 0) ) ) { 
				if ( ($property_size >= $advanced_search['size_min']) ) {
					$property_size_success = true;
				} else {
					$property_size_success = false;
				}
			}
			//scenario 2 - only size max
			elseif ( ( ($advanced_search['size_max'] != '') || ($advanced_search['size_max'] != 0) ) && ($advanced_search['size_min'] == '') ) { 
				if ( ($property_size <= $advanced_search['size_max']) ) {
					$property_size_success = true;
				} else {
					$property_size_success = false;
				}
			}
			//scenario 3 - size min and max are zero
			elseif ( ($advanced_search['size_min'] == '0') && ($advanced_search['size_max'] == 0) ) { 
				$property_size_success = true;
			}
			//scenario 4 - both min and max
			else {
				if ( ($property_size >= $advanced_search['size_min']) && ($property_size <= $advanced_search['size_max']) ) {
					$property_size_success = true;
				} else {
					$property_size_success = false;
				}
			}
			
			// PRICE COMPARISON SCENARIO(S)
	    	$property_price = get_post_meta($post->ID,'price',true);
			$property_price_success = false;
			//scenario 1 - only price min
			if ( ($advanced_search['price_min'] != '') && ( ($advanced_search['price_max'] == '') || ($advanced_search['price_max'] == 0) ) ) { 
				if ( ($property_price >= $advanced_search['price_min']) ) {
					$property_price_success = true;
				} else {
					$property_price_success = false;
				}
			}
			//scenario 2 - only price max
			elseif ( ( ($advanced_search['price_max'] != '') || ($advanced_search['price_max'] != 0) ) && ($advanced_search['price_min'] == '') ) { 
				if ( ($property_price <= $advanced_search['price_max']) ) {
					$property_price_success = true;
				} else {
					$property_price_success = false;
				}
			}
			//scenario 3 - price min and max are zero
			elseif ( ($advanced_search['price_min'] == '0') && ($advanced_search['price_max'] == 0) ) { 
				$property_price_success = true;
			}
			//scenario 4 - both min and max
			else {
				if ( ($property_price >= $advanced_search['price_min']) && ($property_price <= $advanced_search['price_max']) ) {
					$property_price_success = true;
				} else {
					$property_price_success = false;
				}
			}
			
			//format price
			$property_price = (float) $property_price;
			$property_price = number_format($property_price , 0 , '.', ',');
			
	    	if ( $success_location && $success_propertytype ) {  
	    		//Search against post data
	    		if ( $keyword_to_search != '' ) {
	    			//Default WordPress Content
	    			$raw_title = get_the_title();
	    			$raw_content = get_the_content();
	    			$raw_excerpt = get_the_excerpt();
	    			//Comparison
	    			$title_keyword_count = substr_count( strtolower( $raw_title ) , strtolower( $keyword_to_search ) );
	    			$content_keyword_count = substr_count( strtolower( $raw_content ) , strtolower( $keyword_to_search ) );
	    			$excerpt_keyword_count = substr_count( strtolower( $raw_excerpt ) , strtolower( $keyword_to_search ) );
	    			$property_address_count = substr_count( strtolower( $property_address ) , strtolower( $keyword_to_search ) );
	    		}
	    		//Check for matches or blank keyword
	    		
	    		if ( $keyword_to_search == '') {
	    			
	    			if ( ( $location_keyword_count > 0 ) || ( $propertytype_keyword_count > 0 ) || ( $propertyfeatures_keyword_count > 0 ) ) { 

						if ( (count($advanced_search) > 0) && ( ($advanced_search['garages'] != 'all') || ($advanced_search['beds'] != 'all') || ($advanced_search['baths'] != 'all') || ($advanced_search['price_min'] != '0') || ($advanced_search['price_max'] != '0') || ($advanced_search['size_min'] != '0') || ($advanced_search['size_max'] != '0') ) ) {
								
								if ($property_garages_success && $property_beds_success && $property_baths_success && $property_price_success && $property_size_success ) {
									//increment post counter
									
									$count++; 
									$has_results = true;
	    			
									//setup array data here
									array_push($search_results,$post->ID);
								}
							
						} else {
							//increment post counter
							$count++; 
							$has_results = true;
	    			
							//setup array data here
							array_push($search_results,$post->ID);
						}
						
	    			}
	    			elseif ( ( $location_keyword_count == 0 ) && ( $propertytype_keyword_count == 0 ) && ( $propertyfeatures_keyword_count == 0 ) ) { 
						
						if ( (count($advanced_search) > 0) && ( ($advanced_search['garages'] != 'all') || ($advanced_search['beds'] != 'all') || ($advanced_search['baths'] != 'all') || ($advanced_search['price_min'] != '0') || ($advanced_search['price_max'] != '0') || ($advanced_search['size_min'] != '0') || ($advanced_search['size_max'] != '0') ) ) {
								
								if ($property_garages_success && $property_beds_success && $property_baths_success && $property_price_success && $property_size_success ) {
									//increment post counter
									$count++; 
									$has_results = true;
	    			
									//setup array data here
									array_push($search_results,$post->ID);
								}
							
						} else {
							//increment post counter
							$count++; 
							$has_results = true;
	    			
							//setup array data here
							array_push($search_results,$post->ID);
						}
						
	    			}
	    			
	    		} else {
	    		
	    			if ( ( $title_keyword_count > 0 ) || ( $content_keyword_count > 0 ) || ( $excerpt_keyword_count > 0 ) || ( $location_keyword_count > 0 ) || ( $property_address_count > 0 ) || ( $propertytype_keyword_count > 0 ) || ( $propertyfeatures_keyword_count > 0 ) ) {
	    				if ( (count($advanced_search) > 0) && ( ($advanced_search['garages'] != 'all') || ($advanced_search['beds'] != 'all') || ($advanced_search['baths'] != 'all') || ($advanced_search['price_min'] != '0') || ($advanced_search['price_max'] != '0') || ($advanced_search['size_min'] != '0') || ($advanced_search['size_max'] != '0') ) ) {
								
								if ($property_garages_success && $property_beds_success && $property_baths_success && $property_price_success && $property_size_success ) {
									//increment post counter
									$count++; 
									$has_results = true;
	    			
									//setup array data here
									array_push($search_results,$post->ID);
								}
						} else {
							//increment post counter
							$count++; 
							$has_results = true;
	    			
							//setup array data here
							array_push($search_results,$post->ID);
						}
	    			} else {
					
					}
	    		
	    		}
	    		
	    		
	    	}
		
		}
		
	endwhile; else:
    	//no posts	    	
    endif;
	
	return $search_results;
}

?>