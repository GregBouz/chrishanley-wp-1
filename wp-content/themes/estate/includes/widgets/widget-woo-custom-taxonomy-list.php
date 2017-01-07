<?php
/*---------------------------------------------------------------------------------*/
/* Custom Taxonomies List widget */
/*---------------------------------------------------------------------------------*/
class Woo_CustomTaxonomiesList extends WP_Widget {

	function Woo_CustomTaxonomiesList() {
		$widget_ops = array('classname' => 'widget_taxonomies', 'description' => 'This is a WooThemes custom taxonomy list or dropdown widget.' );
		$this->WP_Widget(false, __('Woo - Custom Taxonomies List'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base);
		$c = $instance['count'] ? '1' : '0';
		$h = $instance['hierarchical'] ? '1' : '0';
		$d = $instance['dropdown'] ? '1' : '0';
		$current_taxonomy = $this->_get_current_taxonomy($instance);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$cat_args = array('name' => 'tax-'.$this->number,'id' => 'tax-'.$this->number,'taxonomy' => $current_taxonomy,'orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

		if ( $d ) {
			$cat_args['show_option_none'] = __('Select Taxonomy');
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));

		$terms = get_terms($current_taxonomy);
		
?>

<script type='text/javascript'>
/* <![CDATA[ */
	var taxdropdown<?php echo $this->number; ?> = document.getElementById("tax-<?php echo $this->number; ?>");
	var myArray<?php echo $this->number; ?> = new Array();
	<?php 
	foreach ($terms as $term) {
		echo 'myArray'.$this->number.'['.$term->term_id.'] = "'.$term->slug.'";' ;
	}
	?>

	function onTaxChange<?php echo $this->number; ?>() {
		if ( taxdropdown<?php echo $this->number; ?>.options[taxdropdown<?php echo $this->number; ?>.selectedIndex].value > 0 ) {
			location.href = "<?php echo home_url(); ?>/?<?php echo $current_taxonomy; ?>="+myArray<?php echo $this->number; ?>[taxdropdown<?php echo $this->number; ?>.options[taxdropdown<?php echo $this->number; ?>.selectedIndex].value];
		}
	}
	taxdropdown<?php echo $this->number; ?>.onchange = onTaxChange<?php echo $this->number; ?>;
/* ]]> */
</script>

<?php
		} else {
?>
		<ul>
<?php
		$cat_args['title_li'] = '';
		wp_list_categories(apply_filters('widget_categories_args', $cat_args));
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$current_taxonomy = $this->_get_current_taxonomy($instance);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:') ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
		<?php foreach ( get_object_taxonomies('woo_estate') as $taxonomy ) :
					$tax = get_taxonomy($taxonomy);
					if ( !$tax->show_tagcloud || empty($tax->labels->name) )
						continue;
		?>
			<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
		<?php endforeach; ?>
		</select></p>
	
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Show as dropdown' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
<?php
	}
	
	function _get_current_taxonomy($instance) {
		if ( !empty($instance['taxonomy']) && is_taxonomy($instance['taxonomy']) )
			return $instance['taxonomy'];

		return 'post_tag';
	}
   
}

register_widget('Woo_CustomTaxonomiesList');
?>