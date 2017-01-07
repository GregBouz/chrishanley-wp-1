<?php if (defined('DSIDXPRESS_OPTION_NAME')) { $options = get_option(DSIDXPRESS_OPTION_NAME); } else { $options = array('Activated' => false); } ?>
<div class="search-tab <?php if(get_option('woo_idx_plugin_search') != 'true'){ echo 'no-idx'; }?>">
<?php if(get_option('woo_search_header')) { ?>
		<span id="local-search" class="current"><?php echo stripslashes(get_option('woo_search_header')); ?></span>
		<?php if ( $options['Activated'] && ( get_option('woo_idx_plugin_search') == 'true' ) ) { ?>
		<span id="mls-search"><a class="red-highlight"><?php echo stripslashes(get_option('woo_search_mls_header')); ?></a></span>
		<?php } ?>
<?php } ?>
</div>
   		
    	<div id="search">
    	
    		<form name="property-webref-search" id="property-webref-search" method="get" action="<?php bloginfo('url'); ?>/">
			
				<input type="text" class="text webref" id="s-webref" name="s" value="<?php _e('Property ID', 'woothemes'); ?>" onfocus="if (this.value == '<?php _e('Property ID', 'woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Property ID', 'woothemes'); ?>';}" />
    			 
    			<input type="submit" class="submit button" name="property-search-webref-submit" value="<?php _e('Go To', 'woothemes'); ?>" /> 
    			
    		</form>
    	    		
    		<form name="property-search" id="property-search" method="get" action="<?php bloginfo('url'); ?>/">
    			
    			<div class="query">
	    			
	    			<?php
	    			if (isset($_GET['s'])) { $keyword = strip_tags($_GET['s']);  } else { $keyword = '';  }
					if ( $keyword == 'View More' ) { $keyword = ''; }
	    			?>
	    			
	    			<input type="text" class="main-query text" id="s-main" name="s" value="<?php if ( $keyword != '' ) { echo $keyword; } else { _e(get_option('woo_search_keyword_text'), 'woothemes'); } ?>" onfocus="if (this.value == '<?php _e(get_option('woo_search_keyword_text'), 'woothemes') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e(get_option('woo_search_keyword_text'), 'woothemes') ?>';}" />
    			 
    				<input type="submit" class="submit button" name="property-search-submit" value="<?php _e('Search', 'woothemes'); ?>" />
    				
    				<span class="or"><?php _e('Or', 'woothemes'); ?></span>
    				
    				<div class="fix"></div>
    				
    			</div><!-- /.query -->
    		
    			<div class="filters">
    				
    				<?php if (isset($_GET['sale_type'])) { $sale_type = $_GET['sale_type']; } else { $sale_type = ''; }
    					if ($sale_type == '') { $sale_type = 'all'; } ?>
    				
    				<div class="saletype">
    					<label for="saletype"><?php _e(get_option('woo_label_sale_type'), 'woothemes'); ?>:</label>
    					<input type="radio" name="sale_type" value="all" <?php if ($sale_type == 'all') { ?>checked<?php } ?>> <?php _e('All', 'woothemes') ?>  
    					<input type="radio" name="sale_type" value="sale" <?php if ($sale_type == 'sale') { ?>checked<?php } ?>> <?php _e(get_option('woo_label_for_sale'), 'woothemes') ?>
						<input type="radio" name="sale_type" value="rent" <?php if ($sale_type == 'rent') { ?>checked<?php } ?>> <?php _e(get_option('woo_label_for_rent'), 'woothemes') ?>
					</div><!-- /.saletype -->
					
					<div class="location-type">
    					
    					<label><?php _e(get_option('woo_label_property_location_and_type'), 'woothemes'); ?>:</label>
    					
    					<?php
    						//property locations drop down
    						if (isset($_GET['location_names'])) { $category_ID = $_GET['location_names']; } else { $category_ID = 0; }
            				if ($category_ID > 0) {
            					//Do nothing
            				} else {
            					$category_ID = 0;
            				}
            				$dropdown_options = array	(	
            											'show_option_all'	=> __(get_option('woo_label_locations_dropdown_view_all')), 
            											'hide_empty' 		=> 0, 
            											'hierarchical' 		=> 1,
														'show_count' 		=> 0, 
														'orderby' 			=> 'name',
														'name' 				=> 'location_names',
														'id' 				=> 'location_names',
														'taxonomy' 			=> 'location', 
														'hide_if_empty'		=> 1,
														'selected' 			=> $category_ID
														);
							wp_dropdown_categories($dropdown_options);
    					?>
    					
    					<?php
    						//property types drop down
    						if (isset($_GET['property_types'])) { $category_ID = $_GET['property_types']; } else { $category_ID = 0; }
            				if ($category_ID > 0) {
            					//Do nothing
            				} else {
            					$category_ID = 0;
            				}
            				$dropdown_options = array	(	
            											'show_option_all'	=> __(get_option('woo_label_property_type_dropdown_view_all')), 
            											'hide_empty' 		=> 0, 
            											'hierarchical' 		=> 1,
														'show_count' 		=> 0, 
														'orderby' 			=> 'name',
														'name' 				=> 'property_types',
														'id' 				=> 'property_types',
														'taxonomy' 			=> 'propertytype', 
														'hide_if_empty'		=> 1,
														'selected' 			=> $category_ID,
														'class'				=> 'last'
														);
							wp_dropdown_categories($dropdown_options);
    						
							if (isset($_GET['price_min'])) { $price_min = $_GET['price_min'];  } else { $price_min = '';  }
							if (isset($_GET['price_max'])) { $price_max = $_GET['price_max'];  } else { $price_max = '';  }
					
    						 
    					?>
    					
    				</div><!-- /.location-type -->
					
					<div class="fix"></div>
    				
					
					<div class="price">
						<label for="price_min"><?php _e(get_option('woo_label_min_price'), 'woothemes'); ?> <?php echo '('.get_option('woo_estate_currency').')'; ?>:</label><input type="text" class="text price validate_number" name="price_min" id="price_min" value="<?php if ( $price_min != '' ) { echo $price_min; } ?>" >	
						<label for="price_max"><?php _e(get_option('woo_label_max_price'), 'woothemes'); ?> <?php echo '('.get_option('woo_estate_currency').')'; ?>:</label><input type="text" class="text price validate_number" name="price_max" id="price_max" value="<?php if ( $price_max != '' ) { echo $price_max; } ?>" >	
					</div><!-- /.price -->
					
					<span class="advanced-search-button button"><?php _e(get_option('woo_label_advanced_search'), 'woothemes'); ?> &darr;</span>
					
					<div class="fix"></div>
    			
    			
    			<div id="advanced-search">
    				<?php if (isset($_GET['no_garages'])) { $no_garages = $_GET['no_garages'];  } else { $no_garages = 'all';  } ?>
					<?php if (isset($_GET['no_beds'])) { $no_beds = $_GET['no_beds'];  } else { $no_beds = 'all';  }  ?>
					<?php if (isset($_GET['no_baths'])) { $no_baths = $_GET['no_baths'];  } else { $no_baths = 'all';  }  ?>
					<?php if (isset($_GET['size_min'])) { $size_min = $_GET['size_min'];  } else { $size_min = '';  } ?>
					<?php if (isset($_GET['size_max'])) { $size_max = $_GET['size_max'];  } else { $size_max = '';  } ?>
    				<?php $options_features_amount = array("0","1","2","3","4","5","6","7","8","9","10+"); ?>
    				
    				<div class="features-filters">
    				
    					<label for="no_garages"><?php _e(get_option('woo_label_garages'), 'woothemes'); ?>:</label>
    					<select class="postform" id="no_garages" name="no_garages">
							<option <?php if ($no_garages == 'all') { ?>selected="selected"<?php }?> value="all"><?php _e('Any', 'woothemes') ?></option>
							<?php 
							foreach ($options_features_amount as $option) {
								?><option <?php if ($no_garages == $option) { ?>selected="selected"<?php }?> value="<?php echo $option; ?>"><?php echo $option; ?></option><?php 
							}
							?>
						</select>
						<label for="no_beds"><?php _e(get_option('woo_label_beds'), 'woothemes'); ?>:</label>
						<select class="postform" id="no_beds" name="no_beds">
							<option <?php if ($no_beds == 'all') { ?>selected="selected"<?php }?> value="all"><?php _e('Any', 'woothemes') ?></option>
							<?php 
							foreach ($options_features_amount as $option) {
								?><option <?php if ($no_beds == $option) { ?>selected="selected"<?php }?> value="<?php echo $option; ?>"><?php echo $option; ?></option><?php 
							}
							?>
						</select>
						<label for="no_baths"><?php _e(get_option('woo_label_baths_long'), 'woothemes'); ?>:</label>
						<select class="postform last" id="no_baths" name="no_baths">
							<option <?php if ($no_baths == 'all') { ?>selected="selected"<?php }?> value="all"><?php _e('Any', 'woothemes') ?></option>
							<?php 
							foreach ($options_features_amount as $option) {
								?><option <?php if ($no_baths == $option) { ?>selected="selected"<?php }?> value="<?php echo $option; ?>"><?php echo $option; ?></option><?php 
							}
							?>
						</select>
					
						<label for="size_min"><?php _e(get_option('woo_label_min_size'), 'woothemes'); ?> <?php echo '('.get_option('woo_label_size_metric').')'; ?>:</label><input type="text" class="text size validate_number" name="size_min" id="size_min" value="<?php if ( $size_min != '' ) { echo $size_min; } ?>" >	
						<label for="size_max"><?php _e(get_option('woo_label_max_size'), 'woothemes'); ?> <?php echo '('.get_option('woo_label_size_metric').')'; ?>:</label><input type="text" class="last text size validate_number" name="size_max" id="size_max" value="<?php if ( $size_max != '' ) { echo $size_max; } ?>" >
					</div><!-- /.size -->
						
    			</div><!-- /#advanced-search -->
    			
    			<div class="fix"></div>
    			
    			</div><!-- /.filters -->
    			
    			<?php 
    				$term_names = '';
    				$price_list = '';
    				$size_list = '';
    				//Taxonomies
    				$taxonomy_data_set = get_terms(array('location',/*'pricerange',*/'propertytype','propertyfeatures'), array('fields' => 'names'));
    				$taxonomy_data_set = woo_multidimensional_array_unique($taxonomy_data_set);
    				foreach ($taxonomy_data_set as $data_item) { 
    					//Convert string to UTF-8
						$str_converted = woo_encoding_convert($data_item);
						//Add category name to data string
						$term_names .= htmlspecialchars($str_converted, ENT_QUOTES, 'UTF-8').',';
    				}
    				//Post Custom Fields
    				$meta_data_fields = array('address');
    				$meta_data_set = woo_get_custom_post_meta_entries($meta_data_fields);
    				$meta_data_set = woo_multidimensional_array_unique($meta_data_set);
    				foreach ($meta_data_set as $data_item) { 
    					//Convert string to UTF-8
						$str_converted = woo_encoding_convert($data_item->meta_value);
						//Add category name to data string
						$term_names .= htmlspecialchars($str_converted, ENT_QUOTES, 'UTF-8').',';
    				}
    				$price_list = '';
					//Post Custom Fields
    				$meta_data_fields = array('price');
    				$meta_data_set = woo_get_custom_post_meta_entries($meta_data_fields);
    				$meta_data_set = woo_multidimensional_array_unique($meta_data_set);
    				foreach ($meta_data_set as $data_item) { 
    					//Convert string to UTF-8
						$str_converted = woo_encoding_convert($data_item->meta_value);
						//Add category name to data string
						$price_list .= htmlspecialchars($str_converted, ENT_QUOTES, 'UTF-8').',';
    				}
					//Post Custom Fields
    				$meta_data_fields = array('size');
    				$meta_data_set = woo_get_custom_post_meta_entries($meta_data_fields);
    				$meta_data_set = woo_multidimensional_array_unique($meta_data_set);
    				foreach ($meta_data_set as $data_item) { 
    					//Convert string to UTF-8
						$str_converted = woo_encoding_convert($data_item->meta_value);
						//Add category name to data string
						$size_list .= htmlspecialchars($str_converted, ENT_QUOTES, 'UTF-8').',';
    				}
    			?>
    						   			
    			<script>
  					jQuery(document).ready(function($) {
						
						<?php if ( ( ($no_garages == 'all') || ($no_garages == '') ) && ( ($no_beds == 'all') || ($no_beds == '') ) && ( ($no_baths == 'all') || ($no_baths == '') ) && ( $size_min == '' ) && ( $size_max == '' ) ) { ?>jQuery("#advanced-search").toggle();<?php } ?>
						
						jQuery(".advanced-search-button").click(function(){
							var hidetext = 'Hide <?php echo get_option('woo_label_advanced_search'); ?>';
							var showtext = '<?php echo get_option('woo_label_advanced_search'); ?>';
							var currenttext = jQuery(".advanced-search-button").text();
							//toggle advanced search
							jQuery("#advanced-search").toggle();
							//toggle text
							if (currenttext == hidetext) {
								jQuery(".advanced-search-button").text(showtext);
								//reset search values
								jQuery("#no_garages").val('all');
								jQuery("#no_beds").val('all');
								jQuery("#no_baths").val('all');	
							}
							else {
								jQuery(".advanced-search-button").text(hidetext);
							}
						});
						//GET PHP data items
    					var keyworddataset = "<?php echo $term_names; ?>".split(",");
						var pricedataset = "<?php echo $price_list; ?>".split(",");
						var sizedataset = "<?php echo $size_list; ?>".split(",");
    					//Set autocomplete(s)
						$("#s-main").autocomplete(keyworddataset);
						$("#price_min").autocomplete(pricedataset);
						$("#price_max").autocomplete(pricedataset);
						$("#size_min").autocomplete(sizedataset);
						$("#size_max").autocomplete(sizedataset);
						//Handle autocomplete result
						$("#s").result(function(event, data, formatted) {
    						//Do Nothing
						});
						$("#price_min").result(function(event, data, formatted) {
    						//Do Nothing
						});
						$("#price_max").result(function(event, data, formatted) {
    						//Do Nothing
						});
						$("#size_min").result(function(event, data, formatted) {
    						//Do Nothing
						});
						$("#size_max").result(function(event, data, formatted) {
    						//Do Nothing
						});
							
 					});
  				</script>
 
    			
    			<div class="fix"></div>
    			
    		</form>
    		
    		<?php 
    		
			if ( $options['Activated'] && ( get_option('woo_idx_plugin_search') == 'true' ) ) {
			
				$pluginUrl = DSIDXPRESS_PLUGIN_URL;

				$formAction = get_bloginfo("url");
				if (substr($formAction, strlen($formAction), 1) != "/")
					$formAction .= "/";
				$formAction .= dsSearchAgent_Rewrite::GetUrlSlug();
				
			?>
			
    		<form name="property-mls-search" id="property-mls-search" method="get" action="<?php echo $formAction; ?>">
    			
    			<?php	

				$defaultSearchPanels = dsSearchAgent_ApiRequest::FetchData("AccountSearchPanelsDefault", array(), false, 60 * 60 * 24);
				$defaultSearchPanels = $defaultSearchPanels["response"]["code"] == "200" ? json_decode($defaultSearchPanels["body"]) : null;

				$propertyTypes = dsSearchAgent_ApiRequest::FetchData("AccountSearchSetupPropertyTypes", array(), false, 60 * 60 * 24);
				$propertyTypes = $propertyTypes["response"]["code"] == "200" ? json_decode($propertyTypes["body"]) : null;

				$requestUri = dsSearchAgent_ApiRequest::$ApiEndPoint . "LocationsByType";
				//cities
				$location_cities = explode("\n", get_option('woo_idx_search_cities'));
				//communities
				$location_communities = explode("\n", get_option('woo_idx_search_communities'));
				//Tracts
				$location_tracts = explode("\n", get_option('woo_idx_search_tracts'));
				//Zips
				$location_zips = explode("\n", get_option('woo_idx_search_zips'));
				?>
				
				<div class="mls-property-type">
    				
					<label for="idx-q-PropertyTypes"><?php _e('Property Type', 'woothemes'); ?>:</label>
					<select name="idx-q-PropertyTypes" class="dsidx-search-widget-propertyTypes">
							<option value="All">- All property types -</option>
							<?php
							if (is_array($propertyTypes)) {
								foreach ($propertyTypes as $propertyType) {
									$name = htmlentities($propertyType->DisplayName);
									echo "<option value=\"{$propertyType->SearchSetupPropertyTypeID}\">{$name}</option>";
								}
							}
							?>
					</select>	
				
					<label for="idx-q-MlsNumbers"><?php _e('MLS #', 'woothemes'); ?>:</label>
						<input id="idx-q-MlsNumbers" name="idx-q-MlsNumbers" type="text" class="text" />
						
				</div>
				
				<div class="fix"></div>
					
				<div class="mls-area-details">
		
					<label for="idx-q-Cities"><?php _e('City', 'woothemes'); ?>:</label>
						<select id="idx-q-Cities" name="idx-q-Cities" class="small">
							<?php if (is_array($location_cities)) {
								foreach ($location_cities as $city) {
									$city_name = htmlentities(trim($city));
									echo "<option value=\"{$city_name}\">$city_name</option>";
								}
							} ?>
						</select>
				
					<label for="idx-q-Communities"><?php _e('Community', 'woothemes'); ?>:</label>
						<select id="idx-q-Communities" name="idx-q-Communities" class="small">
							<option value="">- Any -</option>
							<?php if (is_array($location_communities)) {
								foreach ($location_communities as $community) {
									$community_name = htmlentities(trim($community));
									echo "<option value=\"{$community_name}\">$community_name</option>";
								}
							} ?>
						</select>
					
					<label for="idx-q-TractIdentifiers"><?php _e('Tract', 'woothemes'); ?>:</label>
						<select id="idx-q-TractIdentifiers" name="idx-q-TractIdentifiers" class="small">
							<option value="">- Any -</option>
							<?php if (is_array($location_tracts)) {
								foreach ($location_tracts as $tract) {
									$tract_name = htmlentities(trim($tract));
									echo "<option value=\"{$tract_name}\">$tract_name</option>";
								}
							} ?>
						</select>
					
					<label for="idx-q-ZipCodes"><?php _e('Zip', 'woothemes'); ?>:</label>
						<select id="idx-q-ZipCodes" name="idx-q-ZipCodes" class="small">
							<option value="">- Any -</option>
							<?php if (is_array($location_zips)) {
								foreach ($location_zips as $zip) {
									$zip_name = htmlentities(trim($zip));
									echo "<option value=\"{$zip_name}\">$zip_name</option>";
								}
							} ?>
						</select>
				
				</div>
				
				<div class="fix"></div>
				
				<div class="mls-features">
					
					<label for="idx-q-PriceMin"><?php _e('Min Price', 'woothemes'); ?>:</label>
						<input id="idx-q-PriceMin" name="idx-q-PriceMin" type="text" class="text validate_number" />
					
					<label for="idx-q-PriceMax"><?php _e('Max Price', 'woothemes'); ?>:</label>
						<input id="idx-q-PriceMax" name="idx-q-PriceMax" type="text" class="text validate_number" />
					
					<label for="idx-q-ImprovedSqFtMin"><?php _e('Min Size', 'woothemes'); ?> <?php echo '(SQ FT)'; ?>:</label>
						<input id="idx-q-ImprovedSqFtMin" name="idx-q-ImprovedSqFtMin" type="text" class="text validate_number" />
				
					<label for="idx-q-BedsMin"><?php _e('Beds', 'woothemes'); ?>:</label>
						<input id="idx-q-BedsMin" name="idx-q-BedsMin" type="text" class="text validate_number" />
					
					<label for="idx-q-BathsMin"><?php _e('Baths', 'woothemes'); ?>:</label>
						<input id="idx-q-BathsMin" name="idx-q-BathsMin" type="text" class="text validate_number" />
						
				</div>
				
				<input type="submit" value="Search" class="submit button" />
				
		<?php
		if($options["HasSearchAgentPro"] == "yes"){
			
					echo 'try our&nbsp;<a href="'.$formAction.'advanced/"><img src="'.$pluginUrl.'assets/adv_search-16.png" /> Advanced Search</a>';
		}
		?>
				<div class="fix"></div>
				
    		</form>
		<?php } ?>
    	</div><!-- /#search -->