# University Center Objects

A WordPress plugin to associate people, projects, organizations, and publications.

People from organizations work together on projects and publish their research. This plugin makes it easy to associate these 4 content types with each other. Through a set of filters it's possible to customize this completely to change the included content types and redefine them entirely.

By default, University Center Objects provides 4 custom post types that are common to a center, institute, or other organization at a university:

* People
* Organization (entity)
* Project
* Publication

Once associated with each other, lists of these objects will appear on their respective individual front-end views that show the association.

## Theme support

All content types will show up in all themes by default. No explicit theme support is required. It is possible to limit the number of content types supported by the theme by explicitly registering theme support. If at least one content type is explicitly added, the content types not explicitly added will no longer appear.

* `add_theme_support( 'wsuwp_uc_person' )`
* `add_theme_support( 'wsuwp_uc_project' )`
* `add_theme_support( 'wsuwp_uc_entity' )`
* `add_theme_support( 'wsuwp_uc_publication' )`

## Filters

Content type filters are `false` by default. Use the filter to return a `string` containing a post type's slug to replace that content type with one of your own.

* `wsuwp_uc_people_content_type`
* `wsuwp_uc_project_content_type`
* `wsuwp_uc_entity_content_type`
* `wsuwp_uc_publication_content_type`

Other filters are provided to modify the names used when registering the plugin's built in content types. For example, the word "Publication" is used by default for the publication post type. This can be changed to "Paper" or "Abstract" using the provided filter.

* `wsuwp_uc_project_type_names`
* `wsuwp_uc_people_type_names`
* `wsuwp_uc_entity_type_names`
* `wsuwp_uc_publication_type_names`

The plugin provides 2 taxonomies by default, topics and entity types. Filters can be used to disable these taxonomies:

* `wsuwp_uc_topic_taxonomy_enabled`
* `wsuwp_uc_entity_type_taxonomy_enabled`

When a list of associated objects is displayed on another object's view, a filter can be used to determine which of those associated objects should be listed (if any at all).

* `wsuwp_uc_people_to_add_to_content`

## Extending WSUWP Content Syndicate

The plugin includes shortcodes that extend the WSUWP Content Syndicate plugin with support for University Center objects and their associations to other objects.

* `[wsuwp_uc_projects]` will display a list of project names linked to individual project pages.
* `[wsuwp_uc_projects person="person-slug"]` will display a list of project names associated with a person.
* `[wsuwp_uc_projects organization="org-slug"]` will display a list of project names associated with an organization.
* `[wsuwp_uc_projects publication="pub-slug"]` will display a list of project names associated with a publication.

This structure works for organizations, people, and publications as well:

* `[wsuwp_uc_people]`
* `[wsuwp_uc_organizations]`
* `[wsuwp_uc_publications]`

As displayed in the first set of examples, each shortcode can be filtered with an object type. If multiple object types are provided as part of the shortcode, only one will act as a filter.

Object type attributes are `person`, `organization`, `publication`, `project`. The value should be the slug of an individual object.
