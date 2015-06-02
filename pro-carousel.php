<?php

/*
 *
 * @link              http://studio.croati.co
 * @since             1.0.0
 * @package           Pro_Carousel
 *
 * @wordpress-plugin
 * Plugin Name:       Pro Automatika Carousel
 * Plugin URI:        http://studio.croati.co
 * Description:       Carousel za Pro Automatiku d.o.o.
 * Version:           1.0.0
 * Author:            Tonino Jankov
 * Author URI:        http://studio.croati.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pro-carousel
 * Domain Path:       /languages
 */

// If this file is called directly, abort.


if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action('init', 'register_pro_carousel');
add_action('wp_footer', 'print_pro_carousel');

function register_pro_carousel() {
	wp_register_style('pro-carousel-style', plugins_url('assets/owl.carousel.css', __FILE__), array(), '1.0', 'all');
	wp_register_script('pro-carousel-script', plugins_url('assets/owl.carousel.min.js', __FILE__), array('jquery'), '1.0', true);
}

function print_pro_carousel() {
	global $add_pro_carousel;
	if ( ! $add_pro_carousel )
		return;
	
	wp_print_styles('pro-carousel-style');
	wp_print_scripts('pro-carousel-script');
}




add_shortcode( 'pro_carousel', 'carousel' );
function carousel( $atts ) {
	global $add_pro_carousel;
	$add_pro_carousel = true;
	
    ob_start();
    $a = shortcode_atts( array(
        'posts' => '-1',
        'show' => '-1',
        'cat' => 'defaultna-kategorija-slug',
        'thumb_size' => 'medium',
    ), $atts );
    $query = new WP_Query( array(
        'posts_per_page' => $a['show'],
        'order' => 'ASC',
        'orderby' => 'title',
        'category_name' => $a['cat'],
    ) );
    if ( $query->have_posts() ) { 
	?>	
		
		<script type="text/javascript">			
			jQuery(document).ready(function() {			
				jQuery('.pro-carousel').owlCarousel({
				    loop:true,
				    items:3,
					autoplay:true,
					autoplayTimeout:5000,
					autoplayHoverPause:true,
				    margin:28,
				    nav:false,
				    responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:3
				        }
				    }
				});		
			});		
		</script>
		
        <div class="pro-carousel">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="pro-carousel-item" style="">
				<?php the_post_thumbnail( 'medium' );  ?>
                <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                <div class=""><?php the_excerpt(); ?></div>
            </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
        
    <?php $cposts = ob_get_clean();
    return $cposts;
    }
}





?>
