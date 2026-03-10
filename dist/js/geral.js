// Aguarda o DOM estar carregado para garantir que os elementos estao disponiveis
document.addEventListener('DOMContentLoaded', function () {
    var body = document.body;
    
    // INICIA LOTTIE DO WHATSAPP
    var whatsappContainer = document.getElementById('whatsapp-fixed');

    if (whatsappContainer && typeof bodymovin !== 'undefined') {
        bodymovin.loadAnimation({
            container: whatsappContainer,
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: backvars.dist + 'lottie/whatsapp2.json',
            rendererSettings: {
                scaleMode: 'fit',
                clearCanvas: true,
                progressiveLoad: true
            }
        });
    }

    console.log(backvars.dist + 'lottie/whatsapp2.json');

    window.scrollTo(0, 0);
    body.classList.add('topScroll');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            body.classList.add('inScroll');
            body.classList.remove('topScroll');
            return;
        }

        body.classList.remove('inScroll');
        body.classList.add('topScroll');
    });
});