jQuery(document).ready(function(){
		jQuery('.bp-social-button').live('click', function(){
			jQuery(this).toggleClass('active').next().slideToggle('fast');		
	});	
    	jQuery('a.new-window').live('click', function(){
        	window.open(this.href,'newWindow','width=700,height=350');
        	return false;
    });	
});