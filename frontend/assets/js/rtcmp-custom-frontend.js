jQuery(document).ready(function($) 
{
    var swiper = new swiper(".mySwiper", {
        pagination: {
                    el: ".swiper-pagination",
                    type: "fraction",
            },
            navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
            },
    });
});
