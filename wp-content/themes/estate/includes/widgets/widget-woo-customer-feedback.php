<?php
/*---------------------------------------------------------------------------------*/
/* Customer Feedback Widget */
/*---------------------------------------------------------------------------------*/

class Woo_CustomerFeedback extends WP_Widget {

	function Woo_CustomerFeedback() {
		$widget_ops = array('description' => 'Use this widget to add your Customer Feedback as a widget.' );
		parent::WP_Widget(false, __('Woo - Customer Feedback Widget', 'woothemes'),$widget_ops);      
	}

	function widget($args, $instance) {  
		$title = $instance['title'];
		$customerquote = $instance['customerquote'];
		$customername = $instance['customername'];
		$customerlink = $instance['customerlink'];
		$upload = $instance['upload'];
		
        ?><div class="feedback-widget widget"><?php
			if($title != '') {
			?><h3><?php _e($title,'woothemes'); ?></h3><?php
			}
			 
			 ?>
			 
			<div class="outer">
					
					<div class="customer-quote"><em><?php echo $customerquote; ?></em></div>
					<div class="customer-details">
					
						<?php if (isset($upload) && $upload != '') { ?><div class="customer-image"><img src="<?php echo $upload; ?>" alt="Customer Image" width="40" /></span></div><?php } ?>
					
						<div class="customer-name">
							<h4><?php echo $customername; ?></h4>
							<a href="http://<?php echo str_replace('http://','',$customerlink); ?>" target="_blank"><?php echo $customerlink; ?></a>
						</div>

       				</div>
       				
       				<div class="fix"></div>
				
			</div>
		</div><?php
	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		$title = esc_attr($instance['title']);
		$customerquote = esc_attr($instance['customerquote']);
		$customername = esc_attr($instance['customername']);
		$customerlink = esc_attr($instance['customerlink']);
		$upload = esc_attr($instance['upload']);
		?>
		<script type="text/javascript">jQuery(document).ready(function(){ setupAdUploaders(); });</script>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','woothemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('customerquote'); ?>"><?php _e('Customer Quote:','woothemes'); ?></label>
           	<textarea id="<?php echo $this->get_field_id('customerquote'); ?>" name="<?php echo $this->get_field_name('customerquote'); ?>" class="widefat"><?php echo $customerquote; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('customername'); ?>"><?php _e('Customer Name:','woothemes'); ?></label>
           	<input type="text" name="<?php echo $this->get_field_name('customername'); ?>" value="<?php echo $customername; ?>" class="widefat" id="<?php echo $this->get_field_id('customername'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('customerlink'); ?>"><?php _e('Customer Website:','woothemes'); ?></label>
           	<input type="text" name="<?php echo $this->get_field_name('customerlink'); ?>" value="<?php echo $customerlink; ?>" class="widefat" id="<?php echo $this->get_field_id('customerlink'); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('upload'); ?>"><?php _e('Upload Customer Image','woothemes'); ?></label>
        <input type="text" name="<?php echo $this->get_field_name('upload'); ?>" value="<?php echo $upload; ?>" class="widefat upload-box" />
        <span class="button widget_image_upload_button" id="<?php echo $this->get_field_id('upload'); ?>">Upload Image</span>
        <?php if(!empty($upload)) { echo "<img class='woo-upload-start-image' id='image_". $this->get_field_id('upload') ."' src='". $upload . "' width='75' />"; } ?>
        </p>
        <?php
	}
} 

register_widget('Woo_CustomerFeedback');

add_action('admin_head', 'woo_widget_customer_feedback_head');

