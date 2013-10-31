/* ajaxize javascript function - calls ajaxize_this on the server for the div */
jQuery(document).ready( function($) {
    $('div[id^="ajaxize_this"]').each( function() {
        $(this).ajaxError(function(e, xhr, settings, exception) {
            // checking that this is a 404 page in which we want to use ajaxize
            // (as opposed to an error with ajaxize response itself)
            // and that the response is not empty 
            if (ajaxizeParams.is_404 && settings.url.indexOf(escape($(this)[0].id))>0) {
                $(this)[0].innerHTML = xhr.responseText;
            }
        }); 
        var newquery = $.query.set('ajaxize_this', $(this).attr('id')).set('_wpnonce', ajaxizeParams._wpnonce);

        $(this).load(location.pathname + newquery, function() {
            // renaming div id to prevent loops
            $(this).attr('id', 'loaded_' + $(this).attr('id'));
        });
    });
    return false;
});
