<?php
/*
 Template Name: Fase 3 Encerrado
*/

get_header();
?>

<div class="relative text-foz-white">
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-white/5 backdrop-blur-md bg-foz-black/90 shadow-[0_20px_40px_rgba(5,7,12,0.4)]">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-6">
                <a href="#" class="flex items-center gap-3">
                    <img src="<?=THEME_PUBLIC?>logo_foz-plaza-hotel_horizontal-minimal.svg" alt="Foz Plaza Hotel" class="h-7 w-auto">
                </a>

                <div class="hidden md:flex items-center gap-6 text-xs lg:text-sm font-semibold uppercase text-foz-white/80">
                    <a href="#ranking" class="hover:text-foz-yellow transition-colors">Ranking Final</a>
                    <a href="#o-hotel" class="hover:text-foz-yellow transition-colors">O Hotel</a>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-1">
                    <a href="https://www.fozplaza.com.br" target="_blank" class="text-sm sm:text-base gradient-yellow text-[#1a1f2c] font-semibold px-4 md:px-6 py-2 rounded-full transition-transform duration-200 hover:scale-105">
                        Site Oficial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section id="reserva" class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">
        <video
            class="fixed inset-0 h-full w-full object-cover -z-1"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            aria-hidden="true"
        >
            <source src="<?=THEME_PUBLIC?>foz-plaza_institucional.webm" type="video/webm">
            <source src="<?=THEME_PUBLIC?>foz-plaza_institucional.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-linear-to-b from-foz-black/98 via-foz-black/70 to-foz-black"></div>

        <div class="relative z-10 container mx-auto max-w-4xl text-center space-y-8 pt-20">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/10 rounded-full px-4 py-1.5 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-white">Campanha Encerrada</span>
            </div>

            <h1 class="heading-display text-4xl md:text-6xl lg:text-7xl text-foz-white leading-tight">
                Black Friday <span class="text-foz-yellow">2025</span><br>
                Sucesso Absoluto!
            </h1>

            <p class="text-lg md:text-xl text-foz-white/80 max-w-2xl mx-auto leading-relaxed">
                Agradecemos a todos que participaram da nossa maior campanha da história. Todas as oportunidades promocionais foram aproveitadas.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="#ranking" class="gradient-yellow text-foz-black px-8 py-4 rounded-full font-bold text-lg shadow-glow hover:scale-105 transition-transform w-full sm:w-auto">
                    Ver Ranking Final
                </a>
                <a href="#o-hotel" class="px-8 py-4 rounded-full font-medium text-lg border border-white/10 text-foz-white hover:bg-white/5 transition-all w-full sm:w-auto">
                    Conheça o Hotel
                </a>
            </div>
        </div>
    </section>

    <section id="ranking" class="py-16 md:py-24 px-4 bg-foz-black border-t border-white/5">
        <div class="container mx-auto max-w-6xl">
             <div class="text-center mb-12">
                <h2 class="heading-display text-3xl md:text-4xl text-foz-white">Ranking Final</h2>
                <p class="text-foz-white/70 mt-2">Confira os ganhadores da campanha.</p>
            </div>
            <?php require_once __DIR__ . '/../components/ranking-frontend.php'; ?>
        </div>
    </section>

    <!-- Sobre o Hotel -->
    <section id="o-hotel" class="py-16 md:py-24 px-4 bg-[#0B0B0F] relative overflow-hidden border-t border-white/5">
        <div class="container mx-auto max-w-6xl">
            <!-- Header -->
            <div class="text-center mb-12 md:mb-16">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-5 py-2 mb-8 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3 sm:size-6 text-foz-yellow" aria-hidden="true">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="text-[#00af87] font-bold text-xs sm:text-sm sm:uppercase sm:tracking-wide">Top 10% melhores hotéis do mundo</span>
                </div>
                
                <h2 class="heading-display text-3xl md:text-4xl text-foz-white mb-6">Conheça o Foz Plaza Hotel</h2>
                
                <h3 class="text-xl md:text-2xl text-foz-yellow font-medium mb-8 max-w-3xl mx-auto">
                    "Um oásis de conforto e lazer, no centro de Foz do Iguaçu"
                </h3>
                
                <p class="text-foz-white/70 max-w-4xl mx-auto text-base leading-relaxed font-light">
                    Na área mais nobre do centro de Foz do Iguaçu, combinamos em nossa estrutura: <strong class="text-foz-white">Conforto, modernidade, lazer e bem-estar</strong>, em ambientes integrados com um paisagismo verde natural em meio à cidade.
                    <br><br>
                    Uma experiência inesquecível, com ótimo custo benefício e serviços premium.
                </p>
            </div>

            <!-- Gallery -->
            <?php 
            $gallery = get_field('galeria_fotos');
            if( $gallery ): 
                $total_images = count($gallery);
                $half_index = ceil($total_images / 2);
                $gallery_top = array_slice($gallery, 0, $half_index);
                $gallery_bottom = array_slice($gallery, $half_index);

                // Duplicar arrays para garantir loop infinito suave (x4)
                $gallery_top_loop = array_merge($gallery_top, $gallery_top, $gallery_top, $gallery_top);
                $gallery_bottom_loop = array_merge($gallery_bottom, $gallery_bottom, $gallery_bottom, $gallery_bottom);
            ?>
            <div 
                x-data="{
                    active: true,
                    speed: 0.6, // Velocidade em pixels por frame (60fps)
                    init() {
                        const top = this.$refs.containerTop;
                        const bottom = this.$refs.containerBottom;
                        
                        this.$nextTick(() => {
                            // Largura de um ciclo único (1/4 do total pois duplicamos 4x)
                            // Usamos scrollWidth que é a largura total do conteúdo
                            const loopTop = top.scrollWidth / 4;
                            const loopBottom = bottom.scrollWidth / 4;
                            
                            // Posições iniciais
                            let posTop = 0;
                            
                            // Bottom começa avançado para permitir scroll para trás (esquerda)
                            // + Desalinhamento fixo de ~200px (metade de um item médio)
                            let posBottom = (loopBottom * 2) + 200; 
                            
                            const animate = () => {
                                if (this.active) {
                                    // Top Row: Move para direita (conteúdo vai para esquerda)
                                    posTop += this.speed;
                                    if (posTop >= loopTop) {
                                        posTop -= loopTop; // Reset imperceptível
                                    }
                                    top.scrollLeft = posTop;

                                    // Bottom Row: Move para esquerda (conteúdo vai para direita)
                                    posBottom -= this.speed;
                                    if (posBottom <= loopBottom) {
                                        posBottom += loopBottom; // Reset imperceptível
                                    }
                                    bottom.scrollLeft = posBottom;
                                }
                                requestAnimationFrame(animate);
                            };
                            
                            requestAnimationFrame(animate);
                        });
                    }
                }"
                class="relative group mb-14"
                @mouseenter="active = false"
                @mouseleave="active = true"
                @touchstart="active = false"
                @touchend="active = true"
            >
                <!-- Gradient Overlays (Shared) -->
                <div class="absolute left-0 top-0 bottom-0 w-12 md:w-24 bg-linear-to-r from-foz-black to-transparent z-10 pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-12 md:w-24 bg-linear-to-l from-foz-black to-transparent z-10 pointer-events-none"></div>

                <!-- Top Row -->
                <div 
                    x-ref="containerTop"
                    class="flex gap-4 overflow-x-hidden pb-2 pl-4"
                >
                    <?php foreach( $gallery_top_loop as $image_id ): ?>
                        <div class="flex-none w-72 md:w-96 aspect-4/3 relative rounded-2xl overflow-hidden border border-white/10 shadow-lg group-hover:border-foz-yellow/30 transition-colors">
                            <?= wp_get_attachment_image( $image_id, 'medium_large', false, ['class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-700', 'loading' => 'lazy'] ); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bottom Row -->
                <div 
                    x-ref="containerBottom"
                    class="flex gap-4 overflow-x-hidden pt-2 pr-4"
                >
                    <?php foreach( $gallery_bottom_loop as $image_id ): ?>
                        <div class="flex-none w-72 md:w-96 aspect-4/3 relative rounded-2xl overflow-hidden border border-white/10 shadow-lg group-hover:border-foz-yellow/30 transition-colors">
                            <?= wp_get_attachment_image( $image_id, 'medium_large', false, ['class' => 'w-full h-full object-cover hover:scale-110 transition-transform duration-700', 'loading' => 'lazy'] ); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6">
                <a 
                    href="https://fozplaza.com.br" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="px-8 md:px-10 py-4 font-medium text-lg rounded-full border border-white/10 text-foz-white hover:bg-white/5 hover:border-foz-yellow/50 transition-all w-full sm:w-auto text-center flex items-center justify-center gap-2"
                >
                    Visitar site do hotel
                    <svg class="size-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-16 md:py-24 px-4 bg-[#0B0B0F]">
        <?php require_once __DIR__ . '/../components/faq.php'; ?>
    </section>

    <?php require_once __DIR__ . '/../components/foot-fase3.php'; ?>
</div>

<?php get_footer(); ?>