function woo_widget_customer_feedback_head() { 
	?>
    <style type="text/css">
		.woo-upload-nav { height:30px; margin-top:10px; }
		.woo-upload-nav li { float:left}
		.woo-upload-nav li.active a { background: #fff; color: #333 }
		.woo-upload-nav li  a { text-decoration: none;float:left;  width:25px; text-align:center; padding:4px 0; margin-right:4px; background:#f8f8f8; border:1px solid #e7e7e7; border-radius: 8px; 	-moz-border-radius:8px; -webkit-border-radius: 8px; }
		.woo-upload-crop { width:225px; overflow:hidden;border-top:dashed #ccc 1px; margin-top:10px;}
		.woo-upload-holder { width:9000px; }
		.woo-upload-piece { float:left; width:215px; padding:0 5px}
		.upload-box {margin-bottom:10px}
		.woo-upload-start-image, .woo-option-image { margin:10px 0; clear:both; display:block}
		.seperator { text-align:left; padding:2px 0; margin:15px 0 20px 0; border-bottom:2px solid #aaa;  font-weight:700; color: #888}
		.clear {clear:both}  
	</style>
    <?php
	//AJAX Upload
	?>
    <script type="text/javascript">
	
	jQuery(document).ready(function(){
		
		jQuery('.woo-upload-nav a').live('click',function(){
		
			var nav = jQuery(this).parent().parent();
			var navClicked = jQuery(this);
			nav.find('li').removeClass('active');
			navClicked.parent().addClass('active');
			var move = navClicked.attr('rel');
			nav.next().next().children().animate({'marginLeft':move},200);
			return false;
		
		})
	
	});
	
	</script>
	<script type="text/javascript" src="<?php echo get_bloginfo('template_directory'); ?>/functions/js/ajaxupload.js"></script>
	<script type="text/javascript">
		
	function setupAdUploaders(){
		
		jQuery(document).ready(function(){
		
		//AJAX Upload

		jQuery('.widget_image_upload_button').each(function(){
		
		var clickedObject = jQuery(this);
		var clickedID = jQuery(this).attr('id');	
		new AjaxUpload(clickedID, {
			  action: '<?php echo admin_url("admin-ajax.php"); ?>',
			  name: clickedID, // File upload name
			  data: { // Additional data to send
					action: 'woo_widget_ajax_post_action',
					type: 'upload',
					data: clickedID },
			  autoSubmit: true, // Submit file after selection
			  responseType: false,
			  onChange: function(file, extension){},
			  onSubmit: function(file, extension){
					clickedObject.text('Uploading'); // change button text, when user selects file	
					this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
					interval = window.setInterval(function(){
						var text = clickedObject.text();
						if (text.length < 13){	clickedObject.text(text + '.'); }
						else { clickedObject.text('Uploading'); } 
					}, 200);
			  },
			  onComplete: function(file, response) {
			   
				window.clearInterval(interval);
				clickedObject.text('Upload Image');	
				this.enable(); // enable upload button
				setupAdUploaders(); // Reinitialize the uploaders
				
				// If there was an error
				if(response.search('Upload Error') > -1){
					var buildReturn = '<span class="upload-error">' + response + '</span>';
					jQuery(".upload-error").remove();
					clickedObject.parent().after(buildReturn);
				
				}
				else{
					var buildReturn = '<img class="hide woo-option-image" id="image_'+clickedID+'" src="'+response+'" width="75" alt="" />';

					jQuery(".upload-error").remove();
					jQuery("#image_" + clickedID).remove();	
					clickedObject.parent().after(buildReturn);
					jQuery('img#image_'+clickedID).fadeIn();
					clickedObject.next('span').fadeIn();
					clickedObject.prev('input').val(response);
				}				
				
			  }
			 
			});
			
		});

	});
	
	}; // end function
	
	setupAdUploaders();
	
	</script>
    <?php
}

add_action('wp_ajax_woo_widget_ajax_post_action', 'woo_widget_ad_ajax_callback');

function woo_widget_ad_ajax_callback() {
	global $wpdb; // this is how you get access to the database
	$themename = get_option('template') . "_";
	//Uploads
	if(isset($_POST['type'])){
		if($_POST['type'] == 'upload'){
			
			$clickedID = $_POST['data']; // Acts as the name
			$filename = $_FILES[$clickedID];
			$override['test_form'] = false;
			$override['action'] = 'wp_handle_upload';    
			$uploaded_file = wp_handle_upload($filename,$override);
			 
					$upload_tracking[] = $clickedID;
					update_option( $clickedID , $uploaded_file['url'] );
					//update_option( $themename . $clickedID , $uploaded_file['url'] );
			 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }	
			 else { echo $uploaded_file['url']; } // Is the Response
		}
		
	}
	die();

}

?>