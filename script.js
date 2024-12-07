
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function () {
        const dropdown = this.querySelector('.drop-down');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    });
});


document.querySelectorAll('nav a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        if (this.getAttribute('href').startsWith('#')) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});
