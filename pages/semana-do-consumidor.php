<?php
/*
 Template Name: Semana do Consumidor 2026
*/

get_header();
?>

<!-- Flatpickr (date picker) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<style>
    /* Light theme override for this page */
    body {
        background: #f5f5f0 !important;
        color: #1a1a1a !important;
    }

    /* Flatpickr brand customization */
    .flatpickr-calendar {
        border-radius: 12px !important;
        box-shadow: 0 12px 40px rgba(0,0,0,0.15) !important;
        border: 1px solid #e5e5e5 !important;
        font-family: "Inter", sans-serif !important;
    }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected:hover {
        background: #00896b !important;
        border-color: #00896b !important;
        color: #fff !important;
    }
    .flatpickr-day.today {
        border-color: #00896b !important;
    }
    .flatpickr-day:hover {
        background: #e6f9f3 !important;
        border-color: #00896b !important;
    }
    .flatpickr-months .flatpickr-month,
    .flatpickr-weekdays,
    span.flatpickr-weekday {
        background: #00896b !important;
        color: #fff !important;
        font-weight: 600 !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        background: #00896b !important;
        color: #fff !important;
    }
    .flatpickr-current-month input.cur-year {
        color: #fff !important;
    }
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        fill: #fff !important;
        color: #fff !important;
    }
    .flatpickr-months .flatpickr-prev-month:hover svg,
    .flatpickr-months .flatpickr-next-month:hover svg {
        fill: #e6f9f3 !important;
    }
    .numInputWrapper:hover {
        background: rgba(255,255,255,0.15) !important;
    }

    /* Smooth counter transitions */
    .counter-btn {
        transition: all 0.15s ease;
    }
    .counter-btn:active:not(:disabled) {
        transform: scale(0.9);
    }
</style>

