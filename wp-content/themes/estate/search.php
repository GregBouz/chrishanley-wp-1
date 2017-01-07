<?php if ( (isset($_GET['property-search-submit'])) || (get_search_query() == 'View More') || (isset($_GET['property-search-webref-submit'])) ) { ?>
<?php get_template_part( 'includes/property-search-results' ); ?>
<?php } else { ?>
<?php get_header(); ?>

<?php
global $query_string;

$query_args = explode("&", $query_string);
$search_query = array();
//setup search args
foreach($query_args as $key => $string) {
	$query_split = explode("=", $string);
	$search_query[$query_split[0]] = $query_split[1];
} // foreach
$keyword_to_search_raw = get_search_query();
if ( ($keyword_to_search_raw == $woo_options['woo_search_keyword_text']) || ($keyword_to_search_raw == 'View More') ) { $keyword_to_search = ''; } else { $keyword_to_search = $keyword_to_search_raw; }
//limit to posts and pages
$search_query['post_type'] = array('page', 'post');
$search = new WP_Query($search_query);
?>


    <div id="content" class="col-full">
    
    	<div id="archive-blog" class="fl">
    			
       		 	<?php if ($search->have_posts()) : $count = 0; ?>
        
           			<span class="archive_header"><?php _e('Search results', 'woothemes') ?> <?php if ($keyword_to_search != '') { _e('for', 'woothemes'); ?> <em>"<?php printf(the_search_query());?>"</em><?php } ?></span>
           			
           			<div class="fix"></div>
        		<?php while ($search->have_posts()) : $search->the_post(); $count++; ?>
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
        	    
        	     <?php endwhile; else: ?>
        	     
        	     	<span class="archive_header"><?php _e('Search results', 'woothemes') ?> <?php if ($keyword_to_search != '') { _e('for', 'woothemes'); ?> <em>"<?php printf(the_search_query());?>"</em><?php } ?></span>
           			
           			<div class="fix"></div>
           			
           			<div class="post last" >
           				
           				<p><?php _e('Sorry, no results matched your criteria.', 'woothemes') ?></p>
           			
           			</div>
           			
        	     <?php endif;?>
        	     
        	     <div class="fix"></div>
        	     
        	     <?php woo_pagenav(); ?>
        	     
    		</div><!-- /#listings -->
    		
    		<?php get_sidebar('sidebar'); ?>
    		
    	<div class="fix"></div>    	
		
<?php get_footer(); ?>
<?php } ?>