<?php
/**
 * Eventbrite_Pro
 *
 * @package   Eventbrite_Pro_Admin
 * @author    Yaron Guez <yaron@trestian.com>
 * @license   GPL-2.0+
 * @link      http://github.com/yaronguez/eventbrite-pro
 * @copyright 2013 Yaron Guez
 */

/**
 * Eventbrite_Pro_Admin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Eventbrite_Pro_Admin
 * @author  Yaron Guez <yaron@trestian.com>
 */
class Eventbrite_Pro_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * TODO :
		 *
		 * - Decomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 *
		 */
		$plugin = Eventbrite_Pro::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'admin_init', array( $this, 'plugin_admin_init' ) );
		//add_filter( 'TODO', array( $this, 'filter_method_name' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {


		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 *
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Eventbrite_Pro::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 *
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Eventbrite_Pro::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 *
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Eventbrite Pro Settings', $this->plugin_slug ),
			__( 'Eventbrite Pro', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function plugin_admin_init() {
		register_setting( 'eventbrite_pro_options', 'eventbrite_pro_options', array($this, 'eventbrite_pro_options_validate') );
		add_settings_section('eventbrite_api_options', 'API Settings', array($this, 'eventbrite_section_text'), $this->plugin_slug);
		add_settings_field('eventbrite_api_key', 'API Key', array($this, 'eventbrite_api_setting'), $this->plugin_slug, 'eventbrite_api_options');
		add_settings_field('eventbrite_email', 'Eventbrite Email', array($this, 'eventbrite_email_setting'), $this->plugin_slug, 'eventbrite_api_options');
	}

	public function eventbrite_section_text()
	{
		?>
		<p><?php _e('To use Eventbrite Pro you will need an API Key.  You can get one by',$this->plugin_slug);?>
			<a href="http://www.eventbrite.com/api/key/" target="_blank" title="Get Eventbrite API Key"><?php _e('clicking here', $this->plugin_slug);?>.</a></p>
		<?php
	}

	public function eventbrite_api_setting()
	{
		$options = get_option('eventbrite_pro_options');
		?>
		<input id="eventbrite_api_setting" name="eventbrite_pro_options[api_key]" size="40" type="text" value="<?php echo $options['api_key'];?>"/>
		<?php
	}

	public function eventbrite_email_setting()
	{
		$options = get_option('eventbrite_pro_options');
		?>
		<input id="eventbrite_email_setting" name="eventbrite_pro_options[email]" size="40" type="email" value="<?php echo $options['email'];?>"/>
	<?php
	}

	public function eventbrite_pro_options_validate($input)
	{
		$options = get_option('eventbrite_pro_options');
		$api_key = strip_tags(stripslashes(trim($input['api_key'])));
		/*if(strlen($api_key) == 0)
		{
			add_settings_error('eventbrite_api_key','api_key_error',__('An API Key is required to use Eventbrite Pro',$this->plugin_slug),'error');
		}
		else*/
			$options['api_key'] = $api_key;

		$email = strip_tags(stripslashes(trim($input['email'])));
		/*if(strlen($email) == 0)
		{
			add_settings_error('eventbrite_email','email_error',__('Your Eventbrite email address is required to use Eventbrite Pro',$this->plugin_slug),'error');
		}
		else*/
			$options['email'] = $email;
		return $options;


	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}

}
