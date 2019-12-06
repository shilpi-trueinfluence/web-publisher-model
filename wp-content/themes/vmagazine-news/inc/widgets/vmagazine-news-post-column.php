<?php
/**
 * Vmagazine: Block Posts (Column)
 *
 * Widget to display latest or selected category posts as block one style.
 *
 * @package AccessPress Themes
 * @subpackage Vmagazine
 * @since 1.0.0
 */

add_action( 'widgets_init', 'vmagazine_news_register_block_posts_column_widget' );

function vmagazine_news_register_block_posts_column_widget() {
    register_widget( 'vmagazine_lite_block_posts_column' );
}

class vmagazine_lite_block_posts_column extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'vmagazine_lite_block_posts_column',
            'description' => esc_html__( 'Display posts from selected category or latest.', 'vmagazine-news')
        );
         $width = array(
                'width'  => 600
        );
        parent::__construct( 'vmagazine_lite_block_posts_column', esc_html__( 'Vmagazine News : Block Posts(Column)', 'vmagazine-news'), $widget_ops,$width );
    }


    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {

        global $vmagazine_lite_posts_type, $vmagazine_lite_cat_dropdown, $vmagazine_lite_block_post_layout_col;
        
        $fields = array(
                'block_column_layout' => array(
                    'vmagazine_lite_widgets_name' => 'block_column_layout',
                    'vmagazine_lite_widgets_title' => esc_html__( 'Block Column Layouts', 'vmagazine-news'),
                    'vmagazine_lite_widgets_layout_img'      =>  VMAG_WIDGET_IMG_URI.'pc-2.png',
                    'vmagazine_lite_widgets_field_type' => 'widget_layout_image',
                ),
                'block_title' => array(
                    'vmagazine_lite_widgets_name'         => 'block_title',
                    'vmagazine_lite_widgets_title'        => esc_html__( 'Block Title', 'vmagazine-news'),
                    'vmagazine_lite_widgets_field_type'   => 'text'
                ),
                'block_post_type' => array(
                    'vmagazine_lite_widgets_name' => 'block_post_type',
                    'vmagazine_lite_widgets_title' => esc_html__( 'Block posts: ', 'vmagazine-news'),
                    'vmagazine_lite_widgets_field_type' => 'radio',
                    'vmagazine_lite_widgets_default' => 'latest_posts',
                    'vmagazine_lite_widgets_field_options' => $vmagazine_lite_posts_type
                ),
                'block_post_category' => array(
                    'vmagazine_lite_widgets_name' => 'block_post_category',
                    'vmagazine_lite_widgets_title' => esc_html__( 'Category for Block Posts', 'vmagazine-news'),
                    'vmagazine_lite_widgets_default'      => 0,
                    'vmagazine_lite_widgets_field_type' => 'select',
                    'vmagazine_lite_widgets_field_options' => $vmagazine_lite_cat_dropdown
                ),
                'block_posts_count' => array(
                    'vmagazine_lite_widgets_name'         => 'block_posts_count',
                    'vmagazine_lite_widgets_title'        => esc_html__( 'No. of Posts', 'vmagazine-news'),
                    'vmagazine_lite_widgets_default'      => 5,
                    'vmagazine_lite_widgets_field_type'   => 'number'
                ),

                'block_view_all_text' => array(
                    'vmagazine_lite_widgets_name' => 'block_view_all_text',
                    'vmagazine_lite_widgets_title' => esc_html__( 'View All Text', 'vmagazine-news'),
                    'vmagazine_lite_widgets_field_type' => 'text',
                ),

                'block_section_meta' => array(
                        'vmagazine_lite_widgets_name' => 'block_section_meta',
                        'vmagazine_lite_widgets_title' => esc_html__( 'Show/Hide Meta', 'vmagazine-news' ),
                        'vmagazine_lite_widgets_default'=>'show',
                        'vmagazine_lite_widgets_field_options'=>array('show'=>'Show','hide'=>'Hide'),
                        'vmagazine_lite_widgets_field_type' => 'switch',
                        'vmagazine_lite_widgets_description'  => esc_html__('Show or hide post meta options like author name, post date etc','vmagazine-news'),
                    ),

                'block_header_op' => array(
                        'vmagazine_lite_widgets_name' => 'block_header_op',
                        'vmagazine_lite_widgets_title' => esc_html__(' Disable Header', 'vmagazine-news'),
                        'vmagazine_lite_widgets_field_type' => 'checkbox'
                    ),
                
        );
        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        if( empty( $instance ) ) {
            return ;
        }

        $vmagazine_lite_block_title = empty($instance['block_title']) ? '' : $instance['block_title'];
        $vmagazine_lite_block_posts_count = empty($instance['block_posts_count']) ? 5 : $instance['block_posts_count'];
        $vmagazine_lite_block_posts_type = empty($instance['block_post_type']) ? 'latest_posts' : $instance['block_post_type'];
        $vmagazine_lite_block_cat_id = empty($instance['block_post_category']) ? null: $instance['block_post_category'];
        $vmagazine_lite_block_view_all_text = empty($instance['block_view_all_text']) ? '' : $instance['block_view_all_text'];
        $block_section_meta = empty($instance['block_section_meta']) ? 'show' : $instance['block_section_meta'];
        $block_header_op =  empty($instance['block_header_op']) ? 'hide' : 'show';

        echo wp_kses_post($before_widget);
        ?>
        <div class="wrapper-vmagazine-post-col block_layout_4 ">
        <?php if($block_header_op == 'hide'){ ?>
        <?php vmagazine_lite_widget_title( $vmagazine_lite_block_title, $title_url=null, $vmagazine_lite_block_cat_id ); ?>
        <?php } ?>
        <div class="vmagazine-post-col block-post-wrapper block_layout_4  wow zoomIn" data-wow-duration="1s">
            <div class="block-header clearfix">
                
            </div><!-- .block-header-->
            <?php 
                $block_args = vmagazine_lite_query_args( $vmagazine_lite_block_posts_type, $vmagazine_lite_block_posts_count, $vmagazine_lite_block_cat_id );
                $block_query = new WP_Query( $block_args );
                $post_count = 0;
                $total_posts_count = $block_query->post_count;
                if( $block_query->have_posts() ) {
                    while( $block_query->have_posts() ) {
                        $block_query->the_post();
                        $post_count++;
                        $image_id = get_post_thumbnail_id();
                        
                        $img_src = vmagazine_lite_home_element_img('vmagazine-lite-rectangle-thumb');
                        $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                        $post_class = '';
                        if( $post_count == 1 ) { 
                            $post_class = 'first-post'; 
                        } else {
                            $post_class = 'bottom-post';
                        }
                    
                        $vmagazine_lite_font_size = 'small-font';
            ?>
                        <div class="single-post <?php echo esc_attr( $post_class ); ?> clearfix">
                            <div class="post-thumb">
                                <a class="thumb-zoom" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                    <?php echo vmagazine_lite_load_images($img_src); ?>
                                    <div class="image-overlay"></div>
                                </a>
                                <?php do_action( 'vmagazine_lite_post_format_icon' ); ?>
                            </div>
                            <div class="content-wrapper">
                                <?php
                                    if( $block_section_meta == 'show' ): ?>
                                        <div class="post-meta clearfix">
                                            <?php  do_action( 'vmagazine_lite_icon_meta' ); ?>
                                        </div><!-- .post-meta --> 
                                    <?php endif;?>
                                <h3 class="<?php echo esc_attr( $vmagazine_lite_font_size ); ?>">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo esc_html(wp_trim_words( get_the_title(), 20, '...' ));
                                        //the_title(); ?>
                                    </a>
                                </h3>
                            </div><!-- .content-wrapper -->                          
                        </div><!-- .single-post -->
                        <?php
                    }
                }
                ?>
                <?php if( $vmagazine_lite_block_view_all_text ): 
                    vmagazine_lite_block_view_all( $vmagazine_lite_block_cat_id, $vmagazine_lite_block_view_all_text );
                endif;
                wp_reset_postdata();
            ?>
        </div><!-- .block-post-wrapper -->
    </div>
    <?php
        echo wp_kses_post($after_widget);

    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param   array   $new_instance   Values just sent to be saved.
     * @param   array   $old_instance   Previously saved values from database.
     *
     * @uses    vmagazine_lite_widgets_updated_field_value()      defined in vmagazine-widget-fields.php
     *
     * @return  array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            extract( $widget_field );

            // Use helper function to get updated field values
            $instance[$vmagazine_lite_widgets_name] = vmagazine_lite_widgets_updated_field_value( $widget_field, $new_instance[$vmagazine_lite_widgets_name] );
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param   array $instance Previously saved values from database.
     *
     * @uses    vmagazine_lite_widgets_show_widget_field()        defined in vmagazine-widget-fields.php
     */
    public function form( $instance ) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            // Make array elements available as variables
            extract( $widget_field );
            $vmagazine_lite_widgets_field_value = !empty( $instance[$vmagazine_lite_widgets_name]) ? esc_attr($instance[$vmagazine_lite_widgets_name] ) : '';
            vmagazine_lite_widgets_show_widget_field( $this, $widget_field, $vmagazine_lite_widgets_field_value );
        }
    }
}