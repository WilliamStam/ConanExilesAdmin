$(document).ready(function () {


	getData()


	
});
function getData() {
	var key =  $.bbq.getState("playerID");


	$.getData("/app/data/scan/data", {'ID':key}, function (data) {


		$("#content-area").jqotesub($("#template-content"), data);




		$(window).trigger("resize");
	},"data")

}




