<?php
/**
 * Vmagazine News functions and definitions
 *
 * @package Vmagazine News 
 */
 
function vmagazine_news_enqueue_styles() {

    wp_enqueue_style( 'vmag_news-parent-style', get_template_directory_uri() . '/style.css' );

    wp_enqueue_style( 'vmaga-zine-responsive', get_stylesheet_directory_uri(). '/assets/css/child-responsive.css',array('elegant-fonts','vmagazine-lite-responsive'), VMAG_VER );

    $vmagazine_news_font_args = array(
            'family' => 'Roboto:300,400,500,700,900');
    wp_enqueue_style( 'vmagazine-news-google-fonts', add_query_arg( $vmagazine_news_font_args, "//fonts.googleapis.com/css" ) );

}
add_action( 'wp_enqueue_scripts', 'vmagazine_news_enqueue_styles' );

/**
 * Extra Init.
 */
require get_stylesheet_directory() . '/inc/init.php';




if( ! function_exists('vmagazine_lite_widget_template_cus')){
    function vmagazine_lite_widget_template_cus($wp_customize){

    $wp_customize->add_setting('vmagazine_lite_template_layout_setting', array(
            'default'           => 'template-one',
            'sanitize_callback' => 'sanitize_text_field'
          )
       );
    $wp_customize->add_control('vmagazine_lite_template_layout_setting',array(
            'section'      => 'vmagazine_lite_general_options',
            'type'         => 'select',
            'label'        => esc_html__( 'Choose Template', 'vmagazine-news' ),
            'description'  => esc_html__( 'change the template for widgets', 'vmagazine-news' ),
            'priority'     => 41,
            'choices'      => array(
              'template-one'    => esc_html__('Template One','vmagazine-news'),
              'template-three'    => esc_html__('Template Two','vmagazine-news'),
              'template-five'   =>esc_html__('Template Three','vmagazine-news')
          )
          )
        ); 

    /** Enable/Disable header search */
    $wp_customize->add_setting( 'vmagazine_lite_footer_copy_enable', array(
      'default'           => 'show',
      'sanitize_callback' => 'vmagazine_lite_sanitize_switch_option',
    ) );

    $wp_customize->add_control( new vmagazine_lite_Customize_Switch_Control( $wp_customize, 'vmagazine_lite_footer_copy_enable',array(
      'type'      => 'switch',                    
      'label'     => esc_html__( 'Show/Hide Footer Copyright', 'vmagazine-lite' ),
      'section'   => 'vmagazine_lite_buttom_footer_option',
      'choices'   => array(
            'show'  => esc_html__( 'Show', 'vmagazine-lite' ),
            'hide'  => esc_html__( 'Hide', 'vmagazine-lite' )
          )
    ) ) ); 

    }

}

apply_filters( 'vmagazine_lite_widget_template', 'vmagazine_lite_widget_template_cus' );



if ( ! function_exists( 'vmagazine_lite_credit' ) ) {
    /**
     * Display the theme credit/button footer
     * @since  1.0.0
     * @return void
     */
function vmagazine_lite_credit() {
$vmagazine_lite_footer_copy_enable = get_theme_mod('vmagazine_lite_footer_copy_enable','show');
if($vmagazine_lite_footer_copy_enable =='show'){
        ?>
        <div class="site-info">
            <?php $copyright = get_theme_mod( 'vmagazine_lite_copyright_text' ); 
            if( !empty( $copyright ) ) { 
                echo wp_kses_post($copyright) . " | "; 
            } ?>
            <?php echo esc_html__('WordPress Theme :', 'vmagazine-news'); ?> <a href="https://accesspressthemes.com/" target="_blank">VMagazine News</a> 
        </div><!-- .site-info -->               
        <?php
      }
    }
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function vmagazine_news_body_classes( $classes ) {

     global $post;

    $vmagazine_lite_template_layout_setting = get_theme_mod('vmagazine_lite_template_layout_setting','template-three');
    if( $vmagazine_lite_template_layout_setting ){
        $classes[] = $vmagazine_lite_template_layout_setting;   
    }
    
    return $classes;
}
add_filter( 'body_class', 'vmagazine_news_body_classes' );
