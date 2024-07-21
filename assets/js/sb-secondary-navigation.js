jQuery(document).ready(function($) {
    const menu = $('.secondary-menu');
    const navLeft = $('<button class="nav-arrow nav-arrow-left" aria-label="Scroll left">&#9664;</button>');
    const navRight = $('<button class="nav-arrow nav-arrow-right" aria-label="Scroll right">&#9654;</button>');

    if (menu.length) {
        menu.before(navLeft).after(navRight);

        function updateArrowState() {
            const scrollLeft = menu.scrollLeft();
            const maxScroll = menu[0].scrollWidth - menu.width();
            navLeft.prop('disabled', scrollLeft <= 0);
            navRight.prop('disabled', scrollLeft >= maxScroll);
        }

        function scrollMenu(direction) {
            const scrollAmount = menu.width() / 2;
            menu.stop().animate({
                scrollLeft: '+=' + (direction * scrollAmount)
            }, 300, updateArrowState);
        }

        navLeft.on('click', () => scrollMenu(-1));
        navRight.on('click', () => scrollMenu(1));
        menu.on('scroll', updateArrowState);

        function adjustMenu() {
            const isMobile = $(window).width() <= 768;
            
            if (isMobile) {
                $('.secondary-menu .menu-item-has-children > a').off('click').on('click', function(e) {
                    const $parentLi = $(this).parent();
                    const $submenu = $parentLi.children('.sub-menu');

                    if ($parentLi.hasClass('submenu-open')) {
                        $parentLi.removeClass('submenu-open');
                        $submenu.slideUp();
                    } else {
                        // Close all other open submenus
                        $('.secondary-menu .submenu-open').removeClass('submenu-open').children('.sub-menu').slideUp();
                        
                        // Open this submenu
                        $parentLi.addClass('submenu-open');
                        $submenu.slideDown();
                    }
                    
                    // Prevent navigation for parent items with children
                    if ($parentLi.hasClass('menu-item-has-children')) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });

                navLeft.show();
                navRight.show();
                menu.css('overflow-x', 'auto');
            } else {
                $('.secondary-menu .menu-item-has-children > a').off('click');
                $('.secondary-menu .sub-menu').css('display', '');
                $('.secondary-menu > li').removeClass('submenu-open');
                navLeft.hide();
                navRight.hide();
                menu.css('overflow-x', 'visible');
            }
            updateArrowState();
        }

        $(window).on('resize', adjustMenu);
        adjustMenu();
        updateArrowState();

        // Close submenus when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.secondary-menu').length) {
                $('.secondary-menu .submenu-open').removeClass('submenu-open');
                $('.secondary-menu .sub-menu').slideUp();
            }
        });

        // Prevent submenu collapse when clicking inside submenu
        $('.secondary-menu .sub-menu').on('click', function(e) {
            e.stopPropagation();
        });
    } else {
        console.error('Secondary menu not found');
    }
});
