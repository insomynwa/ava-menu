<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * HTML Widget
 */
class Ava_Widget_Mega_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ava-mega-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Mega Menu', 'ava-menu' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'avamenu-icon-86';
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/avamenu-how-to-create-a-mega-menu-using-elementor-with-avamenu-widget/?utm_source=avamenu&utm_medium=ava-mega-menu&utm_campaign=need-help';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'cherry' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Menu', 'ava-menu' ),
			)
		);

		$parent = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;

		if ( $parent ) {
			$this->add_control(
				'menu_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => esc_html__( 'This module can\'t be used inside Mega Menu content. Please, use it to show selected Mega Menu on specific page.', 'ava-menu' )
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'label'   => esc_html__( 'Select Menu', 'ava-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $this->get_available_menus(),
				)
			);

			do_action( 'ava-menu/widgets/mega-menu/controls', $this );

		}

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'ava-menu/mega-menu/css-scheme',
			array(
				'container'                    => '.ava-menu',
				'top_level_item'               => '.ava-menu > .ava-menu-item',
				'top_level_link'               => '.ava-menu .ava-menu-item .top-level-link',
				'top_level_link_hover'         => '.ava-menu .ava-menu-item:hover > .top-level-link',
				'top_level_link_active'        => '.ava-menu .ava-menu-item.ava-current-menu-item .top-level-link',
				'top_level_desc'               => '.ava-menu .ava-menu-item .ava-menu-item-desc.top-level-desc',
				'top_level_desc_hover'         => '.ava-menu .ava-menu-item:hover > .top-level-link .ava-menu-item-desc.top-level-desc',
				'top_level_desc_active'        => '.ava-menu .ava-menu-item.ava-current-menu-item .ava-menu-item-desc.top-level-desc',
				'top_level_icon'               => '.ava-menu .ava-menu-item .top-level-link .ava-menu-icon',
				'top_level_icon_hover'         => '.ava-menu .ava-menu-item:hover > .top-level-link .ava-menu-icon',
				'top_level_icon_active'        => '.ava-menu .ava-menu-item.ava-current-menu-item .top-level-link .ava-menu-icon',
				'top_level_arrow'              => '.ava-menu .ava-menu-item .top-level-link .ava-dropdown-arrow',
				'top_level_arrow_hover'        => '.ava-menu .ava-menu-item:hover > .top-level-link .ava-dropdown-arrow',
				'top_level_arrow_active'       => '.ava-menu .ava-menu-item.ava-current-menu-item .top-level-link .ava-dropdown-arrow',
				'top_level_badge'              => '.ava-menu .ava-menu-item .top-level-link .ava-menu-badge__inner',
				'top_level_badge_wrapper'      => '.ava-menu .ava-menu-item .top-level-link .ava-menu-badge',
				'first_top_level_link'         => '.ava-menu > .ava-regular-item:first-child .top-level-link',
				'first_top_level_link_hover'   => '.ava-menu > .ava-regular-item:first-child:hover > .top-level-link',
				'first_top_level_link_active'  => '.ava-menu > .ava-regular-item:first-child.ava-current-menu-item .top-level-link',
				'last_top_level_link'          => '.ava-menu > .ava-regular-item.ava-has-roll-up:nth-last-child(2) .top-level-link',
				'last_top_level_link_2'        => '.ava-menu > .ava-regular-item.ava-no-roll-up:nth-last-child(1) .top-level-link',
				'last_top_level_link_3'        => '.ava-menu > .ava-responsive-menu-available-items:last-child .top-level-link',
				'last_top_level_link_hover'    => '.ava-menu > .ava-regular-item.ava-has-roll-up:nth-last-child(2):hover .top-level-link',
				'last_top_level_link_2_hover'  => '.ava-menu > .ava-regular-item.ava-no-roll-up:nth-last-child(1):hover .top-level-link',
				'last_top_level_link_3_hover'  => '.ava-menu > .ava-responsive-menu-available-items:last-child:hover .top-level-link',
				'last_top_level_link_active'   => '.ava-menu > .ava-regular-item.ava-current-menu-item.ava-has-roll-up:nth-last-child(2) .top-level-link',
				'last_top_level_link_2_active' => '.ava-menu > .ava-regular-item.ava-current-menu-item.ava-no-roll-up:nth-last-child(1) .top-level-link',
				'last_top_level_link_3_active' => '.ava-menu > .ava-responsive-menu-available-items.ava-current-menu-item:last-child .top-level-link',

				'simple_sub_panel' => '.ava-menu ul.ava-sub-menu',
				'mega_sub_panel'   => '.ava-menu div.ava-sub-mega-menu',

				'sub_level_link'              => '.ava-menu li.ava-sub-menu-item .sub-level-link',
				'sub_level_link_hover'        => '.ava-menu li.ava-sub-menu-item:hover > .sub-level-link',
				'sub_level_link_active'       => '.ava-menu li.ava-sub-menu-item.ava-current-menu-item .sub-level-link',
				'sub_level_desc'              => '.ava-menu .ava-menu-item-desc.sub-level-desc',
				'sub_level_desc_hover'        => '.ava-menu li.ava-sub-menu-item:hover > .sub-level-link .ava-menu-item-desc.sub-level-desc',
				'sub_level_desc_active'       => '.ava-menu li.ava-sub-menu-item.ava-current-menu-item .ava-menu-item-desc.sub-level-desc',
				'sub_level_icon'              => '.ava-menu .ava-menu-item .sub-level-link .ava-menu-icon',
				'sub_level_icon_hover'        => '.ava-menu .ava-menu-item:hover > .sub-level-link .ava-menu-icon',
				'sub_level_icon_active'       => '.ava-menu li.ava-sub-menu-item.ava-current-menu-item .sub-level-link .ava-menu-icon',
				'sub_level_arrow'             => '.ava-menu .ava-menu-item .sub-level-link .ava-dropdown-arrow',
				'sub_level_arrow_hover'       => '.ava-menu .ava-menu-item:hover > .sub-level-link .ava-dropdown-arrow',
				'sub_level_arrow_active'      => '.ava-menu li.ava-sub-menu-item.ava-current-menu-item .sub-level-link .ava-dropdown-arrow',
				'sub_level_badge'             => '.ava-menu .ava-menu-item .sub-level-link .ava-menu-badge__inner',
				'sub_level_badge_wrapper'     => '.ava-menu .ava-menu-item .sub-level-link .ava-menu-badge',
				'first_sub_level_link'        => '.ava-menu .ava-sub-menu > li.ava-sub-menu-item:first-child > .sub-level-link',
				'first_sub_level_link_hover'  => '.ava-menu .ava-sub-menu > li.ava-sub-menu-item:first-child:hover > .sub-level-link',
				'first_sub_level_link_active' => '.ava-menu .ava-sub-menu > li.ava-sub-menu-item.ava-current-menu-item:first-child > .sub-level-link',
				'last_sub_level_link'         => '.ava-menu .ava-sub-menu > li.ava-sub-menu-item:last-child > .sub-level-link',
				'last_sub_level_link_hover'   => '.ava-menu .ava-sub-menu > li.ava-sub-menu-item:last-child:hover > .sub-level-link',
				'last_sub_level_link_active'  => '.ava-menu .ava-sub-menu > li.ava-sub-menu-item.ava-current-menu-item:last-child > .sub-level-link',

				'mobile_toggle'    => '.ava-menu-container .ava-mobile-menu-toggle-button',
				'mobile_container' => '.ava-menu-container .ava-menu-inner',
				'mobile_cover'     => '.ava-mobile-menu-cover',
			)
		);

		/**
		 * `Menu Container` Style Section
		 */
		$this->start_controls_section(
			'section_menu_container_style',
			array(
				'label'      => esc_html__( 'Menu Container', 'ava-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'menu_container_alignment',
			array(
				'label'   => esc_html__( 'Menu Items Alignment', 'ava-menu' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'ava-menu' ),
						'icon'  => ! is_rtl() ? 'fa fa-align-left' : 'fa fa-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'ava-menu' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'ava-menu' ),
						'icon'  => ! is_rtl() ? 'fa fa-align-right' : 'fa fa-align-left',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'ava-menu' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'justify-content: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'menu_container_alignment_misc',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'styles',
				'selectors' => array(
					'.ava-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] => 'flex-grow: 0;',
				),
				'condition' => array(
					'menu_container_alignment!' => 'stretch',
				),
			)
		);

		$this->add_control(
			'menu_container_alignment_stretch',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'styles',
				'selectors' => array(
					'.ava-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] => 'flex-grow: 1;',
					'.ava-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] . ' > a' => 'justify-content: center;',
				),
				'condition' => array(
					'menu_container_alignment' => 'stretch',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'menu_container_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'menu_container_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_container_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'],
			)
		);

		$this->add_responsive_control(
			'menu_container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'ava-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'inherit_first_items_border_radius',
			array(
				'label'     => esc_html__( 'Inherit border radius for the first menu item from main container', 'ava-menu' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'ava-menu' ),
				'label_off' => esc_html__( 'No', 'ava-menu' ),
				'default'   => '',
				'selectors' => array(
					'(desktop){{WRAPPER}} ' . $css_scheme['first_top_level_link'] => 'border-top-left-radius: {{menu_container_border_radius.TOP}}{{menu_container_border_radius.UNIT}};border-bottom-left-radius: {{menu_container_border_radius.LEFT}}{{menu_container_border_radius.UNIT}};',
					'(tablet){{WRAPPER}} ' . $css_scheme['first_top_level_link']  => 'border-top-left-radius: {{menu_container_border_radius_tablet.TOP}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-left-radius: {{menu_container_border_radius_tablet.LEFT}}{{menu_container_border_radius_tablet.UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['first_top_level_link']  => 'border-top-left-radius: {{menu_container_border_radius_mobile.TOP}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-left-radius: {{menu_container_border_radius_mobile.LEFT}}{{menu_container_border_radius_mobile.UNIT}};',
				),
			)
		);

		$this->add_control(
			'inherit_last_items_border_radius',
			array(
				'label'     => esc_html__( 'Inherit border radius for the last menu item from main container', 'ava-menu' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'ava-menu' ),
				'label_off' => esc_html__( 'No', 'ava-menu' ),
				'default'   => '',
				'selectors' => array(
					'(desktop){{WRAPPER}} ' . $css_scheme['last_top_level_link']   => 'border-top-right-radius: {{menu_container_border_radius.RIGHT}}{{menu_container_border_radius.UNIT}};border-bottom-right-radius: {{menu_container_border_radius.BOTTOM}}{{menu_container_border_radius.UNIT}};',
					'(desktop){{WRAPPER}} ' . $css_scheme['last_top_level_link_2'] => 'border-top-right-radius: {{menu_container_border_radius.RIGHT}}{{menu_container_border_radius.UNIT}};border-bottom-right-radius: {{menu_container_border_radius.BOTTOM}}{{menu_container_border_radius.UNIT}};',
					'(desktop){{WRAPPER}} ' . $css_scheme['last_top_level_link_3'] => 'border-top-right-radius: {{menu_container_border_radius.RIGHT}}{{menu_container_border_radius.UNIT}};border-bottom-right-radius: {{menu_container_border_radius.BOTTOM}}{{menu_container_border_radius.UNIT}};',

					'(tablet){{WRAPPER}} ' . $css_scheme['last_top_level_link']    => 'border-top-right-radius: {{menu_container_border_radius_tablet.RIGHT}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_tablet.BOTTOM}}{{menu_container_border_radius_tablet.UNIT}};',
					'(tablet){{WRAPPER}} ' . $css_scheme['last_top_level_link_2']  => 'border-top-right-radius: {{menu_container_border_radius_tablet.RIGHT}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_tablet.BOTTOM}}{{menu_container_border_radius_tablet.UNIT}};',
					'(tablet){{WRAPPER}} ' . $css_scheme['last_top_level_link_3']  => 'border-top-right-radius: {{menu_container_border_radius_tablet.RIGHT}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_tablet.BOTTOM}}{{menu_container_border_radius_tablet.UNIT}};',

					'(mobile){{WRAPPER}} ' . $css_scheme['last_top_level_link']    => 'border-top-right-radius: {{menu_container_border_radius_mobile.RIGHT}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_mobile.BOTTOM}}{{menu_container_border_radius_mobile.UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['last_top_level_link_2']  => 'border-top-right-radius: {{menu_container_border_radius_mobile.RIGHT}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_mobile.BOTTOM}}{{menu_container_border_radius_mobile.UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['last_top_level_link_3']  => 'border-top-right-radius: {{menu_container_border_radius_mobile.RIGHT}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_mobile.BOTTOM}}{{menu_container_border_radius_mobile.UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'menu_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'ava-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'menu_container_min_width',
			array(
				'label'       => esc_html__( 'Min Width (px)', 'ava-menu' ),
				'description' => esc_html__( 'Set 0 to automatic width detection', 'ava-menu' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 900,
					),
				),
				'selectors'   => array(
					'.ava-desktop-menu-active {{WRAPPER}} ' . $css_scheme['container'] => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Main Menu Items` Style Section
		 */
		$this->start_controls_section(
			'section_main_menu_style',
			array(
				'label'      => esc_html__( 'Main Menu Items', 'ava-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'top_item_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['top_level_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Description typography', 'ava-menu' ),
				'name'     => 'top_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['top_level_desc'],
			)
		);

		$this->add_control(
			'top_item_max_width',
			array(
				'label'       => esc_html__( 'Item max width (%)', 'ava-menu' ),
				'description' => esc_html__( 'Leave empty to automatic width detection', 'ava-menu' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => '%',
				),
				'selectors' => array(
					'.ava-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_main_items_style' );

		$state_tabs = array(
			'normal' => esc_html__( 'Normal', 'ava-menu' ),
			'hover'  => esc_html__( 'Hover', 'ava-menu' ),
			'active' => esc_html__( 'Active', 'ava-menu' ),
		);

		foreach( $state_tabs as $tab => $label ) {

			$suffix = ( 'normal' !== $tab ) ? '_' . $tab : '';

			$this->start_controls_tab(
				'tab_main_items_' . $tab,
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				'top_item_text_color' . $suffix,
				array(
					'label'=> esc_html__( 'Text color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'top_item_desc_color' . $suffix,
				array(
					'label'=> esc_html__( 'Description color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_desc' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'top_item_icon_color' . $suffix,
				array(
					'label'=> esc_html__( 'Icon color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_icon' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'top_item_drop_down_arrow_color' . $suffix,
				array(
					'label'=> esc_html__( 'Drop-down arrow color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_arrow' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'top_item_background' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'top_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'top_first_item_border' . $suffix,
					'selector'       => '{{WRAPPER}} ' . $css_scheme[ 'first_top_level_link' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'First Item Border Type', 'Border Control', 'ava-menu' ),
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'label'=> esc_html__( 'Last item border', 'ava-menu' ),
					'name'     => 'top_last_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'last_top_level_link' . $suffix ] . ', {{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_2' . $suffix ] . ', {{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_3' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'Last Item Border Type', 'Border Control', 'ava-menu' ),
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'top_item_box_shadow' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'top_level_link'. $suffix ],
				)
			);

			$this->add_responsive_control(
				'top_item_border_radius' . $suffix,
				array(
					'label'      => esc_html__( 'Border Radius', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'top_item_padding' . $suffix,
				array(
					'label'      => esc_html__( 'Padding', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'top_item_margin' . $suffix,
				array(
					'label'      => esc_html__( 'Margin', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Sub Menu` Style Section
		 */
		$this->start_controls_section(
			'section_sub_menu_style',
			array(
				'label'      => esc_html__( 'Sub Menu', 'ava-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_sub_panel_style' );

		$sub_panel_tabs = array(
			'simple' => esc_html__( 'Simple Panel', 'ava-menu' ),
			'mega'   => esc_html__( 'Mega Panel', 'ava-menu' ),
		);

		foreach ( $sub_panel_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_sub_panel_' . $tab,
				array(
					'label' => $label,
				)
			);

			if ( 'simple' === $tab ) {
				$this->add_control(
					'simple_sub_panel_width',
					array(
						'label'       => esc_html__( 'Width (px)', 'ava-menu' ),
						'type'        => Controls_Manager::SLIDER,
						'range'       => array(
							'px' => array(
								'min' => 100,
								'max' => 400,
							),
						),
						'selectors'   => array(
							'{{WRAPPER}} ' . $css_scheme['simple_sub_panel'] => 'min-width: {{SIZE}}{{UNIT}};',
						),
					)
				);
			}

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => $prefix . 'sub_panel_background',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $prefix . 'sub_panel_border',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ],
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $prefix . 'sub_panel_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ],
				)
			);

			$this->add_responsive_control(
				$prefix . 'sub_panel_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				$prefix . 'sub_panel_padding',
				array(
					'label'      => esc_html__( 'Padding', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				$prefix . 'sub_panel_margin',
				array(
					'label'      => esc_html__( 'Margin', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_control(
			'sub_menu_items_heading',
			array(
				'label'     => esc_html__( 'Sub Menu Items Style', 'ava-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_menu_item_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_level_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Description typography', 'ava-menu' ),
				'name'     => 'sub_menu_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_level_desc'],
			)
		);

		$this->start_controls_tabs( 'tabs_sub_menu_items_style' );

		foreach( $state_tabs as $tab => $label ) {

			$suffix = ( 'normal' !== $tab ) ? '_' . $tab : '';

			$this->start_controls_tab(
				'tab_sub_items_' . $tab,
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				'sub_item_text_color' . $suffix,
				array(
					'label'=> esc_html__( 'Text color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sub_item_desc_color' . $suffix,
				array(
					'label'=> esc_html__( 'Description color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_desc' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sub_item_icon_color' . $suffix,
				array(
					'label'=> esc_html__( 'Icon color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_icon' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sub_item_drop_down_arrow_color' . $suffix,
				array(
					'label'=> esc_html__( 'Drop-down arrow color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_arrow' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'sub_item_background' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'sub_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'sub_first_item_border' . $suffix,
					'selector'       => '{{WRAPPER}} ' . $css_scheme[ 'first_sub_level_link' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'First Item Border Type', 'Border Control', 'ava-menu' ),
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'label'=> esc_html__( 'Last item border', 'ava-menu' ),
					'name'     => 'sub_last_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'last_sub_level_link' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'Last Item Border Type', 'Border Control', 'ava-menu' ),
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'sub_item_box_shadow' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'sub_level_link'. $suffix ],
				)
			);

			$this->add_responsive_control(
				'sub_item_border_radius' . $suffix,
				array(
					'label'      => esc_html__( 'Border Radius', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'sub_item_padding' . $suffix,
				array(
					'label'      => esc_html__( 'Padding', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'sub_item_margin' . $suffix,
				array(
					'label'      => esc_html__( 'Margin', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Mobile Menu` Style Section
		 */
		$this->start_controls_section(
			'section_mobile_menu_style',
			array(
				'label'      => esc_html__( 'Mobile Menu', 'ava-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'mobile_toggle_color',
			array(
				'label'=> esc_html__( 'Toggle text color', 'ava-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'.ava-mobile-menu-active {{WRAPPER}} ' . $css_scheme['mobile_toggle'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mobile_toggle_bg_color',
			array(
				'label'=> esc_html__( 'Toggle background color', 'ava-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'.ava-mobile-menu-active {{WRAPPER}} ' . $css_scheme['mobile_toggle'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mobile_container_bg_color',
			array(
				'label'=> esc_html__( 'Container background color', 'ava-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'.ava-mobile-menu-active {{WRAPPER}} ' . $css_scheme['mobile_container'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mobile_cover_bg_color',
			array(
				'label'=> esc_html__( 'Cover background color', 'ava-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'body.ava-mobile-menu-active ' . $css_scheme['mobile_cover'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$level_tabs = array(
			'top_level' => esc_html__( 'Top Level', 'ava-menu' ),
			'sub_level' => esc_html__( 'Sub Level', 'ava-menu' ),
		);

		/**
		 * `Icon` Style Section
		 */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'      => esc_html__( 'Icon', 'ava-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		foreach( $level_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_' . $tab . '_icon_style',
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				$prefix . 'icon_size',
				array(
					'label' => esc_html__( 'Font size', 'ava-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 150,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ]  => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_margin',
				array(
					'label'      => esc_html__( 'Margin', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_hor_position',
				array(
					'label'   => esc_html__( 'Horizontal position', 'ava-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'ava-menu' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'ava-menu' ),
							'icon'  => 'fa fa-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'ava-menu' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors_dictionary' => array(
						'left'   => 'text-align: left; order: -1;',
						'center' => 'text-align: center; order: 0;',
						'right'  => 'text-align: right; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_ver_position',
				array(
					'label'   => esc_html__( 'Vertical position', 'ava-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'top' => array(
							'title' => esc_html__( 'Top', 'ava-menu' ),
							'icon'  => 'eicon-v-align-top',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'ava-menu' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'ava-menu' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex: 0 0 100%; width: 0; order: -2;',
						'center' => 'align-self: center; flex: 0 0 auto; width: auto;',
						'bottom' => 'flex: 0 0 100%; width: 0; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_order',
				array(
					'label' => esc_html__( 'Order', 'ava-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ]  => 'order: {{SIZE}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Badge` Style Section
		 */
		$this->start_controls_section(
			'section_badge_style',
			array(
				'label'      => esc_html__( 'Badge', 'ava-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_badge_style' );

		foreach( $level_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_' . $tab . '_badge_style',
				array(
					'label' => $label,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => $prefix . 'badge_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_control(
				$prefix . 'badge_color',
				array(
					'label'=> esc_html__( 'Text color', 'ava-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => $prefix . 'badge_background',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $prefix . 'badge_border',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $prefix .     'badge_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_control(
				$prefix . 'badge_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_padding',
				array(
					'label'      => esc_html__( 'Padding', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_margin',
				array(
					'label'      => esc_html__( 'Margin', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_hor_position',
				array(
					'label'   => esc_html__( 'Horizontal position', 'ava-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'ava-menu' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'ava-menu' ),
							'icon'  => 'fa fa-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'ava-menu' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors_dictionary' => array(
						'left'   => 'text-align: left; order: -1;',
						'center' => 'text-align: center; order: 0;',
						'right'  => 'text-align: right; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_ver_position',
				array(
					'label'   => esc_html__( 'Vertical position', 'ava-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'top' => array(
							'title' => esc_html__( 'Top', 'ava-menu' ),
							'icon'  => 'eicon-v-align-top',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'ava-menu' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'ava-menu' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex: 0 0 100%; width: 0; order: -2;',
						'center' => 'align-self: center; flex: 0 0 auto; width: auto;',
						'bottom' => 'flex: 0 0 100%; width: 0; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_order',
				array(
					'label' => esc_html__( 'Order', 'ava-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ]  => 'order: {{SIZE}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_hide_on_mobile',
				array(
					'label'     => esc_html__( 'Hide on mobile', 'ava-menu' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'ava-menu' ),
					'label_off' => esc_html__( 'No', 'ava-menu' ),
					'default'   => '',
					'selectors' => array(
						'.ava-mobile-menu-active {{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'display: none;',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Drop-down Arrow` Style Section
		 */
		$this->start_controls_section(
			'section_arrow_style',
			array(
				'label'      => esc_html__( 'Drop-down Arrow', 'ava-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_arrow_style' );

		foreach( $level_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_' . $tab . '_arrow_style',
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				$prefix . 'arrow_size',
				array(
					'label' => esc_html__( 'Font size', 'ava-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 150,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ]  => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'arrow_margin',
				array(
					'label'      => esc_html__( 'Margin', 'ava-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					),
				)
			);

			$arrow_hor_pos_selectors_dictionary = array(
				'left'   => 'text-align: left; order: -1;',
				'center' => 'text-align: center; order: 0;',
				'right'  => 'text-align: right; order: 2;',
			);

			if ( 'sub_level' === $tab ) {
				$arrow_hor_pos_selectors_dictionary['right'] = $arrow_hor_pos_selectors_dictionary['right'] . 'margin-left: auto!important;';
			}

			$this->add_control(
				$prefix . 'arrow_hor_position',
				array(
					'label'   => esc_html__( 'Horizontal position', 'ava-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'ava-menu' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'ava-menu' ),
							'icon'  => 'fa fa-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'ava-menu' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors_dictionary' => $arrow_hor_pos_selectors_dictionary,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'arrow_ver_position',
				array(
					'label'   => esc_html__( 'Vertical position', 'ava-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'top' => array(
							'title' => esc_html__( 'Top', 'ava-menu' ),
							'icon'  => 'eicon-v-align-top',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'ava-menu' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'ava-menu' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex: 0 0 100%; width: 0; order: -2;',
						'center' => 'align-self: center; flex: 0 0 auto; width: auto;',
						'bottom' => 'flex: 0 0 100%; width: 0; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'arrow_order',
				array(
					'label' => esc_html__( 'Order', 'ava-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ]  => 'order: {{SIZE}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();
		$menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );
		$parent    = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;

		if ( 0 < $parent && isset( $menus[ $parent ] ) ) {
			unset( $menus[ $parent ] );
		}

		return $menus;
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		if ( ! isset( $settings['menu'] ) ) {
			return;
		}

		if ( ! $settings['menu'] ) {

			$allmenus = $this->get_available_menus();

			if ( empty( $allmenus ) ) {
				return;
			} else {
				$allmenus = array_keys( $allmenus );
				$menu     = $allmenus[0];
			}

		} else {
			$menu = $settings['menu'];
		}

		$args = array(
			'menu' => $settings['menu'],
		);

		$preset = isset( $settings['preset'] ) ? absint( $settings['preset'] ) : 0;

		if ( 0 !== $preset ) {
			$preset_options = get_post_meta( $preset, ava_menu_options_presets()->settings_key, true );
			ava_menu_option_page()->pre_set_options( $preset_options );
		} else {
			ava_menu_option_page()->pre_set_options( false );
		}

		$args = array_merge( $args, ava_menu_public_manager()->get_mega_nav_args( $preset ) );

		ava_menu_public_manager()->set_elementor_mode();
		wp_nav_menu( $args );
		ava_menu_public_manager()->reset_elementor_mode();

		if ( $this->is_css_required() ) {
			$dynamic_css = ava_menu()->dynamic_css();
			add_filter( 'cherry_dynamic_css_collector_localize_object', array( $this, 'fix_preview_css' ) );
			$dynamic_css::$collector->print_style();
			remove_filter( 'cherry_dynamic_css_collector_localize_object', array( $this, 'fix_preview_css' ) );
		}

	}

	/**
	 * Check if need to insert custom CSS
	 * @return boolean [description]
	 */
	public function is_css_required() {

		$allowed_actions = array( 'elementor_render_widget', 'elementor' );

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $allowed_actions ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Fix preview styles
	 *
	 * @return array
	 */
	public function fix_preview_css( $data ) {

		if ( ! empty( $data['css'] ) ) {
			printf( '<style>%s</style>', html_entity_decode( $data['css'] ) );
		}

		return $data;
	}

}
