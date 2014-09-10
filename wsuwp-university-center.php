<?php
/*
Plugin Name: University Center
Plugin URI: http://web.wsu.edu/wordpress/plugins/university-center/
Description: Provide custom content types and taxonomies for a university center or organization.
Author: washingtonstateuniversity, jeremyfelt
Version: 0.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class WSUWP_University_Center {

	/**
	 * The slug used to register the project custom content type.
	 *
	 * @var string
	 */
	var $project_content_type = 'wsuwp_uc_project';

	/**
	 * The slug used to register the people custom content type.
	 *
	 * @var string
	 */
	var $people_content_type = 'wsuwp_uc_people';

	/**
	 * The slug used to register the entity custom content type.
	 *
	 * @var string
	 */
	var $entity_content_type = 'wsuwp_uc_entity';

	/**
	 * The slug used to register the entity type taxonomy.
	 *
	 * @var string
	 */
	var $entity_type_taxonomy = 'wsuwp_uc_entity_type';

	/**
	 * The slug used to register a taxonomy for center topics.
	 *
	 * @var string
	 */
	var $topics_taxonomy = 'wsuwp_uc_topics';

	/**
	 * Setup the hooks used by the plugin.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_project_content_type' ) );
		add_action( 'init', array( $this, 'register_people_content_type' ) );
		add_action( 'init', array( $this, 'register_entity_content_type' ) );
		add_action( 'init', array( $this, 'register_entity_type_taxonomy' ) );
		add_action( 'init', array( $this, 'register_topic_taxonomy' ) );

		add_action( 'save_post', array( $this, 'assign_unique_id' ), 10, 2 );
	}

	/**
	 * Register the project content type.
	 */
	public function register_project_content_type() {
		$args = array(
			'labels' => array(
				'name' => __( 'Projects', 'wsuwp_uc' ),
				'singular_name' => __( 'Project', 'wsuwp_uc' ),
				'all_items' => __( 'All Projects', 'wsuwp_uc' ),
				'add_new_item' => __( 'Add Project', 'wsuwp_uc' ),
				'edit_item' => __( 'Edit Project', 'wsuwp_uc' ),
				'new_item' => __( 'New Project', 'wsuwp_uc' ),
				'view_item' => __( 'View Project', 'wsuwp_uc' ),
				'search_items' => __( 'Search Projects', 'wsuwp_uc' ),
				'not_found' => __( 'No Projects found', 'wsuwp_uc' ),
				'not_found_in_trash' => __( 'No Projects found in trash', 'wsuwp_uc' ),
			),
			'description' => __( 'Projects belonging to the center.', 'wsuwp_uc' ),
			'public' => true,
			'hierarchical' => false,
			'menu_icon' => 'dashicons-analytics',
			'supports' => array (
				'title',
				'editor',
				'revisions',
				'thumbnail',
			),
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'project',
				'with_front' => false
			),
		);
		register_post_type( $this->project_content_type, $args );
	}

	/**
	 * Register the people content type.
	 */
	public function register_people_content_type() {
		$args = array(
			'labels' => array(
				'name' => __( 'People', 'wsuwp_uc' ),
				'singular_name' => __( 'Person', 'wsuwp_uc' ),
				'all_items' => __( 'All People', 'wsuwp_uc' ),
				'add_new_item' => __( 'Add Person', 'wsuwp_uc' ),
				'edit_item' => __( 'Edit Person', 'wsuwp_uc' ),
				'new_item' => __( 'New Person', 'wsuwp_uc' ),
				'view_item' => __( 'View Person', 'wsuwp_uc' ),
				'search_items' => __( 'Search People', 'wsuwp_uc' ),
				'not_found' => __( 'No People found', 'wsuwp_uc' ),
				'not_found_in_trash' => __( 'No People found in trash', 'wsuwp_uc' ),
			),
			'description' => __( 'People involved with the center.', 'wsuwp_uc' ),
			'public' => true,
			'hierarchical' => false,
			'menu_icon' => 'dashicons-id-alt',
			'supports' => array (
				'title',
				'editor',
				'revisions',
				'thumbnail',
			),
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'people',
				'with_front' => false
			),
		);

		register_post_type( $this->people_content_type, $args );
	}

	/**
	 * Register the entity content type.
	 */
	public function register_entity_content_type() {
		$args = array(
			'labels' => array(
				'name' => __( 'Entities', 'wsuwp_uc' ),
				'singular_name' => __( 'Entity', 'wsuwp_uc' ),
				'all_items' => __( 'All Entities', 'wsuwp_uc' ),
				'add_new_item' => __( 'Add Entity', 'wsuwp_uc' ),
				'edit_item' => __( 'Edit Entity', 'wsuwp_uc' ),
				'new_item' => __( 'New Entity', 'wsuwp_uc' ),
				'view_item' => __( 'View Entity', 'wsuwp_uc' ),
				'search_items' => __( 'Search Entities', 'wsuwp_uc' ),
				'not_found' => __( 'No Entities found', 'wsuwp_uc' ),
				'not_found_in_trash' => __( 'No Entities found in trash', 'wsuwp_uc' ),
			),
			'description' => __( 'Entities involved with the center.', 'wsuwp_uc' ),
			'public' => true,
			'hierarchical' => false,
			'menu_icon' => 'dashicons-groups',
			'supports' => array (
				'title',
				'editor',
				'revisions',
				'thumbnail',
			),
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'entity',
				'with_front' => false
			),
		);

		register_post_type( $this->entity_content_type, $args );
	}

	/**
	 * Register a taxonomy to track types of entities.
	 */
	public function register_entity_type_taxonomy() {
		$args = array(
			'labels' => array(
				'name' => __( 'Entity Types', 'wsuwp_uc' ),
				'singular_name' => __( 'Entity Type', 'wsuwp_uc' ),
				'search_items' => __( 'Search Entity Types', 'wsuwp_uc' ),
				'all_items' => __( 'All Entity Types', 'wsuwp_uc' ),
				'parent_item' => __( 'Parent Entity Type', 'wsuwp_uc' ),
				'parent_item_colon' => __( 'Parent Entity Type:', 'wsuwp_uc' ),
				'edit_item' => __( 'Edit Entity Type', 'wsuwp_uc' ),
				'update_item' => __( 'Update Entity Type', 'wsuwp_uc' ),
				'add_new_item' => __( 'Add New Entity Type', 'wsuwp_uc' ),
				'new_item_name' => __( 'New Entity Type Name', 'wsuwp_uc' ),
				'menu_name' => __( 'Entity Type', 'wsuwp_uc' ),
			),
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'entity-type' ),
		);

		register_taxonomy( $this->entity_type_taxonomy, $this->entity_content_type, $args );
	}

	/**
	 * Register a taxonomy to track topics for projects, people, and entities.
	 */
	public function register_topic_taxonomy() {
		$args = array(
			'labels' => array(
				'name' => __( 'Topics', 'wsuwp_uc' ),
				'singular_name' => __( 'Topic', 'wsuwp_uc' ),
				'search_items' => __( 'Search Topics', 'wsuwp_uc' ),
				'all_items' => __( 'All Topics', 'wsuwp_uc' ),
				'parent_item' => __( 'Parent Topic', 'wsuwp_uc' ),
				'parent_item_colon' => __( 'Parent Topic:', 'wsuwp_uc' ),
				'edit_item' => __( 'Edit Topic', 'wsuwp_uc' ),
				'update_item' => __( 'Update Topic', 'wsuwp_uc' ),
				'add_new_item' => __( 'Add New Topic', 'wsuwp_uc' ),
				'new_item_name' => __( 'New Topic Name', 'wsuwp_uc' ),
				'menu_name' => __( 'Topic', 'wsuwp_uc' ),
			),
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'topic' ),
		);

		register_taxonomy( $this->topics_taxonomy, array( $this->project_content_type, $this->people_content_type, $this->entity_content_type ), $args );
	}

	/**
	 * Assign the object a unique ID to be used for maintaining relationships.
	 *
	 * @param int     $post_id The ID of the post being saved.
	 * @param WP_Post $post    The full post object being saved.
	 */
	public function assign_unique_id( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Only assign a unique id to content from our registered types - projects, people, and entities.
		if ( ! in_array( $post->post_type, array( $this->project_content_type, $this->people_content_type, $this->entity_content_type ) ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		$unique_id = get_post_meta( $post_id, '_wsuwp_uc_unique_id', true );

		// Generate an ID if it does not yet exist.
		if ( empty( $unique_id ) ) {
			$unique_id = uniqid( 'wsuwp_uc_id_' );
			update_post_meta( $post_id, '_wsuwp_uc_unique_id', $unique_id );
		}

	}
}
new WSUWP_University_Center();