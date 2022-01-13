<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

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
		if ( ! function_exists( 'mai_get_engine_theme' ) ) {
			return;
		}

		mai_enqueue_accordion_styles( $this->args['preview'] );

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
