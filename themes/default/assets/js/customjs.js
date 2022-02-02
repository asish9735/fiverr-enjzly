$(document).ready(function(){

	var base_url = VPATH;

	$('.my-navbar-toggler').click(function(){
		$('#order-status-bar').toggle();
	});	   

	$('[data-toggle="tooltip"]').tooltip();

	$(document).on('click','.dropdown-menu',function(event){		
		event.stopPropagation();
	});

	$(".dropdown-menu .dropdown-item.dropdown-toggle").click(function(){
		$('.collapse.dropdown-submenu').collapse('hide');
	});


	$("#register-modal input[name='u_name']").keypress(function (e) {
			if (!(e.which != 8 && e.which != 0 &&  ((e.which >= 45 && e.which <= 45)  || (e.which >= 48 && e.which <= 57)  || (e.which >= 65 && e.which <= 90) || (e.which >= 95 && e.which <= 95) || (e.which >= 97 && e.which <= 122) ))) {
				event.preventDefault();
			}
		}).keyup(function (e) {
			if (!(e.which != 8 && e.which != 0 &&  ((e.which >= 45 && e.which <= 45)  || (e.which >= 48 && e.which <= 57)  || (e.which >= 65 && e.which <= 90) || (e.which >= 95 && e.which <= 95) || (e.which >= 97 && e.which <= 122) ))) {
				event.preventDefault();
			}
		}).keypress(function (e) {
			if (!(e.which != 8 && e.which != 0 &&  ((e.which >= 45 && e.which <= 45)  || (e.which >= 48 && e.which <= 57)  || (e.which >= 65 && e.which <= 90) || (e.which >= 95 && e.which <= 95) || (e.which >= 97 && e.which <= 122) ))) {
				event.preventDefault();
			}
	});



		$('body').on("click", ".mark-fav", function(event){
			var proposal_id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: base_url + "add_delete_favorite",
				data:{ proposal_id:proposal_id, favorite:"add_favorite"},
				success: function(){
					$('i[data-id="'+proposal_id+'"]').attr({ class:"icon-line-awesome-heart mark-unfav"});
				}
			});
		});

		$('body').on("click", ".mark-unfav", function(event){
			var proposal_id = $(this).attr("data-id");
			$.ajax({
			type:"POST",
			url:base_url + "add_delete_favorite",
			data:{proposal_id:proposal_id,favorite:"delete_favorite"},
			success: function(){
				$('i[data-id="'+proposal_id+'"]').attr({class:"icon-line-awesome-heart mark-fav"});
			}
			});
		});

		$('body').on("click",".proposal-offer",function(){
			var proposal_id = $(this).attr("data-id");
			$.ajax({
			method: "POST",
			url: base_url + "referral_modal",
			data: {proposal_id: proposal_id }
			})
			.done(function(data){
				$(".append-modal").html("");
				$(".append-modal").html(data);
			});
		});

		$(document).on("click", ".closePopup", function(event){
			event.preventDefault();
			$(this).parent().fadeOut();
		});

});

//End