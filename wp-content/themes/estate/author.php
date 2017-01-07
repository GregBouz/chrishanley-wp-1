<?php get_header(); ?>
<?php 
global $wp_query;
global $woo_options;
$no_results = $woo_options['woo_property_search_results'];
$author_obj = $wp_query->get_queried_object();
?>   
    <div id="content" class="col-full">
    	
    		<div id="archive-blog" class="fl">
    			
       		 	<?php if (have_posts()) : $count = 0; ?>
        
           			<span class="archive_header"><?php _e('Archive by Author', 'woothemes'); ?> | <?php echo $author_obj->user_firstname.' '.$author_obj->user_lastname; ?></span>
           			
           			<div class="fix"></div>
        		<?php while (have_posts()) : the_post(); $count++; ?>
        	    <?php global $post; $post_type = $post->post_type; ?>                                                          
        	    <div class="post <?php if($count == 3){ echo 'last'; $count = 0; }?>" >
        	        
        	        <h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				
					<p class="post-meta">
                   		<span class="small"><?php _e('by', 'woothemes') ?></span> <span class="post-author"><?php the_author_posts_link(); ?></span>
                   		<span class="small"><?php _e('on', 'woothemes') ?></span> <span class="post-date"><?php the_time(get_option('date_format')); ?></span>
                    	<span class="small"><?php _e('in', 'woothemes') ?></span> <span class="post-category"><?php the_category(', ') ?></span>
                    	<span class="comments fr"><?php comments_popup_link(__('Comments { 0 }', 'woothemes'), __('Comments { 1 }', 'woothemes'), __('Comments { % }', 'woothemes')); ?></span>
	                </p>
	    	       
	    	       <?php woo_image('width=614&height=180&class=thumbnail'); ?>
	    	       
        	        <div class="entry">
        	            <p><?php echo woo_excerpt( get_the_excerpt(), '185'); ?></p>
        	        </div>
			
        	        <div class="bottom">
	    	       	    	
	    	        	<span class="more-info fr"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Read more', 'woothemes') ?></a></span>
        	        	        
        	        	<div class="fix"></div>
        	        	        
        	       	</div>                    
        	       	
        	    </div><!-- /.property --> 
        	    
        	     <?php endwhile; endif; ?>
        	     
        	     <div class="fix"></div>
        	     
        	     <?php woo_pagenav(); ?>
        	     
    		</div><!-- /#listings -->
    		
    		<?php get_sidebar('sidebar'); ?>
    		
    	<div class="fix"></div>    	
    	
<?php get_footer(); ?>