<main x-data="bookingApp()" class="relative">

    <!-- ===================== HERO ===================== -->
    <section class="relative h-[70vh] min-h-130 flex items-start justify-center overflow-hidden pt-8">
        <!-- Video Background -->
        <video
            class="absolute inset-0 h-full w-full object-cover"
            autoplay muted loop playsinline preload="auto" aria-hidden="true"
        >
            <source src="<?=THEME_PUBLIC?>foz-plaza_institucional.webm" type="video/webm">
            <source src="<?=THEME_PUBLIC?>foz-plaza_institucional.mp4" type="video/mp4">
        </video>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-linear-to-b from-black/50 via-black/30 to-black/70"></div>

        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
            <!-- Hotel Logo -->
            <img
                src="<?=THEME_PUBLIC?>logo_foz-plaza_white.png"
                alt="<?php bloginfo('name'); ?>"
                class="mx-auto w-40 md:w-48 mb-8 opacity-90"
            >

            <!-- Campaign Badge -->
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 mb-5">
                <span class="size-2 rounded-full bg-foz-yellow animate-pulse"></span>
                <span class="text-white/90 text-xs md:text-sm font-medium tracking-wide uppercase">Top 10% melhores hotéis do mundo</span>
            </div>

            <div class="mt-14">
                <img src="<?=THEME_PUBLIC?>logo-semana-consumidor-2026_2.png" alt="<?php bloginfo('name'); ?>" class="mx-auto mb-2 w-76 animate-float">
            </div>

            <!-- Main Headline -->
            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-3 tracking-tight leading-none">
                <span class="text-foz-yellow drop-shadow-lg">20% OFF</span>
            </h1>
            <p class="text-lg md:text-2xl text-white/90 font-light">
                em todas as reservas
            </p>

            <!-- Dates -->
            <div class="inline-flex items-center gap-2.5 bg-[#9e6431] text-white font-bold text-sm md:text-base px-6 py-2.5 rounded-full mt-5 shadow-lg">
                <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                10 a 17 de Março
            </div>
        </div>
    </section>
    <!-- ===================== /HERO ===================== -->


    <!-- ===================== BOOKING FORM ===================== -->
    <section class="relative z-20 -mt-12 px-4 pb-16 md:pb-24">
        <div class="bg-white rounded-2xl shadow-[0_12px_60px_rgba(0,0,0,0.15)] max-w-4xl mx-auto p-6 md:p-10 border border-gray-200">

            <!-- Form Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Reserve agora com <span class="text-foz-green">20% de desconto</span></h2>
                <p class="text-gray-500 text-sm">Escolha suas datas e configure seus quartos</p>
            </div>

            <!-- Dates Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Check-in -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Check-in</label>
                    <div class="relative">
                        <input
                            x-ref="checkInInput"
                            type="text"
                            readonly
                            placeholder="Selecione a data"
                            class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 cursor-pointer focus:outline-none focus:ring-2 focus:ring-foz-green/40 focus:border-foz-green transition"
                        >
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-foz-green pointer-events-none" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                    </div>
                </div>

                <!-- Check-out -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Check-out</label>
                    <div class="relative">
                        <input
                            x-ref="checkOutInput"
                            type="text"
                            readonly
                            placeholder="Selecione a data"
                            class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 cursor-pointer focus:outline-none focus:ring-2 focus:ring-foz-green/40 focus:border-foz-green transition"
                        >
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-foz-green pointer-events-none" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Nights indicator -->
            <template x-if="checkIn && checkOut && nights > 0">
                <div class="flex items-center justify-center gap-2 mb-6">
                    <span class="inline-flex items-center gap-1.5 bg-foz-green-light text-foz-green text-sm font-semibold px-4 py-1.5 rounded-full">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                        <span x-text="nights"></span> <span x-text="nights === 1 ? 'noite' : 'noites'"></span>
                    </span>
                </div>
            </template>

            <!-- Divider -->
            <div class="border-t border-gray-100 my-6"></div>

            <!-- Rooms Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="size-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5M10.5 21H3m0 0V3.545M3 3.545h18M3 3.545L6 21m12-17.455L15 21"/>
                        </svg>
                        Quartos
                    </h3>
                    <button
                        @click="addRoom()"
                        type="button"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-foz-green border border-foz-green/30 bg-foz-green-light hover:bg-foz-green/10 px-4 py-2 rounded-lg transition-colors"
                    >
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Adicionar quarto
                    </button>
                </div>

                <!-- Room Cards -->
                <template x-for="(room, ri) in rooms" :key="ri">
                    <div
                        class="bg-gray-50 rounded-xl p-5 mb-3 border transition-all"
                        :class="totalGuests(ri) > 5 ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-100'"
                    >
                        <!-- Room Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-700 text-sm flex items-center gap-2">
                                <span class="inline-flex items-center justify-center size-6 rounded-full bg-foz-green text-white text-xs font-bold" x-text="ri + 1"></span>
                                Quarto <span x-text="ri + 1"></span>
                            </h4>
                            <button
                                x-show="rooms.length > 1"
                                @click="removeRoom(ri)"
                                type="button"
                                class="text-gray-300 hover:text-red-500 transition-colors p-1"
                                title="Remover quarto"
                            >
                                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Adults -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-2.5 uppercase tracking-wider">Adultos</label>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="decrementAdults(ri)"
                                        :disabled="room.adults <= 1"
                                        type="button"
                                        class="counter-btn size-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:border-gray-300 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="text-xl font-extrabold text-gray-900 w-8 text-center tabular-nums" x-text="room.adults"></span>
                                    <button
                                        @click="incrementAdults(ri)"
                                        :disabled="room.adults >= maxAdults(ri)"
                                        type="button"
                                        class="counter-btn size-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:border-gray-300 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Children -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-2.5 uppercase tracking-wider">Crianças</label>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="decrementChildren(ri)"
                                        :disabled="room.children <= 0"
                                        type="button"
                                        class="counter-btn size-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:border-gray-300 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="text-xl font-extrabold text-gray-900 w-8 text-center tabular-nums" x-text="room.children"></span>
                                    <button
                                        @click="incrementChildren(ri)"
                                        :disabled="room.children >= maxChildren(ri)"
                                        type="button"
                                        class="counter-btn size-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:border-gray-300 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Children Ages -->
                        <template x-if="room.children > 0">
                            <div class="mt-4 pt-4 border-t border-gray-200/60">
                                <label class="block text-xs font-semibold text-gray-600 mb-2.5 uppercase tracking-wider">Idade das crianças</label>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(age, ci) in room.childrenAges" :key="ci">
                                        <div class="relative">
                                            <label class="block text-[10px] text-gray-400 mb-1" x-text="'Criança ' + (ci + 1)"></label>
                                            <select
                                                x-model.number="room.childrenAges[ci]"
                                                class="w-28 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-foz-green/40 focus:border-foz-green appearance-none cursor-pointer"
                                            >
                                                <option value="" disabled>Idade</option>
                                                <option value="0">Menos de 1 ano</option>
                                                <option value="1">1 ano</option>
                                                <template x-for="a in 16" :key="a">
                                                    <option :value="a + 1" x-text="(a + 1) + ' anos'"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Capacity info -->
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs text-gray-400">
                                <span x-text="totalGuests(ri)"></span>/5 hóspedes
                            </span>
                            <template x-if="totalGuests(ri) > 5">
                                <span class="text-xs text-red-500 font-medium flex items-center gap-1">
                                    <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    Máximo de 5 pessoas por quarto
                                </span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Search Button -->
            <button
                @click="search()"
                :disabled="!isValid"
                type="button"
                class="w-full gradient-green text-white font-bold text-lg py-4 rounded-xl hover:scale-[1.02] active:scale-[0.98] transition-transform disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg cursor-pointer"
            >
                Buscar disponibilidade
            </button>

            <p class="text-center text-xs text-gray-400 mt-3">
                O desconto de 20% será aplicado automaticamente na reserva.
            </p>
        </div>
    </section>
    <!-- ===================== /BOOKING FORM ===================== -->


    <!-- ===================== HOTEL SECTION ===================== -->
    <section class="pt-16 pb-1 md:pb-1 md:pt-24 px-4 bg-white">
        <div class="container mx-auto max-w-6xl">

            <!-- Header -->
            <div class="text-center mb-14">
                <!-- TripAdvisor Badge -->
                <div class="inline-flex items-center gap-2 bg-foz-green text-white rounded-full px-5 py-2.5 mb-6 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 text-foz-yellow" aria-hidden="true">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="font-bold text-xs sm:text-sm uppercase tracking-wide">Top 10% melhores hotéis do mundo</span>
                </div>

                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Conheça o Foz Plaza Hotel</h2>

                <p class="text-xl md:text-2xl text-foz-green-dark font-semibold mb-6 max-w-3xl mx-auto italic">
                    "Um oásis de conforto e lazer, no centro de Foz do Iguaçu"
                </p>

                <p class="text-gray-600 max-w-3xl mx-auto text-base md:text-lg leading-relaxed">
                    Na área mais nobre do centro de Foz do Iguaçu, combinamos em nossa estrutura: <strong class="text-gray-900">conforto, modernidade, lazer e bem-estar</strong>, em ambientes integrados com um paisagismo verde natural em meio à cidade. Uma experiência inesquecível, com ótimo custo benefício e serviços premium.
                </p>
            </div>
        </div>
    </section>
    
    <!-- WIDGET INSTAGRAM -->
    <?php
    $ig_config = fozplaza_instagram_get_config();
    $ig_layout = !empty($ig_config['widget_layout']) ? $ig_config['widget_layout'] : '2';
    if ($ig_layout === '2')
    {
        echo fozplaza_instagram_render_widget([
            'limit' => 12,
            'embedded' => true,
            'layout' => '2',
            'title' => '',
            'description' => '',
            'follow_label' => '@fozplazahotel no Instagram',
            'fade_edges' => true,
        ]);   
    }
    ?>
    <!-- WIDGET INSTAGRAM -->

    <section class="pt-4 pb-16 md:pt-8 md:pb-24 px-4 bg-[#f5f5f0]">
        <div class="container mx-auto max-w-6xl">
            <!-- Highlights Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-14">

                <!-- Localização -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-foz-green hover:shadow-md transition-all">
                    <div class="size-10 rounded-lg bg-foz-green flex items-center justify-center mb-4">
                        <svg class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1.5 text-base">Localização Privilegiada</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Centro de Foz do Iguaçu, com fácil acesso a comércios, shopping, gastronomia e principais passeios.</p>
                </div>

                <!-- Lazer -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-foz-green hover:shadow-md transition-all">
                    <div class="size-10 rounded-lg bg-foz-green flex items-center justify-center mb-4">
                        <svg class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1.5 text-base">Lazer Completo</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">3 piscinas, jacuzzis aquecidas, SPA, lounge & bar, área zen, pergolado e redário.</p>
                </div>

                <!-- Família -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-foz-green hover:shadow-md transition-all">
                    <div class="size-10 rounded-lg bg-foz-green flex items-center justify-center mb-4">
                        <svg class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1.5 text-base">Família & Diversão</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Equipe de recreação, sala de jogos, espaço kids e baby room dedicados para todas as idades.</p>
                </div>

                <!-- Gastronomia -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-foz-green hover:shadow-md transition-all">
                    <div class="size-10 rounded-lg bg-foz-green flex items-center justify-center mb-4">
                        <svg class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.379a48.474 48.474 0 00-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M16.5 3.75V16.5"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1.5 text-base">Café da Manhã Premium</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">+80 itens com opções sem glúten e sem lactose. Tapioca, omelete e crepe feitos na hora, do seu jeito.</p>
                </div>

                <!-- Fitness & Spa -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-foz-green hover:shadow-md transition-all">
                    <div class="size-10 rounded-lg bg-foz-green flex items-center justify-center mb-4">
                        <svg class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1.5 text-base">Bem-Estar</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">SPA completo, academia equipada e áreas de relaxamento integradas ao paisagismo natural.</p>
                </div>

                <!-- Sustentabilidade -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-foz-green hover:shadow-md transition-all">
                    <div class="size-10 rounded-lg bg-foz-green flex items-center justify-center mb-4">
                        <svg class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 2.849.698 5.658 2.03 8.185l.569 1.082A21.58 21.58 0 0012 18.75c-1.233 0-2.44-.132-3.6-.381l.569-1.082a21.83 21.83 0 002.03-8.185V3.03m-2.03 16.407A48.278 48.278 0 0112 21.75c1.086 0 2.16-.053 3.22-.156M3.04 7.515C5.315 5.88 8.065 4.5 12 4.5c3.935 0 6.685 1.38 8.96 3.015M3.04 7.515l-.457 6.836a2.15 2.15 0 001.233 2.11c1.37.638 2.552 1.63 3.285 2.912M20.96 7.515l.457 6.836a2.15 2.15 0 01-1.233 2.11c-1.37.638-2.552 1.63-3.285 2.912"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1.5 text-base">Energia 100% Renovável</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Compromisso com sustentabilidade, reduzindo impacto ambiental com práticas responsáveis.</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    type="button"
                    class="gradient-green text-white px-10 py-4 font-bold text-lg rounded-full shadow-lg hover:scale-105 transition-transform w-full sm:w-auto text-center"
                >
                    Reservar com 20% OFF
                </button>

                <a
                    href="https://fozplaza.com.br"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-10 py-4 font-semibold text-base rounded-full border-2 border-gray-300 text-gray-700 hover:bg-gray-100 hover:border-gray-400 transition-all w-full sm:w-auto text-center inline-flex items-center justify-center gap-2"
                >
                    Visitar site do hotel
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                </a>
            </div>
        </div>
    </section>
    <!-- ===================== /HOTEL SECTION ===================== -->


    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-[#111816] py-12 md:py-16 px-4">
        <div class="container mx-auto max-w-6xl">

            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-8">
                <!-- Logo + Tagline -->
                <div class="text-center md:text-left">
                    <img
                        src="<?=THEME_PUBLIC?>logo_foz-plaza_white.png"
                        alt="<?php bloginfo('name'); ?>"
                        class="w-36 mx-auto md:mx-0 mb-4 opacity-80"
                    >
                    <p class="text-white/40 text-sm max-w-xs font-light">
                        Um oásis de conforto e lazer, no centro de Foz do Iguaçu.
                    </p>
                </div>

                <!-- Links -->
                <div class="flex flex-col sm:flex-row gap-8 text-center sm:text-left">
                    <div>
                        <h4 class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-3">Hotel</h4>
                        <ul class="space-y-2">
                            <li><a href="https://fozplaza.com.br" target="_blank" rel="noopener" class="text-white/40 hover:text-white text-sm transition-colors">Site Oficial</a></li>
                            <li><a href="https://fozplaza.com.br/acomodacoes" target="_blank" rel="noopener" class="text-white/40 hover:text-white text-sm transition-colors">Acomodações</a></li>
                            <li><a href="https://fozplaza.com.br/estrutura" target="_blank" rel="noopener" class="text-white/40 hover:text-white text-sm transition-colors">Estrutura</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Divider + Copyright -->
            <div class="border-t border-white/5 mt-10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-white/25 text-xs">
                    &copy; <?= date('Y') ?> Foz Plaza Hotel. Todos os direitos reservados.
                </p>
                <p class="text-white/20 text-[10px]">
                    Semana do Consumidor 2026 — Oferta válida de 10 a 17 de março.
                </p>
            </div>
        </div>
    </footer>
    <!-- ===================== /FOOTER ===================== -->

</main>


<script>
function bookingApp() {
    return {
        checkIn: null,
        checkOut: null,
        rooms: [{ adults: 2, children: 0, childrenAges: [] }],
        fpCheckIn: null,
        fpCheckOut: null,

        init() {
            this.$nextTick(() => this.initCalendars());
        },

        initCalendars() {
            const self = this;

            this.fpCheckIn = flatpickr(this.$refs.checkInInput, {
                locale: 'pt',
                dateFormat: 'd/m/Y',
                altInput: true,
                altFormat: 'j \\de F',
                minDate: 'today',
                disableMobile: true,
                onChange(selectedDates) {
                    if (selectedDates[0]) {
                        self.checkIn = selectedDates[0];
                        const nextDay = new Date(selectedDates[0]);
                        nextDay.setDate(nextDay.getDate() + 1);
                        self.fpCheckOut.set('minDate', nextDay);

                        if (!self.checkOut || self.checkOut <= selectedDates[0]) {
                            self.checkOut = null;
                            self.fpCheckOut.clear();
                            setTimeout(() => self.fpCheckOut.open(), 150);
                        }
                    }
                }
            });

            this.fpCheckOut = flatpickr(this.$refs.checkOutInput, {
                locale: 'pt',
                dateFormat: 'd/m/Y',
                altInput: true,
                altFormat: 'j \\de F',
                minDate: new Date().fp_incr(1),
                disableMobile: true,
                onChange(selectedDates) {
                    if (selectedDates[0]) {
                        self.checkOut = selectedDates[0];
                    }
                }
            });
        },

        get nights() {
            if (!this.checkIn || !this.checkOut) return 0;
            const diff = this.checkOut.getTime() - this.checkIn.getTime();
            return Math.max(0, Math.round(diff / (1000 * 60 * 60 * 24)));
        },

        addRoom() {
            this.rooms.push({ adults: 2, children: 0, childrenAges: [] });
        },

        removeRoom(index) {
            if (this.rooms.length > 1) this.rooms.splice(index, 1);
        },

        totalGuests(index) {
            return this.rooms[index].adults + this.rooms[index].children;
        },

        maxAdults(index) {
            return Math.min(4, 5 - this.rooms[index].children);
        },

        maxChildren(index) {
            return Math.min(4, 5 - this.rooms[index].adults);
        },

        incrementAdults(index) {
            if (this.rooms[index].adults < this.maxAdults(index)) {
                this.rooms[index].adults++;
            }
        },

        decrementAdults(index) {
            if (this.rooms[index].adults > 1) {
                this.rooms[index].adults--;
            }
        },

        incrementChildren(index) {
            if (this.rooms[index].children < this.maxChildren(index)) {
                this.rooms[index].children++;
                this.rooms[index].childrenAges.push('');
            }
        },

        decrementChildren(index) {
            if (this.rooms[index].children > 0) {
                this.rooms[index].children--;
                this.rooms[index].childrenAges.pop();
            }
        },

        get isValid() {
            if (!this.checkIn || !this.checkOut) return false;
            if (this.nights <= 0) return false;
            return this.rooms.every((room, i) => {
                if (room.adults < 1) return false;
                if (this.totalGuests(i) > 5) return false;
                if (room.children > 0 && room.childrenAges.some(a => a === '')) return false;
                return true;
            });
        },

        formatDate(date) {
            if (!date) return '';
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            return `${d}${m}${y}`;
        },

        search() {
            if (!this.isValid) return;

            const checkInStr = this.formatDate(this.checkIn);
            const checkOutStr = this.formatDate(this.checkOut);

            const adultsStr = this.rooms.map(r => r.adults).join(',');
            const childrenStr = this.rooms.map(r => r.children).join(',');
            const childrenAgesStr = this.rooms.map(r => r.childrenAges.join(',')).join(';');

            const params = new URLSearchParams({
                checkIn: checkInStr,
                checkOut: checkOutStr,
                rooms: this.rooms.length,
                ad: adultsStr,
                ch: childrenStr,
            });

            if (this.rooms.some(r => r.children > 0)) {
                params.append('ag', childrenAgesStr);
            }

            // https://book.omnibees.com/hotelresults?c=8274&q=3159&currencyId=16&lang=pt-BR&hotel_folder=&NRooms=2&CheckIn=07042026&CheckOut=11042026&ad=2%2C2&ch=1%2C0&ag=2%2C&Code=&group_code=&loyalty_code=

            const url = `https://book.omnibees.com/hotelresults?c=8274&q=3159&${params.toString()}`;
            window.open(url, '_blank');
        }
    };
}
</script>

<?php get_footer(); ?>
