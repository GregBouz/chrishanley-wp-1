<?php
/*
Template Name: IDX Listings Full Width
*/
?>

<?php get_header(); ?>
       
    <div id="content" class="page col-full">
		<div id="main">
		           
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <div id="idx-listing" class="idx-full">

                    <h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>

                    <div class="entry">
	                	<?php the_content(); ?>
	               	</div><!-- /.entry -->
                    
                </div><!-- /.post -->
                                                    
			<?php endwhile; endif; ?>  
        
		</div><!-- /#main -->

		<div class="fix"></div>
		
<?php get_footer(); ?>