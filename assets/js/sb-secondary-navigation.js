jQuery(document).ready(function($) {
    function adjustMenu() {
        if ($(window).width() <= 768) {
            $('.secondary-menu .menu-item-has-children > a').unbind('click').on('click', function(e) {
                e.preventDefault();
                $(this).siblings('.sub-menu').slideToggle();
                $(this).parent().toggleClass('submenu-open');
            });
        } else {
            $('.secondary-menu .menu-item-has-children > a').unbind('click');
            $('.secondary-menu .sub-menu').show();
        }
    }

    $(window).resize(adjustMenu);
    adjustMenu();
});
