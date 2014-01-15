<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Eventbrite_Pro
 * @author    Yaron Guez <yaron@trestian.com>
 * @license   GPL-2.0+
 * @link      http://github.com/yaronguez/eventbrite-pro
 * @copyright 2013 Yaron Guez
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>


	<form action="options.php" method="post">
		<?php settings_fields('eventbrite_pro_options'); ?>
		<?php do_settings_sections($this->plugin_slug); ?>

		<input name="eventbrite_pro_options[submit]" type="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>

</div>
