(function($){
	var people = wsu_uc.people;
	/**
	* Remove an associated person from a list of people.
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

	$(document ).ready(function() {
		var people_assign_ids = $('#people-assign-ids');
		var people_assign = $('#people-assign');

		people_assign.autocomplete({
			appendTo: '#people-results',
			minLength: 0,
			source: people,
			focus: function( event, ui ) {
				people_assign.val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				// Once an option is selected, clear the input box.
				people_assign.val('');

				// Check to see if this item's ID is already in the list of added people before adding it.
				if ( 0 >= $('#' + ui.item.value ).length ) {
					$('#people-results' ).append('<div class="added-person" id="' + ui.item.value + '" data-name="' + ui.item.label + '">' + ui.item.label + '<span class="person-close dashicons-no-alt"></span></div>');

					var current_ids = people_assign_ids.val();
					if ( '' === current_ids ) {
						current_ids = ui.item.value;
					} else {
						current_ids += ',' + ui.item.value;
					}
					people_assign_ids.val(current_ids);

					delete people[0][ ui.item.value ];
				}

				return false;
			}
		});

		$('#people-results').on( 'click', '.person-close', remove_person );
	});
}(jQuery));