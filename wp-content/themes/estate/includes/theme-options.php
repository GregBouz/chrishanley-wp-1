<?php

add_action( 'admin_head','woo_options' );  

if ( !function_exists( 'woo_options' ) ) :
function woo_options() {
	
// VARIABLES
$themename = "Estate";
$manualurl = 'http://www.woothemes.com/support/theme-documentation/estate/';
$shortname = "woo";

$GLOBALS['template_path'] = get_bloginfo('template_directory');

//Access the WordPress Categories via an Array
$woo_categories = array();  
$woo_categories_obj = get_categories('hide_empty=0');
foreach ($woo_categories_obj as $woo_cat) {
    $woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;}
$categories_tmp = array_unshift($woo_categories, "Select a category:");    
       
//Access the WordPress Pages via an Array
$woo_pages = array();
$woo_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($woo_pages_obj as $woo_page) {
    $woo_pages[$woo_page->ID] = $woo_page->post_name; }
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");       

// Estate Options
$options_features_amount = array("0","1","2","3","4","5","6","7","8","9","10+");

// Estate Agent Capabilities
$agent_default_roles = new WP_Roles();
$agent_roles_list = array();  
$agent_roles_list_obj = $agent_default_roles->role_names;
foreach ($agent_roles_list_obj as $key => $value) {
    $agent_roles_list[$key] = $key; }
$agent_roles_list_tmp = array_unshift($agent_roles_list, "Select a Role:"); 

// Estate Matching radio box
$options_matching_method = array("exact" => "Exact Match","minimum" => "Minimum Value"); 

// Image Alignment radio box
$options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 

// Image Links to Options
$options_image_link_to = array("image" => "The Image","post" => "The Post"); 

//Testing 
$options_select = array("one","two","three","four","five"); 
$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five"); 

//URL Shorteners
if (_iscurlinstalled()) {
	$options_select = array("Off","TinyURL","Bit.ly");
	$short_url_msg = 'Select the URL shortening service you would like to use.'; 
} else {
	$options_select = array("Off");
	$short_url_msg = '<strong>cURL was not detected on your server, and is required in order to use the URL shortening services.</strong>'; 
}

//Stylesheets Reader
$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//More Options


$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$slider_image = array("Left","Right");
$home_layout = array("With sidebar","Without Sidebar");
$more_entries = array("Select a number:","3","6","9","12");
$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

$zoom = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$colors = array('blue'=>'Blue','red'=>'Red','green'=>'Green','yellow'=>'Yellow','pink'=>'Pink','purple'=>'Purple','teal'=>'Teal','white'=>'White','black'=>'Black');                    

function category_nav($options,$colors) {

     $options[] = array(    "name" =>  "Colored & Custom Markers",
					"icon" => "maps",
        "type" => "heading");  
    
    $options[] = array(    "name" =>  "Marker Pin Color",
                        "desc" => "Choose from a preset colored pin.",
                        "id" => "woo_cat_colors_pages",
                        "std" => "red",
                        "type" => "select2",
                        "options" => $colors);
    $options[] = array(    
            			"name" =>  "",
                        "desc" => "Add a custom image. Find more <a href='http://groups.google.com/group/google-chart-api/web/chart-types-for-map-pins?pli=1'>here</a>.",
                        "id" => "woo_cat_custom_marker_pages",
                        "std" => "",
                        "class" => "hidden",
                        "type" => "text");  

    $cats = get_categories('hide_empty=0');

    foreach ($cats as $cat) {

            $options[] = array(    "name" =>  $cat->cat_name,
                        "desc" => "Choose from a preset colored pin.",
                        "id" => "woo_cat_colors_".$cat->cat_ID,
                        "std" => "red",
                        "type" => "select2",
                        "class" => "hidden",
                        "options" => $colors);
            $options[] = array(    
            			"name" =>  "",
                        "desc" => "Add a custom image. Find more <a href='http://groups.google.com/group/google-chart-api/web/chart-types-for-map-pins?pli=1'>here</a>.",
                        "id" => "woo_cat_custom_marker_".$cat->cat_ID,
                        "std" => "",
                        "class" => "hidden",
                        "type" => "text");
                                   
    
    }

    return $options;
}

// THIS IS THE DIFFERENT FIELDS
$options = array();   

$options[] = array( "name" => "General Settings",
					"icon" => "general",
                    "type" => "heading");
                        
$options[] = array( "name" => "Theme Stylesheet",
					"desc" => "Select your themes alternative color scheme.",
					"id" => $shortname."_alt_stylesheet",
					"std" => "default.css",
					"type" => "select",
					"options" => $alt_stylesheets);

$options[] = array( "name" => "Custom Logo",
					"desc" => "Upload a logo for your theme, or specify an image URL directly.",
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload");    
                                                                                     
$options[] = array( "name" => "Text Title",
					"desc" => "Enable if you want Blog Title and Tagline to be text-based. Setup title/tagline in WP -> Settings -> General.",
					"id" => $shortname."_texttitle",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px <a href='http://www.faviconr.com/'>ico image</a> that will represent your website's favicon.",
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload"); 
                                               
$options[] = array( "name" => "Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");        

$options[] = array( "name" => "RSS URL",
					"desc" => "Enter your preferred RSS URL. (Feedburner or other)",
					"id" => $shortname."_feed_url",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "E-Mail URL",
					"desc" => "Enter your preferred E-mail subscription URL. (Feedburner or other)",
					"id" => $shortname."_subscribe_email",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Contact Form E-Mail",
					"desc" => "Enter your E-mail address to use on the Contact Form Page Template. Add the contact form by adding a new page and selecting 'Contact Form' as page template.",
					"id" => $shortname."_contactform_email",
					"std" => "",
					"type" => "text");



$options[] = array( "name" => "Custom CSS",
                    "desc" => "Quickly add some CSS to your theme by adding it to this block.",
                    "id" => $shortname."_custom_css",
                    "std" => "",
                    "type" => "textarea");

$options[] = array( "name" => "Post/Page Comments",
					"desc" => "Select if you want to enable/disable comments on posts and/or pages. ",
					"id" => $shortname."_comments",
					"type" => "select2",
					"options" => array("post" => "Posts Only", "page" => "Pages Only", "both" => "Pages / Posts", "" => "None") );                                                          

$options[] = array( "name" => "Pagination Style",
					"desc" => "Select the style of pagination you would like to use on the blog.",
					"id" => $shortname."_pagination_type",
					"type" => "select2",
					"options" => array( "paginated_links" => "Numbers", "simple" => "Next/Previous" ) );
					    
$options[] = array( "name" => "Styling Options",
					"icon" => "styling",
					"type" => "heading");   
					
$options[] = array( "name" =>  "Body Background Color",
					"desc" => "Pick a custom color for background color of the theme e.g. #697e09",
					"id" => "woo_body_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" => "Body background image",
					"desc" => "Upload an image for the theme's background",
					"id" => $shortname."_body_img",
					"std" => "",
					"type" => "upload");
					
$options[] = array( "name" => "Background image repeat",
                    "desc" => "Select how you would like to repeat the background-image",
                    "id" => $shortname."_body_repeat",
                    "std" => "no-repeat",
                    "type" => "select",
                    "options" => $body_repeat);

$options[] = array( "name" => "Background image position",
                    "desc" => "Select how you would like to position the background",
                    "id" => $shortname."_body_pos",
                    "std" => "top",
                    "type" => "select",
                    "options" => $body_pos);

$options[] = array( "name" =>  "Link Color",
					"desc" => "Pick a custom color for links or add a hex color code e.g. #697e09",
					"id" => "woo_link_color",
					"std" => "",
					"type" => "color");   

$options[] = array( "name" =>  "Link Hover Color",
					"desc" => "Pick a custom color for links hover or add a hex color code e.g. #697e09",
					"id" => "woo_link_hover_color",
					"std" => "",
					"type" => "color");                    

$options[] = array( "name" =>  "Button Color",
					"desc" => "Pick a custom color for buttons or add a hex color code e.g. #697e09",
					"id" => "woo_button_color",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => "Header Contact Info",
					"icon" => "header",
					"type" => "heading");

$options[] = array( "name" => "Company Name",
                    "desc" => "Specify the name that will be displayed in the header.",
                    "id" => $shortname."_header_company_name",
                    "std" => "Estate Property Group",
                    "type" => "text");

$options[] = array( "name" => "Company Contact Number",
                    "desc" => "Specify the contact number that will be displayed in the header.",
                    "id" => $shortname."_header_company_contact_number",
                    "std" => "+27 21 555 5555",
                    "type" => "text");

$options[] = array( "name" => "Company Email Address",
                    "desc" => "Specify the email address that will be displayed in the header.",
                    "id" => $shortname."_header_company_email",
                    "std" => "info@example.com",
                    "type" => "text");
                                        
$options[] = array( "name" => "Layouts",
					"icon" => "layout",
					"type" => "heading");
					
$options[] = array(    "name" => "Homepage layout",
                    "desc" => "Select a layout for your homepage",
                    "id" => $shortname."_home_layout",
                    "std" => "With sidebar",
                    "type" => "select",
                    "options" => $home_layout);
				
$options[] = array(    "name" => "Property Singlepage layout",
                    "desc" => "Select a layout for your singlepage",
                    "id" => $shortname."_property_single_layout",
                    "std" => "Without sidebar",
                    "type" => "select",
                    "options" => $home_layout);  

$options[] = array(    "name" => "Property Archivepage layout",
                    "desc" => "Select a layout for your property taxonomy archivepage (Locations, Property Types, Additional Features)",
                    "id" => $shortname."_property_archive_layout",
                    "std" => "Without sidebar",
                    "type" => "select",
                    "options" => $home_layout);  

$options[] = array( 	"name" => "Property Searchpage layout",
                    "desc" => "Select a layout for your searchpage",
                    "id" => $shortname."_property_search_layout",
                    "std" => "Without sidebar",
                    "type" => "select",
                    "options" => $home_layout);

$options[] = array( "name" => "Featured Panel",
					"icon" => "featured",
					"type" => "heading");
					
$options[] = array( "name" => "Enable Featured Panel",
					"desc" => "Show the featured panel on the front page.",
					"id" => $shortname."_featured",
					"std" => "false",
					"type" => "checkbox");  

$options[] = array( "name" => "Featured Panel Title",
                    "desc" => "Include a short title for your featured panel on the home page, e.g. Featured Properties.",
                    "id" => $shortname."_featured_header",
                    "std" => "Featured Properties",
                    "type" => "text");
                    
$options[] = array( "name" => "Featured Tag",
                    "desc" => "Add comma separated list for the tags that you would like to have displayed in the featured section on your homepage. For example, if you add 'tag1, tag3' here, then all properties tagged with either 'tag1' or 'tag3' will be shown in the featured area.",
                    "id" => $shortname."_featured_tags",
                    "std" => "",
                    "type" => "text");

$options[] = array(    "name" => "Featured Entries",
                    "desc" => "Select the number of property entries that should appear in the Featured panel.",
                    "id" => $shortname."_featured_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries);
                    
$options[] = array(    "name" => "Slider Image Position",
                    "desc" => "Select the alignment for the featured slider image",
                    "id" => $shortname."_slider_image",
                    "std" => "Left",
                    "type" => "select",
                    "options" => $slider_image);   

$options[] = array(    "name" => "Auto Start",
                    "desc" => "Set the slider to start sliding automatically. Adjust the speed of sliding underneath.",
                    "id" => $shortname."_slider_auto",
                    "std" => "false",
                    "type" => "checkbox");   

$options[] = array(    "name" => "Animation Speed",
                    "desc" => "The time in <b>seconds</b> the animation between frames will take e.g. 0.6",
                    "id" => $shortname."_slider_speed",
                    "std" => 0.6,
					"type" => "select",
					"options" => array( '0.0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1.0', '1.1', '1.2', '1.3', '1.4', '1.5', '1.6', '1.7', '1.8', '1.9', '2.0' ) );
                    
$options[] = array(    "name" => "Auto Slide Interval",
                    "desc" => "The time in <b>seconds</b> each slide pauses for, before sliding to the next. Only when using Auto Start option above.",
                    "id" => $shortname."_slider_interval",
					"std" => "4",
					"type" => "select",
					"options" => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' ) );

$options[] = array( "name" => "More Properties Panel",
					"icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => "More Properties Title",
                    "desc" => "Include a short title for the More Properties section on the home page, e.g. More Properties.",
                    "id" => $shortname."_more_header",
                    "std" => "More Properties",
                    "type" => "text");
                    
$options[] = array(    "name" => "More Entries",
                    "desc" => "Select the number of entries that should appear in the Featured panel.",
                    "id" => $shortname."_more_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $more_entries);
                    
$options[] = array( "name" => "Enable More Properties Link",
					"desc" => "Show the archives link below the more properties section.",
					"id" => $shortname."_archives_link",
					"std" => "true",
					"type" => "checkbox"); 
					
$options[] = array( "name" => "Property Labels",
					"icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => "Property Prefix Symbol",
                    "desc" => "Specify the prefix that will be attached to a property to give it a unique number that can be used in the search.",
                    "id" => $shortname."_estate_property_prefix",
                    "std" => "PROP",
                    "type" => "text");
					
$options[] = array( "name" => "Currency Symbol",
                    "desc" => "Specify the currency that your properties price will be shown in.",
                    "id" => $shortname."_estate_currency",
                    "std" => "$",
                    "type" => "text");

$options[] = array( "name" => "On Show Label",
                    "desc" => "Specify the text that will be displayed on the On Show property labels.",
                    "id" => $shortname."_label_on_show",
                    "std" => "On Show",
                    "type" => "text");
                    
$options[] = array( "name" => "Garages Plural Label",
                    "desc" => "Specify the text that will be displayed on the frontend for more than one Garage.",
                    "id" => $shortname."_label_garages",
                    "std" => "Garages",
                    "type" => "text");                    

$options[] = array( "name" => "Garages Singular Label",
                    "desc" => "Specify the text that will be displayed on the frontend for a single Garage.",
                    "id" => $shortname."_label_garage",
                    "std" => "Garage",
                    "type" => "text");  
                    
$options[] = array( "name" => "Unit of Measure Label",
                    "desc" => "Specify the text that will be displayed on the frontend for the Unit of Measure.",
                    "id" => $shortname."_label_size_metric",
                    "std" => "sq ft",
                    "type" => "text");
                     
$options[] = array( "name" => "Beds Plural Label",
                    "desc" => "Specify the text that will be displayed on the frontend for more than one Bed.",
                    "id" => $shortname."_label_beds",
                    "std" => "Beds",
                    "type" => "text");                    

$options[] = array( "name" => "Beds Singular Label",
                    "desc" => "Specify the text that will be displayed on the frontend for a single Bed.",
                    "id" => $shortname."_label_bed",
                    "std" => "Bed",
                    "type" => "text"); 
                    
$options[] = array( "name" => "Baths Plural Label",
                    "desc" => "Specify the text that will be displayed on the frontend for more than one Baths.",
                    "id" => $shortname."_label_baths",
                    "std" => "Baths",
                    "type" => "text"); 

$options[] = array( "name" => "Bathrooms Plural Label",
                    "desc" => "Specify the text that will be displayed on the frontend search and backend for more than one Bathroom.",
                    "id" => $shortname."_label_baths_long",
                    "std" => "Bathrooms",
                    "type" => "text"); 
                    
$options[] = array( "name" => "Baths Singular Label",
                    "desc" => "Specify the text that will be displayed on the frontend for a single Bath.",
                    "id" => $shortname."_label_bath",
                    "std" => "Bath",
                    "type" => "text");
                    
$options[] = array( "name" => "Property Icons",
					"icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => "Garages Big Icon",
					"desc" => "Upload a big icon for property garages, or specify the image address of your online icon. (http://yoursite.com/icon-big.png) <br/><strong>For best results use a 25px x 25px sized image.</strong>",
					"id" => $shortname."_garage_logo_big",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-garage-big.png",
					"type" => "upload"); 

$options[] = array( "name" => "Garages Small Icon",
					"desc" => "Upload a small icon for property garages, or specify the image address of your online icon. (http://yoursite.com/icon-small.png) <br/><strong>For best results use a 18px x 18px sized image.</strong>",
					"id" => $shortname."_garage_logo_small",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-garage-small.png",
					"type" => "upload"); 

$options[] = array( "name" => "Beds Big Icon",
					"desc" => "Upload a big icon for property beds, or specify the image address of your online icon. (http://yoursite.com/icon-big.png) <br/><strong>For best results use a 25px x 25px sized image.</strong>",
					"id" => $shortname."_bed_logo_big",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-bed-big.png",
					"type" => "upload"); 

$options[] = array( "name" => "Beds Small Icon",
					"desc" => "Upload a small icon for property beds, or specify the image address of your online icon. (http://yoursite.com/icon-small.png) <br/><strong>For best results use a 18px x 18px sized image.</strong>",
					"id" => $shortname."_bed_logo_small",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-bed-small.png",
					"type" => "upload"); 

$options[] = array( "name" => "Baths Big Icon",
					"desc" => "Upload a big icon for property baths, or specify the image address of your online icon. (http://yoursite.com/icon-big.png) <br/><strong>For best results use a 25px x 25px sized image.</strong>",
					"id" => $shortname."_bath_logo_big",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-bath-big.png",
					"type" => "upload"); 

$options[] = array( "name" => "Baths Small Icon",
					"desc" => "Upload a small icon for property baths, or specify the image address of your online icon. (http://yoursite.com/icon-small.png) <br/><strong>For best results use a 18px x 18px sized image.</strong>",
					"id" => $shortname."_bath_logo_small",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-bath-small.png",
					"type" => "upload"); 
										
$options[] = array( "name" => "Size Big Icon",
					"desc" => "Upload a big icon for the property size, or specify the image address of your online icon. (http://yoursite.com/icon-big.png) <br/><strong>For best results use a 25px x 25px sized image.</strong>",
					"id" => $shortname."_size_logo_big",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-size-big.png",
					"type" => "upload"); 

$options[] = array( "name" => "Size Small Icon",
					"desc" => "Upload a small icon for the property size, or specify the image address of your online icon. (http://yoursite.com/icon-small.png) <br/><strong>For best results use a 18px x 18px sized image.</strong>",
					"id" => $shortname."_size_logo_small",
					"std" => get_bloginfo('template_directory')."/images/ico-property/ico-size-small.png",
					"type" => "upload"); 
										
$options[] = array( "name" => "Property Search",
					"icon" => "misc",
					"type" => "heading");
					
$options[] = array( "name" => "Search box Title",
                    "desc" => "Include a short title for the search box on the home page, e.g. Search Our Properties.",
                    "id" => $shortname."_search_header",
                    "std" => "Search Our Properties",
                    "type" => "text");

$options[] = array( "name" => "Search Keyword Text",
                    "desc" => "Default text that is displayed in the search textbox.",
                    "id" => $shortname."_search_keyword_text",
                    "std" => "Search...",
                    "type" => "text");
                    
$options[] = array( "name" => "Display Searchbox on Single property pages",
					"desc" => "Enable if you want the searchbox to be displayed when viewing single properties.",
					"id" => $shortname."_displaysearch_single",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Search Results",
                    "desc" => "Select the number of entries that should appear on the search results page.",
                    "id" => $shortname."_property_search_results",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries);

$options[] = array( "name" => "Search by Features Matching Method",
					"desc" => "Choose the matching method for Search Results. <br /><strong>Exact Match</strong> means only properties with the same number of baths, beds, garages searched for will be returned while <strong>Minimum Value</strong> means that all properties with at least the amount of baths, beds, garages searched for will be returned.",
					"id" => $shortname."_feature_matching_method",
					"std" => "exact",
					"type" => "radio",
					"options" => $options_matching_method); 

$options[] = array( "name" => "Sale Type Label",
                    "desc" => "Specify the text that will be displayed on the frontend search and backend for Sale Type.",
                    "id" => $shortname."_label_sale_type",
                    "std" => "Sale Type",
                    "type" => "text"); 
                    
$options[] = array( "name" => "For Sale Label",
                    "desc" => "Specify the text that will be displayed on the frontend search and backend for the For Sale text.",
                    "id" => $shortname."_label_for_sale",
                    "std" => "For Sale",
                    "type" => "text"); 
                    
$options[] = array( "name" => "For Rent Label",
                    "desc" => "Specify the text that will be displayed on the frontend search and backend for the For Rent text.",
                    "id" => $shortname."_label_for_rent",
                    "std" => "For Rent",
                    "type" => "text"); 

$options[] = array( "name" => "Property Location & Type Label",
                    "desc" => "Specify the text that will be displayed on the frontend search for the Property Location & Type label.",
                    "id" => $shortname."_label_property_location_and_type",
                    "std" => "Property Location &amp; Type",
                    "type" => "text"); 
                    
$options[] = array( "name" => "Locations Dropdown View All Label",
                    "desc" => "Specify the text that will be displayed on the frontend search Locations dropdown View All option.",
                    "id" => $shortname."_label_locations_dropdown_view_all",
                    "std" => "View all Locations",
                    "type" => "text"); 

$options[] = array( "name" => "Property Type Dropdown View All Label",
                    "desc" => "Specify the text that will be displayed on the frontend search Property Type dropdown View All option.",
                    "id" => $shortname."_label_property_type_dropdown_view_all",
                    "std" => "View all Property Types",
                    "type" => "text"); 
                    
$options[] = array( "name" => "Min Price Label",
                    "desc" => "Specify the text that will be displayed on the frontend search for the Min Price label.",
                    "id" => $shortname."_label_min_price",
                    "std" => "Min Price",
                    "type" => "text"); 
                    
$options[] = array( "name" => "Max Price Label",
                    "desc" => "Specify the text that will be displayed on the frontend search for the Max Price label.",
                    "id" => $shortname."_label_max_price",
                    "std" => "Max Price",
                    "type" => "text"); 

$options[] = array( "name" => "Advanced Search Button Label",
                    "desc" => "Specify the text that will be displayed on the frontend search Advanced Search Button.",
                    "id" => $shortname."_label_advanced_search",
                    "std" => "Advanced Search",
                    "type" => "text"); 
                    
$options[] = array( "name" => "Min Size Label",
                    "desc" => "Specify the text that will be displayed on the frontend search for the Min Size label.",
                    "id" => $shortname."_label_min_size",
                    "std" => "Min Size",
                    "type" => "text"); 
                    
$options[] = array( "name" => "Max Size Label",
                    "desc" => "Specify the text that will be displayed on the frontend search for the Max Size label.",
                    "id" => $shortname."_label_max_size",
                    "std" => "Max Size",
                    "type" => "text"); 
                                                                                
/*-----------------------------------------------------------------------------------*/
/* dsIDXpress Plugin Settings */
/*-----------------------------------------------------------------------------------*/                   
if (defined('DSIDXPRESS_OPTION_NAME')) {
	$idx_options = get_option(DSIDXPRESS_OPTION_NAME);
	$pluginUrl = DSIDXPRESS_PLUGIN_URL;
} else {
	$idx_options = array('Activated' => false);
}
if ($idx_options['Activated']) {

	$options[] = array( "name" => "dsIDXpress Plugin Integration",
						"type" => "heading");
	
	$options[] = array( "name" => "Enable the IDX Plugin Search",
						"desc" => "Enable if you want the searchbox to show the option to search the MLS database.",
						"id" => $shortname."_idx_plugin_search",
						"std" => "false",
						"type" => "checkbox");

	$options[] = array( "name" => "Search box Title",
                    	"desc" => "Include a short title for the search box on the home page, e.g. Search the MLS.",
                    	"id" => $shortname."_search_mls_header",
                    	"std" => "Search the MLS",
                    	"type" => "text");
                    	
	$options[] = array( "name" => "Cities",
						"desc" => "Add the cities that you want to show up in the search options. <strong>ONE city per line</strong>.<br /><a target='_blank' href='$pluginUrl/locations.php?type=city'>Click here for a list of cities</a>.",
						"id" => $shortname."_idx_search_cities",
						"std" => "",
						"type" => "textarea"); 
	
	$options[] = array( "name" => "Communities",
						"desc" => "Add the communities that you want to show up in the search options. <strong>ONE community per line</strong>.<br /><a target='_blank' href='$pluginUrl/locations.php?type=community'>Click here for a list of communities</a>.",
						"id" => $shortname."_idx_search_communities",
						"std" => "",
						"type" => "textarea"); 
	
	$options[] = array( "name" => "Tracts",
						"desc" => "Add the tracts that you want to show up in the search options. <strong>ONE tract per line</strong>.<br /><a target='_blank' href='$pluginUrl/locations.php?type=tract'>Click here for a list of tracts</a>.",
						"id" => $shortname."_idx_search_tracts",
						"std" => "",
						"type" => "textarea"); 
	
	$options[] = array( "name" => "Zips",
						"desc" => "Add the zips that you want to show up in the search options. <strong>ONE zip per line</strong>.<br /><a target='_blank' href='$pluginUrl/locations.php?type=zip'>Click here for a list of zips</a>.",
						"id" => $shortname."_idx_search_zips",
						"std" => "",
						"type" => "textarea");
						 				
}
					
$options[] = array( "name" => "Single Property Page",
					"icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => "On Show Label",
                    "desc" => "Specify the text that will be displayed below the address for the property if the property is on show.",
                    "id" => $shortname."_label_property_details_on_show",
                    "std" => "This property is currently on show",
                    "type" => "text");
                    
$options[] = array( "name" => "Additional Features Label",
                    "desc" => "Specify the text that will be displayed above the Additional Features for the property.",
                    "id" => $shortname."_label_additional_features",
                    "std" => "Features",
                    "type" => "text");
                    
$options[] = array( "name" => "Additional Features Links",
					"desc" => "Enable if you want the Features panel on a single property page to have links to their taxonomy archive page.",
					"id" => $shortname."_clickable_additional_features",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Gallery Label",
                    "desc" => "Specify the text that will be displayed above the Gallery for the property.",
                    "id" => $shortname."_label_gallery",
                    "std" => "Gallery",
                    "type" => "text");
 
$options[] = array( "name" => "Virtual Tour Label",
                    "desc" => "Specify the text that will be displayed above the Virtual Tour for the property.",
                    "id" => $shortname."_label_virtual_tour",
                    "std" => "Virtual Tour",
                    "type" => "text");

$options[] = array( "name" => "Property Map Label",
                    "desc" => "Specify the text that will be displayed above the Google Map for the property.",
                    "id" => $shortname."_label_property_map",
                    "std" => "Property Map",
                    "type" => "text");
                    
$options[] = array( "name" => "Related Properties Tour Label",
                    "desc" => "Specify the text that will be displayed above the Related Properties.",
                    "id" => $shortname."_label_related_properties",
                    "std" => "More properties in this area",
                    "type" => "text");
                                                                               					
$options[] = array( "name" => "Contact Agent Button Label",
                    "desc" => "Specify the text that will be displayed on the Contact Agent button.",
                    "id" => $shortname."_label_contact_agent_button",
                    "std" => "Contact Agent",
                    "type" => "text");  

$options[] = array( "name" => "Email Agent Label",
                    "desc" => "Specify the text that will be displayed on the link to email the Agent.",
                    "id" => $shortname."_label_agent_email_link",
                    "std" => "Email this agent",
                    "type" => "text");

$options[] = array( "name" => "Contact Agent using Contact Form",
					"desc" => "Will use the contact form as the means to contact the properties agent instead of opening your email client.",
					"id" => $shortname."_contact_form_link",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Contact Form Page",
                    "desc" => "Select the page that your Contact Form is on. Remember to apply the Contact Form page template to this page.",
                    "id" => $shortname."_contact_form_page",
                    "std" => "",
                    "type" => "select",
                    "options" => $woo_pages);  
                    															
$options[] = array( "name" => "Agent Setup",
					"icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => "New User Role",
					"desc" => "Create new User Role for Agents.",
					"id" => $shortname."_agent_user_role_enable",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Agent Role Name",
                    "desc" => "Specify the name that will be used to identify Agents.",
                    "id" => $shortname."_agent_role_name",
                    "std" => "Agent",
                    "type" => "text");

$options[] = array( "name" => "Agent Default Capabilities",
					"desc" => "Select the default user capabilities for an agent.",
					"id" => $shortname."_agent_role_default",
					"std" => "Select a Role:",
					"type" => "select",
					"options" => $agent_roles_list);
					
$options[] = array( "name" => "Maps",
					"icon" => "maps",
				    "type" => "heading");    

$options[] = array( "name" => "Google Maps API Key",
					"desc" => "Enter your Google Maps API key before using any of Postcard's mapping functionality. <a href='http://code.google.com/apis/maps/signup.html'>Signup for an API key here</a>.",
					"id" => $shortname."_maps_apikey",
					"std" => "",
					"class" => "hidden",
					"type" => "text"); 
					
$options[] = array( "name" => "Disable Mousescroll",
					"desc" => "Turn off the mouse scroll action for all the Google Maps on the site. This could improve usability on your site.",
					"id" => $shortname."_maps_scroll",
					"std" => "",
					"type" => "checkbox");

$options[] = array( "name" => "Single Page Map Height",
					"desc" => "Height in pixels for the maps displayed on Single.php pages.",
					"id" => $shortname."_maps_single_height",
					"std" => "250",
					"type" => "text");

$options[] = array( "name" => "Enable Latitude & Longitude Coordinates:",
					"desc" => "Enable or disable coordinates in the head of single posts pages.",
					"id" => $shortname."_coords",
					"std" => "true",
					"type" => "checkbox");
					
$options[] = array( "name" => "Default Map Zoom Level",
					"desc" => "Set this to adjust the default in the post & page edit backend.",
					"id" => $shortname."_maps_default_mapzoom",
					"std" => "9",
					"type" => "select2",
					"options" => $zoom);

$options[] = array( "name" => "Default Map Type",
					"desc" => "Set this to the default rendered in the post backend.",
					"id" => $shortname."_maps_default_maptype",
					"std" => "Normal",
					"type" => "select2",
					"options" => array('G_NORMAL_MAP' => 'Normal','G_SATELLITE_MAP' => 'Satellite','G_HYBRID_MAP' => 'Hybrid','G_PHYSICAL_MAP' => 'Terrain'));

$options = category_nav($options,$colors);

$options[] = array( "name" => "Navigation Options",
					"icon" => "nav",
					"type" => "heading");    

$options[] = array( "name" => "Display Locations Taxonomy in Secondary Menu area:",
					"desc" => "Enabling this will output your Property Locations as menu links to their archive pages.<br /><strong> Note: If you enable the WordPress Menu Management option below, this option will not be outputted.</strong>",
					"id" => $shortname."_location_menu_items",
					"std" => "true",
					"type" => "checkbox");

$options[] = array( "name" => "Display Property Types Taxonomy in Secondary Menu area:",
					"desc" => "Enabling this will output your Property Types as menu links to their archive pages.<br /><strong> Note: If you enable the WordPress Menu Management option below, this option will not be outputted.</strong>",
					"id" => $shortname."_propertytype_menu_items",
					"std" => "true",
					"type" => "checkbox");

$options[] = array( "name" => "Display Post Categories Taxonomy in Secondary Menu area:",
					"desc" => "Enabling this will output your Post Categories as menu links to their archive pages.<br /><strong> Note: If you enable the WordPress Menu Management option below, this option will not be outputted.</strong>",
					"id" => $shortname."_category_menu_items",
					"std" => "true",
					"type" => "checkbox");

if ( !function_exists('wp_nav_menu') ) {										
	
	$options[] = array( "name" => "Exclude Pages from Top Navigation",
						"desc" => "Enter a comma-separated list of <a href='http://support.wordpress.com/pages/8/'>ID's</a> that you'd like to exclude from the top navigation. (e.g. 12,23,27,44)",
						"id" => $shortname."_pages_exclude",
						"std" => "",
						"type" => "text");
					
	$options[] = array( "name" => "Exclude Categories from Main Navigation",
						"desc" => "Enter a comma-separated list of <a href='http://support.wordpress.com/pages/8/'>ID's</a> that you'd like to exclude from the main navigation. (e.g. 12,23,27,44)",
						"id" => $shortname."_cats_exclude",
						"std" => "",
						"type" => "text");

}
 					                   
$options[] = array( "name" => "Dynamic Images",
					"type" => "heading",
					"icon" => "image");    
				    				   
$options[] = array( "name" => 'Dynamic Image Resizing',
					"desc" => "",
					"id" => $shortname."_wpthumb_notice",
					"std" => 'There are two alternative methods of dynamically resizing the thumbnails in the theme, <strong>WP Post Thumbnail</strong> or <strong>TimThumb - Custom Settings panel</strong>. We recommend using WP Post Thumbnail option.',
					"type" => "info");					

$options[] = array( "name" => "WP Post Thumbnail",
					"desc" => "Use WordPress post thumbnail to assign a post thumbnail. Will enable the <strong>Featured Image panel</strong> in your post sidebar where you can assign a post thumbnail.",
					"id" => $shortname."_post_image_support",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox" );

$options[] = array( "name" => "WP Post Thumbnail - Dynamic Image Resizing",
					"desc" => "The post thumbnail will be dynamically resized using native WP resize functionality. <em>(Requires PHP 5.2+)</em>",
					"id" => $shortname."_pis_resize",
					"std" => "true",
					"class" => "hidden",
					"type" => "checkbox" );

$options[] = array( "name" => "WP Post Thumbnail - Hard Crop",
					"desc" => "The post thumbnail will be cropped to match the target aspect ratio (only used if 'Dynamic Image Resizing' is enabled).",
					"id" => $shortname."_pis_hard_crop",
					"std" => "true",
					"class" => "hidden last",
					"type" => "checkbox" );

$options[] = array( "name" => "TimThumb - Custom Settings Panel",
					"desc" => "This will enable the <a href='http://code.google.com/p/timthumb/'>TimThumb</a> (thumb.php) script which dynamically resizes images added through the <strong>custom settings panel below the post</strong>. Make sure your themes <em>cache</em> folder is writable. <a href='http://www.woothemes.com/2008/10/troubleshooting-image-resizer-thumbphp/'>Need help?</a>",
					"id" => $shortname."_resize",
					"std" => "true",
					"type" => "checkbox" );

$options[] = array( "name" => "Automatic Image Thumbnail",
					"desc" => "If no thumbnail is specifified then the first uploaded image in the post is used.",
					"id" => $shortname."_auto_img",
					"std" => "false",
					"type" => "checkbox" );
					                    
$options[] = array( "name" => "Thumbnail Image Dimensions",
					"desc" => "Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.",
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_thumb_w',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_thumb_h',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Height')
								  ));
                                                                                                
$options[] = array( "name" => "Thumbnail Image alignment",
					"desc" => "Select how to align your thumbnails with posts.",
					"id" => $shortname."_thumb_align",
					"std" => "alignleft",
					"type" => "radio",
					"options" => $options_thumb_align); 

$options[] = array( "name" => "Show thumbnail in Single Posts",
					"desc" => "Show the attached image in the single post page.",
					"id" => $shortname."_thumb_single",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => "Single Image Dimensions",
					"desc" => "Enter an integer value i.e. 250 for the image size. Max width is 576.",
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_single_w',
											'type' => 'text',
											'std' => 200,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_single_h',
											'type' => 'text',
											'std' => 200,
											'meta' => 'Height')
								  ));

$options[] = array( "name" => "Add thumbnail to RSS feed",
					"desc" => "Add the the image uploaded via your Custom Settings to your RSS feed",
					"id" => $shortname."_rss_thumb",
					"std" => "false",
					"type" => "checkbox");  
					
//Footer
$options[] = array( "name" => "Footer Customization",
					"icon" => "footer",
                    "type" => "heading");
					
					
$options[] = array( "name" => "Custom Affiliate Link",
					"desc" => "Add an affiliate link to the WooThemes logo in the footer of the theme.",
					"id" => $shortname."_footer_aff_link",
					"std" => "",
					"type" => "text");	
									
$options[] = array( "name" => "Enable Custom Footer (Left)",
					"desc" => "Activate to add the custom text below to the theme footer.",
					"id" => $shortname."_footer_left",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => "Custom Text (Left)",
					"desc" => "Custom HTML and Text that will appear in the footer of your theme.",
					"id" => $shortname."_footer_left_text",
					"std" => "<p></p>",
					"type" => "textarea");
						
$options[] = array( "name" => "Enable Custom Footer (Right)",
					"desc" => "Activate to add the custom text below to the theme footer. Enabling the custom right footer disables the WooThemes logo link, which means you can't use the affiliate link option above.",
					"id" => $shortname."_footer_right",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => "Custom Text (Right)",
					"desc" => "Custom HTML and Text that will appear in the footer of your theme.",
					"id" => $shortname."_footer_right_text",
					"std" => "<p></p>",
					"type" => "textarea");
							
/* Subscribe & Connect */
$options[] = array( "name" => "Subscribe & Connect",
					"type" => "heading",
					"icon" => "connect" );

$options[] = array( "name" => "Enable Subscribe & Connect - Single Post",
					"desc" => "Enable the subscribe & connect area on single posts. You can also add this as a <a href='".home_url()."/wp-admin/widgets.php'>widget</a> in your sidebar.",
					"id" => $shortname."_connect",
					"std" => 'false',
					"type" => "checkbox" );

$options[] = array( "name" => "Subscribe Title",
					"desc" => "Enter the title to show in your subscribe & connect area.",
					"id" => $shortname."_connect_title",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Text",
					"desc" => "Change the default text in this area.",
					"id" => $shortname."_connect_content",
					"std" => '',
					"type" => "textarea" );

$options[] = array( "name" => "Subscribe By E-mail ID (Feedburner)",
					"desc" => "Enter your <a href='http://www.woothemes.com/tutorials/how-to-find-your-feedburner-id-for-email-subscription/'>Feedburner ID</a> for the e-mail subscription form.",
					"id" => $shortname."_connect_newsletter_id",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => 'Subscribe By E-mail to MailChimp', 'woothemes',
					"desc" => 'If you have a MailChimp account you can enter the <a href="http://woochimp.heroku.com" target="_blank">MailChimp List Subscribe URL</a> to allow your users to subscribe to a MailChimp List.',
					"id" => $shortname."_connect_mailchimp_list_url",
					"std" => '',
					"type" => "text");

$options[] = array( "name" => "Enable RSS",
					"desc" => "Enable the subscribe and RSS icon.",
					"id" => $shortname."_connect_rss",
					"std" => 'true',
					"type" => "checkbox" );

$options[] = array( "name" => "Twitter URL",
					"desc" => "Enter your  <a href='http://www.twitter.com/'>Twitter</a> URL e.g. http://www.twitter.com/woothemes",
					"id" => $shortname."_connect_twitter",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Facebook URL",
					"desc" => "Enter your  <a href='http://www.facebook.com/'>Facebook</a> URL e.g. http://www.facebook.com/woothemes",
					"id" => $shortname."_connect_facebook",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "YouTube URL",
					"desc" => "Enter your  <a href='http://www.youtube.com/'>YouTube</a> URL e.g. http://www.youtube.com/woothemes",
					"id" => $shortname."_connect_youtube",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Flickr URL",
					"desc" => "Enter your  <a href='http://www.flickr.com/'>Flickr</a> URL e.g. http://www.flickr.com/woothemes",
					"id" => $shortname."_connect_flickr",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "LinkedIn URL",
					"desc" => "Enter your  <a href='http://www.www.linkedin.com.com/'>LinkedIn</a> URL e.g. http://www.linkedin.com/in/woothemes",
					"id" => $shortname."_connect_linkedin",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Delicious URL",
					"desc" => "Enter your <a href='http://www.delicious.com/'>Delicious</a> URL e.g. http://www.delicious.com/woothemes",
					"id" => $shortname."_connect_delicious",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Google+ URL",
					"desc" => "Enter your <a href='http://plus.google.com/'>Google+</a> URL e.g. https://plus.google.com/104560124403688998123/",
					"id" => $shortname."_connect_googleplus",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Enable Related Posts",
					"desc" => "Enable related posts in the subscribe area. Uses posts with the same <strong>tags</strong> to find related posts. Note: Will not show in the Subscribe widget.",
					"id" => $shortname."_connect_related",
					"std" => 'true',
					"type" => "checkbox" );
                     
                                              

// Add extra options through function
if ( function_exists("woo_options_add") )
	$options = woo_options_add($options);

if ( get_option('woo_template') != $options) update_option('woo_template',$options);      
if ( get_option('woo_themename') != $themename) update_option('woo_themename',$themename);   
if ( get_option('woo_shortname') != $shortname) update_option('woo_shortname',$shortname);
if ( get_option('woo_manual') != $manualurl) update_option('woo_manual',$manualurl);

// Estate Options
$options_property_selling_type = array("sale" => get_option('woo_label_for_sale'),"rent" => get_option('woo_label_for_rent')); 
$options_property_selling_metric = array("once" => "Once off", "week" => "Per Week", "month" => "Per Month");
                                     
// Woo Metabox Options
$woo_metaboxes = array();

/*$woo_metaboxes[] = array (	"name"  => "calendar",
							"label" => "Date",
							"type" => "calendar",
							"desc" => "Select a date");*/
			
$woo_metaboxes[] = array (	"name" => "image",
							"label" => "Image",
							"type" => "upload",
							"desc" => "Upload file here...");

if ( get_post_type() == 'woo_estate' || !get_post_type() ) {

$woo_metaboxes[] = array(   "name"  => "address",
		    				"std"  => "",
		    				"label" => "Physical Address",
		    				"type" => "text",
		    				"desc" => "Enter the physical address of the property.");

$woo_metaboxes[] = array(   "name"  => "price",
		    				"std"  => "",
		    				"label" => "Price in ".get_option('woo_estate_currency')."",
		    				"type" => "text",
		    				"desc" => "Enter the price of the property excluding the currency symbol.");

$woo_metaboxes[] = array(   "name"  => "size",
		    				"std"  => "",
		    				"label" => "Size in ".get_option('woo_label_size_metric')."",
		    				"type" => "text",
		    				"desc" => "Enter the size of the property excluding the Unit of Measure.");

$woo_metaboxes[] = array(   "name" => "garages",
                    		"label" => "Number of ".get_option('woo_label_garages'),
                    		"desc" => "Enter the number of ".get_option('woo_label_garages')." of the property.",
                    		"std" => "0",
                    		"type" => "select",
                    		"options" => $options_features_amount);
                    
$woo_metaboxes[] = array(   "name" => "beds",
                    		"label" => "Number of ".get_option('woo_label_beds'),
                    		"desc" => "Enter the number of ".get_option('woo_label_beds')." of the property.",
                    		"std" => "0",
                    		"type" => "select",
                    		"options" => $options_features_amount);

$woo_metaboxes[] = array(   "name" => "bathrooms",
                    		"label" => "Number of ".get_option('woo_label_baths_long'),
                    		"desc" => "Enter the number of ".get_option('woo_label_baths_long')." of the property.",
                    		"std" => "0",
                    		"type" => "select",
                    		"options" => $options_features_amount);
                    		                    		
$woo_metaboxes[] = array( 	"name" => "sale_type",
							"label" => get_option('woo_label_sale_type'),
							"desc" => "Specify if the property is for Sale or for Rent",
							"std" => "sale",
							"type" => "radio",
							"options" => $options_property_selling_type);   							

$woo_metaboxes[] = array(   "name" => "sale_metric",
                    		"label" => "Payment Frequency",
                    		"desc" => "How often do the payments get made?",
                    		"std" => "Once off",
                    		"type" => "select",
                    		"options" => $options_property_selling_metric);
							
$woo_metaboxes[] = array (	"name" => "on_show",
							"std" => "false",
							"label" => get_option('woo_label_on_show'),
							"type" => "checkbox",
							"desc" => "This house is currently on show.");

} // End Properties Metaboxes

$woo_metaboxes[] = array (	"name"  => "embed",
							"std"  => "",
							"label" => "Embed Code",
							"type" => "textarea",
							"desc" => "Add your video embed code here");
    
// Add extra metaboxes through function
if ( function_exists("woo_metaboxes_add") )
	$woo_metaboxes = woo_metaboxes_add($woo_metaboxes);
    
if ( get_option('woo_custom_template') != $woo_metaboxes) update_option('woo_custom_template',$woo_metaboxes);      

}
endif; // woo_options()

//Global options setup
add_action( 'init','woo_global_options' );
function woo_global_options(){
	// Populate WooThemes option in array for use in theme
	global $woo_options;
	$woo_options = get_option( 'woo_options' );
}

?>