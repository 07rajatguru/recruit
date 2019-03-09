
$(document).ready(function() {

	$("#owl-demo").owlCarousel({
		autoPlay: 3000, 
		items : 4,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [979,3]
	});

	$('.About_team.owl-carousel').owlCarousel({
        loop: false,
        stagePadding: 0,
        center: false,
        mouseDrag: false,
        touchDrag: true,
        margin: 30,
        autoplay: true,
        autoplayTimeout: 5000,
        smartSpeed: 1000,
        autoplayHoverPause: false,
        dotsEach: true,
        autoHeight:false,
        navText: ["<span title='Previous'><i class='fa fa-angle-left'></i></span>","<span title='Next'><i class='fa fa-angle-right'></i></span>"],
        responsive:{
            0:{items: 1, dots: true, nav: false},
            480:{items: 2, dots: true, nav: false},
            768:{items: 3, dots: true, nav: false},
            1024:{items: 3, dots: true, nav: false},
            1025:{items: 4, dots: true, nav: false},
        }
    });

	var HeaderH = $("#header").height();
	$(".inner_banner").css("margin-top", HeaderH - 5);

    // counter
    $('.counter').counterUp({
        delay: 10,
        time: 2000
    });
});
$('.grid').isotope({
  itemSelector: '.grid_item',
  percentPosition: true,
  masonry: {
    columnWidth: '.grid_sizer'
}
});
$(window).on("load resize",function(e){
    if ($(window).width() >= 768) {
        var i=0;
        $('.dashboard_right').each(function(){
            i++;
            $(this).attr('id','menu'+i);
            var size = $('#menu'+i).children().size();
            var height = $('#menu'+i).height();
            $('#menu'+i).children().css({'min-height':height/size})
        });
    }else{
        $('.dashboard_counter').removeAttr('style')
    }
});