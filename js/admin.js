(function($){
	var people = wsu_uc.people;

	/**
	* Remove an associated objectperson from a list of people.
	*/
	function remove_person() {
		var remove_id = $(this).parent().get(0).id;
		var remove_element = $('#' + remove_id );
		var remove_name = remove_element.data('name');
		var people_assign_ids = $('#people-assign-ids' ).val().split(',');

		var new_people_assign_ids = [];

		for ( var k in people_assign_ids ) {
			if ( remove_id !== people_assign_ids[k] ) {
				new_people_assign_ids.push(people_assign_ids[k]);
			}
		}

		people_assign_ids = new_people_assign_ids.join(',');

		$('#people-assign-ids' ).val(people_assign_ids);

		// Update the autocomplete source data with the removed item.
		people.push( { 'value' : remove_id, 'label' : remove_name } );

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
			$('#people-results').on( 'click', '.uc-object-close', remove_person );
		}

		if ( 0 !== $('#wsuwp_uc_assign_projects' ).length ) {
			autocomplete_object( 'projects' );
		}

		if ( 0 !== $('#wsuwp_uc_assign_entities' ).length ) {
			autocomplete_object( 'entities' );
		}
	});
}(jQuery));