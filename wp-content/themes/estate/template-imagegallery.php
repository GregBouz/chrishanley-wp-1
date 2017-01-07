<?php
/*
Template Name: Image Gallery
*/
?>

<?php get_header(); ?>
       
    <div id="content" class="page col-full">
		<div id="main" class="col-left">
                                                                            
            <div class="post page fl">

                <h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
                
				<div class="entry">
                <?php query_posts('post_type=woo_estate&showposts=60'); ?>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>				
                    <?php $wp_query->is_home = false; ?>

                    <?php woo_get_image('image',99,99,'thumbnail alignleft'); ?>
                
                <?php endwhile; endif; ?>	
                </div>

            </div><!-- /.post -->
                                                            
		</div><!-- /#main -->
		
        <?php get_sidebar(); ?>

		<div class="fix"></div>
		
<?php get_footer(); ?>