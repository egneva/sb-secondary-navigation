jQuery(document).ready(function($) {
    $('.secondary-menu .menu-item-has-children > a').on('click', function(e) {
        if ($(window).width() <= 768) {
            e.preventDefault();
            $(this).parent().toggleClass('submenu-open');
        }
    });
});
