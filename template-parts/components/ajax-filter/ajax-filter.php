<?php
/**
 * Component - ajax-filter
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$formFields = $args['form_fields'] ?? null;
?>

<?php if ($formFields) { ?>
    <div class="ajax-filter">
        <form class="ajax-filter__form form">
            <?php
            foreach ($formFields as $field) {
                switch ($field['field_type']) {
                    case 'select':
                        /**
                         * Include component: select-field
                         */
                        get_template_part(
                            'template-parts/components/form/select-field/select-field',
                            '',
                            [
                                'id' => $field['field_settings']['id'],
                                'name' => $field['field_settings']['name'],
                                'label' => $field['field_settings']['label'],
                                'options' => $field['field_settings']['options'],
                                'current_option_key' => $field['field_settings']['current_option_key'],
                            ],
                        );

                        break;

//                    case 'text':
//                        // Include component: text-field
//                        break;
//
//                    case 'textarea':
//                        // Include component: textarea-field
//                        break;
                }
            }
            ?>
        </form>
    </div>
<?php } ?>
