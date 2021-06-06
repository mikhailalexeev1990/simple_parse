document.addEventListener('DOMContentLoaded', () => {
    let burgerMenu = document.querySelector('.burger-menu');
    let mobileNav = document.querySelector('.mobile-nav');
    burgerMenu.addEventListener('click', function() {
        burgerMenu.classList.toggle('menu-on');
        mobileNav.classList.toggle('--fixed');
    });
});
