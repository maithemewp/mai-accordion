<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Mai_Accordion {
	protected $args;
	protected $block;

	/**
	 * Gets it started.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function __construct( $args, $block = false ) {
		$args        = wp_parse_args( $args, $this->get_defaults() );
		$this->args  = $this->get_sanitized_args( $args );
		$this->block = $block;
	}

	/**
	 * Gets defaults.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_defaults() {
		return [
			'preview' => false,
			'content' => '', // Required.
			'id'      => '',
			'class'   => '',
			'schema'  => '',
			'row_gap' => 'md',
		];
	}

	/**
	 * Gets sanitized args.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_sanitized_args( $args ) {
		$args['preview'] = rest_sanitize_boolean( $args['preview'] );
		$args['content'] = $args['content'];
		$args['id']      = sanitize_html_class( $args['id'] );
		$args['class']   = esc_html( $args['class'] );
		$args['schema']  = sanitize_key( $args['schema'] );
		$args['row_gap'] = esc_html( $args['row_gap'] );

		return $args;
	}

	/**
	 * Gets the accordion.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get() {
		if ( ! class_exists( 'Mai_Engine' ) ) {
			return;
		}

		// Add inline CSS.
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$url    = MAI_ACCORDION_PLUGIN_URL . "assets/mai-accordion{$suffix}.css";
		$path   = MAI_ACCORDION_PLUGIN_DIR . "assets/mai-accordion{$suffix}.css";
		wp_enqueue_style( 'mai-accordion', $url );
		wp_style_add_data( 'mai-accordion', 'path', $path );

		$attributes = [
			'class' => 'mai-accordion',
			'style' => '',
		];

		if ( $this->args['id'] ) {
			$attributes['id'] = $this->args['id'];
		}

		if ( $this->args['class'] ) {
			$attributes['class'] = mai_add_classes( $this->args['class'], $attributes['class'] );
		}

		$attributes['style'] = sprintf( '--row-gap:%s;', $this->args['row_gap'] ? sprintf( 'var(--spacing-%s)', $this->args['row_gap'] ) : 0 );

		if ( ! $this->block ) {
			$this->args['content'] = mai_get_processed_content( $this->args['content'] );
		}

		if ( $this->args['schema'] ) {
			$attributes['data-schema-type'] = 'faq';
		}

		return genesis_markup(
			[
				'open'    => '<div %s>',
				'close'   => '</div>',
				'context' => 'mai-accordion',
				'content' => $this->args['content'],
				'echo'    => false,
				'atts'    => $attributes,
			]
		);
	}
}
