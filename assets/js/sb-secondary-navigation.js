jQuery(document).ready(function($) {
    const menu = $('.secondary-menu');
    const navLeft = $('<button class="nav-arrow nav-arrow-left">&#9664;</button>');
    const navRight = $('<button class="nav-arrow nav-arrow-right">&#9654;</button>');

    menu.before(navLeft).after(navRight);

    function updateArrowState() {
        navLeft.prop('disabled', menu.scrollLeft() <= 0);
        navRight.prop('disabled', menu.scrollLeft() >= menu[0].scrollWidth - menu.width());
    }

    function scrollMenu(direction) {
        const scrollAmount = menu.width() / 2;
        menu.animate({
            scrollLeft: menu.scrollLeft() + direction * scrollAmount
        }, 300, updateArrowState);
    }

    navLeft.on('click', () => scrollMenu(-1));
    navRight.on('click', () => scrollMenu(1));

    menu.on('scroll', updateArrowState);

    function adjustMenu() {
        if ($(window).width() <= 768) {
            $('.secondary-menu .menu-item-has-children > a').unbind('click').on('click', function(e) {
                e.preventDefault();
                $(this).siblings('.sub-menu').slideToggle();
                $(this).parent().toggleClass('submenu-open');
            });
            navLeft.show();
            navRight.show();
        } else {
            $('.secondary-menu .menu-item-has-children > a').unbind('click');
            $('.secondary-menu .sub-menu').show();
            navLeft.hide();
            navRight.hide();
        }
        updateArrowState();
    }

    $(window).on('resize', adjustMenu);
    adjustMenu();
});
