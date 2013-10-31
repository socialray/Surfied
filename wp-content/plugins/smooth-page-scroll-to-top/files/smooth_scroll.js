/* From: http://gazpo.com/2012/02/scrolltop/
* 
* scrollTop() > 100 = If scroll more than 100px, it fades in the .scrollup button
*
* scrollTop: 0 = scrolls to the very top of the page, at 0px position
*
* 600 = Represents the duration of animation in milliseconds!
* Higher values indicate slower animations.
* You can also use 'fast', 'slow' or 'normal' instead of milliseconds.
* 
* Fixed jQuery.noConflict(); = ($ to jQuery) :)
*/
jQuery(document).ready(function($){
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	});
	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});
});