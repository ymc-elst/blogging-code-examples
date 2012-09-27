/*
 * Handle the content in the toolbar-Layer
 * to do a simple functions we just read
 * the cookie of the eZ Publish admin. If the developer
 * is "is_logged_in" we can do anything with ajax in the profiler
 * toolbar what we can do in the eZ Admin
*/
var PROFILER = function() {
    return {
        handleEzAdminLogin : function() {
            /*
            check if user is logged in to eZAdmin
            Otherwise Ajax-Calls to Admin will not work.
            */
            var adminframe= document.getElementById('adminframe');
            var admindocument= 'contentDocument' in adminframe? adminframe.contentDocument : adminframe.contentWindow.document; // IE compat
            var isLoggedIn = admindocument.cookie.indexOf("is_logged_in");

            if (isLoggedIn == -1) {

                //if not logged in disable the #ClearAllCacheButton
                $('input[name="ClearAllCacheButton"]').attr("disabled", "disabled");
                $('#ClearAllCacheButton').css({ opacity: 0.3 });

            } else {

                //if logged in, don't show the "please login first" message
                $('#loginMessage' ).remove();

            }
        },

        /*
         * For example use the clear all caches function
         * from the eZ Publish Admin with ajax and inject
         * it into the symfony profiler toolbar
        */
        clearEzCache : function() {
          //attach a submit handler to the form
            $('#searchForm').submit(function(event) {

            //stop form from submitting normally
            event.preventDefault();

            //once clicked show the spinner
            $('#cacheClearResult').empty().append('<img src="/ezpublish5/web/design/admin2/images/2/loader.gif">');

            //get some values from elements on the page:
            var $form = $( this ),
                term = $form.find('input[name="ClearAllCacheButton"]').val(),
                url = $form.attr('action');

            //Send the data using post and put the results in a div
            $.post(url, { ClearAllCacheButton: term },

                function(data) {
                    var content = $(data).find('.message-feedback');

                    if (content.length != 0) {

                        //successful request, all caches cleared
                        $('#cacheClearResult').empty().append('<span class="success">All caches cleared.</span>');

                    } else {

                        //no response or empty response... something went wrong
                        $('#cacheClearResult').empty().append('<span class="error">! ERROR ! couldn`t clear caches.</span>');
                        return false;

                    }
                });
            });
        }
    }
}();

$(document).ready(function() {
    /*
     * urgs, waitin 'til symfony profiler toolbar is loaded and injected into DOM
     * baaad workaround :-)
     */
    setTimeout("PROFILER.handleEzAdminLogin();", 2000);
    setTimeout("PROFILER.clearEzCache()", 2000);
});
