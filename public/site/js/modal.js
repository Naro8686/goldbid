$(".modal").each( function(){
	$(this).wrap('<div class="overlay"></div>')
});

$(".open-modal").on('click', function(e){
	if($(this).data("modal") === '#modal1' && document.location.pathname === '/register') return false;
	if($(this).data("modal") === '#modal2' && document.location.pathname === '/login') return false;
	e.preventDefault();
    e.stopImmediatePropagation();

	let $this = $(this),
			modal = $($this).data("modal");

	$(modal).parents(".overlay").addClass("open");
	setTimeout( function(){
		$(modal).addClass("open");
	}, 350);

	$(document).on('click', function(e){
		let target = $(e.target);
		if ($(target).hasClass("overlay")){
			$(target).find(".modal").each( function(){
				$(this).removeClass("open");
			});
			setTimeout( function(){
				$(target).removeClass("open");
			}, 350);
		}
	});
});

$(".close-modal").on('click', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	let $this = $(this),
			modal = $($this).data("modal");
	$(modal).removeClass("open");
	setTimeout( function(){
		$(modal).parents(".overlay").removeClass("open");
	}, 350);

});
