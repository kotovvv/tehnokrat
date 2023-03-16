jQuery(document).ready(function($){

    if($(window).width() >= 768){

        $('.guaran li div').matchHeight({
            byRow: false
        });

        $('.deprived li div').matchHeight({
            byRow: false
        });

        $('.subject li div').matchHeight({
            byRow: false
        });

    }

});