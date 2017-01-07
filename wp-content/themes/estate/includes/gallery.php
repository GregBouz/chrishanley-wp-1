<?php
global $woo_options;
global $post;
$use_timthumb = false; 		// Set to false to disable for this section of theme. Images will be downsized instead of resized to 640px width
$repeat = 20; 				// Number of maximum attachments to get 
$photo_size = 'large';		// The WP "size" to use for the large image
$thumb_size = 'thumbnail';	// The WP "size" to use for the thumbnail
$thumb_dim = 66;			// Size of thumbnails
if ( $post->post_type == 'woo_estate' ) {
	$woo_layout_setting = $woo_options['woo_property_single_layout'];
} else {
	$woo_layout_setting = $woo_options['woo_single_layout'];
}

if($woo_layout_setting == "Without Sidebar" ) {
	$width_setting = 374;
} else {
	$width_setting = 594;
}

$id = get_the_id();
$attachments = get_children( array(
'post_parent' => $id,
'numberposts' => $repeat,
'post_type' => 'attachment',
'post_mime_type' => 'image',
'order' => 'DESC', 
'orderby' => 'menu_order date')
);
if ( !empty($attachments) ) :
	$counter = 0;
	$photo_output = '';
	$thumb_output = '';	
	foreach ( $attachments as $att_id => $attachment ) {
		$counter++;
		
		$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
		
		// Caption text
		$caption = "";
		if ($attachment->post_excerpt) 
			$caption = '<span class="photo-caption">'.$attachment->post_excerpt.'</span>';	
			
		// Save large photo
		$src = wp_get_attachment_image_src($att_id, $photo_size, true);
		if ( get_option('woo_resize') == "true" && $use_timthumb == "true" )
  			$photo_output .= '<div><a href="'. $src[0] .'" rel="lightbox-group" class="thickbox" title="'.$attachment->post_excerpt.'">' . woo_image( 'src=' . $src[0] . '&width='.$width_setting.'&class=single-photo&meta=' . $alt . '&return=true' ) . '</a>'.$caption.'</div>';
		else
  			$photo_output .= '<div><a href="'. $src[0] .'" rel="lightbox-group" class="thickbox" title="'.$attachment->post_excerpt.'"><img src="'. $src[0] .'" width="'.$width_setting.'" class="single-photo" alt="'. $alt .'" /></a>'.$caption.'</div>'; 
		
		// Save thumbnail
		$src = wp_get_attachment_image_src($att_id, $thumb_size, true);
		$thumb_output .= '<li><a href="#"><img src="'. $src[0] .'" height="'.$thumb_dim.'" width="'.$thumb_dim.'" class="single-thumb" alt="'. $alt .'" />' . "</a></li>\n"; 
	}  
endif; ?>

<?php 
	if ($counter == 1) {
		?><div id="single-gallery-image"><?php
			echo $photo_output; // This will show the large photo in the slider
		?></div><?php
	} else {
?>

<!-- Start Photo Slider -->
<div id="loopedSlider" class="gallery <?php if($woo_layout_setting == "Without Sidebar" ) { ?>no-sidebar<?php } else { ?>sidebar<?php } ?>">
    <div class="container">
        <div class="slides">
            <?php echo $photo_output; // This will show the large photo in the slider ?>
        </div>
    </div>
    
    <?php if ($counter > 1) : ?>
	
	<div class="fix"></div>
	                      
    <ul class="pagination">
		<?php echo $thumb_output; // This will show the large photo in the slider ?>
    </ul>                      
    <?php endif; ?>
    
<div class="fix"></div>
</div>
<?php if (get_option("woo_property_single_layout") == "With sidebar") { $counter_limit = 7; } else { $counter_limit = 5; } ?>
<?php if ($counter < $counter_limit) { ?>
<style type="text/css">

.jcarousel-prev { display:none!important; }
.jcarousel-next { display:none!important; }

</style>
<?php } ?>
<?php } ?>
<!-- End Photo Slider -->
