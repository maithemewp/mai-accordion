<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

class Mai_Accordion_Blocks {

	function __construct() {
		add_action( 'acf/init', [ $this, 'register_blocks' ], 10, 3 );
		add_action( 'acf/init', [ $this, 'register_field_group' ], 10, 3 );
	}

	function register_blocks() {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		// Register.
		acf_register_block_type(
			[
				'name'            => 'mai-accordion',
				'title'           => __( 'Mai Accordion', 'mai-accordion' ),
				'description'     => __( 'A custom accordion block.', 'mai-accordion' ),
				'render_callback' => [ $this, 'do_accordion' ],
				'category'        => 'widget',
				'keywords'        => [ 'accordion', 'faq', 'toggle' ],
				'icon'            => mai_get_svg_icon( 'bars', 'light' ),
				'supports'        => [
					'align' => false,
					'mode'  => false,
					'jsx'   => true,
				],
			]
		);

		acf_register_block_type(
			[
				'name'            => 'mai-accordion-item',
				'title'           => __( 'Mai Accordion Item', 'mai-accordion' ),
				'description'     => __( 'A custom accordion item block.', 'mai-accordion' ),
				'render_callback' => [ $this, 'do_accordion_item' ],
				'category'        => 'widget',
				'icon'            => mai_get_svg_icon( 'bars', 'light' ),
				'parent'          => [ 'acf/mai-accordion' ],
				'supports'        => [
					'align' => false,
					'mode'  => false,
					'jsx'   => true,
				],
			]
		);
	}

	function do_accordion( $block, $content = '', $is_preview = false ) {
		$args = [
			'preview' => $is_preview,
			'content' => $this->get_accordion_inner_blocks(),
			'row_gap' => get_field( 'row_gap' ),
		];
		if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
			$args['class'] = $block['className'];
		}
		$accordion = new Mai_Accordion( $args, true );
		echo $accordion->get();
	}

	function do_accordion_item( $block, $content = '', $is_preview = false ) {
		$args = [
			'preview' => $is_preview,
			'content' => $this->get_accordion_item_inner_blocks(),
			'title'   => get_field( 'title' ),
		];
		if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
			$args['class'] = $block['className'];
		}
		$accordion = new Mai_Accordion_Item( $args, true );
		echo $accordion->get();
	}

	function get_accordion_inner_blocks() {
		$allowed    = [ 'acf/mai-accordion-item' ];
		$template   = [
			[ 'acf/mai-accordion-item', [], [] ]
		];

		return sprintf( '<InnerBlocks allowedBlocks="%s" template="%s" />', esc_attr( wp_json_encode( $allowed ) ), esc_attr( wp_json_encode( $template ) ) );
	}

	function get_accordion_item_inner_blocks() {
		$template = [
			[ 'core/paragraph', [], [] ],
		];

		return sprintf( '<InnerBlocks template="%s" />', esc_attr( wp_json_encode( $template ) ) );
	}

	function register_field_group() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			[
				'key'    => 'mai_accordion_field_group',
				'title'  => __( 'Mai Accordion', 'mai-engine' ),
				'fields' => [
					[
						'key'           => 'mai_accordion_row_gap',
						'label'         => __( 'Bottom Spacing', 'mai-engine' ),
						'name'          => 'row_gap',
						'type'          => 'button_group',
						'default_value' => 'md',
						'choices'       => [
							''   => __( 'None', 'mai-engine' ),
							'xs' => __( 'XS', 'mai-engine' ),
							'sm' => __( 'S', 'mai-engine' ),
							'md' => __( 'M', 'mai-engine' ),
							'lg' => __( 'L', 'mai-engine' ),
							'xl' => __( 'XL', 'mai-engine' ),
						],
						'wrapper' => [
							'class' => 'mai-acf-button-group',
						],
					],
				],
				'location' => [
					[
						[
							'param'    => 'block',
							'operator' => '==',
							'value'    => 'acf/mai-accordion',
						],
					],
				],
				'active' => true,
			]
		);

		acf_add_local_field_group(
			[
				'key'       => 'mai_accordion_item_field_group',
				'title'     => __( 'Mai Accordion Item', 'mai-engine' ),
				'fields'    => [
					[
						'key'   => 'mai_accordion_item_title',
						'label' => __( 'Title', 'mai-engine' ),
						'name'  => 'title',
						'type'  => 'text',
					],
				],
				'location' => [
					[
						[
							'param'    => 'block',
							'operator' => '==',
							'value'    => 'acf/mai-accordion-item',
						],
					],
				],
				'active'  => true,
			]
		);
	}
}
