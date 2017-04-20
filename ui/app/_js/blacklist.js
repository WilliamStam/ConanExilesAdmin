$(document).ready(function () {


	getData();

	$(document).on("click",".form-ip-ban",function(e){
		e.preventDefault();
		var id = $(this).attr("data-id");
		$.bbq.pushState({"form":"form-ip-ban","ID":id});
		getFormIpBan()
	})

	if ($.bbq.getState("form")=="form-ip-ban"){
		getFormIpBan()
	}

	$(document).on("submit","#form-ip-ban-capture",function(e){
		e.preventDefault();
		var $this = $(this);
		var data = $(this).serialize();
		var ID = $.bbq.getState("ID");
		$.post("/app/save/blacklist/ip_ban?ID=" + ID, data, function (result) {
			result = result.data;
			validationErrors(result, $this);
			if (!result.errors && typeof getData == 'function') {
				getData();
				$this.closest(".modal").modal("hide")
			}
		})


	})

	
});
function getData() {
	var key =  $.bbq.getState("playerID");


	$.getData("/app/data/blacklist/data", {'ID':key}, function (data) {


		$("#content-area").jqotesub($("#template-content"), data);




		$(window).trigger("resize");
	},"data")

}


function getFormIpBan() {
	var ID =  $.bbq.getState("ID");


	$.getData("/app/data/blacklist/ip_ban", {'ID':ID}, function (data) {


		$("#form-modal").jqotesub($("#template-modal-ip-ban"), data).modal("show").on("hide.bs.modal", function () {
			$.bbq.pushState({"form": ""});

		});




		$(window).trigger("resize");
	},"form")

}



