$(function() {
	// run the currently selected effect
	function hideEffect() {
		selectedEffect="fade";
		var options = {};
		// some effects have required parameters
		if ( selectedEffect === "scale" ) {
			options = { percent: 100 };
		} else if ( selectedEffect === "size" ) {
			options = { to: { width: 280, height: 185 } };
		}
		// run the effect
		$( "#effect" ).hide( selectedEffect, options, 500, callback );
		$( "#resultSearch" ).show( "fade", options, 500, callback );
		$( "#paginator" ).show( "fade", options, 500, callback );
		//$( "#effect" ).hide();
	};

	
	function showEffect() {
		selectedEffect="fade";
		var options = {};
		// some effects have required parameters
		if ( selectedEffect === "scale" ) {
			options = { percent: 100 };
		} else if ( selectedEffect === "size" ) {
			options = { to: { width: 280, height: 185 } };
		}
		// run the effect
		$( "#effect" ).show( selectedEffect, options, 500, callback );
		$( "#resultSearch" ).hide( "fade", options, 500, callback );
		$( "#paginator" ).hide( "fade", options, 500, callback );
		//$( "#effect" ).hide();
	};	
	
	function callback() {
		setTimeout(function() {
			//$( "#effect:visible" ).removeAttr( "style" ).fadeOut();
		}, 1000 );
	};

	
	// set effect from select menu value
	$( "#botonhide" ).click(function() {
		hideEffect();
		xajax_auxSearchShow(20,1,xajax.getFormValues('formSearch'));
		return false;
	});

	$( "#botonshow" ).click(function() {
		showEffect();
		return false;
	});	
	
	

});
