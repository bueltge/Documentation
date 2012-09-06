var farbtastic;

( function($) {
	
	var pickColor = function(a) {
		farbtastic.setColor(a);
		$('#text-color').val(a);
		$('#text-color-example').css('background-color', a);
	};
	
	$(document).ready( function() {
		$('#default-color').wrapInner('<a href="#" />');
		
		farbtastic = $.farbtastic('#colorPickerDiv', pickColor);
		
		pickColor( $('#text-color').val() );
		
		$('.pickcolor').click( function(e) {
			$('#colorPickerDiv').show();
			e.preventDefault();
		});
		
		$('#text-color').keyup( function() {
			var a = $('#text-color').val(),
				b = a;
			
			a = a.replace(/[^a-fA-F0-9]/, '');
			if ( '#' + a !== b )
				$('#text-color').val(a);
			if ( a.length === 3 || a.length === 6 )
				pickColor( '#' + a );
		});
		
		$(document).mousedown( function() {
			$('#colorPickerDiv').hide();
		});
		
		$('#default-color a').click( function(e) {
			pickColor( '#' + this.innerHTML.replace(/[^a-fA-F0-9]/, '') );
			e.preventDefault();
		});
		
		$('.image-radio-option.color-scheme input:radio').change( function() {
			var currentDefault = $('#default-color a'),
				newDefault = $(this).next().val();
			
			if ( $('#text-color').val() == currentDefault.text() )
				pickColor( newDefault );
			
			currentDefault.text( newDefault );
		});
	});
	
} )(jQuery);
