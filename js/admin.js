(function($){
	/**
	 * Remove a previously associated object and update the list of objects
	 * that are now associated with the primary post object.
	 *
	 * @param event
	 */
	function remove_object( event ) {
		var remove_id = $(this).parent().get(0).id;
		var remove_element = $('#' + remove_id );
		var remove_name = remove_element.data('name');
		var objects_assign_ids = $('#' + event.data + '-assign-ids' ).val().split(',');

		var new_objects_assign_ids = [];

		for ( var k in objects_assign_ids ) {
			if ( remove_id !== objects_assign_ids[k] ) {
				new_objects_assign_ids.push(objects_assign_ids[k]);
			}
		}

		objects_assign_ids = new_objects_assign_ids.join(',');

		$('#' + event.data + '-assign-ids' ).val(objects_assign_ids);

		// Update the autocomplete source data with the removed item.
		wsu_uc[event.data].push( { 'value' : remove_id, 'label' : remove_name } );

		// Remove the actual item from the associated list.
		remove_element.remove();
	}

	function autocomplete_object( object_type ) {
		var assign_ids = $('#' + object_type + '-assign-ids');
		var assign = $('#' + object_type + '-assign');

		assign.autocomplete({
			appendTo: '#' + object_type + '-results',
			minLength: 0,
			source: wsu_uc[ object_type ],
			focus: function( event, ui ) {
				assign.val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				// Once an option is selected, clear the input box.
				assign.val('');

				// Check to see if this item's ID is already in the list of added people before adding it.
				if ( 0 >= $('#' + ui.item.value ).length ) {
					$('#' + object_type + '-results' ).append('<div class="added-' + object_type + ' added-object" id="' + ui.item.value + '" data-name="' + ui.item.label + '">' + ui.item.label + '<span class="uc-object-close dashicons-no-alt"></span></div>');

					var current_ids = assign_ids.val();
					if ( '' === current_ids ) {
						current_ids = ui.item.value;
					} else {
						current_ids += ',' + ui.item.value;
					}
					assign_ids.val(current_ids);

					delete wsu_uc[ object_type ][0][ ui.item.value ];
				}

				return false;
			}
		});
	}

	$(document ).ready(function() {
		if ( 0 !== $('#wsuwp_uc_assign_people' ).length ) {
			autocomplete_object( 'people' );
			$('#people-results').on( 'click', '.uc-object-close','people', remove_object );
		}

		if ( 0 !== $('#wsuwp_uc_assign_projects' ).length ) {
			autocomplete_object( 'projects' );
			$('#projects-results' ).on('click', '.uc-object-close', 'projects', remove_object );
		}

		if ( 0 !== $('#wsuwp_uc_assign_entities' ).length ) {
			autocomplete_object( 'entities' );
			$('#entities-results' ).on('click', '.uc-object-close', 'entities', remove_object );
		}
	});
}(jQuery));