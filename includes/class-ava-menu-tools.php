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

if ( ! class_exists( 'Ava_Menu_Tools' ) ) {

	/**
	 * Define Ava_Menu_Tools class
	 */
	class Ava_Menu_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Render Icon HTML
		 *
		 * @param  string $icon Icon slug to render.
		 * @return string
		 */
		public function get_icon_html( $icon = '' ) {
			$format = apply_filters( 'ava-menu/tools/icon-format', '<i class="ava-menu-icon fa %s"></i>', $icon );
			return sprintf( $format, esc_attr( $icon ) );
		}

		/**
		 * Render Icon HTML
		 *
		 * @param  string $icon      Icon slug to render.
		 * @param  string $icon_base Base icon class to render.
		 * @return string
		 */
		public function get_dropdown_arrow_html( $icon = '', $icon_base = 'fa' ) {
			$format = apply_filters(
				'ava-menu/tools/dropdown-arrow-format',
				'<i class="ava-dropdown-arrow %2$s %1$s"></i>',
				$icon,
				$icon_base
			);
			return sprintf( $format, esc_attr( $icon ), esc_attr( $icon_base ) );
		}

		/**
		 * Get menu badge HTML
		 *
		 * @param  string $badge Badge HTML.
		 * @return string
		 */
		public function get_badge_html( $badge = '', $depth = 0 ) {

			if ( 0 < $depth ) {
				$is_hidden = ava_menu_option_page()->get_option( 'ava-menu-sub-badge-hide', 'false' );
			} else {
				$is_hidden = ava_menu_option_page()->get_option( 'ava-menu-top-badge-hide', 'false' );
			}

			$hide_on_mobile = ( 'true' === $is_hidden ) ? ' ava-hide-mobile' : '';

			$format = apply_filters(
				'ava-menu/tools/badge-format',
				'<small class="ava-menu-badge%2$s"><span class="ava-menu-badge__inner">%1$s</span></small>',
				$badge,
				$depth
			);
			return sprintf( $format, esc_attr( $badge ), $hide_on_mobile );
		}

		/**
		 * Add menu item dynamic CSS
		 *
		 * @param integer $item_id [description]
		 * @param string  $wrapper [description]
		 */
		public function add_menu_css( $item_id = 0, $wrapper = '' ) {

			$settings   = ava_menu_settings_item()->get_settings( $item_id );
			$css_scheme = apply_filters( 'ava-menu/item-css/sheme', array(
				'icon_color' => array(
					'selector' => '> a .ava-menu-icon:before',
					'rule'     => 'color',
					'value'    => '%1$s !important;',
				),
				'badge_color' => array(
					'selector' => '> a .ava-menu-badge .ava-menu-badge__inner',
					'rule'     => 'color',
					'value'    => '%1$s !important;',
				),
				'badge_bg_color' => array(
					'selector' => '> a .ava-menu-badge .ava-menu-badge__inner',
					'rule'     => 'background-color',
					'value'    => '%1$s !important;',
				),
				'item_padding' => array(
					'selector' => '> a',
					'rule'     => 'padding-%s',
					'value'    => '',
					'desktop'  => true,
				),
				'custom_mega_menu_width' => array(
					'selector' => '> .ava-sub-mega-menu',
					'rule'     => 'width',
					'value'    => '%1$spx !important;',
				),
				// for Vertical Mega Menu
				'mega_menu_width' => array(
					'selector' => '> .ava-custom-nav__mega-sub',
					'rule'     => 'width',
					'value'    => '%1$spx !important;',
				),
			) );

			foreach ( $css_scheme as $setting => $data ) {

				if ( empty( $settings[ $setting ] ) ) {
					continue;
				}

				$_wrapper = $wrapper;

				if ( isset( $data['desktop'] ) && true === $data['desktop'] ) {
					$_wrapper = 'body:not(.ava-mobile-menu-active) ' . $wrapper;
				}

				if ( is_array( $settings[ $setting ] ) && isset( $settings[ $setting ]['units'] ) ) {

					ava_menu_dynmic_css()->add_dimensions_css(
						array(
							'selector'  => sprintf( '%1$s %2$s', $_wrapper, $data['selector'] ),
							'rule'      => $data['rule'],
							'values'    => $settings[ $setting ],
							'important' => true,
						)
					);

				} else {

					ava_menu()->dynamic_css()->add_style(
						sprintf( '%1$s %2$s', $_wrapper, $data['selector'] ),
						array(
							$data['rule'] => sprintf( $data['value'], esc_attr( $settings[ $setting ] ) ),
						)
					);

				}

			}

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
 * Returns instance of Ava_Menu_Tools
 *
 * @return object
 */
function ava_menu_tools() {
	return Ava_Menu_Tools::get_instance();
}
