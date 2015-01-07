# Changelog

## 0.5.0 (January 6, 2015)

* Provide `wsuwp_uc_get_meta()` to retrieve meta values for university objects using friendly field names.
* Capture suffix as part of a person's name details.
* Capture a secondary title as part of a person's information.

## 0.4.0 (January 6, 2015)

* Capture more information about people.

## 0.3.1 (December 8, 2014)

* Sort archives for people, entities, and projects by title. Publications sort by date.
* Set `posts_per_page` to 2000 for people, entities, projects, and publications.

## 0.3.0 (December 5, 2014)

* Introduce publications as a content type. Publications can be assigned to people, entities, and projects.
* Add a meta box to capture URLs for all content types.
* Add a meta box to capture email address for people.
* Add support for author to the people content type.
* Hide the automatic display of related object headings of related objects of that type are not assigned.
* Add filters to support the renaming of object type and taxonomy labels.
* Add settings fields to general settings to change the singular and plural names for all object types.
	* Note that changes here may break existing URL structures as the singular name will become a part of the URL.

## 0.2.2 (November 10, 2014)

* Introduce an upgrade routine to handle tasks such as rewrite rules flushing.
* Resolve issue with missing array index.

## 0.2.1 (October 9, 2014)

* Check for theme support for `wsuwp_uc_person`, `wsuwp_uc_entity`, and `wsuwp_uc_project` before registering post types and taxonomies.
    * If one of these is registered, we leave it in the hands of the theme to decide what is supported.
    * If none of these are registered, we assume that intent is to support all.