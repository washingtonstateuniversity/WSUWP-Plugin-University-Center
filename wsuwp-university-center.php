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
	 * Setup the hooks used by the plugin.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_project_content_type' ) );
		add_action( 'init', array( $this, 'register_people_content_type' ) );
		add_action( 'init', array( $this, 'register_entity_content_type' ) );
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
}
new WSUWP_University_Center();