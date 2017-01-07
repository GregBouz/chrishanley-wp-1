<?php
/*
Template Name: Blog
*/
?>
<?php get_header(); ?>
<?php global $woo_options; ?>

    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
        <!-- #main Starts -->
        <div id="archive-blog" class="fl">      
                    
		<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumb"><p>','</p></div>'); } ?>
        <?php 
			// WP 3.0 PAGED BUG FIX
			if ( get_query_var('paged') )
				$paged = get_query_var('paged');
			elseif ( get_query_var('page') ) 
				$paged = get_query_var('page');
			else 
				$paged = 1;
			//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
       		 
             query_posts("post_type=post&paged=$paged"); 
        ?>
        <?php if (have_posts()) : $count = 0; ?>
        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <!-- Post Starts -->

            <div <?php post_class(); ?>>

                <h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-521fc15706b86b20"></script>
				
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
    
            </div><!-- /.post -->
                                                
        <?php endwhile; else: ?>
            <div class="post">
                <p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
            </div><!-- /.post -->
        <?php endif; ?>  
    
            <?php woo_pagenav(); ?>
                
        </div><!-- /#main -->
            
		<?php get_sidebar(); ?>
		
<?php get_footer(); ?>