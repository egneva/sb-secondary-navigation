(function() {
    var secondaryNav = document.querySelector('.secondary-menu-container');
    var headerHeight = document.querySelector('header').offsetHeight;
    var lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // Make the nav sticky only after scrolling past the header
        if (scrollTop > headerHeight) {
            secondaryNav.style.position = 'fixed';
            
            // Hide nav when scrolling down, show when scrolling up
            if (scrollTop > lastScrollTop) {
                secondaryNav.classList.add('nav-up');
            } else {
                secondaryNav.classList.remove('nav-up');
            }
        } else {
            secondaryNav.style.position = 'static';
        }

        lastScrollTop = scrollTop;
    });
})();
