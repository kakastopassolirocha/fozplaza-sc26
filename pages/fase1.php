<?php
/*
 Template Name: Fase 1
*/

// Redirect to fase-2
if(!is_super_admin())
{
    wp_redirect(home_url('/fase-2'));
    exit;
}

get_header();
?>
<?php
$lead_config = [
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('lead_registration'),
    'isLoggedIn' => is_user_logged_in(),
    'user' => null,
    'successRedirect' => '/minha-area',
];

if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $lead_config['user'] = [
        'name' => $current_user->display_name,
        'email' => $current_user->user_email,
        'refId' => get_user_meta($current_user->ID, 'ref_id', true) ?: '',
    ];
}
?>
<script>
    window.blackFridayLead = <?php echo wp_json_encode($lead_config); ?>;
</script>
<div x-data="blackFridayApp()">
    <?php require_once(THEME_DIR . 'components/modal-cadastro.php'); ?>
    <!-- Navigation -->
    <nav
        class="fixed top-0 left-0 right-0 z-50 border-b border-white/5 backdrop-blur-md transition-colors duration-300"
        :class="navIsOpaque ? 'bg-foz-black/90 shadow-[0_20px_40px_rgba(5,7,12,0.4)]' : 'bg-transparent'"
    >
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-6">
                <a href="#sobre" class="flex items-center gap-3">
                    <img src="<?=THEME_PUBLIC?>logo_foz-plaza-hotel_horizontal-minimal.svg" alt="Foz Plaza Hotel" class="h-7 w-auto">
                </a>

                <div class="hidden md:flex items-center gap-6 text-xs md:text-sm font-semibold uppercase tracking-[0.25em] text-foz-white/80">
                    <a href="#sobre" class="hover:text-foz-yellow transition-colors">Sobre a Black</a>
                    <a href="#ranking" class="hover:text-foz-yellow transition-colors">Ranking de Prêmios</a>
                    <a href="#o-hotel" class="hover:text-foz-yellow transition-colors">O Hotel</a>
                </div>

                <div class="flex items-center gap-3">
                    <button 
                        type="button"
                        @click="openRegistrationModal()"
                        x-show="!isRegistered && !isLogged"
                        class="gradient-yellow text-[#1a1f2c] font-semibold px-4 md:px-6 py-2 rounded-full transition-transform duration-200 hover:scale-105"
                    >
                        PARTICIPAR
                    </button>
                    <a 
                        href="/minha-area"
                        class="font-semibold px-4 md:px-6 py-2 rounded-full transition-transform duration-200 hover:scale-105"
                        :class="(isRegistered || isLogged) ? 'gradient-yellow text-[#1a1f2c]' : 'text-foz-white/80'"
                    >
                        Minha Área
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-28 relative min-h-[75vh] flex items-center justify-center px-4 pb-20 overflow-hidden">
        <video
            class="fixed inset-0 h-screen w-screen object-cover -z-1"
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
        <div class="absolute inset-0 bg-linear-to-b from-foz-black/60 via-foz-black/20 to-foz-black/90"></div>

        <!-- hero content-->
        <div class="relative z-10 w-full max-w-4xl mx-auto text-center">

            <!-- Pontos fortes -->
            <div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8 text-sm text-white">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 fill-foz-yellow text-foz-yellow">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Top 10% melhores hotéis do mundo
                </div>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 text-foz-yellow">
                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Centro de Foz do Iguaçu
                </div>
            </div>
            <!-- Pontos fortes -->
            
            <div class="flex justify-center mt-3 mb-6">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-foz-yellow/90 px-5 py-2 text-xs md:text-base font-semibold text-foz-black animate-pulse-glow">
                    📢 O maior desconto da história: <span class="font-bold bg-foz-black px-2 text-foz-yellow rounded-sm animate-pulse">50% OFF</span> de verdade!
                </span>
            </div>

            <div class="flex flex-col gap-12 lg:flex-row lg:gap-32 items-center">
                <img 
                    src="<?=THEME_PUBLIC?>logo-campanha.svg" 
                    alt="Foz Plaza Hotel Black Friday 2025" 
                    class="mt-16 mx-auto w-52 md:w-72 lg:w-[24rem] animate-float"
                >

                <!-- countdown -->
                <div class="mx-auto size-min">
                    <!-- <p class="text-xs md:text-base uppercase tracking-[0.35em] text-foz-white/95 text-center">Abertura das vendas em:</p> -->
                    <h5 class="uppercase text-center bg-foz-black/70 px-4 py-1 text-sm md:text-base rounded-full text-foz-yellow font-semibold shadow-glow z-2 relative w-fit mx-auto">
                        Abertura das vendas em:
                    </h5>
                    <div class="-mt-3 bg-black/50 backdrop-blur-sm border border-white/10 rounded-3xl py-6 px-10 shadow-elevated z-1 relative">
                        <div class="flex items-end justify-center gap-2 md:gap-4">
                            <div class="flex flex-col items-center">
                                <div class="heading-display text-3xl md:text-5xl text-foz-yellow/80 leading-none" x-text="countdown.days">00</div>
                                <div class="mt-2 text-[10px] md:text-xs uppercase tracking-wide text-foz-white/60">Dia</div>
                            </div>
                            <span class="pb-6 text-2xl md:text-5xl font-bold text-white">:</span>
                            <div class="flex flex-col items-center">
                                <div class="heading-display text-3xl md:text-5xl text-foz-yellow/80 leading-none" x-text="countdown.hours">00</div>
                                <div class="mt-2 text-[10px] md:text-xs uppercase tracking-wide text-foz-white/60">Horas</div>
                            </div>
                            <span class="pb-6 text-2xl md:text-5xl font-bold text-white">:</span>
                            <div class="flex flex-col items-center">
                                <div class="heading-display text-3xl md:text-5xl text-foz-yellow/80 leading-none" x-text="countdown.minutes">00</div>
                                <div class="mt-2 text-[10px] md:text-xs uppercase tracking-wide text-foz-white/60">Min</div>
                            </div>
                            <span class="pb-6 text-2xl md:text-5xl font-bold text-white">:</span>
                            <div class="flex flex-col items-center">
                                <div class="heading-display text-3xl md:text-5xl text-foz-yellow/80 leading-none" x-text="countdown.seconds">00</div>
                                <div class="mt-2 text-[10px] md:text-xs uppercase tracking-wide text-foz-white/60">Seg</div>
                            </div>
                        </div>
                        <p class="text-center text-[10px] sm:text-xs mt-6 tracking-wide font-light text-white/70 [&_strong]:text-foz-yellow">
                             <strong>Segunda-feira</strong> às <strong>00h01</strong><br class="sm:hidden"> <small>(Horário de Brasília)</small>
                        </p>
                    </div>
                    <p class="mt-4 text-[11px] md:text-base text-foz-yellow-light text-center z-2 relative">
                        Vendas de <strong class="text-yellow-400">24 a 30 de novembro</strong> de 2025
                    </p>
                </div>
                <!-- countdown -->
            </div>

            <div class="mt-20 space-y-3 mx-auto max-w-[440px]">
                <p class="text-xl md:text-2xl text-foz-white/80 mb-8">
                    São apenas <strong class="text-foz-yellow">250 reservas</strong> disponíveis.<br>
                    Cadastre-se agora para ter acesso!
                </p>
            </div>
            

            <div class="mt-14 flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <button 
                    type="button"
                    @click="openRegistrationModal()"
                    class="gradient-yellow text-[#1a1f2c] font-semibold px-8 md:px-10 py-3 md:py-4 text-base md:text-lg rounded-full transition-transform duration-200 hover:scale-105 animate-badge-blink"
                >
                    Quero participar
                </button>
                <button 
                    type="button"
                    @click="scrollToHowItWorks()"
                    class="px-8 md:px-10 py-3 md:py-4 text-base md:text-lg font-medium rounded-full border border-white/10 text-foz-white/80 hover:text-foz-white hover:border-white/30 transition-colors"
                >
                    Como funciona
                </button>
            </div>
            <p class="mt-6 text-white/75 text-sm font-light mx-auto max-w-[440px]">
                Cadastre-se para receber os códigos de desconto 1 dia antes das vendas, garantir acesso antecipado e participar do ranking de prêmios.
            </p>
        </div>
        <!-- hero content-->
    </section>
    <!-- Hero Section -->

    <!-- Como Funciona -->
    <section id="sobre" class="pb-16 md:pb-24 pt-8 md:pt-12 px-4 bg-linear-to-b from-foz-black/90 via-foz-black to-foz-black">
        <div class="container mx-auto max-w-6xl text-center">
            <div class="text-center mb-14">
                <h2 class="heading-display text-4xl md:text-5xl text-foz-white mb-4">Como vai funcionar?</h2>
                <p class="text-base md:text-lg text-foz-white/60">Acesso antecipado + cupons exclusivos + ranking de prêmios</p>
            </div>

            <!-- Passos -->
            <div class="grid gap-6 md:grid-cols-3 mb-14">
                <!-- Passo 1 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 text-center shadow-elevated hover-elevate hover:border-foz-yellow/40 transition-all">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 bg-foz-yellow/20 blur-2xl rounded-full"></div>
                            <div class="relative bg-foz-yellow rounded-full p-4 sm:p-5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-10 text-foz-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <line x1="19" y1="8" x2="19" y2="14"/>
                                    <line x1="22" y1="11" x2="16" y2="11"/>
                                </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 bg-foz-orange text-foz-black rounded-full size-8 flex items-center justify-center font-bold text-lg border-2 border-background">
                                1
                            </div>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-foreground mb-3">
                            Ative sua participação
                        </h3>
                        <p class="text-foz-white/70 font-light text-sm md:text-base leading-relaxed">
                            <strong class="text-foz-yellow font-bold">Cadastre-se</strong> para receber os cupons, garantir acesso antecipado e participar do ranking de prêmios.
                        </p>
                    </div>
                </div>
                <!-- passo 1-->

                <!-- Passo 2 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 text-center shadow-elevated hover-elevate hover:border-foz-yellow/40 transition-all">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 bg-foz-yellow/20 blur-2xl rounded-full"></div>
                            <div class="relative bg-foz-yellow rounded-full p-4 sm:p-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-10 text-foz-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="9" cy="21" r="1.5"/>
                                <circle cx="19" cy="21" r="1.5"/>
                                <path d="M2 4h3l3.6 10.59a2 2 0 0 0 1.9 1.41H19a2 2 0 0 0 2-1.66L22.3 9H7.15"/>
                            </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 bg-foz-orange text-foz-black rounded-full size-8 flex items-center justify-center font-bold text-lg border-2 border-background">
                                2
                            </div>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-foreground mb-3">
                            Ranking de Prêmios
                        </h3>
                        <p class="text-foz-white/70 font-light text-sm md:text-base leading-relaxed">
                            Participe do ranking e <strong class="text-foz-yellow font-bold">ganhe prêmios</strong> como diárias grátis, jantar, passeios e muito mais!
                        </p>
                    </div>
                </div>

                <!-- Passo 3 -->
                <div class="bg-white/5 border border-white/10 rounded-3xl p-8 text-center shadow-elevated hover-elevate hover:border-foz-yellow/40 transition-all">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 bg-foz-yellow/20 blur-2xl rounded-full"></div>
                            <div class="relative bg-foz-yellow rounded-full p-4 sm:p-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-8 sm:size-10 text-foz-black">
                                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path><path d="M13 5v2"></path><path d="M13 17v2"></path><path d="M13 11v2"></path>
                                </svg>
                            </div>
                            <div class="absolute -top-2 -right-2 bg-foz-orange text-foz-black rounded-full size-8 flex items-center justify-center font-bold text-lg border-2 border-background">
                                3
                            </div>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-foreground mb-3">
                            Receba seus cupons
                        </h3>
                        <p class="text-foz-white/70 font-light text-sm md:text-base leading-relaxed">
                            No dia 23 de Nov, enviamos os códigos exclusivos para você garantir<br><strong class="text-foz-yellow font-bold">desconto de 50% OFF</strong>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Passos -->

            <button 
                @click="openRegistrationModal()"
                class="gradient-yellow text-foz-black px-8 md:px-12 py-4 md:py-6 text-lg md:text-xl font-bold rounded-full shadow-glow hover:scale-105 transition-transform inline-flex items-center gap-3"
            >
                Ativar minha participação
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </section>
    <!-- Como Funciona -->

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
                <button 
                    @click="openRegistrationModal()"
                    class="gradient-yellow text-foz-black px-8 md:px-10 py-4 font-bold text-lg rounded-full shadow-glow hover:scale-105 transition-transform w-full sm:w-auto text-center"
                >
                    Ativar minha Participação
                </button>
                
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
    <!-- Sobre o Hotel -->

    <!-- Benefits Section -->
    <section class="py-16 md:py-24 px-4 bg-[#0B0B0F]">
        <div class="container mx-auto">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-foz-white mb-4">
                    PORQUE VOCÊ <span class="text-foz-white/60">NÃO PODE PERDER A BLACK?</span>
                </h2>
                <p class="text-base md:text-lg text-foz-white/60">
                    Aqui vão 12 bons motivos pra você participar agora!
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto [&_strong]:text-foz-yellow">
                <!-- Benefit 1 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">🏆</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">O maior desconto da história</h3>
                    <p class="text-foz-white/70"><strong>50% OFF GARANTIDO</strong> para hospedagens válidas até dezembro de 2026. É agora ou nunca!</p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Reservas limitadas</h3>
                    <p class="text-foz-white/70">Serão apenas <strong>250 reservas</strong> disponíveis. Não perca tempo, garanta as suas com o maior desconto!</p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">⭐</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Top 10% melhores do mundo</h3>
                    <p class="text-foz-white/70">
                        Oportunidade de se hospedar em um hotel entre os <strong>10% melhores hotéis do mundo no TripAdvisor</strong> por 2 anos consecutivos.
                        <!-- <strong>Traveller's Choice</strong> - Entre os 10% melhores hotéis do mundo no TripAdvisor por 2 anos consecutivos. -->
                    </p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">🎭</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Temporadas temáticas</h3>
                    <p class="text-foz-white/70">Programação diferente todo mês! O hotel <strong>"se transforma"</strong> o ano inteiro para você.</p>
                </div>
                
                <!-- Benefit 5 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">✈️</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Transfer-in incluso</h3>
                    <p class="text-foz-white/70">Do aeroporto <strong>IGU direto para o hotel</strong>. Chegou, relaxou!</p>
                </div>
                
                <!-- Benefit 6 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">💳</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Até 10x sem juros</h3>
                    <p class="text-foz-white/70">Aproveite o super desconto e <strong>parcele sem pesar</strong> no bolso!</p>
                </div>
                
                <!-- Benefit 7 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">👨‍👩‍👧‍👦</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Equipe de recreação</h3>
                    <p class="text-foz-white/70">
                        Recreadores diariamente em altas temporadas e feriados, e aos finais de semana em baixas temporadas. <strong>Diversão garantida!</strong>
                    </p>
                </div>
                
                <!-- Benefit 8 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">🏅</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Ranking de prêmios</h3>
                    <p class="text-foz-white/70">Indique amigos, ganhe pontos e dispute prêmios incríveis, como <strong>hospedagem grátis, jantares, passeios</strong> e muito mais!</p>
                </div>
                
                <!-- Benefit 9 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">🎁</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Indique e ganhe</h3>
                    <p class="text-foz-white/70"><strong>Brinde exclusivo</strong> do Foz Plaza para todos que indicar 10+ amigos!</p>
                </div>
                
                <!-- Benefit 10 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">🔄</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Remarcação flexível</h3>
                    <p class="text-foz-white/70">Garante o desconto agora e pode <strong>usar como crédito</strong> se precisar remarcar. Segurança total!</p>
                </div>
                
                <!-- Benefit 11 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">👶</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Criança free</h3>
                    <p class="text-foz-white/70"><strong>1 criança até 7 anos</strong> hospedada gratuitamente. Economia e diversão!</p>
                </div>
                
                <!-- Benefit 12 -->
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 hover:border-white/20 transition-all">
                    <div class="text-4xl mb-4">☕</div>
                    <h3 class="text-xl font-semibold text-foz-white mb-2 uppercase tracking-tight">Café da manhã incluso</h3>
                    <p class="text-foz-white/70">Todas as reservas incluem nosso <strong>café da manhã premium</strong>, mais de 80 itens + Tapioca/Omelete/Crepe Show.</p>
                </div>
            </div>
            <!-- grid benefícios -->

            <div class="flex justify-center mt-14">
                <button 
                    @click="openRegistrationModal()"
                    class="gradient-yellow text-foz-black px-8 md:px-12 py-4 md:py-6 text-lg md:text-xl font-bold rounded-full shadow-glow hover:scale-105 transition-transform inline-flex items-center gap-3"
                >
                    Não quero perder!
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Registration Form Section -->
    <section id="cadastro" class="py-16 md:py-24 px-4 bg-[#090e19]">
        <div class="container mx-auto max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="heading-display text-4xl md:text-5xl mb-4 text-foz-white">
                    Garanta seu <span class="text-foz-white/60">acesso exclusivo</span>
                </h2>
                <p class="text-base md:text-lg text-foz-white/60">
                    Cadastre-se agora e receba os <strong class="text-foz-yellow">cupons de desconto</strong> em primeira mão!
                </p>
                <p class="text-sm text-foz-white/50 mt-2">
                    ⚡ Cupons serão revelados 1 dia antes das vendas (23/11/2025)
                </p>
            </div>
            
            <div class="bg-white/5 p-8 md:p-12 rounded-3xl border border-white/10 shadow-elevated">
                <form @submit.prevent="submitForm()" class="space-y-6">
                    <div 
                        x-show="formErrors.general"
                        x-transition
                        role="alert"
                        class="rounded-2xl border border-foz-orange/40 bg-foz-orange/10 px-4 py-3 text-sm text-foz-yellow"
                        x-text="formErrors.general"
                    ></div>
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold mb-2 text-foz-yellow">
                            Nome completo *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            x-model="formData.name"
                            required
                            :aria-invalid="formErrors.name ? 'true' : 'false'"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
                            placeholder="Digite seu nome completo"
                        >
                        <p 
                            x-show="formErrors.name"
                            x-text="formErrors.name"
                            class="mt-2 text-sm text-foz-yellow"
                        ></p>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2 text-foz-yellow">
                            E-mail *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            x-model="formData.email"
                            required
                            :aria-invalid="formErrors.email ? 'true' : 'false'"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
                            placeholder="seu@email.com"
                        >
                        <p 
                            x-show="formErrors.email"
                            x-text="formErrors.email"
                            class="mt-2 text-sm text-foz-yellow"
                        ></p>
                    </div>
                    
                    <!-- WhatsApp -->
                    <div>
                        <label for="whatsapp-number" x-text="selectedPhoneCountry().code === 'BR' ? 'Número WhatsApp (DDD + Número) *' : 'Telefono WhatsApp *'" class="block text-sm font-semibold mb-2 text-foz-yellow">
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div 
                                class="relative w-46"
                                @click.outside="phoneDropdown.main = false"
                                @keydown.escape.window="phoneDropdown.main = false"
                            >
                                <button
                                    type="button"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-left text-foz-white focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50 flex items-center justify-between gap-3"
                                    @click="phoneDropdown.main = !phoneDropdown.main"
                                    :aria-expanded="phoneDropdown.main"
                                    aria-haspopup="listbox"
                                >
                                    <span class="flex items-center gap-2">
                                        <span class="text-lg" x-text="selectedPhoneCountry().flag" aria-hidden="true"></span>
                                        <span class="text-sm" x-text="selectedPhoneCountry().name"></span>
                                    </span>
                                    <span class="text-sm text-foz-white/60" x-text="selectedPhoneCountry().dialCode"></span>
                                </button>

                                <div
                                    x-show="phoneDropdown.main"
                                    x-transition
                                    class="absolute left-0 right-0 mt-2 bg-foz-black/95 border border-white/10 rounded-xl shadow-elevated max-h-64 w-60 overflow-y-auto z-30"
                                    role="listbox"
                                >
                                    <template x-for="country in phoneCountryOptions" :key="country.code">
                                        <button
                                            type="button"
                                            class="w-full px-4 py-2 flex items-center justify-between gap-3 text-sm hover:bg-white/10 focus:bg-white/10 text-foz-white"
                                            @click="formData.whatsappDialCode = country.dialCode; phoneDropdown.main = false"
                                            :aria-selected="formData.whatsappDialCode === country.dialCode"
                                            role="option"
                                        >
                                            <span class="flex items-center gap-2">
                                                <span class="text-lg" x-text="country.flag" aria-hidden="true"></span>
                                                <span x-text="country.name"></span>
                                            </span>
                                            <span class="text-foz-white/60" x-text="country.dialCode"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input 
                                    type="tel" 
                                    id="whatsapp-number" 
                                    x-model="formData.whatsapp"
                                    required
                                    inputmode="tel"
                                    :aria-invalid="formErrors.whatsapp ? 'true' : 'false'"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
                                    :placeholder="selectedPhoneCountry().code === 'BR' ? '(00) 00000-0000' : 'Telefono WhatsApp'"
                                    :maxlength="selectedPhoneCountry().code === 'BR' ? 11 : 14"
                                    x-mask="selectedPhoneCountry().code === 'BR' ? '(99) 99999-9999' : '99999999999999'"
                                >
                            </div>
                        </div>
                        <p 
                            x-show="formErrors.whatsapp"
                            x-text="formErrors.whatsapp"
                            class="mt-2 text-sm text-foz-yellow"
                        ></p>
                    </div>
                    
                    <!-- Checkbox 1: Indique e Ganhe -->
                    <div class="bg-foz-black/30 p-4 rounded-lg border border-foz-yellow/20">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="formData.participateInRanking"
                                class="mt-1 size-5 text-foz-yellow bg-foz-black border-white/15 rounded focus:ring-foz-yellow focus:ring-2"
                                checked
                            >
                            <span class="text-sm text-foz-white">
                                Aceito participar da dinâmica <strong class="text-foz-yellow">"Indique e Ganhe"</strong> e <strong class="text-foz-yellow">"Ranking de Prêmios"</strong> (recomendado - você ganha prêmios ao indicar amigos!)
                            </span>
                        </label>
                        
                        <!-- CPF Field (conditional) -->
                        <div x-show="formData.participateInRanking" x-transition class="mt-4">
                            <label for="cpf" class="block text-sm font-semibold mb-2 text-foz-yellow">
                                CPF (necessário para o ranking) *
                            </label>
                            <input 
                                type="text" 
                                id="cpf" 
                                x-model="formData.cpf"
                                :required="formData.participateInRanking"
                                x-mask="999.999.999-99"
                                :aria-invalid="formErrors.cpf ? 'true' : 'false'"
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
                                placeholder="000.000.000-00"
                                maxlength="14"
                            >
                            <p 
                                x-show="formErrors.cpf"
                                x-text="formErrors.cpf"
                                class="mt-2 text-sm text-foz-yellow"
                            ></p>
                        </div>
                    </div>
                    
                    <!-- Checkbox 2: Consent -->
                    <div class="bg-foz-black/30 p-4 rounded-lg border border-foz-orange/20">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="formData.acceptCommunication"
                                required
                                checked
                                class="mt-1 size-5 text-foz-yellow bg-foz-black border-white/15 rounded focus:ring-foz-yellow focus:ring-2"
                                
                            >
                            <span class="text-sm text-foz-white">
                                Concordo em receber comunicações do <strong class="text-foz-yellow">Foz Plaza Hotel</strong> sobre a campanha da Black Friday 2025, incluindo a revelação dos cupons, avisos de abertura de vendas e ofertas exclusivas. *
                            </span>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        :disabled="formStatus.isLoading"
                        :aria-busy="formStatus.isLoading"
                        class="w-full gradient-yellow text-foz-black px-8 py-5 text-xl md:text-2xl font-bold rounded-full shadow-glow hover:scale-105 transition-transform flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span x-text="formStatus.isLoading ? 'Enviando...' : 'CADASTRAR'"></span>
                        <svg x-show="!formStatus.isLoading" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                    
                    <p class="text-xs text-center text-foz-white/60">
                        Ao preencher o formulário, você concorda com nossa Política de Privacidade e Termos de Uso.
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- Covered Coupons Section -->
    <section class="py-16 md:py-24 px-4 bg-[#080f1c]">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="heading-display text-3xl md:text-5xl text-foz-white mb-4">
                    Os <span class="text-foz-white/60">cupons secretos</span>
                </h2>
                <p class="text-base md:text-lg text-foz-white/60 mb-2">
                    Três níveis de descontos liberados conforme o uso
                </p>
                <p class="text-sm text-foz-white/50">
                    🔓 Revelação coletiva em <strong class="text-foz-white">23 de novembro, 20h00</strong>
                </p>
            </div>
            
            <!-- Countdown for coupon revelation -->
            <div class="bg-white/5 border border-white/10 rounded-3xl shadow-elevated p-6 md:p-8 mb-12 max-w-3xl mx-auto">
                <p class="text-xs md:text-sm uppercase tracking-[0.35em] mb-4 text-foz-white/60 text-center">
                    Cupons serão revelados em
                </p>
                
                <div class="grid grid-cols-4 gap-2 md:gap-4">
                    <div class="bg-black/20 rounded-2xl p-3 md:p-4">
                        <div class="text-3xl md:text-5xl heading-display text-foz-white" x-text="couponCountdown.days">00</div>
                        <div class="text-xs md:text-sm uppercase text-foz-white/50">Dias</div>
                    </div>
                    <div class="bg-black/20 rounded-2xl p-3 md:p-4">
                        <div class="text-3xl md:text-5xl heading-display text-foz-white" x-text="couponCountdown.hours">00</div>
                        <div class="text-xs md:text-sm uppercase text-foz-white/50">Horas</div>
                    </div>
                    <div class="bg-black/20 rounded-2xl p-3 md:p-4">
                        <div class="text-3xl md:text-5xl heading-display text-foz-white" x-text="couponCountdown.minutes">00</div>
                        <div class="text-xs md:text-sm uppercase text-foz-white/50">Min</div>
                    </div>
                    <div class="bg-black/20 rounded-2xl p-3 md:p-4">
                        <div class="text-3xl md:text-5xl heading-display text-foz-white" x-text="couponCountdown.seconds">00</div>
                        <div class="text-xs md:text-sm uppercase text-foz-white/50">Seg</div>
                    </div>
                </div>
            </div>
            
            <!-- Covered Coupons -->
            <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                <!-- Coupon 1 -->
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10 hover:border-white/25 transition-colors min-h-[300px] flex flex-col items-center text-center">
                    <div class="text-5xl mb-4">🎫</div>
                    <div class="heading-display text-3xl text-foz-white mb-2">Cupom #1</div>
                    <p class="text-base font-medium text-foz-yellow mb-5">Código com 50% de desconto</p>
                    <div class="bg-black/30 border border-white/10 rounded-2xl px-4 py-6 space-y-2 w-full">
                        <div class="text-5xl">🔒</div>
                        <p class="text-sm text-foz-white/70">50 disponíveis</p>
                        <p class="text-xs text-foz-white/50">Revelação em 23/11 às 20h00</p>
                    </div>
                </div>
                
                <!-- Coupon 2 -->
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10 hover:border-white/25 transition-colors min-h-[300px] flex flex-col items-center text-center">
                    <div class="text-5xl mb-4">🎫</div>
                    <div class="heading-display text-3xl text-foz-white mb-2">Cupom #2</div>
                    <p class="text-base font-medium text-foz-yellow mb-5">Código com 40% de desconto</p>
                    <div class="bg-black/30 border border-white/10 rounded-2xl px-4 py-6 space-y-2 w-full">
                        <div class="text-5xl">🔒</div>
                        <p class="text-sm text-foz-white/70">100 disponíveis</p>
                        <p class="text-xs text-foz-white/50">Revelação em 23/11 às 20h00</p>
                    </div>
                </div>
                
                <!-- Coupon 3 -->
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10 hover:border-white/25 transition-colors min-h-[300px] flex flex-col items-center text-center">
                    <div class="text-5xl mb-4">🎫</div>
                    <div class="heading-display text-3xl text-foz-white mb-2">Cupom #3</div>
                    <p class="text-base font-medium text-foz-yellow mb-5">Código com 40% de desconto</p>
                    <div class="bg-black/30 border border-white/10 rounded-2xl px-4 py-6 space-y-2 w-full">
                        <div class="text-5xl">🔒</div>
                        <p class="text-sm text-foz-white/70">100 disponíveis</p>
                        <p class="text-xs text-foz-white/50">Revelação em 23/11 às 20h00</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <div class="inline-block bg-white/5 border border-white/10 rounded-2xl px-6 py-5">
                    <p class="text-foz-white/65">
                        💡 <strong class="text-foz-white">Dica:</strong> quanto antes você reservar, maior será o desconto.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ranking Section -->
    <section id="ranking" class="py-16 md:py-24 px-4 bg-linear-to-b bg-foz-black">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <img src="<?=THEME_PUBLIC?>selo-ranking.png" alt="Ranking de Prêmios" class="w-64 mx-auto mb-6 animate-float">
                <h2 class="heading-display text-4xl md:text-6xl mb-4">
                    <span class="text-gradient">RANKING DE PRÊMIOS</span>
                </h2>
                <p class="text-lg md:text-xl text-foz-white/80">
                    Dispute com outros participantes e leve prêmios incríveis!
                </p>
            </div>

            <div
                class="bg-white/5 border border-white/10 rounded-3xl shadow-elevated overflow-hidden mb-12"
                x-show="leaderboard.length || leaderboardStatus.error"
                x-cloak>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-5 border-b border-white/10">
                    <div>
                        <h3 class="heading-display text-2xl text-foz-white">Top 20 em tempo real</h3>
                        <p class="text-sm text-foz-white/60">O ranking exibe apenas participantes com 5 pontos ou mais. Atualização automática a cada 30 segundos.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs uppercase tracking-[0.35em] text-foz-yellow/80" x-show="leaderboardStatus.isLoading">Atualizando...</span>
                        <button
                            type="button"
                            @click="loadLeaderboard()"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/20 text-xs font-semibold uppercase tracking-[0.2em] text-foz-white/80 hover:text-foz-white hover:border-foz-yellow/50 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M5 19a9 9 0 0014-7V12M19 5a9 9 0 00-14 7v1"></path>
                            </svg>
                            Atualizar
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4" x-show="leaderboardStatus.error">
                    <p class="text-sm text-foz-orange" x-text="leaderboardStatus.error"></p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-black/40 text-left text-xs uppercase tracking-[0.3em] text-foz-white/60">
                            <tr>
                                <th scope="col" class="px-4 py-3 w-20">Posição</th>
                                <th scope="col" class="px-4 py-3">Participante</th>
                                <th scope="col" class="px-4 py-3 text-right">Pontos</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5" x-show="leaderboard.length">
                            <template x-for="entry in leaderboard" :key="entry.position">
                                <tr :class="leaderboardRowClass(entry.position)" class="transition-colors">
                                    <td class="px-4 py-4 align-middle">
                                        <span class="inline-flex items-center justify-center size-9 rounded-full font-semibold border" :class="leaderboardBadgeClass(entry.position)" x-text="entry.position"></span>
                                    </td>
                                    <td class="px-4 py-4 align-middle">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold" x-text="entry.name"></span>
                                            <span class="text-xs" :class="leaderboardPhoneClass(entry.position)" x-text="entry.phone"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 align-middle text-right">
                                        <span class="text-base font-bold" x-text="entry.points"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3 border-t border-white/10 text-xs text-foz-white/60 leading-relaxed">
                    <span class="font-semibold text-foz-yellow">Zona premiada:</span> os 10 primeiros colocados levam prêmios. Quanto mais pontos, mais perto da liderança!<br>
                    <span class="font-semibold text-foz-yellow">Pontuação:</span> 1 indicação válida = 1 ponto.<br>
                    <span class="font-semibold text-foz-yellow">Regra de desempate:</span> Em caso de empate em pontos, o critério de desempate será quem alcançou a pontuação primeiro.<br>
                    <span class="font-semibold text-foz-yellow">Regra de validade:</span> As premiações só são válidas   a partir de 5 pontos.
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Prize 1 -->
                <div class="bg-linear-to-br from-foz-yellow/30 to-foz-orange/30 p-6 rounded-xl border-2 border-foz-yellow relative">
                    <div class="absolute -top-4 -right-4 bg-linear-to-br from-foz-yellow to-foz-orange w-12 h-12 rounded-full flex items-center justify-center heading-display text-2xl text-foz-black shadow-lg">
                        1º
                    </div>
                    <div class="text-4xl mb-3">🏆</div>
                    <h3 class="heading-display text-xl text-foz-yellow mb-2">1º LUGAR</h3>
                    <p class="text-foz-white">
                        <strong>3 noites</strong> para <strong>2 pessoas</strong><br>
                        Categoria <strong>Premium</strong>
                    </p>
                </div>
                
                <!-- Prize 2 -->
                <div class="bg-linear-to-br from-foz-orange/30 to-foz-brown/30 p-6 rounded-xl border-2 border-foz-orange relative">
                    <div class="absolute -top-4 -right-4 bg-linear-to-br from-gray-300 to-gray-400 w-12 h-12 rounded-full flex items-center justify-center heading-display text-2xl text-foz-black shadow-lg">
                        2º
                    </div>
                    <div class="text-4xl mb-3">🥈</div>
                    <h3 class="heading-display text-xl text-foz-orange mb-2">2º LUGAR</h3>
                    <p class="text-foz-white">
                        <strong>2 noites</strong> para <strong>2 pessoas</strong><br>
                        Categoria <strong>Classic</strong>
                    </p>
                </div>
                
                <!-- Prize 3 -->
                <div class="bg-linear-to-br from-foz-brown/30 to-foz-dark-red/30 p-6 rounded-xl border-2 border-foz-brown relative">
                    <div class="absolute -top-4 -right-4 bg-linear-to-br from-amber-600 to-amber-700 w-12 h-12 rounded-full flex items-center justify-center heading-display text-2xl text-foz-white shadow-lg">
                        3º
                    </div>
                    <div class="text-4xl mb-3">🥉</div>
                    <h3 class="heading-display text-xl text-foz-yellow/30 mb-2">3º LUGAR</h3>
                    <p class="text-foz-white">
                        <strong>Jantar</strong> para <strong>2 pessoas</strong><br>
                        + <strong>1 drink</strong> por pessoa
                    </p>
                </div>
                
                <!-- Prize 4 -->
                <div class="bg-linear-to-br from-foz-brown/30 to-foz-dark-red/30 p-6 rounded-xl border border-white/10">
                    <div class="text-3xl mb-3">🍽️</div>
                    <h3 class="heading-display text-xl text-foz-white mb-2">4º LUGAR</h3>
                    <p class="text-foz-white/80">
                        <strong>Jantar</strong> para <strong>2 pessoas</strong><br>
                        + <strong>1 drink</strong> por pessoa
                    </p>
                </div>
                
                <!-- Prize 5 -->
                <div class="bg-linear-to-br from-foz-brown/30 to-foz-dark-red/30 p-6 rounded-xl border border-white/10">
                    <div class="text-3xl mb-3">🍫</div>
                    <h3 class="heading-display text-xl text-foz-white mb-2">5º LUGAR</h3>
                    <p class="text-foz-white/80">
                        <strong>Cesta de chocolates</strong><br>
                        Florybal Chocolateria
                    </p>
                </div>
                
                <!-- Prize 6 -->
                <div class="bg-linear-to-br from-foz-brown/30 to-foz-dark-red/30 p-6 rounded-xl border border-white/10">
                    <div class="text-3xl mb-3">💆</div>
                    <h3 class="heading-display text-xl text-foz-white mb-2">6º LUGAR</h3>
                    <p class="text-foz-white/80">
                        <strong>Massagem</strong> de <strong>50 minutos</strong><br>
                        no SPA
                    </p>
                </div>
                
                <!-- Prize 7 -->
                <div class="bg-linear-to-br from-foz-brown/30 to-foz-dark-red/30 p-6 rounded-xl border border-white/10">
                    <div class="text-3xl mb-3">🚌</div>
                    <h3 class="heading-display text-xl text-foz-white mb-2">7º LUGAR</h3>
                    <p class="text-foz-white/80">
                        <strong>Passeio City Tour</strong><br>
                        Brasil e Argentina
                    </p>
                </div>
                
                <!-- Prize 8-10 -->
                <div class="bg-linear-to-br from-foz-brown/30 to-foz-dark-red/30 p-6 rounded-xl border border-white/10 md:col-span-2">
                    <div class="text-3xl mb-3">✨</div>
                    <h3 class="heading-display text-xl text-foz-white mb-2">8º, 9º E 10º LUGAR</h3>
                    <p class="text-foz-white/80">
                        <strong>Kit "Contém Afeto"</strong><br>
                        Aromatizantes, sabonete líquido e creme<br>
                        <span class="text-sm">(Fragrância exclusiva do Foz Plaza Hotel)</span>
                    </p>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <div class="inline-block bg-foz-orange/10 border border-foz-orange/30 rounded-xl p-6">
                    <p class="text-foz-white/80 text-left text-xs md:text-sm">
                        ⚠️ <strong class="text-foz-orange">Importante:</strong><br><br> 
                        Todos os prêmios devem ser retirados <strong class="text-foz-yellow">presencialmente no hotel</strong>. O Foz Plaza Hotel não realiza envio de prêmios.
					    <br><br>As premiações no ranking são válidas a partir de 5 pontos!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Indique e Ganhe Section -->
    <section id="faq" class="py-16 md:py-24 px-4 bg-foz-black/90">
        <div class="container mx-auto max-w-6xl">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2">
                    <img src="<?=THEME_PUBLIC?>selo-indique-ganhe.png" alt="Indique e Ganhe" class="w-64 mx-auto animate-float">
                </div>
                
                <div class="lg:w-1/2">
                    <h2 class="heading-display text-4xl md:text-6xl mb-6">
                        <span class="text-gradient">INDIQUE E GANHE</span>
                    </h2>
                    
                    <div class="space-y-4 text-foz-white/80">
                        <p class="text-lg">
                            Compartilhe sua empolgação e <strong class="text-foz-yellow">ganhe prêmios incríveis</strong>!
                        </p>
                        
                        <div class="bg-linear-to-r from-foz-dark-red/50 to-foz-brown/50 p-6 rounded-xl border border-foz-orange/30">
                            <h3 class="heading-display text-2xl text-foz-yellow mb-4">COMO FUNCIONA:</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <span class="text-foz-yellow font-bold">1.</span>
                                    <span>Cadastre-se na campanha</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-foz-yellow font-bold">2.</span>
                                    <span>Receba seu <strong class="text-foz-yellow">link único</strong> de indicação</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-foz-yellow font-bold">3.</span>
                                    <span>Compartilhe com amigos e família</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-foz-yellow font-bold">4.</span>
                                    <span>Cada cadastro = <strong class="text-foz-yellow">1 ponto</strong></span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-foz-yellow font-bold">5.</span>
                                    <span>Acompanhe em tempo real sua pontuação e posição no ranking</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="bg-linear-to-r from-foz-yellow/20 to-foz-orange/20 p-6 rounded-xl border-2 border-foz-yellow">
                            <p class="text-lg font-bold text-foz-yellow mb-2">🎁 BÔNUS ESPECIAL:</p>
                            <p>
                                Indique <strong class="text-foz-yellow">10 ou mais amigos</strong> e ganhe um <strong class="text-foz-yellow">aromatizador automotivo</strong> exclusivo da linha "Contém Afeto" do Foz Plaza Hotel!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 md:py-24 px-4 bg-foz-black">
        <div class="container mx-auto max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="heading-display text-4xl md:text-6xl mb-4">
                    PERGUNTAS <span class="text-gradient">FREQUENTES</span>
                </h2>
                <p class="text-lg text-foz-white/80">
                    Tire suas dúvidas sobre a Black Friday 2025
                </p>
            </div>
            
            <div class="space-y-4" x-data="{ openFaq: 1 }">
                <!-- FAQ 1 -->
                <div class="bg-linear-to-br from-foz-dark-red/30 to-foz-brown/30 rounded-xl border border-foz-orange/30 overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 1 ? null : 1"
                        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-foz-white/5 transition-colors"
                    >
                        <span class="font-semibold text-foz-yellow">Quando acontece a Black Friday do Foz Plaza?</span>
                        <svg 
                            class="size-5 text-foz-yellow transition-transform" 
                            :class="{ 'rotate-180': openFaq === 1 }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-transition class="px-6 pb-4 text-foz-white/80">
                        <p>A janela de vendas acontece de <strong class="text-foz-yellow">24 a 30 de novembro de 2025</strong>. As reservas são válidas para hospedagens durante todo o ano de 2026 (até 20/12/2026).</p>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="bg-linear-to-br from-foz-dark-red/30 to-foz-brown/30 rounded-xl border border-foz-orange/30 overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 2 ? null : 2"
                        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-foz-white/5 transition-colors"
                    >
                        <span class="font-semibold text-foz-yellow">Como funcionam os cupons de desconto?</span>
                        <svg 
                            class="size-5 text-foz-yellow transition-transform" 
                            :class="{ 'rotate-180': openFaq === 2 }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-transition class="px-6 pb-4 text-foz-white/80">
                        <p>São 3 cupons progressivos:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li><strong class="text-foz-yellow">Cupom #1:</strong> 50% OFF para as primeiras 50 reservas</li>
                            <li><strong class="text-foz-yellow">Cupom #2:</strong> 40% OFF para as próximas 100 reservas</li>
                            <li><strong class="text-foz-yellow">Cupom #3:</strong> 30% OFF para as últimas 100 reservas</li>
                        </ul>
                        <p class="mt-2">Os cupons serão revelados em <strong class="text-foz-yellow">23 de novembro às 00h00</strong>, um dia antes do início das vendas.</p>
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="bg-linear-to-br from-foz-dark-red/30 to-foz-brown/30 rounded-xl border border-foz-orange/30 overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 3 ? null : 3"
                        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-foz-white/5 transition-colors"
                    >
                        <span class="font-semibold text-foz-yellow">O que está incluído na promoção?</span>
                        <svg 
                            class="size-5 text-foz-yellow transition-transform" 
                            :class="{ 'rotate-180': openFaq === 3 }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-transition class="px-6 pb-4 text-foz-white/80">
                        <p>A promoção inclui:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Hospedagem com desconto de até 50%</li>
                            <li>Café da manhã premium incluso</li>
                            <li>Transfer-in do aeroporto IGU para o hotel</li>
                            <li>1 criança até 7 anos grátis no mesmo quarto</li>
                            <li>Parcelamento em até 10x sem juros</li>
                            <li>Remarcação flexível (crédito)</li>
                        </ul>
                    </div>
                </div>
                
                <!-- FAQ 4 -->
                <div class="bg-linear-to-br from-foz-dark-red/30 to-foz-brown/30 rounded-xl border border-foz-orange/30 overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 4 ? null : 4"
                        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-foz-white/5 transition-colors"
                    >
                        <span class="font-semibold text-foz-yellow">Como funciona a dinâmica "Indique e Ganhe"?</span>
                        <svg 
                            class="size-5 text-foz-yellow transition-transform" 
                            :class="{ 'rotate-180': openFaq === 4 }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-transition class="px-6 pb-4 text-foz-white/80">
                        <p>Ao se cadastrar, você recebe um <strong class="text-foz-yellow">link único de indicação</strong>. Compartilhe com amigos e família. Cada pessoa que se cadastrar através do seu link = <strong class="text-foz-yellow">1 ponto</strong> para você. Acompanhe sua pontuação em tempo real e dispute o ranking de prêmios!</p>
                        <p class="mt-2"><strong class="text-foz-yellow">Bônus:</strong> Indique 10 ou mais amigos e ganhe um aromatizador automotivo exclusivo!</p>
                    </div>
                </div>
                
                <!-- FAQ 5 -->
                <div class="bg-linear-to-br from-foz-dark-red/30 to-foz-brown/30 rounded-xl border border-foz-orange/30 overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 5 ? null : 5"
                        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-foz-white/5 transition-colors"
                    >
                        <span class="font-semibold text-foz-yellow">Posso remarcar minha reserva?</span>
                        <svg 
                            class="size-5 text-foz-yellow transition-transform" 
                            :class="{ 'rotate-180': openFaq === 5 }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-transition class="px-6 pb-4 text-foz-white/80">
                        <p>Sim! A campanha oferece <strong class="text-foz-yellow">remarcação flexível</strong>. Você garante o desconto agora e, se precisar remarcar, pode usar o valor como <strong class="text-foz-yellow">crédito</strong> para futuras hospedagens. Consulte as condições específicas no momento da reserva.</p>
                    </div>
                </div>
                
                <!-- FAQ 6 -->
                <div class="bg-linear-to-br from-foz-dark-red/30 to-foz-brown/30 rounded-xl border border-foz-orange/30 overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 6 ? null : 6"
                        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-foz-white/5 transition-colors"
                    >
                        <span class="font-semibold text-foz-yellow">Como resgato os prêmios do ranking?</span>
                        <svg 
                            class="size-5 text-foz-yellow transition-transform" 
                            :class="{ 'rotate-180': openFaq === 6 }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 6" x-transition class="px-6 pb-4 text-foz-white/80">
                        <p>Todos os prêmios devem ser <strong class="text-foz-yellow">retirados presencialmente no hotel</strong>. Os vencedores serão comunicados após o encerramento da campanha (entre 03 e 05 de dezembro) com instruções de resgate.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section id="hotel" class="py-16 md:py-24 px-4 bg-linear-to-b from-foz-black via-foz-dark-red/30 to-foz-black">
        <div class="container mx-auto max-w-4xl text-center">
            <img src="<?=THEME_PUBLIC?>selo-vendas.png" alt="24 a 30 de Novembro" class="w-48 mx-auto mb-8 animate-float">
            
            <h2 class="heading-display text-4xl md:text-6xl mb-6">
                NÃO PERCA <span class="text-gradient">ESSA<br>OPORTUNIDADE!</span>
            </h2>
            
            <p class="text-xl md:text-2xl text-foz-white/80 mb-8">
                São apenas <strong class="text-foz-yellow">250 reservas</strong> disponíveis.<br>
                Cadastre-se agora e garanta seu lugar!
            </p>
            
            <button 
                @click="openRegistrationModal()"
                class="gradient-yellow text-foz-black px-8 md:px-12 py-4 md:py-6 text-xl md:text-2xl font-bold rounded-full shadow-glow hover:scale-105 transition-transform inline-flex items-center gap-3"
            >
                QUERO ME CADASTRAR AGORA
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <a href="#" class="hover:text-foz-yellow transition-colors">Termos e Condições</a>
                    <span>•</span>
                    <a href="#" class="hover:text-foz-yellow transition-colors">Política de Privacidade</a>
                    <span>•</span>
                    <a href="https://fozplaza.com.br" target="_blank" class="hover:text-foz-yellow transition-colors">fozplaza.com.br</a>
                </div>
            </div>
        </div>
    </footer>

    

    <!-- Success Modal (After Registration) -->
    <div 
        x-show="showSuccessModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-foz-black/95 backdrop-blur-sm"></div>
            
            <div class="relative bg-linear-to-br from-foz-yellow via-foz-orange to-foz-yellow rounded-2xl shadow-glow max-w-md w-full p-8 text-center">
                <div class="text-6xl mb-4 animate-bounce">🎉</div>
                <h2 class="heading-display text-lg md:text-3xl text-foz-black mb-4">
                    CADASTRO REALIZADO<br>COM SUCESSO!
                </h2>
                <p class="text-sm md:text-lg text-foz-black/80 mb-6">
                    Você agora faz parte da <strong>Black Friday 2025</strong> do Foz Plaza Hotel!
                </p>
                <p class="text-xs md:text-sm text-foz-black/80 mb-6">
                    ✅ Você receberá os cupons por e-mail<br>
                    ✅ Acompanhe o ranking em tempo real<br>
                    ✅ Compartilhe e ganhe prêmios
                </p>
                <button 
                    @click="closeSuccessModal()"
                    class="bg-foz-black text-foz-yellow px-8 py-3 font-bold rounded-full hover:scale-105 transition-transform"
                >
                    VER MINHA ÁREA
                </button>
            </div>
        </div>
    </div>

    <!-- IP Warning Modal -->
    <div
        x-show="ipWarning.isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-120 flex items-center justify-center px-4"
        @keydown.escape.window="cancelIpWarning()"
        style="display: none;"
    >
        <div class="fixed inset-0 bg-foz-black/90 backdrop-blur-sm"></div>
        <div
            class="relative max-w-lg w-full bg-linear-to-br from-foz-black via-foz-dark-red to-foz-black border border-white/10 rounded-3xl shadow-glow p-6 md:p-8 text-center space-y-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="ip-warning-title"
            aria-describedby="ip-warning-message"
        >
            <div class="text-4xl">⚠️</div>
            <h2 id="ip-warning-title" class="heading-display text-2xl md:text-3xl text-foz-white">Cadastro duplicado detectado</h2>
            <p id="ip-warning-message" class="text-sm md:text-base text-foz-white/70" x-text="ipWarning.message || 'Detectamos outro cadastro realizado com este mesmo IP. Deseja continuar mesmo assim?'">
            </p>
            <div class="grid md:grid-cols-2 gap-3 pt-2">
                <button
                    type="button"
                    @click="cancelIpWarning()"
                    class="w-full px-4 py-3 text-sm md:text-base font-semibold rounded-full border border-white/20 text-foz-white hover:bg-white/10 transition-colors"
                >
                    Revisar dados
                </button>
                <button
                    type="button"
                    @click="confirmIpWarning()"
                    class="w-full px-4 py-3 text-sm md:text-base font-semibold rounded-full gradient-yellow text-foz-black hover:scale-105 transition-transform"
                >
                    Continuar cadastro
                </button>
            </div>
        </div>
    </div>

    <!-- Referral Toast -->
    <div
        x-show="toast.isVisible"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-4 right-4 z-50 w-[calc(100%-2rem)] max-w-sm"
        style="z-index: 130; display: none;"
        role="status"
        aria-live="polite"
    >
        <div class="bg-linear-to-br from-foz-yellow via-foz-orange to-foz-yellow text-foz-black rounded-2xl shadow-glow p-4 md:p-5 border border-foz-orange/40">
            <div class="flex items-start gap-3">
                <div class="text-2xl md:text-3xl" aria-hidden="true">🎉</div>
                <p class="text-sm md:text-base leading-relaxed font-medium" x-text="toast.message"></p>
                <button
                    type="button"
                    class="text-foz-black/60 hover:text-foz-black transition-colors"
                    @click="hideToast()"
                    aria-label="Fechar aviso de indicação"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Alpine.js Application Logic -->
    <script>
        function blackFridayApp() {
            return {
                countdown: {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                },

                couponCountdown: {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                },

                phoneCountryOptions: [
                    { code: 'BR', name: 'Brasil', dialCode: '+55', flag: '🇧🇷' },
                    { code: 'AR', name: 'Argentina', dialCode: '+54', flag: '🇦🇷' },
                    { code: 'BO', name: 'Bolívia', dialCode: '+591', flag: '🇧🇴' },
                    { code: 'CL', name: 'Chile', dialCode: '+56', flag: '🇨🇱' },
                    { code: 'CO', name: 'Colômbia', dialCode: '+57', flag: '🇨🇴' },
                    { code: 'CR', name: 'Costa Rica', dialCode: '+506', flag: '🇨🇷' },
                    { code: 'CU', name: 'Cuba', dialCode: '+53', flag: '🇨🇺' },
                    { code: 'EC', name: 'Equador', dialCode: '+593', flag: '🇪🇨' },
                    { code: 'SV', name: 'El Salvador', dialCode: '+503', flag: '🇸🇻' },
                    { code: 'GT', name: 'Guatemala', dialCode: '+502', flag: '🇬🇹' },
                    { code: 'HN', name: 'Honduras', dialCode: '+504', flag: '🇭🇳' },
                    { code: 'MX', name: 'México', dialCode: '+52', flag: '🇲🇽' },
                    { code: 'NI', name: 'Nicarágua', dialCode: '+505', flag: '🇳🇮' },
                    { code: 'PA', name: 'Panamá', dialCode: '+507', flag: '🇵🇦' },
                    { code: 'PY', name: 'Paraguai', dialCode: '+595', flag: '🇵🇾' },
                    { code: 'PE', name: 'Peru', dialCode: '+51', flag: '🇵🇪' },
                    { code: 'UY', name: 'Uruguai', dialCode: '+598', flag: '🇺🇾' },
                    { code: 'VE', name: 'Venezuela', dialCode: '+58', flag: '🇻🇪' }
                ],

                formData: {
                    name: '',
                    email: '',
                    whatsapp: '',
                    whatsappDialCode: '+55',
                    cpf: '',
                    participateInRanking: true,
                    acceptCommunication: true
                },

                formErrors: {
                    general: '',
                    name: '',
                    email: '',
                    whatsapp: '',
                    cpf: ''
                },

                formStatus: {
                    isLoading: false,
                    isSuccess: false
                },

                ipWarning: {
                    isOpen: false,
                    message: ''
                },

                phoneDropdown: {
                    main: false,
                    modal: false
                },

                userData: {
                    name: '',
                    email: '',
                    whatsapp: '',
                    whatsappDialCode: '+55',
                    whatsappNumber: '',
                    refId: '',
                    referralLink: '',
                    stats: {
                        referrals: 0,
                        points: 0,
                        ranking: null
                    }
                },

                isLogged: false,
                isRegistered: false,
                showRegistrationModal: false,
                showSuccessModal: false,
                navIsOpaque: false,

                toast: {
                    isVisible: false,
                    message: ''
                },

                toastTimer: null,

                leaderboard: [],
                leaderboardStatus: {
                    isLoading: false,
                    error: ''
                },
                leaderboardTimer: null,

                selectedPhoneCountry() {
                    return this.phoneCountryOptions.find(country => country.dialCode === this.formData.whatsappDialCode) || this.phoneCountryOptions[0];
                },

                init() {
                    this.checkRegistration();
                    this.startCountdowns();
                    this.checkReferralCode();
                    this.loadLeaderboard();

                    if (! this.leaderboardTimer) {
                        this.leaderboardTimer = setInterval(() => this.loadLeaderboard(true), 30000);
                        window.addEventListener('beforeunload', () => {
                            if (this.leaderboardTimer) {
                                clearInterval(this.leaderboardTimer);
                                this.leaderboardTimer = null;
                            }
                        }, { once: true });
                    }

                    this.handleScroll();
                    window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
                },

                checkRegistration() {
                    const config = window.blackFridayLead || {};
                    this.isLogged = Boolean(config.isLoggedIn);

                    if (this.isLogged && config.user) {
                        this.isRegistered = true;
                        this.userData.name = config.user.name || this.userData.name;
                        this.userData.email = config.user.email || this.userData.email;
                        this.userData.refId = config.user.refId || this.userData.refId;
                        if (this.userData.refId) {
                            this.updateReferralLink();
                        }
                        return;
                    }

                    const storedUser = localStorage.getItem('blackfriday_user');
                    if (!storedUser) {
                        return;
                    }

                    try {
                        const parsed = JSON.parse(storedUser);
                        // this.isRegistered = true;
                        Object.assign(this.userData, parsed);
                        if (this.userData.refId) {
                            this.updateReferralLink();
                        }
                    } catch (error) {
                        console.error('lead storage parse error', error);
                        try {
                            localStorage.removeItem('blackfriday_user');
                        } catch (storageError) {
                            console.error('lead storage cleanup error', storageError);
                        }
                    }
                },

                startCountdowns() {
                    const salesDate = new Date('2025-11-24T00:00:01-03:00');
                    const couponDate = new Date('2025-11-23T20:00:00-03:00');

                    const updateCountdowns = () => {
                        const now = new Date();

                        let diff = salesDate - now;
                        if (diff > 0) {
                            this.countdown.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            this.countdown.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            this.countdown.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            this.countdown.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        }

                        diff = couponDate - now;
                        if (diff > 0) {
                            this.couponCountdown.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            this.couponCountdown.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            this.couponCountdown.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            this.couponCountdown.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        }
                    };

                    updateCountdowns();
                    setInterval(updateCountdowns, 1000);
                },

                checkReferralCode() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const ref = urlParams.get('ref');
                    if (!ref) {
                        return;
                    }

                    const sanitizedRef = ref.trim().toLowerCase().replace(/[^a-z0-9]/g, '');
                    if (sanitizedRef === '') {
                        return;
                    }

                    let shouldRegisterVisit = true;
                    const visitKey = `ref_visit_${sanitizedRef}`;

                    try {
                        shouldRegisterVisit = !sessionStorage.getItem(visitKey);
                    } catch (storageError) {
                        console.error('ref visit session read error', storageError);
                    }

                    this.validateReferralCode(sanitizedRef, shouldRegisterVisit);
                },

                async submitForm(forceIp = false) {
                    if (this.formStatus.isLoading) {
                        return;
                    }

                    this.ipWarning.isOpen = false;
                    this.ipWarning.message = '';

                    this.clearErrors();

                    if (!this.validateForm()) {
                        return;
                    }

                    const ajaxUrl = window.blackFridayLead?.ajaxUrl;
                    if (!ajaxUrl) {
                        this.formErrors.general = 'Serviço temporariamente indisponível. Atualize a página e tente novamente.';
                        return;
                    }

                    await this.sendLeadRequest(ajaxUrl, forceIp);
                },

                async sendLeadRequest(ajaxUrl, forceIp) {
                    const payload = this.buildRequestPayload(forceIp);
                    this.formStatus.isLoading = true;

                    try {
                        const response = await fetch(ajaxUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            body: payload
                        });

                        let result;
                        try {
                            result = await response.json();
                        } catch (parseError) {
                            console.error('lead submit parse error', parseError);
                            this.formErrors.general = 'Não foi possível interpretar a resposta do servidor. Tente novamente em instantes.';
                            return;
                        }

                        if (!response.ok) {
                            this.handleServerError(result.data || {});
                            return;
                        }

                        if (!result.success) {
                            const data = result.data || {};

                            if (data.status === 'ip_warning') {
                                if (forceIp) {
                                    this.handleServerError({
                                        field: 'general',
                                        message: data.message || 'Não foi possível concluir o cadastro com este IP. Tente novamente em instantes.'
                                    });
                                } else {
                                    this.showIpWarning(data.message);
                                }
                                return;
                            }

                            this.handleServerError(data);
                            return;
                        }

                        this.handleSuccess(result.data);
                    } catch (error) {
                        console.error('lead submit error', error);
                        this.formErrors.general = 'Não foi possível concluir o cadastro. Verifique sua conexão e tente novamente.';
                    } finally {
                        this.formStatus.isLoading = false;
                    }
                },

                buildRequestPayload(forceIp) {
                    const payload = new FormData();
                    payload.append('action', 'register_lead');

                    if (window.blackFridayLead?.nonce) {
                        payload.append('nonce', window.blackFridayLead.nonce);
                    }

                    const sanitizedDialCode = this.formData.whatsappDialCode.replace(/[^0-9]/g, '');
                    const sanitizedPhone = this.formData.whatsapp.replace(/[^0-9]/g, '');
                    const sanitizedCpf = this.formData.cpf.replace(/[^0-9]/g, '');

                    payload.append('name', this.formData.name.trim());
                    payload.append('email', this.formData.email.trim());
                    payload.append('whatsapp_dial_code', sanitizedDialCode);
                    payload.append('whatsapp_number', sanitizedPhone);
                    payload.append('participate_in_ranking', this.formData.participateInRanking ? '1' : '');
                    payload.append('accept_communication', this.formData.acceptCommunication ? '1' : '');
                    payload.append('cpf', sanitizedCpf);
                    payload.append('origin_url', window.location.href);

                    let referralCode = null;
                    try {
                        referralCode = localStorage.getItem('referral_code');
                    } catch (storageError) {
                        console.error('lead storage read error', storageError);
                    }

                    if (referralCode) {
                        payload.append('referral_code', referralCode);
                    }

                    if (forceIp) {
                        payload.append('force_ip', '1');
                    }

                    return payload;
                },

                async validateReferralCode(refId, shouldRegisterVisit) {
                    const ajaxUrl = window.blackFridayLead?.ajaxUrl;

                    if (!ajaxUrl) {
                        return;
                    }

                    const payload = new FormData();
                    payload.append('action', 'validate_referral');

                    if (window.blackFridayLead?.nonce) {
                        payload.append('nonce', window.blackFridayLead.nonce);
                    }

                    payload.append('ref_id', refId);

                    if (shouldRegisterVisit) {
                        payload.append('register_visit', '1');
                    }

                    try {
                        const response = await fetch(ajaxUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            body: payload
                        });

                        let result = null;

                        try {
                            result = await response.json();
                        } catch (parseError) {
                            console.error('referral validate parse error', parseError);
                            this.clearStoredReferral(refId);
                            return;
                        }

                        if (!response.ok || !result?.success) {
                            this.clearStoredReferral(refId);
                            return;
                        }

                        const data = result.data || {};
                        const canonicalRef = (data.refId || refId || '').toLowerCase();

                        if (canonicalRef === '') {
                            this.clearStoredReferral(refId);
                            return;
                        }

                        this.storeReferralCode(canonicalRef);

                        try {
                            sessionStorage.setItem(`ref_visit_${canonicalRef}`, '1');
                            if (canonicalRef !== refId) {
                                sessionStorage.setItem(`ref_visit_${refId}`, '1');
                            }
                        } catch (storageError) {
                            console.error('ref visit session write error', storageError);
                        }

                        const inviterName = data.firstName || data.name || canonicalRef;
                        this.showReferralToast(inviterName);
                    } catch (error) {
                        console.error('referral validate request error', error);
                    }
                },

                storeReferralCode(refId) {
                    const value = (refId || '').toString().trim();

                    if (value === '') {
                        return;
                    }

                    try {
                        localStorage.setItem('referral_code', value);
                    } catch (storageError) {
                        console.error('referral storage write error', storageError);
                    }
                },

                clearStoredReferral(refId) {
                    const value = (refId || '').toString().trim();

                    try {
                        const stored = localStorage.getItem('referral_code');
                        if (!value || stored === value) {
                            localStorage.removeItem('referral_code');
                        }
                    } catch (storageError) {
                        console.error('referral storage remove error', storageError);
                    }
                },

                async loadLeaderboard(silent = false) {
                    const ajaxUrl = window.blackFridayLead?.ajaxUrl;

                    if (!ajaxUrl) {
                        return;
                    }

                    if (!silent) {
                        this.leaderboardStatus.isLoading = true;
                        this.leaderboardStatus.error = '';
                    }

                    const payload = new FormData();
                    payload.append('action', 'get_referral_leaderboard');

                    if (window.blackFridayLead?.nonce) {
                        payload.append('nonce', window.blackFridayLead.nonce);
                    }

                    try {
                        const response = await fetch(ajaxUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            body: payload,
                        });

                        let result = null;

                        try {
                            result = await response.json();
                        } catch (parseError) {
                            console.error('leaderboard parse error', parseError);
                        }

                        if (!response.ok || !result?.success) {
                            if (!silent) {
                                this.leaderboardStatus.error = 'Não foi possível carregar o ranking. Atualize a página e tente novamente.';
                            }
                            return;
                        }

                        const items = Array.isArray(result.data?.items) ? result.data.items : [];
                        this.leaderboard = items.filter(item => parseInt(item.points) >= 5);

                        this.leaderboardStatus.error = '';
                    } catch (error) {
                        console.error('leaderboard request error', error);
                        if (!silent) {
                            this.leaderboardStatus.error = 'Não foi possível carregar o ranking. Verifique sua conexão e tente novamente.';
                        }
                    } finally {
                        if (!silent) {
                            this.leaderboardStatus.isLoading = false;
                        }
                    }
                },

                leaderboardRowClass(position) {
                    if (position === 1) {
                        return 'bg-linear-to-r from-foz-yellow to-foz-orange text-foz-black border border-foz-yellow/50';
                    }

                    if (position === 2 || position === 3) {
                        return 'bg-linear-to-r from-foz-orange/40 to-foz-brown/40 text-foz-white border border-foz-orange/40';
                    }

                    if (position <= 10) {
                        return 'bg-foz-yellow/10 text-foz-white border border-foz-yellow/30';
                    }

                    return 'bg-white/5 text-foz-white border border-white/5';
                },

                leaderboardBadgeClass(position) {
                    if (position === 1) {
                        return 'bg-foz-black/10 text-foz-black border border-foz-black/20';
                    }

                    if (position === 2 || position === 3) {
                        return 'bg-foz-black/30 text-foz-white border border-white/10';
                    }

                    if (position <= 10) {
                        return 'bg-foz-yellow/20 text-foz-yellow border border-foz-yellow/40';
                    }

                    return 'bg-black/30 text-foz-white border border-white/10';
                },

                leaderboardPhoneClass(position) {
                    if (position === 1) {
                        return 'text-foz-black/70';
                    }

                    if (position <= 10) {
                        return 'text-foz-white/80';
                    }

                    return 'text-foz-white/60';
                },

                handleServerError(data) {
                    const field = data.field || 'general';
                    const message = data.message || 'Não foi possível concluir o cadastro. Tente novamente em instantes.';

                    if (Object.prototype.hasOwnProperty.call(this.formErrors, field)) {
                        this.formErrors[field] = message;
                    } else {
                        this.formErrors.general = message;
                    }

                    if (field !== 'general' && !this.formErrors.general) {
                        this.formErrors.general = 'Corrija os campos destacados para continuar.';
                    }
                },

                showIpWarning(message) {
                    this.ipWarning.message = message || 'Detectamos outro cadastro realizado com este mesmo IP. Deseja continuar mesmo assim?';
                    this.ipWarning.isOpen = true;
                    this.formErrors.general = '';
                },

                confirmIpWarning() {
                    this.ipWarning.isOpen = false;
                    this.ipWarning.message = '';
                    this.submitForm(true);
                },

                cancelIpWarning() {
                    const warningMessage = this.ipWarning.message || 'Cadastro não enviado. Revise os dados antes de continuar.';
                    this.ipWarning.isOpen = false;
                    this.ipWarning.message = '';
                    this.formErrors.general = warningMessage;
                },

                handleSuccess(data) {
                    const user = data?.user || {};

                    this.isRegistered = true;
                    this.showSuccessModal = true;
                    this.showRegistrationModal = false;
                    this.formStatus.isSuccess = true;

                    this.userData.name = user.name || this.formData.name;
                    this.userData.email = user.email || this.formData.email;
                    this.userData.whatsappDialCode = this.formData.whatsappDialCode;
                    this.userData.whatsappNumber = this.formData.whatsapp;
                    this.userData.whatsapp = `${this.formData.whatsappDialCode} ${this.formData.whatsapp}`.trim();
                    this.userData.refId = user.ref_id || this.userData.refId;
                    this.updateReferralLink();

                    
                    const userDataTrack = {
                        em: this.userData.email,
                        ph: this.userData.whatsapp.replace(/[^0-9]/g, ''),
                        fn: this.userData.name.split(' ')[0] || '',
                        ln: this.userData.name.split(' ').slice(1).join(' ') || ''
                    }
                    // console.log('Tracking lead with data:', userDataTrack);
                    INKTRACK.track('Lead', {}, userDataTrack);

                    try {
                        localStorage.setItem('blackfriday_user', JSON.stringify({
                            name: this.userData.name,
                            email: this.userData.email,
                            whatsappDialCode: this.userData.whatsappDialCode,
                            whatsappNumber: this.userData.whatsappNumber,
                            whatsapp: this.userData.whatsapp,
                            refId: this.userData.refId,
                            referralLink: this.userData.referralLink
                        }));
                    } catch (storageError) {
                        console.error('lead storage write error', storageError);
                    }

                    try {
                        localStorage.removeItem('referral_code');
                    } catch (storageError) {
                        console.error('lead storage remove error', storageError);
                    }

                    this.resetForm();
                },

                resetForm() {
                    this.formData = {
                        name: '',
                        email: '',
                        whatsapp: '',
                        whatsappDialCode: '+55',
                        cpf: '',
                        participateInRanking: true,
                        acceptCommunication: true
                    };

                    this.phoneDropdown.main = false;
                    this.phoneDropdown.modal = false;
                },

                clearErrors() {
                    this.formErrors.general = '';
                    this.formErrors.name = '';
                    this.formErrors.email = '';
                    this.formErrors.whatsapp = '';
                    this.formErrors.cpf = '';
                },

                validateForm() {
                    let hasErrors = false;

                    if (this.formData.name.trim().length < 3) {
                        this.formErrors.name = 'Informe seu nome completo.';
                        hasErrors = true;
                    }

                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(this.formData.email.trim())) {
                        this.formErrors.email = 'Digite um e-mail válido.';
                        hasErrors = true;
                    }

                    const phoneDigits = this.formData.whatsapp.replace(/[^0-9]/g, '');
                    if (phoneDigits.length < 8) {
                        this.formErrors.whatsapp = 'Informe um número de WhatsApp válido.';
                        hasErrors = true;
                    }

                    const cpfDigits = this.formData.cpf.replace(/[^0-9]/g, '');
                    if (this.formData.participateInRanking) {
                        if (!this.validateCPF(cpfDigits)) {
                            this.formErrors.cpf = 'Informe um CPF válido.';
                            hasErrors = true;
                        }
                    } else if (cpfDigits && !this.validateCPF(cpfDigits)) {
                        this.formErrors.cpf = 'Informe um CPF válido.';
                        hasErrors = true;
                    }

                    if (!this.formData.acceptCommunication) {
                        this.formErrors.general = 'É necessário aceitar as comunicações para participar.';
                        hasErrors = true;
                    }

                    if (hasErrors && !this.formErrors.general) {
                        this.formErrors.general = 'Corrija os campos destacados para continuar.';
                    }

                    return !hasErrors;
                },

                validateCPF(cpf) {
                    if (!cpf || cpf.length !== 11) {
                        return false;
                    }

                    if (/^(\d)\1{10}$/.test(cpf)) {
                        return false;
                    }

                    let sum = 0;
                    for (let i = 0; i < 9; i++) {
                        sum += parseInt(cpf.charAt(i), 10) * (10 - i);
                    }
                    let remainder = sum % 11;
                    const digit1 = remainder < 2 ? 0 : 11 - remainder;
                    if (digit1 !== parseInt(cpf.charAt(9), 10)) {
                        return false;
                    }

                    sum = 0;
                    for (let i = 0; i < 10; i++) {
                        sum += parseInt(cpf.charAt(i), 10) * (11 - i);
                    }
                    remainder = sum % 11;
                    const digit2 = remainder < 2 ? 0 : 11 - remainder;
                    return digit2 === parseInt(cpf.charAt(10), 10);
                },

                showReferralToast(inviterName) {
                    const safeName = (inviterName || '').toString().trim();
                    const firstName = safeName ? safeName.split(/\s+/)[0] : 'Um amigo';
                    this.showToast(`Uhuu! ${firstName} convidou você para a Black Foz Plaza, vamos participar juntos da maior promoção da história! 😁`);
                },

                showToast(message) {
                    this.toast.message = message;
                    this.toast.isVisible = true;

                    if (this.toastTimer) {
                        clearTimeout(this.toastTimer);
                    }

                    this.toastTimer = setTimeout(() => {
                        this.toast.isVisible = false;
                        this.toastTimer = null;
                    }, 6000);
                },

                hideToast() {
                    if (this.toastTimer) {
                        clearTimeout(this.toastTimer);
                        this.toastTimer = null;
                    }

                    this.toast.isVisible = false;
                },

                updateReferralLink() {
                    if (!this.userData.refId) {
                        this.userData.referralLink = '';
                        return;
                    }

                    const origin = window.location.origin || '';
                    const path = window.location.pathname || '';
                    this.userData.referralLink = `${origin}${path}?ref=${this.userData.refId}`;
                },

                closeSuccessModal() {
                    const redirectTarget = window.blackFridayLead?.successRedirect || '/minha-area';
                    this.showSuccessModal = false;
                    window.location.href = redirectTarget;
                },

                handleScroll() {
                    this.navIsOpaque = window.scrollY >= 60;
                },

                openRegistrationModal() {
                    console.log(this.isRegistered, this.isLogged);


                    if (this.isRegistered || this.isLogged) {
                        const redirectTarget = window.blackFridayLead?.successRedirect || '/minha-area';
                        window.location.href = redirectTarget;
                        return;
                    }
                    this.showRegistrationModal = true;
                },

                closeRegistrationModal() {
                    this.showRegistrationModal = false;
                    this.phoneDropdown.modal = false;
                },

                scrollToHowItWorks() {
                    const target = document.getElementById('faq');
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            }
        }
    </script>
    
    <!-- Simple input mask for phone and CPF -->
    <script>
        // Simple Alpine.js mask directive
        document.addEventListener('alpine:init', () => {
            Alpine.directive('mask', (el, { expression }) => {
                el.addEventListener('input', (e) => {
                    let value = e.target.value.replace(/\D/g, '');
                    
                    if (expression === '(99) 99999-9999') {
                        if (value.length <= 11) {
                            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                        }
                    } else if (expression === '999.999.999-99') {
                        if (value.length <= 11) {
                            value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
                        }
                    }
                    
                    e.target.value = value;
                });
            });
        });
    </script>
</div>
<?php get_footer(); ?>