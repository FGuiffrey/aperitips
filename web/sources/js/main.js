$(document).ready(function () {

    const app = {
        sticky : {
            element: $('.js_header'),
            class: 'sticky',
            height: $('#push').height() + 76,
            burgerBtn : $('.js_burger_btn')
        },
        anchor : {
            element: $('.js_link'),
            class : 'active',
            findNav: $('#js_nav').find('')
        },
        nav : $('#js_nav')
    };


    const onScroll = function(){
        let scrollPos = $(document).scrollTop();
        app.anchor.findNav.each(function () {
            let currLink = $(this);
            let refElement = $(currLink).attr('href');
            refElement = refElement.substring(refElement.indexOf('#'));
            if ($(refElement).position().top <= scrollPos && $(refElement).position().offsetTop + $(refElement).height() > scrollPos) {
                app.anchor.findNav.removeClass(app.anchor.class);
                currLink.addClass(app.anchor.class);
            }
            else{
                currLink.removeClass(app.anchor.class);
            }
        });

        if( $(this).scrollTop() > app.sticky.height ) {
            app.sticky.element.addClass(app.sticky.class);
        } else {
            app.sticky.element.removeClass(app.sticky.class);
        }
    };


    $(document).on('scroll', onScroll);

    //check if we're on the homepage
    if($('.push-container').length > 0) {

        app.anchor.element.on('click', function (e) {
            e.preventDefault();
            $(document).off('scroll');

            $('.js_link').each(function () {
                $(this).removeClass(app.anchor.class);
            });
            $(this).addClass(app.anchor.class);

            let target = this.hash;
            let targetElement = $(target);
            $('html, body').stop().animate({
                'scrollTop': targetElement.offset().top + 2
            }, 500, 'swing', function () {
                window.location.hash = target;
                $(document).on('scroll', onScroll);
            });
        });

    }

    //MOBILE NAV

    app.sticky.burgerBtn.on('click', function(){
        app.nav.toggleClass('open');
        app.sticky.burgerBtn.toggleClass('triggered');
    });

    app.anchor.element.on('click', function(){
        app.nav.toggleClass('open');
        app.sticky.burgerBtn.toggleClass('triggered');
    });

});
