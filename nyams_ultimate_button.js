jQuery(function($){
	var showToTheTopLink = function() {
		var topLink = $('#top-link'); 
		if (topLink.length != 1) { //if no button is found add it once in the page
			topLink = $('<div>')
							.attr('id', 'top-link')
							.addClass('top-link')
							.addClass('top-link-hide')
							.html('<a href="#top" title="'+nyams_ultimate_button.title+'">'+nyams_ultimate_button.text+'</a>')
							.appendTo($('body'));
		}
		if ($(window).scrollTop() > 0) {
			if (topLink.hasClass('top-link-hide')) {
				topLink.removeClass('top-link-hide');
			}
		} else {
			if (!topLink.hasClass('top-link-hide')) {
				topLink
					.addClass('top-link-hide')
					.find('a')
					.removeClass('top-link-clicked');
			}
		}
	};
	$(window)
		.scroll(function(){ showToTheTopLink(); })
		.on('click', '#top-link a', function(e) { 
			e.preventDefault(); 
			if ($(window).scrollTop() > 0 && !$(this).hasClass('top-link-clicked')) {
				$(this).addClass('top-link-clicked');
				$('html, body').animate({scrollTop: $('html, body').offset().top}, 2E3, 'linear');
			}
		});
	showToTheTopLink();
});
