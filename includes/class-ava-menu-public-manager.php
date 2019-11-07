<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Ava_Menu_Public_Manager' ) ) {

	/**
	 * Define Ava_Menu_Public_Manager class
	 */
	class Ava_Menu_Public_Manager {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_filter( 'wp_nav_menu_args', array( $this, 'set_menu_args' ), 99999 );
			add_filter( 'walker_nav_menu_start_el', array( $this, 'fix_double_desc' ), 0, 4 );
			add_action( 'ava-menu/blank-page/after-content', array( $this, 'set_menu_canvas_bg' ) );
		}

		/**
		 * Add background from options from menu canvas
		 */
		public function set_menu_canvas_bg() {
			ava_menu_dynmic_css()->add_single_bg_styles( 'ava-menu-sub-panel-mega', 'body' );
		}

		/**
		 * Fix double decription bug.
		 *
		 * @param  string  $item_output The menu item output.
		 * @param  WP_Post $item        Menu item object.
		 * @param  int     $depth       Depth of the menu.
		 * @param  array   $args        wp_nav_menu() arguments.
		 * @return string
		 */
		public function fix_double_desc( $item_output, $item, $depth, $args ) {
			$item->description = '';
			return $item_output;
		}

		/**
		 * Set mega menu arguments
		 *
		 * @param [type] $args [description]
		 */
		public function set_menu_args( $args ) {

			if ( ! isset( $args['theme_location'] ) ) {
				return $args;
			}

			$location = $args['theme_location'];

			$menu_id = $this->get_menu_id( $location );

			if ( false === $menu_id ) {
				return $args;
			}

			$settings = ava_menu_settings_nav()->get_settings( $menu_id );

			$settings = apply_filters( 'ava-menu/public-manager/menu-settings', $settings );
			$location = apply_filters( 'ava-menu/public-manager/menu-location', $location );

			if ( ! isset( $settings[ $location ] ) ) {
				return $args;
			}

			if ( ! isset( $settings[ $location ]['enabled'] ) || 'true' !== $settings[ $location ]['enabled'] ) {
				return $args;
			}

			$preset = isset( $settings[ $location ]['preset'] ) ? absint( $settings[ $location ]['preset'] ) : 0;

			if ( 0 !== $preset ) {
				$preset_options = get_post_meta( $preset, ava_menu_options_presets()->settings_key, true );
				ava_menu_option_page()->pre_set_options( $preset_options );
			} else {
				ava_menu_option_page()->pre_set_options( false );
			}

			$args = array_merge( $args, $this->get_mega_nav_args( $preset ) );

			return $args;

		}

		/**
		 * Returns array ow Mega Mneu attributes for wp_nav_menu() function.
		 *
		 * @return array
		 */
		public function get_mega_nav_args( $preset = 0 ) {
			global $is_iphone;

			// Get animation type for mega menu instance
			$animation_type = ava_menu_option_page()->get_option( 'ava-menu-animation', 'fade' );

			$raw_attributes = apply_filters( 'ava-menu/set-menu-args/', array(
				'class' => array(
					'ava-menu',
					( ! empty( $preset ) ? 'ava-preset-' . $preset : '' ),
					'ava-menu--animation-type-' . $animation_type,
					$is_iphone ? 'ava-menu--iphone-mode' : '',
				),
			) );

			$attributes = '';

			foreach ( $raw_attributes as $name => $value ) {

				if ( is_array( $value ) ) {
					$value = implode( ' ', $value );
				}

				$attributes .= sprintf( ' %1$s="%2$s"', esc_attr( $name ), esc_attr( $value ) );
			}

			$roll_up = ava_menu_option_page()->get_option( 'ava-menu-roll-up', 'false' );

			$args = array(
				'menu_class' => '',
				'items_wrap' => '<div class="ava-menu-container"><div class="ava-menu-inner"><ul' . $attributes . '>%3$s</ul></div></div>',
				'before'     => '',
				'after'      => '',
				'walker'     => new Ava_Menu_Main_Walker(),
				'roll_up'    => filter_var( $roll_up, FILTER_VALIDATE_BOOLEAN ),
			);

			$this->add_dynamic_styles( $preset );

			return $args;

		}

		/**
		 * Add menu dynamic styles
		 */
		public function add_dynamic_styles( $preset = 0 ) {

			if ( ava_menu_css_file()->is_enqueued( $preset ) ) {
				return;
			} else {
				ava_menu_css_file()->add_preset_to_save( $preset );
			}

			$preset_class = ( 0 !== $preset ) ? '.ava-preset-' . $preset : '';
			$wrapper      = sprintf( '.ava-menu%1$s', $preset_class );

			ava_menu_dynmic_css()->add_fonts_styles( $wrapper );
			ava_menu_dynmic_css()->add_backgrounds( $wrapper );
			ava_menu_dynmic_css()->add_borders( $wrapper );
			ava_menu_dynmic_css()->add_shadows( $wrapper );
			ava_menu_dynmic_css()->add_positions( $wrapper );

			$css_scheme = apply_filters( 'ava-menu/menu-css/scheme', array(
				'ava-menu-container-alignment' => array(
					'selector'  => '',
					'rule'      => 'justify-content',
					'value'     => '%1$s',
					'important' => true,
				),
				'ava-menu-mega-padding' => array(
					'selector'  => '',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => true,
				),
				'ava-menu-min-width' => array(
					'selector'  => '',
					'rule'      => 'min-width',
					'value'     => '%1$spx',
					'important' => false,
					'desktop'   => true,
				),
				'ava-menu-mega-border-radius' => array(
					'selector'  => '',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => true,
				),
				'ava-menu-item-text-color' => array(
					'selector'  => '.ava-menu-item .top-level-link',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-item-desc-color' => array(
					'selector'  => '.ava-menu-item .ava-menu-item-desc.top-level-desc',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-item-padding' => array(
					'selector'  => '.ava-menu-item .top-level-link',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-margin' => array(
					'selector'  => '.ava-menu-item .top-level-link',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-border-radius' => array(
					'selector'  => '.ava-menu-item .top-level-link',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-top-badge-text-color' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-badge__inner',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-badge-padding' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-badge__inner',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-top-badge-margin' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-badge',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-top-badge-border-radius' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-badge__inner',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-badge-text-color' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-badge-padding' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-badge-margin' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-badge-border-radius' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-text-color-hover' => array(
					'selector'  => '.ava-menu-item:hover > .top-level-link',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-item-desc-color-hover' => array(
					'selector'  => '.ava-menu-item:hover > .top-level-link .ava-menu-item-desc.top-level-desc',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-item-padding-hover' => array(
					'selector'  => '.ava-menu-item:hover > .top-level-link',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-margin-hover' => array(
					'selector'  => '.ava-menu-item:hover > .top-level-link',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-border-radius-hover' => array(
					'selector'  => '.ava-menu-item:hover > .top-level-link',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-text-color-active' => array(
					'selector'  => '.ava-menu-item.ava-current-menu-item .top-level-link',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-item-desc-color-active' => array(
					'selector'  => '.ava-menu-item.ava-current-menu-item .ava-menu-item-desc.top-level-desc',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-item-padding-active' => array(
					'selector'  => '.ava-menu-item.ava-current-menu-item .top-level-link',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-margin-active' => array(
					'selector'  => '.ava-menu-item.ava-current-menu-item .top-level-link',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-item-border-radius-active' => array(
					'selector'  => '.ava-menu-item.ava-current-menu-item .top-level-link',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-panel-width-simple' => array(
					'selector'  => 'ul.ava-sub-menu',
					'rule'      => 'min-width',
					'value'     => '%1$spx',
					'important' => false,
				),
				'ava-menu-sub-panel-padding-simple' => array(
					'selector'  => 'ul.ava-sub-menu',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-panel-margin-simple' => array(
					'selector'  => 'ul.ava-sub-menu',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-panel-border-radius-simple' => array(
					'selector'  => 'ul.ava-sub-menu',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-panel-padding-mega' => array(
					'selector'  => 'div.ava-sub-mega-menu',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-panel-margin-mega' => array(
					'selector'  => 'div.ava-sub-mega-menu',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-panel-border-radius-mega' => array(
					'selector'  => 'div.ava-sub-mega-menu',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-text-color' => array(
					'selector'  => 'li.ava-sub-menu-item .sub-level-link',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-desc-color' => array(
					'selector'  => '.ava-menu-item-desc.sub-level-desc',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-padding' => array(
					'selector'  => 'li.ava-sub-menu-item .sub-level-link',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-margin' => array(
					'selector'  => 'li.ava-sub-menu-item .sub-level-link',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-border-radius' => array(
					'selector'  => 'li.ava-sub-menu-item .sub-level-link',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-text-color-hover' => array(
					'selector'  => 'li.ava-sub-menu-item:hover > .sub-level-link',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-desc-color-hover' => array(
					'selector'  => 'li.ava-sub-menu-item:hover > .sub-level-link .ava-menu-item-desc.sub-level-desc',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-padding-hover' => array(
					'selector'  => 'li.ava-sub-menu-item:hover > .sub-level-link',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-margin-hover' => array(
					'selector'  => 'li.ava-sub-menu-item:hover > .sub-level-link',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-border-radius-hover' => array(
					'selector'  => 'li.ava-sub-menu-item:hover > .sub-level-link',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-text-color-active' => array(
					'selector'  => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-desc-color-active' => array(
					'selector'  => 'li.ava-sub-menu-item.ava-current-menu-item .ava-menu-item-desc.sub-level-desc',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-padding-active' => array(
					'selector'  => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
					'rule'      => 'padding-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-margin-active' => array(
					'selector'  => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-border-radius-active' => array(
					'selector'  => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
					'rule'      => 'border-%s-radius',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-top-icon-color' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-icon',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-icon-color-hover' => array(
					'selector'  => '.ava-menu-item:hover > .top-level-link .ava-menu-icon',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-icon-color-active' => array(
					'selector'  => '.ava-menu-item.ava-current-menu-item .top-level-link .ava-menu-icon',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-icon-color' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-icon',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-icon-color-hover' => array(
					'selector'  => '.ava-menu-item:hover > .sub-level-link .ava-menu-icon',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-icon-color-active' => array(
					'selector'  => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link .ava-menu-icon',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-arrow-color' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-dropdown-arrow',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-arrow-color-hover' => array(
					'selector'  => '.ava-menu-item:hover > .top-level-link .ava-dropdown-arrow',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-arrow-color-active' => array(
					'selector'  => '.ava-menu-item.ava-current-menu-item .top-level-link .ava-dropdown-arrow',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-arrow-color' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-dropdown-arrow',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-arrow-color-hover' => array(
					'selector'  => '.ava-menu-item:hover > .sub-level-link .ava-dropdown-arrow',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-arrow-color-active' => array(
					'selector'  => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link .ava-dropdown-arrow',
					'rule'      => 'color',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-icon-order' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-icon',
					'rule'      => 'order',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-icon-order' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-icon',
					'rule'      => 'order',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-badge-order' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-badge',
					'rule'      => 'order',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-badge-order' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-badge',
					'rule'      => 'order',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-arrow-order' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-dropdown-arrow',
					'rule'      => 'order',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-sub-arrow-order' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-dropdown-arrow',
					'rule'      => 'order',
					'value'     => '%1$s',
					'important' => false,
				),
				'ava-menu-top-icon-size' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-icon',
					'rule'      => 'font-size',
					'value'     => '%spx',
					'important' => false,
				),
				'ava-menu-top-icon-margin' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-menu-icon',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-icon-size' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-icon',
					'rule'      => 'font-size',
					'value'     => '%spx',
					'important' => false,
				),
				'ava-menu-sub-icon-margin' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-menu-icon',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-top-arrow-size' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-dropdown-arrow',
					'rule'      => 'font-size',
					'value'     => '%spx',
					'important' => false,
				),
				'ava-menu-top-arrow-margin' => array(
					'selector'  => '.ava-menu-item .top-level-link .ava-dropdown-arrow',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-sub-arrow-size' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-dropdown-arrow',
					'rule'      => 'font-size',
					'value'     => '%spx',
					'important' => false,
				),
				'ava-menu-sub-arrow-margin' => array(
					'selector'  => '.ava-menu-item .sub-level-link .ava-dropdown-arrow',
					'rule'      => 'margin-%s',
					'value'     => '',
					'important' => false,
				),
				'ava-menu-mobile-toggle-color' => array(
					'selector'  => '.ava-menu-container .ava-mobile-menu-toggle-button',
					'rule'      => 'color',
					'value'     => '%s',
					'important' => false,
					'mobile'    => true,
				),
				'ava-menu-mobile-toggle-bg' => array(
					'selector'  => '.ava-menu-container .ava-mobile-menu-toggle-button',
					'rule'      => 'background-color',
					'value'     => '%s',
					'important' => false,
					'mobile'    => true,
				),
				'ava-menu-mobile-container-bg' => array(
					'selector'  => '.ava-menu-container .ava-menu-inner',
					'rule'      => 'background-color',
					'value'     => '%s',
					'important' => false,
					'mobile'    => true,
				),
				'ava-menu-mobile-cover-bg' => array(
					'selector'  => '.ava-mobile-menu-cover',
					'rule'      => 'background-color',
					'value'     => '%s',
					'important' => false,
					'mobile'    => true,
				),
			) );

			foreach ( $css_scheme as $setting => $data ) {

				$value = ava_menu_option_page()->get_option( $setting );

				if ( empty( $value ) ) {
					continue;
				}

				$_wrapper = $wrapper;

				if ( isset( $data['mobile'] ) && true === $data['mobile'] ) {
					$_wrapper = '.ava-mobile-menu-active';
				}

				if ( isset( $data['desktop'] ) && true === $data['desktop'] ) {
					$_wrapper = '.ava-desktop-menu-active ' . $_wrapper;
				}

				if ( is_array( $value ) && isset( $value['units'] ) ) {

					ava_menu_dynmic_css()->add_dimensions_css(
						array(
							'selector'  => sprintf( '%1$s %2$s', $_wrapper, $data['selector'] ),
							'rule'      => $data['rule'],
							'values'    => $value,
							'important' => $data['important'],
						)
					);

					continue;
				}

				$important = ( true === $data['important'] ) ? ' !important' : '';

				ava_menu()->dynamic_css()->add_style(
					sprintf( '%1$s %2$s', $_wrapper, $data['selector'] ),
					array(
						$data['rule'] => sprintf( $data['value'], esc_attr( $value ) ) . $important,
					)
				);

			}

			$items_map = array(
				'first' => array(
					'top-left'    => 'top',
					'bottom-left' => 'left',
				),
				'last'  => array(
					'top-right'    => 'right',
					'bottom-right' => 'bottom',
				),
			);

			foreach ( $items_map as $item => $data ) {

				$parent_radius = ava_menu_option_page()->get_option( 'ava-menu-mega-border-radius' );

				if ( ! $parent_radius ) {
					continue;
				}

				$is_enabled = ava_menu_option_page()->get_option( 'ava-menu-inherit-' . $item . '-radius' );

				if ( 'true' !== $is_enabled ) {
					continue;
				}

				$styles = array();

				foreach ( $data as $rule => $val ) {

					if ( ! $parent_radius ) {
						continue;
					}

					$styles[ 'border-' . $rule . '-radius' ] = $parent_radius[ $val ] . $parent_radius['units'];
				}

				if ( ! empty( $styles ) ) {

					$selector = '%1$s > .ava-menu-item:%2$s-child > .top-level-link';

					if ( 'last' === $item ) {
						$selectors = array(
							'%1$s > .ava-regular-item.ava-has-roll-up:nth-last-child(2) .top-level-link',
							'%1$s > .ava-regular-item.ava-no-roll-up:nth-last-child(1) .top-level-link',
							'%1$s > .ava-responsive-menu-available-items:last-child .top-level-link',
						);

						$selector = join( ',', $selectors );
					}

					ava_menu()->dynamic_css()->add_style(
						sprintf( $selector, $wrapper, $item ),
						$styles
					);
				}

			}

			// extra options
			$max_width = ava_menu_option_page()->get_option( 'ava-menu-item-max-width', 0 );

			if ( 0 !== absint( $max_width ) ) {
				ava_menu()->dynamic_css()->add_style(
					sprintf( '.ava-desktop-menu-active %1$s > .ava-menu-item', $wrapper ),
					array(
						'max-width' => absint( $max_width ) . '%',
					)
				);
			}

			ava_menu()->dynamic_css()->add_style(
				sprintf( '%1$s .ava-menu-badge', $wrapper ),
				array(
					'display' => 'block',
				)
			);

			$menu_align = ava_menu_option_page()->get_option( 'ava-menu-container-alignment' );

			if ( 'stretch' === $menu_align ) {
				ava_menu()->dynamic_css()->add_style(
					sprintf( '.ava-desktop-menu-active %1$s > .ava-menu-item', $wrapper ),
					array(
						'flex-grow' => 1,
					)
				);

				ava_menu()->dynamic_css()->add_style(
					sprintf( '.ava-desktop-menu-active %1$s > .ava-menu-item > a', $wrapper ),
					array(
						'justify-content' => 'center',
					)
				);
			}

		}

		/**
		 * Get menu ID for current location
		 *
		 * @param  [type] $location [description]
		 * @return [type]           [description]
		 */
		public function get_menu_id( $location = null ) {
			$locations = get_nav_menu_locations();
			return isset( $locations[ $location ] ) ? $locations[ $location ] : false;
		}

		/**
		 * Save in object chache trigger that defines we output menu in Elementor
		 *
		 * @return void
		 */
		public function set_elementor_mode() {
			wp_cache_set( 'ava-menu-in-elementor', true );
		}

		/**
		 * Reset trigger that defines we output menu in Elementor
		 *
		 * @return void
		 */
		public function reset_elementor_mode() {
			wp_cache_delete( 'ava-menu-in-elementor' );
		}

		/**
		 * Check if current menu inside Elementor
		 *
		 * @return boolean
		 */
		public function is_elementor_mode() {
			return wp_cache_get( 'ava-menu-in-elementor' );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
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

/**
 * Returns instance of Ava_Menu_Public_Manager
 *
 * @return object
 */
function ava_menu_public_manager() {
	return Ava_Menu_Public_Manager::get_instance();
}
