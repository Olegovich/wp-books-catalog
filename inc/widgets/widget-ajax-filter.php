<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create widget with ajax-filter
 * @since 1.0.0
 * @package Blogus Child theme
 * @subpackage inc/widgets/
 * @author Olegovich
 */
class Ajax_filter_widget extends WP_Widget
{
    /**
     * Ajax_filter_widget constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'ajax_filter',
            esc_html__('Ajax filter', 'blogus-child'),
            [
                'description' => esc_html__('An ajax filter', 'blogus-child'),
            ]
        );
    }

    /**
     * Front-end display of widget.
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     * @return void
     * @see WP_Widget::widget()
     */
    public function widget($args, $instance): void
    {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        /**
         * Include component: books-filter
         */
        get_template_part('template-parts/components/books-filter/books-filter');

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     * @param array $instance Previously saved values from database.
     * @return void
     * @see WP_Widget::form()
     */
    public function form($instance): void
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'blogus-child');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"
            ><?php esc_attr_e('Title:', 'blogus-child'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                   type="text"
                   value="<?php echo esc_attr($title); ?>"
            >
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     * @return array Updated safe values to be saved.
     * @see WP_Widget::update()
     */
    public function update($new_instance, $old_instance): array
    {
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';

        return $instance;
    }
}
