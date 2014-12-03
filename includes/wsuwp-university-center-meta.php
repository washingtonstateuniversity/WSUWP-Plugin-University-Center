<?php

class WSUWP_University_Center_Meta {
	/**
	 * Setup hooks and filters.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 11, 1 );
		add_action( 'save_post', array( $this, 'save_object_url' ), 12, 2 );
		add_action( 'save_post', array( $this, 'save_person_email' ), 12, 2 );
	}

	/**
	 * Add meta boxes used to capture pieces of information.
	 *
	 * @param string $post_type
	 */
	public function add_meta_boxes( $post_type ) {
		if ( ! in_array( $post_type, wsuwp_uc_get_object_type_slugs() ) ) {
			return;
		}

		add_meta_box( 'wsuwp_uc_object_url', 'URL', array( $this, 'display_object_url_meta_box' ), null, 'normal', 'default' );
		add_meta_box( 'wsuwp_uc_person_email', 'Email Address', array( $this, 'display_person_email_meta_box' ), null, 'side', 'default' );
	}

	/**
	 * Display a meta box to capture the URL for an object.
	 *
	 * @param WP_Post $post
	 */
	public function display_object_url_meta_box( $post ) {
		$object_url = get_post_meta( $post->ID, '_wsuwp_uc_object_url', true );

		if ( ! empty( $object_url ) ) {
			$object_url = esc_url( $object_url );
		} else {
			$object_url = '';
		}

		wp_nonce_field( 'save_object_url', '_uc_object_url_nonce' );
		?>
		<label for="wsuwp-uc-object-url">URL:</label>
		<input type="text" class="widefat" id="wsuwp-uc-object-url" name="wsuwp_uc_object_url" value="<?php echo $object_url; ?>" />
		<p class="description">Enter a URL to be displayed to guide visitors toward more information.</p>
		<?php
	}

	/**
	 * Display a meta box to capture a person's email address.
	 *
	 * @param WP_Post $post
	 */
	public function display_person_email_meta_box( $post ) {
		$person_email = get_post_meta( $post->ID, '_wsuwp_uc_person_email', true );

		wp_nonce_field( 'save_person_email', '_uc_person_email_nonce' );
		?>
		<label for="wsuwp-uc-person-email">Email:</label>
		<input type="text" id="wsuwp-uc-person-email" name="wsuwp_uc_person_email" value="<?php echo esc_attr( $person_email ); ?>" />
		<p class="description">An email address entered here will be publicly available on this person's profile page.</p>
		<?php
	}

	/**
	 * Assign a URL to an object when saved through the object's meta box.
	 *
	 * @param int     $post_id The ID of the post being saved.
	 * @param WP_Post $post    The full post object being saved.
	 */
	public function save_object_url( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Only assign a unique id to content from our registered types.
		if ( ! in_array( $post->post_type, wsuwp_uc_get_object_type_slugs() ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( ! isset( $_POST['_uc_object_url_nonce'] ) || false === wp_verify_nonce( $_POST['_uc_object_url_nonce'], 'save_object_url' ) ) {
			return;
		}

		if ( isset( $_POST['wsuwp_uc_object_url'] ) ) {
			if ( empty( trim( $_POST['wsuwp_uc_object_url'] ) ) ) {
				delete_post_meta( $post_id, '_wsuwp_uc_object_url' );
			} else {
				update_post_meta( $post_id, '_wsuwp_uc_object_url', esc_url_raw( $_POST['wsuwp_uc_object_url'] ) );
			}
		}

		return;
	}

	/**
	 * Assign an email to a person when saved through a meta box.
	 *
	 * @param int     $post_id ID of the post being saved.
	 * @param WP_Post $post    The full post object being saved.
	 */
	public function save_person_email( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wsuwp_uc_get_object_type_slug( 'people' ) !== $post->post_type ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( ! isset( $_POST['_uc_person_email_nonce'] ) || false === wp_verify_nonce( $_POST['_uc_person_email_nonce'], 'save_person_email' ) ) {
			return;
		}

		if ( isset( $_POST['wsuwp_uc_person_email'] ) ) {
			if ( empty( trim( $_POST['wsuwp_uc_person_email'] ) ) ) {
				delete_post_meta( $post_id, '_wsuwp_uc_person_email' );
			} else {
				update_post_meta( $post_id, '_wsuwp_uc_person_email', sanitize_email( $_POST['wsuwp_uc_person_email'] ) );
			}
		}

		return;
	}
}
$wsuwp_university_center_meta = new WSUWP_University_Center_Meta();