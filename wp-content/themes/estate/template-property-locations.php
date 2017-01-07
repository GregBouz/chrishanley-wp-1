<?php
/*
Template Name: Property Locations
*/
?>
<?php get_header(); ?>
       
    <div id="content" class="page col-full">
		<div id="main" class="col-left">
					
	        <div class="post fl">
	        
	        	<h2 class="title"><?php the_title(); ?></h2>
	        	
	        	<div class="entry">
	            
	            	<h3><?php _e('Property Locations', 'woothemes') ?></h3>
	
		            <ul>
	    	            <?php wp_list_categories('taxonomy=location&title_li=&hierarchical=0&show_count=1') ?>	
	        	    </ul>
			        
			        <h3><?php _e('Properties per Location','woothemes'); ?></h3>
			        
			        <?php
					$terms = get_terms( 'location' );
					
			        foreach ($terms as $term) {
						
						query_posts('post_type=woo_estate&location='.$term->slug);
			
			        ?>
	        
	        			<h4><?php echo $term->name; ?></h4>
						
			        	<ul>	
	    	        	    <?php while (have_posts()) : the_post(); ?>
	        	    	    <li style="font-weight:normal !important;"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php _e('Comments', 'woothemes') ?> (<?php echo $post->comment_count ?>)</li>
	            		    <?php endwhile;  ?>
			        	</ul>
	    
	    		    <?php } ?>
					
	    		</div><!-- /.entry -->
	    						
	        </div><!-- /.post -->                    
	                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

		<div class="fix"></div>
		
<?php get_footer(); ?>
