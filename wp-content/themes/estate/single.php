<?php get_header(); ?>
<?php global $woo_options; ?>
              
	<?php if (have_posts()) : $count = 0; ?>
	<?php while (have_posts()) : the_post(); $count++; ?>
        
		<?php 
        global $post;
        $post_type = $post->post_type;
        	
	    //Custom meta boxes
    	
	    ?>      
    	<div id="content" class="single col-full">
			
		<div id="single-blog" class="fl">
		
			<div <?php post_class(); ?>>

<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53ada8e601194449"></script>

<div class="addthis_native_toolbox"></div>


<h1 class="title"><?php the_title(); ?></h1>
				
				<p class="post-meta">
                    <span class="small"><?php _e('by', 'woothemes') ?></span> <span class="post-author"><?php the_author_posts_link(); ?></span>
                    <span class="small"><?php _e('on', 'woothemes') ?></span> <span class="post-date"><?php the_time(get_option('date_format')); ?></span>
                    <span class="small"><?php _e('in', 'woothemes') ?></span> <span class="post-category"><?php the_category(', ') ?></span>
                </p>
			
				<div class="entry <?php if(!$gallery){ echo 'no-gallery';  }?>">
                    <?php the_content(); ?>
				</div>
										
				<div class="fix"></div>
					
				<?php woo_subscribe_connect(); ?>

            </div><!-- /.post -->
                <div class="addthis_native_toolbox"></div>
            <div class="clear"></div>
                
                <?php $comm = get_option('woo_comments'); if ( 'open' == $post->comment_status && ($comm == "post" || $comm == "both") ) : ?>
	                <?php comments_template('', true); ?>
                <?php endif; ?>
                                                    
			<?php endwhile; ?>         
           	<?php endif; ?>  
        	
		</div><!-- /#single-property -->
		
		<?php get_sidebar(); ?>

		<div class="fix"></div>
		
<?php get_footer(); ?>