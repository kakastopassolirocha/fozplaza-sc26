<!-- Final CTA Section -->
    <section id="hotel" class="py-16 md:py-24 px-4 bg-linear-to-b from-foz-black via-foz-dark-red/30 to-foz-black">
        <div class="container mx-auto max-w-4xl text-center">
            <img src="<?=THEME_PUBLIC?>selo-vendas.png" alt="24 a 30 de Novembro" class="w-32 mx-auto mb-8 animate-float">
            
            <h2 class="heading-display text-2xl md:text-4xl mb-6">
                NÃO PERCA <span class="text-gradient">ESSA<br>OPORTUNIDADE!</span>
            </h2>
            
            <p class="text-lg md:text-xl text-foz-white/80 mb-8">
                São apenas <strong class="text-foz-yellow">250 reservas</strong> disponíveis.<br>
                Participe agora e aproveite o maior desconto da história!
            </p>
            
            <button 
                @click="scrollToForm()"
                class="gradient-yellow text-foz-black px-8 md:px-12 py-4 md:py-6 text-lg md:text-xl font-bold rounded-full shadow-glow hover:scale-105 transition-transform inline-flex items-center gap-3"
            >
                QUERO APROVEITAR O SUPER DESCONTO
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 px-4 bg-foz-black border-t border-foz-white/10">
        <div class="container mx-auto text-center">
            <div class="mb-6">
                <img src="<?=THEME_PUBLIC?>logo-horizontal-cor.svg" alt="Foz Plaza Hotel" class="h-20 mx-auto mb-4">
                <p class="text-foz-white/60 text-sm">
                    Um oásis de conforto e lazer, no centro de Foz do Iguaçu
                </p>
            </div>
            
            <div class="border-t border-foz-white/10 pt-6">
                <p class="text-foz-white/40 text-xs">
                    © 2025 Foz Plaza Hotel. Todos os direitos reservados.<br>
                    Black Friday 2025 - Campanha válida de 24 a 30 de novembro de 2025
                </p>
                <div class="mt-4 flex justify-center gap-4 text-xs text-foz-white/40">
                    <!-- <a href="#" class="hover:text-foz-yellow transition-colors">Termos e Condições</a>
                    <span>•</span> -->
                    <a href="https://fozplaza.com.br/politica-de-privacidade" target="_blank" class="hover:text-foz-yellow transition-colors">Política de Privacidade</a>
                    <span>•</span>
                    <a href="https://fozplaza.com.br" target="_blank" class="hover:text-foz-yellow transition-colors">fozplaza.com.br</a>
                </div>
            </div>
        </div>
    </footer>