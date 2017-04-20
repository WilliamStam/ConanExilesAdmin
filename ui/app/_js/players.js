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




