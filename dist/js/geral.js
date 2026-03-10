// Aguarda o DOM estar carregado para garantir que os elementos estao disponiveis
document.addEventListener('DOMContentLoaded', function () {
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
});