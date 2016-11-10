# University Center Objects

A WordPress plugin that provides content objects and relationships common to a center, institute, or other organization at a university.

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
