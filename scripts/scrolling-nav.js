//jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});
$(document).ready(function(){
      $('body').append('<div id="toTop" class="btn btn-green-crt"><i class="fa fa-arrow-up"></i></div>');
    	$(window).scroll(function () {
			if ($(this).scrollTop() != 0) {
				$('#toTop').fadeIn();
			} else {
				$('#toTop').fadeOut();
			}
		}); 
    $('#toTop').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
});
// Ty daniel <3, te amo bebe esto aparecerá publico <3
$(function() {
    $("#forum a").not(".dd-selected, .dd-option, .bbc_link").each(function(i){
        var titulo = $(this).attr("href");
            if (this.href.indexOf("#") != -1 || this.href.indexOf("javascript") != -1) 
            {
                 $(this).attr("href", titulo);   
            }
             else
            {
               $(this).attr("href", titulo + "#forum"); 
            }   
    });
});
