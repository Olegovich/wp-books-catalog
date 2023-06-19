<?php
/**
 * Component - notification
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$notificationClass = $args['notification_class'] ?? '';
?>

<p class="notification <?php echo $notificationClass; ?>"
   role="alert"
   aria-live="polite"
   hidden
></p>
