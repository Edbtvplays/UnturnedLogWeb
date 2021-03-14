$(document).ready(function(){
	var usersData = $('#userList').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"action.php",
			type:"POST",
			data:{action:'listplayer'},
			dataType:"json"
		},
		"language": {
			"lengthMenu": "_MENU_",
			"search": ""
		},
		"columnDefs":[
			{
				"targets":[0, 1, 2],
				"orderable":false,
			},
		],
		"pageLength": 25
	});
	$('#userList').DataTable.on( 'search.dt', function () {
		console.log(usersData)
		usersData
			.search( this.value )
			.draw();
	});
});