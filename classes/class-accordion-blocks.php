<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Mai_Accordion_Blocks {
	/**
	 * Gets it started.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function __construct() {
		add_action( 'acf/init', [ $this, 'register_blocks' ], 10, 3 );
		add_action( 'acf/init', [ $this, 'register_field_group' ], 10, 3 );
	}

	/**
	 * Registers blocks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function register_blocks() {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		acf_register_block_type(
			[
				'name'            => 'mai-accordion',
				'title'           => __( 'Mai Accordion', 'mai-accordion' ),
				'description'     => __( 'A custom accordion block.', 'mai-accordion' ),
				'render_callback' => [ $this, 'do_accordion' ],
				'category'        => 'widget',
				'keywords'        => [ 'accordion', 'faq', 'toggle' ],
				'icon'            => '<svg role="img" aria-hidden="true" focusable="false" style="display:block;" width="20" height="20" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,0,13)"><g transform="matrix(0.333333,0,0,4,19,-60)"><path d="M9,16.125C9,16.056 8.328,16 7.5,16L4.5,16C3.672,16 3,16.056 3,16.125L3,16.375C3,16.444 3.672,16.5 4.5,16.5L7.5,16.5C8.328,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g><g transform="matrix(2.66667,0,0,4,-6,-60)"><path d="M9,16.125C9,16.056 8.916,16 8.813,16L3.188,16C3.084,16 3,16.056 3,16.125L3,16.375C3,16.444 3.084,16.5 3.188,16.5L8.813,16.5C8.916,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g></g><g transform="matrix(1,0,0,1,0,7)"><g transform="matrix(0.333333,0,0,4,19,-60)"><path d="M9,16.125C9,16.056 8.328,16 7.5,16L4.5,16C3.672,16 3,16.056 3,16.125L3,16.375C3,16.444 3.672,16.5 4.5,16.5L7.5,16.5C8.328,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g><g transform="matrix(2.66667,0,0,4,-6,-60)"><path d="M9,16.125C9,16.056 8.916,16 8.813,16L3.188,16C3.084,16 3,16.056 3,16.125L3,16.375C3,16.444 3.084,16.5 3.188,16.5L8.813,16.5C8.916,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g></g><g transform="matrix(1,0,0,1,0,1)"><g transform="matrix(0.333333,0,0,4,19,-60)"><path d="M9,16.125C9,16.056 8.328,16 7.5,16L4.5,16C3.672,16 3,16.056 3,16.125L3,16.375C3,16.444 3.672,16.5 4.5,16.5L7.5,16.5C8.328,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g><g transform="matrix(2.66667,0,0,4,-6,-60)"><path d="M9,16.125C9,16.056 8.916,16 8.813,16L3.188,16C3.084,16 3,16.056 3,16.125L3,16.375C3,16.444 3.084,16.5 3.188,16.5L8.813,16.5C8.916,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g></g></svg>',
				'enqueue_assets'  => function() {
					mai_accordion_enqueue_styles();
				},
				'supports'        => [
					'align'  => false,
					'anchor' => true,
					'mode'   => false,
					'jsx'    => true,
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
				'icon'            => '<svg role="img" aria-hidden="true" focusable="false" style="display:block;" width="20" height="20" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g id="Box" transform="matrix(0.149758,0,0,0.0333276,-30.5651,9.00005)"><path d="M340.984,45.006L340.984,255.042L227.467,255.042L227.467,45.006M351,15.83C351,7.087 349.423,-0 347.477,-0C328.485,-0 238.488,-0.001 220.561,-0.001C219.737,-0.002 218.945,1.471 218.362,4.092C217.779,6.713 217.451,10.267 217.451,13.974C217.451,63.846 217.451,232.855 217.451,284.76C217.451,288.815 217.81,292.704 218.448,295.572C219.086,298.439 219.951,300.05 220.854,300.05C239.591,300.05 330.001,300.05 347.912,300.05C349.618,300.05 351,293.838 351,286.176C351,236.602 351,68.393 351,15.83Z" style="fill:rgb(35,31,32);fill-rule:nonzero;"/></g><g transform="matrix(1,0,0,1,0,1)"><g transform="matrix(0.333333,0,0,4,19,-60)"><path d="M9,16.125C9,16.056 8.328,16 7.5,16L4.5,16C3.672,16 3,16.056 3,16.125L3,16.375C3,16.444 3.672,16.5 4.5,16.5L7.5,16.5C8.328,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g><g transform="matrix(2.66667,0,0,4,-6,-60)"><path d="M9,16.125C9,16.056 8.916,16 8.813,16L3.188,16C3.084,16 3,16.056 3,16.125L3,16.375C3,16.444 3.084,16.5 3.188,16.5L8.813,16.5C8.916,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g></g></svg>',
				'parent'          => [ 'acf/mai-accordion' ],
				'supports'        => [
					'align' => false,
					'mode'  => false,
					'jsx'   => true,
				],
			]
		);
	}

	/**
	 * Renders the accordion container.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function do_accordion( $block, $content = '', $is_preview = false ) {
		$args = [
			'preview' => $is_preview,
			'content' => $this->get_accordion_inner_blocks(),
			'row_gap' => get_field( 'row_gap' ),
		];

		if ( isset( $block['anchor'] ) && ! empty( $block['anchor'] ) ) {
			$args['id'] = $block['anchor'];
		}

		if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
			$args['class'] = $block['className'];
		}

		$accordion = new Mai_Accordion( $args, true );

		echo $accordion->get();
	}

	/**
	 * Renders each accordion item.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function do_accordion_item( $block, $content = '', $is_preview = false ) {
		$args = [
			'preview' => $is_preview,
			'content' => $this->get_accordion_item_inner_blocks(),
			'title'   => get_field( 'title' ),
			'open'    => get_field( 'open' ),
		];

		if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
			$args['class'] = $block['className'];
		}

		$accordion = new Mai_Accordion_Item( $args, true );

		echo $accordion->get();
	}

	/**
	 * Gets accordion inner blocks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_accordion_inner_blocks() {
		$allowed    = [ 'acf/mai-accordion-item' ];
		$template   = [
			[ 'acf/mai-accordion-item', [], [] ]
		];

		return sprintf( '<InnerBlocks allowedBlocks="%s" template="%s" />', esc_attr( wp_json_encode( $allowed ) ), esc_attr( wp_json_encode( $template ) ) );
	}

	/**
	 * Gets accordion item inner blocks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_accordion_item_inner_blocks() {
		$template = [
			[ 'core/paragraph', [], [] ],
		];

		return sprintf( '<InnerBlocks template="%s" />', esc_attr( wp_json_encode( $template ) ) );
	}

	/**
	 * Registers field groups.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
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
					[
						'key'     => 'mai_accordion_item_open',
						'name'    => 'open',
						'type'    => 'true_false',
						'message' => __( 'Load open by default', 'mai-engine' ),
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
