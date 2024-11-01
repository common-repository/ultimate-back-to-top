jQuery(function($) {
	var container = $('<div>').attr('id', 'colorpicker').appendTo($('body')).hide(),
		colorpicker = $.farbtastic(container),
		preview = $('#nub_preview').find('.top-link'),
		update_position = function() {
			var offset		= $('#nub_option_offset').val() + 'px',
				pos			= $('input[name="nyams_ultimate_button[position]"]:checked').val(),
				op_pos		= (pos == 'left') ? 'right' : 'left',
				op_offset 	= ($('#nub_preview').width() - (preview.width() + parseInt(offset, 10))) + 'px';
				css = {};
				css[op_pos] = op_offset;
				css[pos] = offset;
			preview
				.css(css);
		},
		update_color = function() {
			$('#nub_preview').find('a').css({'border-color' : $('#nub_option_border_color').val(), 'background-color' : $('#nub_option_background_color').val(),  'color' : $('#nub_option_font_color').val() });
		},
		update_button = function() {
			preview.remove();
			preview = $('<div>')
						.attr('id', 'top-link')
						.addClass('top-link')
						.html('<a href="#top" title="'+$('#nub_option_title').val()+'">'+$('#nub_option_text').val()+'</a>')
						.appendTo($('#nub_preview'));
			update_position();
			update_color();
		};

	//COLOR PICKER
	$('.color')
		.focus(function() {
			var currentInput = $(this), dimension = currentInput.offset();
			container.css({'top' : (dimension.top - 100) + 'px', 'left' : (dimension.left + currentInput.width() + 30) + 'px'}).show();
			colorpicker.linkTo(currentInput).setColor(currentInput.val());					
		})
		.blur(function(){ container.hide();
	});

	//PREVIEW
	$('#nub_option_text').blur(function(){ if ($('#nub_option_text').val() != preview.find('a').html()) { update_button(); } });
	$('#nub_option_title').blur(function(){ if ($('#nub_option_title').val() != preview.find('a').attr('title')) { update_button(); } });
	$('#nub_option_border_color').blur(function(){ update_color(); });
	$('#nub_option_background_color').blur(function(){ update_color(); });
	$('#nub_option_font_color').blur(function(){ update_color(); });
	$('input[name="nyams_ultimate_button[position]"]').click(function(){  update_position(); });
	$('#nub_option_offset').blur(function(){ update_position() });

	//INIT PREVIEW
	update_position();
	update_color();
});
