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
		$(document).ready(function () {
			$('#eventList').dataTable({
				"language": {
					"lengthMenu": "_MENU_",
					"search": ""
				},
				"bInfo": false,
				"columnDefs": [{
					"orderable": false,
					"targets": 0,
				}]
			});
		});

	}
});