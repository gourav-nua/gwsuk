var config = {
    paths: {
        'owl.carousel': 'Olegnax_Core/owl.carousel/owl.carousel.min',
        'OXowlCarousel': 'Olegnax_Core/owl.carousel',
        'jquery/lazyload': 'Olegnax_Core/js/jquery.lazyload',
    },
    shim: {
        'owl.carousel': {deps: ['jquery', 'jquery-ui-modules/widget']},
        "Olegnax_Core/js/jquery.lazyload": ["jquery"]
    }
};