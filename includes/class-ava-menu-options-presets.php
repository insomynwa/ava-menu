<?php
/**
 * Option page Class
 */

// If class `Popups_Options_Page` doesn't exists yet.
if ( ! class_exists( 'Ava_Menu_Options_Presets' ) ) {

	/**
	 * Ava_Menu_Options_Presets class.
	 */
	class Ava_Menu_Options_Presets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance = null;

		/**
		 * Post type name.
		 *
		 * @var string
		 */
		public $post_type = 'ava_options_preset';

		public $settings_key = 'ava_preset_settings';
		public $title_key    = 'ava_preset_name';

		/**
		 * Preset list
		 *
		 * @var null
		 */
		public $presets = null;

		/**
		 * Attach hooks
		 */
		public function init() {

			add_action( 'init', array( $this, 'register_post_type' ) );

			add_filter( 'ava-menu/options-page/tabs', array( $this, 'register_presets_tab' ) );
			add_filter( 'ava-menu/assets/admin/localize', array( $this, 'localize_presets_msg' ) );

			add_filter( 'ava-menu/nav-settings/registered', array( $this, 'add_menu_settings' ) );

			add_action( 'ava-menu/options-page/before-render', array( $this, 'register_presets_settings' ), 10, 2 );
			add_action( 'ava-menu/widgets/mega-menu/controls', array( $this, 'add_widget_settings' ) );

			add_action( 'wp_ajax_ava_menu_create_preset', array( $this, 'create_preset' ) );
			add_action( 'wp_ajax_ava_menu_update_preset', array( $this, 'update_preset' ) );
			add_action( 'wp_ajax_ava_menu_load_preset', array( $this, 'load_preset' ) );
			add_action( 'wp_ajax_ava_menu_delete_preset', array( $this, 'delete_preset' ) );

		}

		/**
		 * Add widget settings
		 *
		 * @param object $widget Widget instance.
		 */
		public function add_widget_settings( $widget ) {

			$presets = $this->get_presets();

			if ( empty( $presets ) ) {
				return;
			}

			$presets = array( '0' => esc_html__( 'Not Selected', 'ava-menu' ) ) + $presets;

			$widget->add_control(
				'preset',
				array(
					'label'   => esc_html__( 'Menu Preset', 'ava-menu' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => $presets,
				)
			);

		}

		/**
		 * Register post type
		 *
		 * @return void
		 */
		public function register_post_type() {

			register_post_type( $this->post_type, array(
				'public'      => false,
				'has_archive' => false,
				'rewrite'     => false,
				'can_export'  => true,
			) );

		}

		/**
		 * Create preset callback.
		 *
		 * @return void
		 */
		public function create_preset() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'ava-menu' ),
				) );
			}

			$name     = isset( $_POST['name'] ) ? esc_attr( $_POST['name'] ) : false;
			$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : false;

			if ( ! $settings ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Settings not provided', 'ava-menu' ),
				) );
			}

			if ( ! $name ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Please, specify preset name', 'ava-menu' ),
				) );
			}

			$post_title = 'ava_preset_' . md5( $name );

			if ( post_exists( $post_title ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Preset with the same name already exists, please change it', 'ava-menu' ),
				) );
			}

			$preset_id = wp_insert_post( array(
				'post_type'   => $this->post_type,
				'post_status' => 'publish',
				'post_title'  => $post_title,
				'meta_input'  => array(
					$this->title_key    => esc_attr( $name ),
					$this->settings_key => $settings,
				),
			) );

			do_action( 'ava-menu/presets/created' );

			wp_send_json_success( array(
				'preset' => array(
					'id'   => $preset_id,
					'name' => esc_attr( $name ),
				),
			) );

		}

		/**
		 * Update preset callback.
		 *
		 * @return void
		 */
		public function update_preset() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'ava-menu' ),
				) );
			}

			$preset   = isset( $_POST['preset'] ) ? absint( $_POST['preset'] ) : false;
			$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : false;

			if ( ! $preset ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Preset ID not defined', 'ava-menu' ),
				) );
			}

			update_post_meta( $preset, $this->settings_key, $settings );

			do_action( 'ava-menu/presets/updated' );

			wp_send_json_success( array() );

		}

		/**
		 * Load preset callback.
		 *
		 * @return void
		 */
		public function load_preset() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'ava-menu' ),
				) );
			}

			$preset = isset( $_POST['preset'] ) ? absint( $_POST['preset'] ) : false;

			if ( ! $preset ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Preset ID not defined', 'ava-menu' ),
				) );
			}

			$preset_settings = get_post_meta( $preset, $this->settings_key, true );

			update_option( ava_menu_option_page()->options_slug(), $preset_settings );

			do_action( 'ava-menu/presets/loaded' );

			wp_send_json_success( array() );

		}

		/**
		 * Delete preset callback
		 *
		 * @return void
		 */
		public function delete_preset() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'ava-menu' ),
				) );
			}

			$preset = isset( $_POST['preset'] ) ? absint( $_POST['preset'] ) : false;

			if ( ! $preset ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Preset ID not defined', 'ava-menu' ),
				) );
			}

			wp_delete_post( $preset, true );

			do_action( 'ava-menu/presets/deleted' );

			wp_send_json_success( array() );

		}

		/**
		 * Register prests tab.
		 *
		 * @param  array $tabs Existing tabs.
		 * @return array
		 */
		public function register_presets_tab( $tabs ) {

			$tabs['presets_tab'] = array(
				'parent' => 'tab_vertical',
				'title'  => esc_html__( 'Presets Manager', 'ava-menu' ),
			);

			return $tabs;

		}

		/**
		 * Localized presets managers
		 *
		 * @return array
		 */
		public function localize_presets_msg( $data ) {

			$strings = array();

			$strings['preset'] = array(
				'nameError'     => esc_html__( 'Please, specify preset name', 'ava-menu' ),
				'loadError'     => esc_html__( 'Please, select preset to load', 'ava-menu' ),
				'updateError'   => esc_html__( 'Please, select preset to update', 'ava-menu' ),
				'deleteError'   => esc_html__( 'Please, select preset to delete', 'ava-menu' ),
				'created'       => esc_html__( 'New preset was created. Page will be reloaded', 'ava-menu' ),
				'updated'       => esc_html__( 'Preset updated', 'ava-menu' ),
				'loaded'        => esc_html__( 'Preset loaded. Page will be reloaded to apply changes', 'ava-menu' ),
				'deleted'       => esc_html__( 'Preset deleted. Page will be reloaded to apply changes', 'ava-menu' ),
				'confirmUpdate' => esc_html__( 'Are you sure you want to rewrite existig preset?', 'ava-menu' ),
				'confirmDelete' => esc_html__( 'Are you sure you want to delete this preset?', 'ava-menu' ),
				'confirmLoad'   => esc_html__( 'Are you sure you want to load this preset? All unsaved options will be lost.', 'ava-menu' ),
			);

			$data['optionPageMessages'] = array_merge( $data['optionPageMessages'], $strings );
			$data['menuPageUrl']        = add_query_arg(
				array( 'page' => ava_menu()->plugin_slug ),
				esc_url( admin_url( 'admin.php' ) )
			);

			return $data;

		}

		/**
		 * Register presets settings
		 *
		 * @param  object $builder      Builder instance.
		 * @param  object $options_page Options page instance.
		 * @return void
		 */
		public function register_presets_settings( $builder, $options_page ) {

			ob_start();
			include ava_menu()->get_template( 'admin/presets-controls.php' );
			$controls = ob_get_clean();

			$builder->register_control(
				array(
					'ava-presets-controls' => array(
						'type'   => 'html',
						'parent' => 'presets_tab',
						'class'  => 'ava-menu-presets',
						'html'   => $controls,
					),
				)
			);

		}

		/**
		 * Add menu settings.
		 *
		 * @param  array $settings Settings array.
		 */
		public function add_menu_settings( $settings ) {

			$presets = $this->get_presets();

			if ( empty( $presets ) ) {
				return $settings;
			}

			$presets = array( '0' => esc_html__( 'Not Selected', 'ava-menu' ) ) + $presets;

			$settings['preset'] = array(
				'type'    => 'select',
				'id'      => 'ava-preset',
				'name'    => 'preset',
				'value'   => '',
				'options' => $presets,
				'label'   => esc_html__( 'Select menu preset', 'ava-menu' ),
			);

			return $settings;
		}

		/**
		 * Get presets list
		 *
		 * @return array
		 */
		public function get_presets() {

			if ( null !== $this->presets ) {
				return $this->presets;
			}

			$presets = get_posts( array(
				'post_type'      => $this->post_type,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			) );

			if ( empty( $presets ) ) {
				$this->presets = array();
				return $this->presets;
			}

			$result = array();

			foreach ( $presets as $preset ) {
				$result[ $preset->ID ] = get_post_meta( $preset->ID, $this->title_key, true );
			}

			$this->presets = $result;

			return $this->presets;

		}

		/**
		 * Presets select HTML for manager options
		 *
		 * @param  string $slug        Slug for JS processing.
		 * @param  string $placeholder Placeholder.
		 * @return void
		 */
		public function preset_select( $slug = 'ava-load-preset', $placeholder = '' ) {

			$presets = $this->get_presets();

			echo '<select class="cherry-ui-select ' . $slug . '">';
				echo '<option value="" selected disabled>' . $placeholder . '</option>';
				foreach ( $presets as $key => $name ) {
					printf( '<option value="%1$s">%2$s</option>', $key, $name );
				}
			echo '</select>';

		}

		/**
		 * Preset action button
		 *
		 * @param  string $action Button ID.
		 * @param  string $label  Button label.
		 * @return void
		 */
		public function preset_action( $action = '', $label = '' ) {
			echo '<button type="button" class="cherry5-ui-button cherry5-ui-button-normal-style ui-button" id="' . $action . '">';
				echo '<span class="text">' . $label . '</span>';
				echo '<span class="loader-wrapper"><span class="loader"></span></span>';
				echo '<span class="dashicons dashicons-yes icon"></span>';
			echo '</button>';
			echo '<div class="ava-preset-msg"></div>';
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}

if ( ! function_exists( 'ava_menu_options_presets' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function ava_menu_options_presets() {
		return Ava_Menu_Options_Presets::get_instance();
	}
}