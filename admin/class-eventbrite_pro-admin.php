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

		// Clear the cache when w3tc empties its cache
		add_action('w3tc_pgcache_flush', array($this, 'eventbrite_pro_clear_cache'));

		/*
		 * Registers settings if on options page
		 */
		if ( ! empty ( $GLOBALS['pagenow'] )
			and ( 'options-general.php' === $GLOBALS['pagenow']
				or 'options.php' === $GLOBALS['pagenow']
			)
		)
		{
			add_action( 'admin_init', array( $this, 'eventbrite_register_settings' ) );
		}


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


	public function eventbrite_register_settings()
	{
		$option_name = 'eventbrite_pro_options';

		//fetch existing options
		$option_values = get_option($option_name);

		//set defaults
		$default_values = array(
			'api_key'=>'',
			'email'=>'',
			'cache'=> -1
		);

		//parse option values and discard the rest
		$data = shortcode_atts($default_values, $option_values);


		register_setting(
			'eventbrite_pro_option_group',	 // Option Group ID
			$option_name,		// Option Name
			array(&$this, 'eventbrite_pro_options_validate') // Validation Callback
		);

		add_settings_section(
			'eventbrite_api_options',	// Section ID
			'API Settings', 		// Title
			array(&$this, 'eventbrite_section_text'), //Render Section
			$this->plugin_slug // Menu slug
		);

		add_settings_field(
			'eventbrite_api_key', // Field ID
			'API Key', // Label
			array(&$this, 'eventbrite_render_text_field'), // Render field
			$this->plugin_slug, 	// Menu slug
			'eventbrite_api_options',	// Section ID
			array(
				'label_for' => 'label1', // Makes field clickable
				'name'=>'api_key', // Field name key
				'value'=>esc_attr($data['api_key']), // Field value
				'option_name'=>$option_name, // Option name
				'type'=>'text' // Input type
			)
		);

		add_settings_field(
			'eventbrite_email', // Field ID
			'Eventbrite Email', // Label
			array($this, 'eventbrite_render_text_field'), // Render field
			$this->plugin_slug, // Menu slug
			'eventbrite_api_options', //Section ID
			array(
				'label_for' => 'label2',
				'name'=>'email',
				'value'=>esc_attr($data['email']),
				'option_name'=>$option_name,
				'type'=>'email'
			)
		);

		add_settings_field(
			'eventbrite_cache',
			'Cache Length',
			array($this, 'eventbrite_cache_setting'),
			$this->plugin_slug,
			'eventbrite_api_options',
			array
			(
				'label_for' => 'label3',
				'name' 		=> 'cache',
				'value' 	=> esc_attr($data['cache']),
				'options' 	=> array(
					'-1' => 'Select a cache length...',
					'1' => '1 Hour',
					'3'	 => '3 Hours',
					'6'	 => '6 Hours',
					'12' => '12 Hours',
					'24' => '1 Day',
					'72' => '3 Days',
					'168'=> '1 Week'
				),
				'option_name' => $option_name
			)
		);
	}

	function eventbrite_section_text()
	{
		?>
		<p><?php _e('To use Eventbrite Pro you will need an API Key.  You can get one by',$this->plugin_slug);?>
			<a href="http://www.eventbrite.com/api/key/" target="_blank" title="Get Eventbrite API Key"><?php _e('clicking here', $this->plugin_slug);?>.</a></p>
		<?php
	}

	function eventbrite_render_text_field($args)
	{
		printf(
			'<input name="%1$s[%2$s]" id="%3$s" value="%4$s" class="regular-text" type="%5$s">',
			$args['option_name'],
			$args['name'],
			$args['label_for'],
			$args['value'],
			$args['type']
		);

	}

	function eventbrite_render_dropdown($args)
	{
		printf(
			'<select name="%1$s[%2$s]" id="%3$s">',
			$args['option_name'],
			$args['name'],
			$args['label_for']
		);

		foreach($args['options'] as $val => $title)
		{

			printf(
				'<option value="%1$s" %2$s>%3$s</option>',
				$val,
				selected($val, $args['value'], FALSE),
				$title
			);
		}

		echo '</select>';
	}

	public function eventbrite_cache_setting($args)
	{
		if($args['value'] != -1)
		{
			unset($args['options']['-1']);
		}
		$this->eventbrite_render_dropdown($args);


		if($args['value'] != -1)
			echo '<input type="submit" class="button" name="eventbrite_pro_options[reset_cache]" value="' . __('Clear Cache', $this->plugin_slug) . '"/>';
		echo '<p class="description">How often do you want to check for new events? Eventbrite may terminate your API Key you if you abuse their API.</p>';

	}

	public function eventbrite_pro_options_validate($input)
	{
		$options = get_option('eventbrite_pro_options');

		if(!is_array($input))
		{
			return $options;
		}

		if(isset($input['submit']))
		{
			$api_key = strip_tags(stripslashes(trim($input['api_key'])));
			if(strlen($api_key) == 0)
			{
				add_settings_error('eventbrite_api_key','api_key_error',__('An API Key is required to use Eventbrite Pro',$this->plugin_slug),'error');
			}
			else
				$options['api_key'] = $api_key;


			$email = strip_tags(stripslashes(trim($input['email'])));
			if(!isset($input['email']) || strlen($email) == 0)
			{
				add_settings_error('eventbrite_email','email_error',__('Your Eventbrite email address is required to use Eventbrite Pro',$this->plugin_slug),'error');
			}
			else
				$options['email'] = $email;


			$cache = $input['cache'];
			if(!is_numeric($cache) || $cache < 1)
			{
				add_settings_error('eventbrite_cache','cache_error',__('A cache of at least 1 hour is required.',$this->plugin_slug),'error');
			}
			elseif(!isset($options['cache']) || ($options['cache'] != floatval($cache))) //only update the cache if it's different
			{
				$options['cache'] = floatval($cache);
				$this->eventbrite_pro_clear_cache();
			}
		}

		if(isset($input['reset_cache']))
		{
			$this->eventbrite_pro_clear_cache();
			add_settings_error('eventbrite_cache_clear','cache_clear',__('The events cache has been cleared.',$this->plugin_slug),'updated');
		}

		return $options;
	}

	public function eventbrite_pro_clear_cache()
	{
		delete_transient('eventbrite_events');
	}

}
