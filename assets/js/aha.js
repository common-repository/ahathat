 jQuery(document).ready(function(){
	//jQuery(function(){
	//var appendthis =  ("<div class='modas-overlay js-modas-close'></div>");

	jQuery('body').on('click','a[data-modas-id]',function(e){
		e.preventDefault();
		// jQuery("body").append(appendthis);
	    jQuery(".modas-overlay").fadeTo(500, 0.7);
		//jQuery(".js-modasbox").fadeIn(500);
		var modasBox = jQuery(this).attr('data-modas-id');
		jQuery('#'+modasBox).fadeIn(jQuery(this).data());
	});  

	jQuery('body').on('click','.js-modas-close, .modas-overlay',function(){
    	jQuery(".modas-box, .modas-overlay").fadeOut(500, function() {
        	jQuery(".modas-overlay").remove();
    	});
	});

	jQuery(window).resize(function() { 
	    jQuery(".modas-box").css({
        	top: (jQuery(window).height() - jQuery(".modas-box").outerHeight()) / 2,
        	left: (jQuery(window).width() - jQuery(".modas-box").outerWidth()) / 2
    	});
	});

	jQuery(window).resize();
 }); 