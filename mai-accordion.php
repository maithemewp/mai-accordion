<?php

/**
 * Plugin Name:     Mai Accordion
 * Plugin URI:      https://website.com
 * Description:     Core funtionality for website.com
 * Version:         0.1.0
 *
 * Author:          BizBudding, Mike Hemberger
 * Author URI:      https://bizbudding.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', 'mai_register_accordion_blocks' );
/**
 * Register the accordion blocks
 *
 * @since TBD
 *
 * @return void
 */
function mai_register_accordion_blocks() {
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	acf_register_block_type(
		[
			'name'            => 'mai-accordion',
			'title'           => __( 'Mai Accordion', 'mai-engine', 'mai-engine' ),
			'description'     => __( 'A custom accordion block.', 'mai-engine' ),
			'render_callback' => 'mai_do_accordion_block',
			'category'        => 'widget',
			'keywords'        => [ 'accordion', 'faq', 'toggle' ],
			// 'icon'            => '',
			// 'enqueue_assets'  => '',
			'enqueue_assets'  => 'mai_accordion_enqueue_assets',
			'supports'        => [
				// 'align'              => [ 'wide', 'full' ],
				'align'              => false,
				'mode'               => false,
				'__experimental_jsx' => true,
			],
		]
	);

	acf_register_block_type(
		[
			'name'            => 'mai-accordion-item',
			'title'           => __( 'Mai Accordion Item', 'mai-engine', 'mai-engine' ),
			'description'     => __( 'A custom accordion item block.', 'mai-engine' ),
			'render_callback' => 'mai_do_accordion_item_block',
			'category'        => 'widget',
			// 'keywords'        => [],
			// 'icon'            => '',
			'supports'        => [
				'align'              => false,
				'mode'               => false,
				'__experimental_jsx' => true,
			],
		]
	);
}

add_action( 'acf/init', 'mai_register_accordion_field_groups' );
/**
 * Register Mai Columns block field group.
 *
 * @since TBD
 *
 * @return void
 */
function mai_register_accordion_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		[
			'key'    => 'mai_accordion',
			'title'  => __( 'Mai Accordion', 'mai-engine' ),
			'fields' => [
				[
					'key'           => 'mai_accordion',
					'label'         => __( 'Bottom Spacing', 'mai-engine' ),
					'name'          => 'row_gap',
					'type'          => 'button_group',
					'default_value' => 'md',
					'choices'       => [
						''   => __( 'None', 'mai-engine' ),
						'xs' => __( 'XS', 'mai-engine' ),
						'sm' => __( 'SM', 'mai-engine' ),
						'md' => __( 'MD', 'mai-engine' ),
						'lg' => __( 'LG', 'mai-engine' ),
						'xl' => __( 'XL', 'mai-engine' ),
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
		],
	);

	acf_add_local_field_group(
		[
			'key'       => 'mai_accordion_item',
			'title'     => __( 'Mai Accordion Item', 'mai-engine' ),
			'fields'    => [
				[
					'key'   => 'title',
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
		],
	);
}

/**
 * Callback function to render the accordion block.
 *
 * @since TBD
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function mai_do_accordion_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$allowed    = [ 'acf/mai-accordion-item' ];
	$template   = [
		[ 'acf/mai-accordion-item', [], [] ]
	];
	$attributes = [
		'class' => 'mai-accordion',
		'style' => '',
	];

	if ( isset( $block['className'] ) && $block['className'] ) {
		$attributes['class'] = mai_add_classes( $block['className'], $attributes['class'] );
	}

	$row_gap = get_field( 'row_gap' );
	$row_gap = $row_gap ? sprintf( 'var(--spacing-%s)', esc_html( $row_gap ) ) : 0;

	$attributes['style'] .= sprintf( '--row-gap:%s;', $row_gap );

	// if ( isset( $block['align'] ) && $block['align'] ) {
	// 	$attributes['class'] .= ' align' . $block['align'];
	// }

	genesis_markup(
		[
			'open'    => '<div %s>',
			'close'   => '</div>',
			'context' => 'mai-accordion',
			'content' => sprintf( '<InnerBlocks allowedBlocks="%s" template="%s" />', esc_attr( wp_json_encode( $allowed ) ), esc_attr( wp_json_encode( $template ) ) ),
			'echo'    => true,
			'atts'    => $attributes,
		]
	);

}

function mai_do_accordion_item_block() {

	$attributes = [
		'class' => 'mai-accordion-item',
	];

	if ( isset( $block['className'] ) && $block['className'] ) {
		$attributes['class'] = mai_add_classes( $block['className'], $attributes['class'] );
	}

	$content  = sprintf( '<summary class="mai-accordion-summary">%s</summary>', esc_html( get_field( 'title' ) ) );
	$content .= '<div class="mai-accordion-content">';
		$content .= '<InnerBlocks />';
	$content .= '</div>';

	genesis_markup(
		[
			'open'    => '<details %s>',
			'close'   => '</details>',
			'context' => 'mai-accordion-item',
			'content' => $content,
			'echo'    => true,
			'atts'    => $attributes,
		]
	);
}

function mai_accordion_enqueue_assets() {
	wp_enqueue_style( 'mai-accordion', plugin_dir_url( __FILE__ ) . '/mai-accordion.css', [], '0.1.0' );
}
