/*!
 * Theme meline29
 *
 * Copyright (c) 2013-2014 Eduardo Ramos
 * Licensed under GNU GPL v3 or later
 *
 */

(function($) {
    $(document).ready(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 550) {
                $('#toTop').fadeIn();
            } else {
                $('#toTop').fadeOut();
            }
        });

        $("#navigationAccordionToggle").click(function(event) {
            event.preventDefault();
            $("#navigationAccordion").slideToggle();
        });
        
        $(window).resize(function() {
            if(this.resizeTO) clearTimeout(this.resizeTO);
            this.resizeTO = setTimeout(function() {
                $(this).trigger('resizeEnd');
            }, 300);
        });
        
        //Restore accordion visibility on window resize:
        $(window).bind('resizeEnd', function() {
            $("#navigationAccordion").attr('style', null);//Remove slideToggle display rule
        });
    });
})(jQuery);

//POPUP Feature
$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

    $('a[data-modal-id]').click(function(e) {
        e.preventDefault();
    $("body").append(appendthis);
    $("body").addClass("modal-body-overlay");
    $(".modal-overlay").fadeTo(500, 0.7);
    
    
    //$(".js-modalbox").fadeIn(500);
        var modalBox = $(this).attr('data-modal-id');
        $('#'+modalBox).fadeIn($(this).data());
    });  
  
  
$(".js-modal-close, .modal-overlay, .body-modal-close").click(function() {
    $(".modal-box, .modal-overlay").fadeOut(500, function() {
        $("#msg_body").val("");
        $("#reset_notify").html("");
        $(".modal-overlay").remove();
    }); 
});
 
$(window).resize(function() {
  $(".modal-box").css({
        top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
    });
});
$(window).resize();



//For Navigation tirpal level Issue 

$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
    // Avoid following the href location when clicking
    event.preventDefault(); 
    // Avoid having the menu to close when clicking
    event.stopPropagation(); 
    // Re-add .open to parent sub-menu item
    $(this).parent().addClass('open');
    $(this).parent().find("ul").parent().find("li.dropdown").addClass('open');
});

//End JS
 
});



