<?php

class University_Center_Syndicate_Shortcode_Publication extends WSU_Syndicate_Shortcode_Base {
	/**
	 * @var array A list of defaults specific to "publications" that will override the
	 *            base defaults set for all syndicate shortcodes.
	 */
	public $local_default_atts = array(
		'output' => 'headlines',
		'host'   => '',
		'query'  => 'publications',
	);

	/**
	 * @since 0.8.0
	 *
	 * @var array A set of default attributes for this shortcode only.
	 */
	public $local_extended_atts = array(
		'entity' => '',
		'person' => '',
		'project' => '',
	);

	/**
	 * @var string Shortcode name.
	 */
	public $shortcode_name = 'wsuwp_uc_publications';

	public function __construct() {
		parent::construct();
	}

	public function add_shortcode() {
		add_shortcode( $this->shortcode_name, array( $this, 'display_shortcode' ) );
	}

	/**
	 * Display publications from a provided host in a structured format using the
	 * WP REST API.
	 *
	 * @param array $atts Attributes passed to the shortcode.
	 *
	 * @return string Content to display in place of the shortcode.
	 */
	public function display_shortcode( $atts ) {
		$atts = $this->process_attributes( $atts );

		if ( ! $site_url = $this->get_request_url( $atts ) ) {
			return '<!-- ' . $this->shortcode_name . ' ERROR - an empty host was supplied -->';
		}

		if ( $content = $this->get_content_cache( $atts, $this->shortcode_name ) ) {
			return $content;
		}

		$request_url = esc_url( $site_url['host'] . $site_url['path'] . $this->default_path ) . $atts['query'];
		$request_url = $this->build_taxonomy_filters( $atts, $request_url );

		if ( ! empty( $atts['entity'] ) ) {
			$slug = sanitize_key( $atts['entity'] );
			$request_url = add_query_arg( array( 'filter[uc_entity]' => $slug ), $request_url );
		} elseif ( ! empty( $atts['person'] ) ) {
			$slug = sanitize_key( $atts['person'] );
			$request_url = add_query_arg( array( 'filter[uc_person]' => $slug ), $request_url );
		} elseif ( ! empty( $atts['project'] ) ) {
			$slug = sanitize_key( $atts['project'] );
			$request_url = add_query_arg( array( 'filter[uc_project]' => $slug ), $request_url );
		}

		if ( $atts['count'] ) {
			$count = ( 100 < absint( $atts['count'] ) ) ? 100 : $atts['count'];
			$request_url = add_query_arg( array( 'per_page' => absint( $count ) ), $request_url );
		}

		$response = wp_remote_get( $request_url );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$data = wp_remote_retrieve_body( $response );

		if ( empty( $data ) ) {
			return '';
		}

		$content = '<div class="content-syndicate-publications-wrapper">';

		$publications = json_decode( $data );

		$publications = apply_filters( 'wsuwp_uc_publications_sort_items', $publications, $atts );

		foreach ( $publications as $publication ) {
			$content .= $this->generate_item_html( $publication, $atts['output'] );
		}

		$content .= '</div><!-- end content-syndicate-publications-wrapper -->';

		$this->set_content_cache( $atts, $this->shortcode_name, $content );

		return $content;
	}

	/**
	 * Generate the HTML used for individual publications when called with the shortcode.
	 *
	 * @param stdClass $publication Data returned from the WP REST API.
	 * @param string   $type        The type of output expected.
	 *
	 * @return string The generated HTML for an individual publication.
	 */
	private function generate_item_html( $publication, $type ) {
		if ( 'headlines' === $type ) {
			ob_start();
			?>
			<div class="content-syndicate-publication-container">
				<div class="uco-syndicate-publication-name"><?php echo esc_html( $publication->title->rendered ); ?></div>
			</div>
			<?php
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}

		return apply_filters( 'wsuwp_uc_publications_item_html', '', $publication, $type );
	}
}
