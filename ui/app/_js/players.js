$(document).ready(function () {


	getData()


	$(document).on("click",".activity-btn",function(e){
		e.preventDefault();
		var act = $(this).attr("data-activity");

		$.bbq.pushState({"filter-activity":act});
		getData()
	});
	$(document).on("submit","#search-form", function (e) {
		e.preventDefault();

		$.bbq.pushState({
			"filter-search": $("#search").val()
		})

		getData();
	});
	$(document).on("reset","#search-form", function (e) {
		e.preventDefault();

		$.bbq.removeState("filter-search")

		getData();
	});
	$(document).on("click",".record", function (e) {
		e.preventDefault();
		var id = $(this).attr("data-id");

		$.bbq.pushState({"playerID":id});


		getDetails();
	});

	if ($.bbq.getState("playerID")){
		getDetails();
	}
	
});
function getData() {
	var key =  $.bbq.getState("playerID");
	var acti =  $.bbq.getState("filter-activity");
	var search = $.bbq.getState("filter-search");

	$.getData("/app/data/players/data", {'ID':key,'activity':acti,'search':search}, function (data) {


		$("#content-area").jqotesub($("#template-content"), data);




		$(window).trigger("resize");
	},"data")

}

function getDetails() {
	var key =  $.bbq.getState("playerID");


	$.getData("/app/data/players/details", {'ID':key}, function (data) {


		$("#modal-window").jqotesub($("#template-modal-player-details"), data).modal("show").on("hide.bs.modal", function () {
			$.bbq.pushState({"playerID": ""});

		});





	},"details")

}




