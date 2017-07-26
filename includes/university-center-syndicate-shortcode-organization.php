<?php

class University_Center_Syndicate_Shortcode_Organization extends WSU_Syndicate_Shortcode_Base {
	/**
	 * @var array A list of defaults specific to "organizations" that will override the
	 *            base defaults set for all syndicate shortcodes.
	 */
	public $local_default_atts = array(
		'output' => 'headlines',
		'host'   => '',
		'site'   => '',
		'query'  => 'organizations',
	);

	/**
	 * @since 0.8.0
	 *
	 * @var array A set of default attributes for this shortcode only.
	 */
	public $local_extended_atts = array(
		'person' => '',
		'project' => '',
		'publication' => '',
	);

	/**
	 * @var string Shortcode name.
	 */
	public $shortcode_name = 'wsuwp_uc_organizations';

	public function __construct() {
		parent::construct();
	}

	public function add_shortcode() {
		add_shortcode( $this->shortcode_name, array( $this, 'display_shortcode' ) );
	}

	/**
	 * Display organizations from a provided host in a structured format using the
	 * WP REST API.
	 *
	 * @param array $atts Attributes passed to the shortcode.
	 *
	 * @return string Content to display in place of the shortcode.
	 */
	public function display_shortcode( $atts ) {
		$atts = $this->process_attributes( $atts );

		if ( '' === $atts['host'] && '' === $atts['site'] ) {
			$atts['site'] = get_home_url();
		}

		$site_url = $this->get_request_url( $atts );
		if ( ! $site_url ) {
			return '<!-- ' . $this->shortcode_name . ' ERROR - an empty host was supplied -->';
		}

		$content = $this->get_content_cache( $atts, $this->shortcode_name );
		if ( $content ) {
			return $content;
		}

		$request_url = esc_url( $site_url['host'] . $site_url['path'] . $this->default_path ) . $atts['query'];
		$request_url = $this->build_taxonomy_filters( $atts, $request_url );

		if ( ! empty( $atts['person'] ) ) {
			$slug = sanitize_key( $atts['person'] );
			$request_url = add_query_arg( array(
				'filter[uc_person]' => $slug,
			), $request_url );
		} elseif ( ! empty( $atts['project'] ) ) {
			$slug = sanitize_key( $atts['project'] );
			$request_url = add_query_arg( array(
				'filter[uc_project]' => $slug,
			), $request_url );
		} elseif ( ! empty( $atts['publication'] ) ) {
			$slug = sanitize_key( $atts['publication'] );
			$request_url = add_query_arg( array(
				'filter[uc_publication]' => $slug,
			), $request_url );
		}

		if ( ! empty( $atts['offset'] ) ) {
			$atts['count'] = absint( $atts['count'] ) + absint( $atts['offset'] );
		}

		if ( $atts['count'] ) {
			$count = ( 100 < absint( $atts['count'] ) ) ? 100 : $atts['count'];
			$request_url = add_query_arg( array(
				'per_page' => absint( $count ),
			), $request_url );
		}

		$response = wp_remote_get( $request_url );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$data = wp_remote_retrieve_body( $response );

		if ( empty( $data ) ) {
			return '';
		}

		$content = '<div class="content-syndicate-organizations-wrapper">';

		$organizations = json_decode( $data );

		$organizations = apply_filters( 'wsuwp_uc_organizations_sort_items', $organizations, $atts );

		$offset_x = 0;

		foreach ( $organizations as $organization ) {
			if ( $offset_x < absint( $atts['offset'] ) ) {
				$offset_x++;
				continue;
			}

			$content .= $this->generate_item_html( $organization, $atts['output'] );
		}

		$content .= '</div><!-- end content-syndicate-organizations-wrapper -->';

		$this->set_content_cache( $atts, $this->shortcode_name, $content );

		return $content;
	}

	/**
	 * Generate the HTML used for individual organizations when called with the shortcode.
	 *
	 * @param stdClass $organization Data returned from the WP REST API.
	 * @param string   $type         The type of output expected.
	 *
	 * @return string The generated HTML for an individual organization.
	 */
	private function generate_item_html( $organization, $type ) {
		ob_start();
		?>
		<div class="content-syndicate-organization-container">

			<?php
			if ( 'excerpts' === $type || 'full' === $type ) {
				?>
				<div class="uco-syndicate-organization-thumbnail">
				<?php
				if ( ! empty( $organization->featured_media ) && isset( $organization->_embedded->{'wp:featuredmedia'} ) && 0 < count( $organization->_embedded->{'wp:featuredmedia'} ) ) {
					$feature = $organization->_embedded->{'wp:featuredmedia'}[0]->media_details;

					if ( isset( $feature->sizes->{'post-thumbnail'} ) ) {
						$thumbnail = $feature->sizes->{'post-thumbnail'}->source_url;
					} elseif ( isset( $subset_feature->sizes->{'thumbnail'} ) ) {
						$thumbnail = $feature->sizes->{'thumbnail'}->source_url;
					} else {
						$thumbnail = $organization->_embedded->{'wp:featuredmedia'}[0]->source_url;
					}

					?><img src="<?php echo esc_url( $thumbnail ); ?>"><?php
				}
				?>
				</div>
				<?php
			}
			?>

			<div class="uco-syndicate-organization-name">
				<a href="<?php echo esc_url( $organization->link ); ?>"><?php echo esc_html( $organization->title->rendered ); ?></a>
			</div>

			<?php
			if ( 'excerpts' === $type ) {
				?>
				<div class="uco-syndicate-organization-excerpt">
					<?php echo wp_kses_post( $organization->excerpt->rendered ); ?>
					<a class="uco-syndicate-organization-read-story" href="<?php echo esc_url( $organization->link ); ?>">Read Story</a>
				</div>
				<?php
			} elseif ( 'full' === $type ) {
				?>
				<div class="uco-syndicate-organization-content">
					<?php echo wp_kses_post( $organization->content->rendered ); ?>
				</div>
				<?php

			}
			?>

		</div>
		<?php

		$html = ob_get_clean();

		if ( in_array( $type, array( 'headlines', 'excerpts', 'full' ), true ) ) {
			return $html;
		}

		return apply_filters( 'wsuwp_uc_organizations_item_html', '', $organization, $type );
	}
}
