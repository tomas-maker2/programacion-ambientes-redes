const idioma = navigator.language;

const saludo = document.getElementById('saludo');

if (idioma.startsWith('es')) {
    saludo.textContent = 'Hola, ¿cómo les va?';
} else {
    saludo.textContent = 'Hello, how are you?';
}
