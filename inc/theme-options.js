var farbtastic;

( function($) {
	
	// set ID or class for the textarea
	var colorpicker    = '#text-color',
	    example        = '#text-color-example',
	    colorpickerdiv = '#text-colorPickerDiv';
	
	var pickColor = function(a) {
	    farbtastic.setColor(a);
	    $(colorpicker).val(a);
	    $(example).css('background-color', a);
	};
	
	$(document).ready( function() {
		$('#text-default-color').wrapInner('<a href="#" />');
		
		farbtastic = $.farbtastic(colorpickerdiv, pickColor);
		
		pickColor( $(colorpicker).val() );
		
		$('.text-pickcolor').click( function(e) {
			$(colorpickerdiv).show();
			e.preventDefault();
		});
		
		$(colorpicker).keyup( function() {
			var a = $(colorpicker).val(),
				b = a;
			
			a = a.replace(/[^a-fA-F0-9]/, '');
			if ( '#' + a !== b )
				$(colorpicker).val(a);
			if ( a.length === 3 || a.length === 6 )
				pickColor( '#' + a );
		});
		
		$(document).mousedown( function() {
			$(colorpickerdiv).hide();
		});
		
		$('#text-default-color a').click( function(e) {
			pickColor( '#' + this.innerHTML.replace(/[^a-fA-F0-9]/, '') );
			e.preventDefault();
		});
		
		$('.image-radio-option.color-scheme input:radio').change( function() {
			var currentDefault = $('#text-default-color a'),
				newDefault = $(this).next().val();
			
			if ( $(colorpicker).val() == currentDefault.text() )
				pickColor( newDefault );
			
			currentDefault.text( newDefault );
		});
	});
	
} )(jQuery);
