<?php
/**
 * Component - select-field
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$id = $args['id'] ?? 'select_component';
$name = $args['name'] ?? null;
$label = $args['label'] ?? null;
$options = $args['options'] ?? null;
$currentOptionKey = $args['current_option_key'] ?? '';
?>

<?php if ($options) { ?>
    <div class="form__row">
        <label class="form__label"
               for="<?php echo $id . '_field'; ?>"
        ><?php echo $label; ?></label>

        <select class="form__field"
                id="<?php echo $id . '_field'; ?>"
            <?php if ($name) {
                echo 'name="' . $name . '"';
            } ?>
        >
            <?php
            foreach ($options as $key => $value) {
                $selectedOption = $key === $currentOptionKey;
                ?>
                <option value="<?php echo $key; ?>"
                    <?php if ($selectedOption) {
                        echo 'selected';
                    } ?>
                ><?php echo $value; ?></option>
            <?php } ?>
        </select>
    </div>
<?php } ?>
