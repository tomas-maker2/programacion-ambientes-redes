const menus = document.querySelectorAll('.menu');

menus.forEach(function (menu) {
    const lista = menu.querySelector('.desplegable');

    menu.addEventListener('mouseenter', function () {
        lista.classList.remove('oculto');
    });

    menu.addEventListener('mouseleave', function () {
        lista.classList.add('oculto');
    });
});
