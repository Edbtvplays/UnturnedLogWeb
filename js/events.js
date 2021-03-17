$(document).ready(function(){
	// Gets All Parameters from the URL
	let searchParams = new URLSearchParams(window.location.search)

	// Tries to See if the Player Paramter is in the URL if it isnt throw a Error.
	let param = searchParams.has('player') // true
	if (!param) {
		console.error("No Player Key Entered ")
	}

	// If a Parameter does exsist.
	else {
		// Get Statistical Data
		console.log("Executed");
		// Data Table Gets information for all Events.
		var EventsData = $('#eventList').DataTable({
			"lengthChange": false,
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				url: "action.php",
				type: "POST",
				data: {action: 'listevents', id: searchParams.get('player')},
				dataType: "json"
			},
			"language": {
				"lengthMenu": "_MENU_",
				"search": ""
			},
			"columnDefs": [
				{
					"targets": [0, 1, 2, 3],
					"orderable": true,
					"searchable": true
				},
			],
			"pageLength": 25
		});
		console.log(EventsData)
	}
});