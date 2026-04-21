// Efek Mouse Parallax untuk Gambar Produk
const card = document.querySelector('.card');
const img = document.querySelector('.product-img');

card.addEventListener('mousemove', (e) => {
    let xAxis = (window.innerWidth / 2 - e.pageX) / 25;
    let yAxis = (window.innerHeight / 2 - e.pageY) / 25;
    img.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
});

card.addEventListener('mouseleave', () => {
    img.style.transition = "all 0.5s ease";
    img.style.transform = `rotateY(0deg) rotateX(0deg)`;
});