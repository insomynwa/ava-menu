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

if ( ! class_exists( 'Ava_Menu_Dynamic_CSS' ) ) {

	/**
	 * Define Ava_Menu_Dynamic_CSS class
	 */
	class Ava_Menu_Dynamic_CSS {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Builder instance
		 *
		 * @var null
		 */
		public $builder = null;

		/**
		 * Fonts holder.
		 *
		 * @var array
		 */
		private $fonts = null;

		/**
		 * Initialize builde rinstance
		 *
		 * @param  [type] $builder [description]
		 * @return [type]          [description]
		 */
		public function init_builder( $builder ) {
			$this->builder= $builder;
		}

		/**
		 * Register typography options.
		 *
		 * @param array $args [description]
		 */
		public function add_typography_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'   => '',
				'name'    => '',
				'parent'  => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s typography', 'ava-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-font-size' => array(
						'type'       => 'slider',
						'max_value'  => 70,
						'min_value'  => 8,
						'value'      => $this->get_option(
							$args['name'] . '-font-size',
							isset( $args['defaults']['font-size'] ) ? $args['defaults']['font-size'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s font size', 'ava-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-show',
					),
					$args['name'] . '-font-family' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s font family', 'ava-menu' ), $args['label'] ),
						'filter'           => true,
						'value'            => $this->get_option(
							$args['name'] . '-font-family',
							isset( $args['defaults']['font-family'] ) ? $args['defaults']['font-family'] : false
						),
						'options'          => $this->get_fonts_list(),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-font-weight' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s font weight', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-font-weight',
							isset( $args['defaults']['font-weight'] ) ? $args['defaults']['font-weight'] : false
						),
						'options'          => array(
							''       => esc_html__( 'Default', 'ava-menu' ),
							'100'    => '100',
							'200'    => '200',
							'300'    => '300',
							'400'    => '400',
							'500'    => '500',
							'600'    => '600',
							'700'    => '700',
							'800'    => '800',
							'900'    => '900',
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-text-transform' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s text transform', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-text-transform',
							isset( $args['defaults']['text-transform'] ) ? $args['defaults']['text-transform'] : false
						),
						'options'          => array(
							''           => esc_html__( 'Default', 'ava-menu' ),
							'uppercase'  => esc_html__( 'Uppercase', 'ava-menu' ),
							'lowercase'  => esc_html__( 'Lowercase', 'ava-menu' ),
							'capitalize' => esc_html__( 'Capitalize', 'ava-menu' ),
							'none'       => esc_html__( 'Normal', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-font-style' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s font style', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-font-style',
							isset( $args['defaults']['font-style'] ) ? $args['defaults']['font-style'] : false
						),
						'options'          => array(
							''           => esc_html__( 'Default', 'ava-menu' ),
							'normal' => esc_html__( 'Normal', 'ava-menu' ),
							'italic' => esc_html__( 'Italic', 'ava-menu' ),
							'oblique' => esc_html__( 'Oblique', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-line-height' => array(
						'type'       => 'slider',
						'max_value'  => 10,
						'min_value'  => 0.1,
						'value'      => $this->get_option(
							$args['name'] . '-line-height',
							isset( $args['defaults']['line-height'] ) ? $args['defaults']['line-height'] : false
						),
						'step_value' => 0.1,
						'title'      => sprintf( esc_html__( '%s line height', 'ava-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-show',
					),
					$args['name'] . '-letter-spacing' => array(
						'type'       => 'slider',
						'max_value'  => 5,
						'min_value'  => -5,
						'value'      => $this->get_option(
							$args['name'] . '-letter-spacing',
							isset( $args['defaults']['letter-spacing'] ) ? $args['defaults']['letter-spacing'] : false
						),
						'step_value' => 0.1,
						'title'      => sprintf( esc_html__( '%s letter spacing', 'ava-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-show',
					),
					$args['name'] . '-subset' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s subset', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-subset',
							isset( $args['defaults']['subset'] ) ? $args['defaults']['subset'] : false
						),
						'options'          => array(
							'latin'    => esc_html__( 'Latin', 'ava-menu' ),
							'greek'    => esc_html__( 'Greek', 'ava-menu' ),
							'cyrillic' => esc_html__( 'Cyrillic', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
				)
			);

		}

		/**
		 * Background options array
		 *
		 * @param array $args [description]
		 */
		public function add_background_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'    => '',
				'name'     => '',
				'parent'   => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s background', 'ava-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s background color', 'ava-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-color',
							isset( $args['defaults']['color'] ) ? $args['defaults']['color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-show',
					),
					$args['name'] . '-gradient-switch' => array(
						'type'   => 'switcher',
						'title'  => esc_html__( 'Gradient background', 'ava-menu' ),
						'value'  => $this->get_option( $args['name'] . '-gradient-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
						'parent' => $args['parent'],
						'master'      => $args['name'] . '-show',
					),
					$args['name'] . '-second-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s background second color', 'ava-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-second-color',
							isset( $args['defaults']['second-color'] ) ? $args['defaults']['second-color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-show',
					),
					$args['name'] . '-direction' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background gradient direction', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-direction',
							isset( $args['defaults']['direction'] ) ? $args['defaults']['direction'] : false
						),
						'options'          => array(
							'right'  => esc_html__( 'From Left to Right', 'ava-menu' ),
							'left'   => esc_html__( 'From Right to Left', 'ava-menu' ),
							'bottom' => esc_html__( 'From Top to Bottom', 'ava-menu' ),
							'top'    => esc_html__( 'From Bottom to Top', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-image' => array(
						'type'           => 'media',
						'parent'         => $args['parent'],
						'title'          => sprintf( esc_html__( '%s background image', 'ava-menu' ), $args['label'] ),
						'value'          => $this->get_option(
							$args['name'] . '-image',
							isset( $args['defaults']['image'] ) ? $args['defaults']['image'] : false
						),
						'multi_upload'       => false,
						'library_type'       => 'image',
						'upload_button_text' => esc_html__( 'Choose Image', 'ava-menu' ),
						'class'              => '',
						'label'              => '',
						'master'             => $args['name'] . '-show',
					),
					$args['name'] . '-position' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background position', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-position',
							isset( $args['defaults']['position'] ) ? $args['defaults']['position'] : false
						),
						'options'          => array(
							''              => esc_html__( 'Default', 'ava-menu' ),
							'top left'      => esc_html__( 'Top Left', 'ava-menu' ),
							'top center'    => esc_html__( 'Top Center', 'ava-menu' ),
							'top right'     => esc_html__( 'Top Right', 'ava-menu' ),
							'center left'   => esc_html__( 'Center Left', 'ava-menu' ),
							'center center' => esc_html__( 'Center Center', 'ava-menu' ),
							'center right'  => esc_html__( 'Center Right', 'ava-menu' ),
							'bottom left'   => esc_html__( 'Bottom Left', 'ava-menu' ),
							'bottom center' => esc_html__( 'Bottom Center', 'ava-menu' ),
							'bottom right'  => esc_html__( 'Bottom Right', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-attachment' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background attachment', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-attachment',
							isset( $args['defaults']['attachment'] ) ? $args['defaults']['attachment'] : false
						),
						'options'          => array(
							''       => esc_html__( 'Default', 'ava-menu' ),
							'scroll' => esc_html__( 'Scroll', 'ava-menu' ),
							'fixed'  => esc_html__( 'Fixed', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-repeat' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background repeat', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-repeat',
							isset( $args['defaults']['repeat'] ) ? $args['defaults']['repeat'] : false
						),
						'options'          => array(
							''          => esc_html__( 'Default', 'ava-menu' ),
							'no-repeat' => esc_html__( 'No Repeat', 'ava-menu' ),
							'repeat'    => esc_html__( 'Repeat', 'ava-menu' ),
							'repeat-x'  => esc_html__( 'Repeat X', 'ava-menu' ),
							'repeat-y'  => esc_html__( 'Repeat Y', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-size' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background size', 'ava-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-size',
							isset( $args['defaults']['size'] ) ? $args['defaults']['size'] : false
						),
						'options'          => array(
							''        => esc_html__( 'Default', 'ava-menu' ),
							'auto'    => esc_html__( 'Auto', 'ava-menu' ),
							'cover'   => esc_html__( 'Cover', 'ava-menu' ),
							'contain' => esc_html__( 'Contain', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
				)
			);

		}

		/**
		 * Register border options
		 *
		 * @param array $args [description]
		 */
		public function add_border_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'    => '',
				'name'     => '',
				'parent'   => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-border-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s border', 'ava-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-border-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-border-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-border-style' => array(
						'type'    => 'select',
						'parent'  => $args['parent'],
						'title'   => sprintf( esc_html__( '%s border style', 'ava-menu' ), $args['label'] ),
						'value'   => $this->get_option(
							$args['name'] . '-border-style',
							isset( $args['defaults']['border-style'] ) ? $args['defaults']['border-style'] : false
						),
						'options' => array(
							'solid'  => esc_html__( 'Solid', 'ava-menu' ),
							'double' => esc_html__( 'Double', 'ava-menu' ),
							'dotted' => esc_html__( 'Dotted', 'ava-menu' ),
							'dashed' => esc_html__( 'Dashed', 'ava-menu' ),
							'none'   => esc_html__( 'None', 'ava-menu' ),
						),
						'master'           => $args['name'] . '-border-show',
					),
					$args['name'] . '-border-width' => array(
						'type'        => 'dimensions',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s border width', 'ava-menu' ), $args['label'] ),
						'range'       => array(
							'px' => array(
								'min'  => 0,
								'max'  => 30,
								'step' => 1,
							),
						),
						'value' => $this->get_option(
							$args['name'] . '-border-width',
							isset( $args['defaults']['border-width'] ) ? $args['defaults']['border-width'] : false
						),
						'master' => $args['name'] . '-border-show',
					),
					$args['name'] . '-border-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s border color', 'ava-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-border-color',
							isset( $args['defaults']['border-color'] ) ? $args['defaults']['border-color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-border-show',
					),
				)
			);

		}

		/**
		 * Register box-shadow options
		 *
		 * @param array $args [description]
		 */
		public function add_box_shadow_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'    => '',
				'name'     => '',
				'parent'   => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-box-shadow-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s box shadow', 'ava-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-box-shadow-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-box-shadow-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-box-shadow-h' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-h',
							isset( $args['defaults']['box-shadow-h'] ) ? $args['defaults']['box-shadow-h'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - position of the horizontal shadow', 'ava-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-v' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-v',
							isset( $args['defaults']['box-shadow-v'] ) ? $args['defaults']['box-shadow-v'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - position of the vertical shadow', 'ava-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-blur' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-blur',
							isset( $args['defaults']['box-shadow-blur'] ) ? $args['defaults']['box-shadow-blur'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - shadow blur distance', 'ava-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-spread' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-spread',
							isset( $args['defaults']['box-shadow-spread'] ) ? $args['defaults']['box-shadow-spread'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - shadow size', 'ava-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s shadow color', 'ava-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-box-shadow-color',
							isset( $args['defaults']['box-shadow-color'] ) ? $args['defaults']['box-shadow-color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-inset' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s shadow inset', 'ava-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-box-shadow-color',
							isset( $args['defaults']['box-shadow-inset'] ) ? $args['defaults']['box-shadow-inset'] : 'false'
						),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
						'parent' => $args['parent'],
						'master' => $args['name'] . '-box-shadow-show',
					),
				)
			);

		}

		/**
		 * Returns google fonts list.
		 *
		 * @return array
		 */
		public function get_fonts_list() {

			if ( empty( $this->fonts ) ) {
				$this->fonts = ava_menu()->customizer()->get_fonts();
				$this->fonts = array_merge( array( '0' => esc_html__( 'Select Font...', 'ava-menu' ) ), $this->fonts );
			}

			return $this->fonts;
		}

		/**
		 * Add font-related styles.
		 */
		public function add_fonts_styles( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.ava-menu';

			$fonts_options = apply_filters( 'ava-menu/menu-css/fonts', array(
				'ava-top-menu'       => '.ava-menu-item .top-level-link',
				'ava-top-menu-desc'  => '.ava-menu-item-desc.top-level-desc',
				'ava-sub-menu'       => '.ava-menu-item .sub-level-link',
				'ava-sub-menu-desc'  => '.ava-menu-item-desc.sub-level-desc',
				'ava-menu-top-badge' => '.ava-menu-item .top-level-link .ava-menu-badge__inner',
				'ava-menu-sub-badge' => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
			) );

			foreach ( $fonts_options as $font => $selector ) {
				$this->add_single_font_styles( $font, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * Add backgound styles.
		 */
		public function add_backgrounds( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.ava-menu';

			$bg_options = apply_filters( 'ava-menu/menu-css/backgrounds', array(
				'ava-menu-container'        => '',
				'ava-menu-item'             => '.ava-menu-item .top-level-link',
				'ava-menu-item-hover'       => '.ava-menu-item:hover > .top-level-link',
				'ava-menu-item-active'      => '.ava-menu-item.ava-current-menu-item .top-level-link',
				'ava-menu-top-badge-bg'     => '.ava-menu-item .top-level-link .ava-menu-badge__inner',
				'ava-menu-sub-badge-bg'     => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
				'ava-menu-sub-panel-simple' => 'ul.ava-sub-menu',
				'ava-menu-sub-panel-mega'   => 'div.ava-sub-mega-menu',
				'ava-menu-sub'              => 'li.ava-sub-menu-item .sub-level-link',
				'ava-menu-sub-hover'        => 'li.ava-sub-menu-item:hover > .sub-level-link',
				'ava-menu-sub-active'       => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
			) );

			foreach ( $bg_options as $option => $selector ) {
				$this->add_single_bg_styles( $option, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * Add border styles.
		 */
		public function add_borders( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.ava-menu';

			$options = apply_filters( 'ava-menu/menu-css/borders', array(
				'ava-menu-container'         => '',
				'ava-menu-item'              => '.ava-menu-item .top-level-link',
				'ava-menu-first-item'        => '> .ava-regular-item:first-child .top-level-link',
				'ava-menu-last-item'         => array(
					'> .ava-regular-item.ava-has-roll-up:nth-last-child(2) .top-level-link',
					'> .ava-regular-item.ava-no-roll-up:nth-last-child(1) .top-level-link',
					'> .ava-responsive-menu-available-items:last-child .top-level-link',
				),
				'ava-menu-item-hover'        => '.ava-menu-item:hover > .top-level-link',
				'ava-menu-first-item-hover'  => '> .ava-regular-item:first-child:hover > .top-level-link',
				'ava-menu-last-item-hover'   => array(
					'> .ava-regular-item.ava-has-roll-up:nth-last-child(2):hover .top-level-link',
					'> .ava-regular-item.ava-no-roll-up:nth-last-child(1):hover .top-level-link',
					'> .ava-responsive-menu-available-items:last-child:hover .top-level-link',
				),
				'ava-menu-item-active'       => '.ava-menu-item.ava-current-menu-item .top-level-link',
				'ava-menu-first-item-active' => '> .ava-regular-item:first-child.ava-current-menu-item .top-level-link',
				'ava-menu-last-item-active'  => array(
					'> .ava-regular-item.ava-current-menu-item.ava-has-roll-up:nth-last-child(2) .top-level-link',
					'> .ava-regular-item.ava-current-menu-item.ava-no-roll-up:nth-last-child(1) .top-level-link',
					'> .ava-responsive-menu-available-items.ava-current-menu-item:last-child .top-level-link',
				),
				'ava-menu-top-badge'         => '.ava-menu-item .top-level-link .ava-menu-badge__inner',
				'ava-menu-sub-badge'         => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
				'ava-menu-sub-panel-simple'  => 'ul.ava-sub-menu',
				'ava-menu-sub-panel-mega'    => 'div.ava-sub-mega-menu',
				'ava-menu-sub'               => 'li.ava-sub-menu-item .sub-level-link',
				'ava-menu-sub-hover'         => 'li.ava-sub-menu-item:hover > .sub-level-link',
				'ava-menu-sub-active'        => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
				'ava-menu-sub-first'         => '.ava-sub-menu > li.ava-sub-menu-item:first-child > .sub-level-link',
				'ava-menu-sub-first-hover'   => '.ava-sub-menu > li.ava-sub-menu-item:first-child:hover > .sub-level-link',
				'ava-menu-sub-first-active'  => '.ava-sub-menu > li.ava-sub-menu-item.ava-current-menu-item:first-child > .sub-level-link',
				'ava-menu-sub-last'          => '.ava-sub-menu > li.ava-sub-menu-item:last-child > .sub-level-link',
				'ava-menu-sub-last-hover'    => '.ava-sub-menu > li.ava-sub-menu-item:last-child:hover > .sub-level-link',
				'ava-menu-sub-last-active'   => '.ava-sub-menu > li.ava-sub-menu-item.ava-current-menu-item:last-child > .sub-level-link',
			) );

			foreach ( $options as $option => $selector ) {

				if ( is_array( $selector ) ) {

					$final_selector = '';
					$delimiter      = '';

					foreach ( $selector as $part ) {
						$final_selector .= sprintf(
							'%3$s%1$s %2$s',
							$wrapper,
							$part,
							$delimiter
						);
						$delimiter = ', ';
					}
				} else {
					$final_selector = $wrapper . ' ' . $selector;
				}

				$this->add_single_border_styles( $option, $final_selector );
			}

		}

		/**
		 * Add shadows styles.
		 */
		public function add_shadows( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.ava-menu';

			$options = apply_filters( 'ava-menu/menu-css/shadows', array(
				'ava-menu-container'        => '',
				'ava-menu-item'             => '.ava-menu-item .top-level-link',
				'ava-menu-item-hover'       => '.ava-menu-item:hover > .top-level-link',
				'ava-menu-item-active'      => '.ava-menu-item.ava-current-menu-item .top-level-link',
				'ava-menu-top-badge'        => '.ava-menu-item .top-level-link .ava-menu-badge__inner',
				'ava-menu-sub-badge'        => '.ava-menu-item .sub-level-link .ava-menu-badge__inner',
				'ava-menu-sub-panel-simple' => 'ul.ava-sub-menu',
				'ava-menu-sub-panel-mega'   => 'div.ava-sub-mega-menu',
				'ava-menu-sub'              => 'li.ava-sub-menu-item .sub-level-link',
				'ava-menu-sub-hover'        => 'li.ava-sub-menu-item:hover > .sub-level-link',
				'ava-menu-sub-active'       => 'li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
			) );

			foreach ( $options as $option => $selector ) {
				$this->add_single_shadow_styles( $option, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * Add single font styles
		 */
		public function add_single_font_styles( $font, $selector ) {

			$enbaled = $this->get_option( $font . '-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$font_settings = array(
				'font-size'      => 'px',
				'font-family'    => '',
				'font-weight'    => '',
				'text-transform' => '',
				'font-style'     => '',
				'line-height'    => 'em',
				'letter-spacing' => 'em',
			);

			foreach ( $font_settings as $setting => $units ) {

				$value = $this->get_option( $font . '-' . $setting );

				if ( '' === $value || false === $value ) {
					continue;
				}

				if ( 'font-family' === $setting && 0 === $value ) {
					continue;
				}

				ava_menu()->dynamic_css()->add_style(
					$selector,
					array(
						$setting => $value . $units,
					)
				);
			}

		}

		/**
		 * Add single background option.
		 *
		 * @param [type] $options  [description]
		 * @param [type] $selector [description]
		 */
		public function add_single_bg_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$type = $this->get_option( $option . '-bg-type' );

			$settings = array(
				'color',
				'image',
				'position',
				'attachment',
				'repeat',
				'size',
			);

			$is_gradient = $this->get_option( $option . '-gradient-switch' );

			foreach ( $settings as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value ) {
					continue;
				}

				if ( 'image' === $setting && 'true' !== $is_gradient ) {
					$value = wp_get_attachment_image_url( $value, 'full' );
					$value = sprintf( 'url("%s")', esc_url( $value ) );
				}

				ava_menu()->dynamic_css()->add_style(
					$selector,
					array(
						'background-' . $setting => $value,
					)
				);

			}

			if ( 'true' === $is_gradient ) {
				$color_start = $this->get_option( $option . '-color' );
				$color_end   = $this->get_option( $option . '-second-color' );
				$direction   = $this->get_option( $option . '-direction', 'horizontal' );

				if ( ! $color_start || ! $color_end ) {
					return;
				}

				ava_menu()->dynamic_css()->add_style(
					$selector,
					array(
						'background-image' => sprintf(
							'linear-gradient( to %1$s, %2$s, %3$s )',
							$direction, $color_start, $color_end
						),
					)
				);

			}

		}

		public function add_dimensions_css( $args = array() ) {

			$defaults = array(
				'selector'  => '',
				'rule'      => '',
				'values'    => array(),
				'important' => false,
			);

			$args = wp_parse_args( $args, $defaults );

			$value     = $args['values'];
			$selector  = $args['selector'];
			$rule      = $args['rule'];
			$important = ( true === $args['important'] ) ? ' !important' : '';

			$properties = array(
				'top'    => 'top-left',
				'right'  => 'top-right',
				'bottom' => 'bottom-right',
				'left'   => 'bottom-left',
			);

			foreach ( $properties as $position => $radius_position ) {

				if ( isset( $value[ $position ] ) && '' !== $value[ $position ] ) {

					$prop = $value[ $position ] . $value['units'] . $important;

					if ( false !== strpos( $rule, 'radius' ) ) {
						ava_menu()->dynamic_css()->add_style(
							$selector,
							array(
								sprintf( $rule, $radius_position ) => $prop,
							)
						);
					} else {
						ava_menu()->dynamic_css()->add_style(
							$selector,
							array(
								sprintf( $rule, $position ) => $prop,
							)
						);
					}

				}

			}
		}

		/**
		 * Add single border option.
		 *
		 * @param [type] $options  [description]
		 * @param [type] $selector [description]
		 */
		public function add_single_border_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-border-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$type = $this->get_option( $option . '-bg-type' );

			$settings = array(
				'border-style',
				'border-width',
				'border-color',
			);

			foreach ( $settings as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value ) {
					continue;
				}

				if ( 'border-width' === $setting ) {

					ava_menu_dynmic_css()->add_dimensions_css(
						array(
							'selector' => $selector,
							'rule'     => 'border-%s-width',
							'values'   => $value,
						)
					);

					continue;
				}

				ava_menu()->dynamic_css()->add_style(
					$selector,
					array(
						$setting => $value,
					)
				);

			}

		}

		public function add_single_shadow_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-box-shadow-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$result = '';

			foreach ( array( 'box-shadow-h', 'box-shadow-v', 'box-shadow-blur' ) as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value ) {
					$value = 0;
				}

				$result .= $value . 'px ';
			}

			$spread = $this->get_option( $option . '-box-shadow-spread' );

			if ( '' !== $spread && false !== $spread ) {
				$result .= $spread . 'px ';
			}

			$color = $this->get_option( $option . '-box-shadow-color' );

			if ( '' !== $color && false !== $color ) {
				$result .= $color;
			}

			$inset = $this->get_option( $option . '-box-shadow-inset' );

			if ( 'true' === $inset ) {
				$result .= ' inset';
			}

			ava_menu()->dynamic_css()->add_style(
				$selector,
				array(
					'box-shadow' => $result,
				)
			);

		}

		/**
		 * Process position styles
		 */
		public function add_positions( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.ava-menu';

			$options = apply_filters( 'ava-menu/menu-css/positions', array(
				'ava-menu-top-icon-%s-position'  => '.ava-menu-item .top-level-link .ava-menu-icon',
				'ava-menu-sub-icon-%s-position'  => '.ava-menu-item .sub-level-link .ava-menu-icon',
				'ava-menu-top-badge-%s-position' => '.ava-menu-item .top-level-link .ava-menu-badge',
				'ava-menu-sub-badge-%s-position' => '.ava-menu-item .sub-level-link .ava-menu-badge',
				'ava-menu-top-arrow-%s-position' => '.ava-menu-item .top-level-link .ava-dropdown-arrow',
				'ava-menu-sub-arrow-%s-position' => '.ava-menu-item .sub-level-link .ava-dropdown-arrow',
			) );

			foreach ( $options as $option => $selector ) {
				$this->add_single_position( $option, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * add single position
		 */
		public function add_single_position( $option, $selector ) {

			$v_pos = $this->get_option( sprintf( $option, 'ver' ) );
			$h_pos = $this->get_option( sprintf( $option, 'hor' ) );

			$order_map = array(
				'left'  => -1,
				'right' => 2,
			);

			$styles = array();

			switch ( $v_pos ) {

				case 'top':
					$styles = array(
						'flex'  => '0 0 100%',
						'width' => 0,
						'order' => -2,
					);
					break;

				case 'center':
					$styles = array(
						'align-self' => 'center',
					);
					break;

				case 'bottom':
					$styles = array(
						'flex'  => '0 0 100%',
						'width' => 0,
						'order' => 2,
					);
					break;
			}

			switch ( $h_pos ) {

				case 'left':
				case 'right':

					if ( in_array( $v_pos, array( 'top', 'bottom' ) ) ) {
						$styles['text-align'] = $h_pos;
					} else {
						$styles['order'] = $order_map[ $h_pos ];
					}
					break;

				case 'center':
					if ( in_array( $v_pos, array( 'top', 'bottom' ) ) ) {
						$styles['text-align'] = 'center';
					}
					break;

			}

			if ( 'ava-menu-sub-arrow-%s-position' === $option && 'right' === $h_pos ) {
				$styles['margin-left'] = 'auto !important';
			}

			if ( ! empty( $styles ) ) {
				ava_menu()->dynamic_css()->add_style( $selector, $styles );
			}

		}

		/**
		 * Get option wrapper
		 *
		 * @param  string  $option  [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get_option( $option = '', $default = false ) {
			return ava_menu_option_page()->get_option( $option, $default );
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
 * Returns instance of Ava_Menu_Dynamic_CSS
 *
 * @return object
 */
function ava_menu_dynmic_css() {
	return Ava_Menu_Dynamic_CSS::get_instance();
}
