<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Gn_Custom_Divi_Accordion_With_Load_More_Button' ) ) :

	/**
	 * Main Gn_Custom_Divi_Accordion_With_Load_More_Button Class.
	 *
	 * @package		GNCUSTOMDI
	 * @subpackage	Classes/Gn_Custom_Divi_Accordion_With_Load_More_Button
	 * @since		1.0.0
	 * @author		George Nicolaou
	 */
	final class Gn_Custom_Divi_Accordion_With_Load_More_Button {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Gn_Custom_Divi_Accordion_With_Load_More_Button
		 */
		private static $instance;

		/**
		 * GNCUSTOMDI helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Gn_Custom_Divi_Accordion_With_Load_More_Button_Helpers
		 */
		public $helpers;

		/**
		 * GNCUSTOMDI settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Gn_Custom_Divi_Accordion_With_Load_More_Button_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'gn-custom-divi-accordion-with-load-more-button' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'gn-custom-divi-accordion-with-load-more-button' ), '1.0.0' );
		}

		/**
		 * Main Gn_Custom_Divi_Accordion_With_Load_More_Button Instance.
		 *
		 * Insures that only one instance of Gn_Custom_Divi_Accordion_With_Load_More_Button exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Gn_Custom_Divi_Accordion_With_Load_More_Button	The one true Gn_Custom_Divi_Accordion_With_Load_More_Button
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Gn_Custom_Divi_Accordion_With_Load_More_Button ) ) {
				self::$instance					= new Gn_Custom_Divi_Accordion_With_Load_More_Button;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Gn_Custom_Divi_Accordion_With_Load_More_Button_Helpers();
				self::$instance->settings		= new Gn_Custom_Divi_Accordion_With_Load_More_Button_Settings();

				//Fire the plugin logic
				new Gn_Custom_Divi_Accordion_With_Load_More_Button_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'GNCUSTOMDI/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once GNCUSTOMDI_PLUGIN_DIR . 'core/includes/classes/class-gn-custom-divi-accordion-with-load-more-button-helpers.php';
			require_once GNCUSTOMDI_PLUGIN_DIR . 'core/includes/classes/class-gn-custom-divi-accordion-with-load-more-button-settings.php';

			require_once GNCUSTOMDI_PLUGIN_DIR . 'core/includes/classes/class-gn-custom-divi-accordion-with-load-more-button-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'gn-custom-divi-accordion-with-load-more-button', FALSE, dirname( plugin_basename( GNCUSTOMDI_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.