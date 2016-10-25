<?php

class University_Center_Syndicate_Shortcode_Project extends WSU_Syndicate_Shortcode_Base {
	/**
	 * @var array A list of defaults specific to "projects" that will override the
	 *            base defaults set for all syndicate shortcodes.
	 */
	public $local_default_atts = array(
		'output' => 'headlines',
		'host'   => '',
		'query'  => 'projects',
	);

	/**
	 * @var string Shortcode name.
	 */
	public $shortcode_name = 'wsuwp_uc_projects';

	public function __construct() {
		parent::construct();
	}

	public function add_shortcode() {
		add_shortcode( 'wsuwp_uc_projects', array( $this, 'display_shortcode' ) );
	}

	/**
	 * Display projects from a provided host in a structured format using the
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

		$content = '<div class="content-syndicate-project-wrapper">';

		$projects = json_decode( $data );

		$projects = apply_filters( 'wsuwp_uc_project_sort_items', $projects, $atts );

		foreach ( $projects as $project ) {
			$content .= $this->generate_item_html( $project, $atts['output'] );
		}

		$content .= '</div><!-- end content-syndicate-project-wrapper -->';

		$this->set_content_cache( $atts, $this->shortcode_name, $content );

		return $content;
	}

	/**
	 * Generate the HTML used for individual projects when called with the shortcode.
	 *
	 * @param stdClass $project Data returned from the WP REST API.
	 * @param string   $type    The type of output expected.
	 *
	 * @return string The generated HTML for an individual project.
	 */
	private function generate_item_html( $project, $type ) {
		if ( 'headlines' === $type ) {
			ob_start();
			?>
			<div class="content-syndicate-project-container">
				<div class="uco-syndicate-project-name"><?php echo esc_html( $project->title->rendered ); ?></div>
			</div>
			<?php
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}

		return apply_filters( 'wsuwp_uc_project_item_html', '', $project, $type );
	}
}
