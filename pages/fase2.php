<?php
/*
 Template Name: Fase 2
*/

// Redirect to fase-3
if(!is_super_admin())
{
    wp_redirect(home_url('/'));
    exit;
}

get_header();
?>

<!-- Flatpickr Dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>

<style>
    /* Custom Flatpickr Styles for Foz Plaza Black */
    .flatpickr-calendar {
        background: radial-gradient(circle at 20% 20%, rgba(251, 191, 36, 0.08), transparent 35%), radial-gradient(circle at 80% 0%, rgba(255, 255, 255, 0.05), transparent 30%), #0f1118 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 18px !important;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.02) inset !important;
        font-family: inherit !important;
    }
    
    .flatpickr-calendar.arrowTop:before, .flatpickr-calendar.arrowTop:after {
        border-bottom-color: #0f1118 !important;
    }
    
    .flatpickr-calendar.arrowBottom:before, .flatpickr-calendar.arrowBottom:after {
        border-top-color: #0f1118 !important;
    }

    .flatpickr-months .flatpickr-month {
        background: transparent !important;
        color: #fff !important;
        fill: #fff !important;
        font-weight: 700 !important;
        letter-spacing: 0.02em !important;
    }

    .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
        color: #fff !important;
        fill: #fff !important;
        border-radius: 12px !important;
        padding: 6px !important;
        transition: background 150ms ease, transform 150ms ease;
    }

    .flatpickr-months .flatpickr-prev-month:hover svg, .flatpickr-months .flatpickr-next-month:hover svg {
        fill: #fbbf24 !important; /* amber-400 */
    }

    .flatpickr-months .flatpickr-prev-month:hover, .flatpickr-months .flatpickr-next-month:hover {
        background: rgba(255, 255, 255, 0.06) !important;
        transform: translateY(-1px);
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months, .flatpickr-current-month input.cur-year {
        background: transparent !important;
        color: #fff !important;
        font-weight: 700 !important;
    }

    .flatpickr-weekdays {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
    }

    span.flatpickr-weekday {
        color: rgba(255, 255, 255, 0.6) !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 11px !important;
        background: transparent !important;
    }

    .flatpickr-day {
        color: #e4e4e7 !important; /* zinc-200 */
        border-radius: 10px !important;
        font-weight: 600 !important;
        transition: transform 120ms ease, background-color 120ms ease, color 120ms ease;
    }

    .flatpickr-day:hover, .flatpickr-day.prevMonthDay:hover, .flatpickr-day.nextMonthDay:hover, .flatpickr-day:focus, .flatpickr-day.prevMonthDay:focus, .flatpickr-day.nextMonthDay:focus {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: transparent !important;
        transform: translateY(-1px);
    }

    .flatpickr-day.today:not(.selected):not(.inRange) {
        border: 1px solid rgba(251, 191, 36, 0.7) !important;
        color: #fbbf24 !important;
    }

    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
        background: linear-gradient(135deg, #fbbf24, #f59e0b) !important; /* amber-400 to amber-500 */
        border-color: transparent !important;
        color: #0c0c12 !important;
        font-weight: 700 !important;
        box-shadow: 0 10px 30px rgba(251, 191, 36, 0.35) !important;
    }

    .flatpickr-day.startRange:not(.endRange) {
        border-radius: 999px 0 0 999px !important;
    }

    .flatpickr-day.endRange:not(.startRange) {
        border-radius: 0 999px 999px 0 !important;
    }

    .flatpickr-day.startRange.endRange {
        border-radius: 999px !important;
    }

    .flatpickr-day.inRange {
        background: linear-gradient(90deg, rgba(251, 191, 36, 0.1), rgba(251, 191, 36, 0.2)) !important;
        border-color: transparent !important;
        color: #e2e8f0 !important;
        box-shadow: -5px 0 0 rgba(251, 191, 36, 0.08), 5px 0 0 rgba(251, 191, 36, 0.08) !important;
    }
    
    .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover {
        color: rgba(255,255,255,0.1) !important;
    }
</style>

<?php
$bookingYear = 2026;
$minCheckInDate = sprintf('%d-01-02', $bookingYear);
$maxCheckInDate = sprintf('%d-12-20', $bookingYear);
$maxCheckOutDate = sprintf('%d-12-31', $bookingYear);

$couponPercentages = [
    'bf50' => (int) get_field('porcentagem_bf50'),
    'bf40' => (int) get_field('porcentagem_bf40'),
    'bf30' => (int) get_field('porcentagem_bf30'),
];

foreach ($couponPercentages as $key => $value) {
    if (! is_numeric($value)) {
        $couponPercentages[$key] = 0;
    }
    $couponPercentages[$key] = max(0, min(100, (int) $couponPercentages[$key]));
}

$coupons = [
    [
        'code' => 'BF50',
        'label' => '50% OFF',
        'percent_used' => $couponPercentages['bf50'],
        'limit' => 60,
        'description' => 'Primeiras 60 reservas com o maior desconto.',
    ],
    [
        'code' => 'BF40',
        'label' => '40% OFF',
        'percent_used' => $couponPercentages['bf40'],
        'limit' => 90,
        'description' => 'Segunda faixa com 90 reservas disponíveis.',
    ],
    [
        'code' => 'BF30',
        'label' => '30% OFF',
        'percent_used' => $couponPercentages['bf30'],
        'limit' => 100,
        'description' => 'Desconto garantido para as 100 últimas reservas.',
    ],
];

$weightedUsed = 0;
foreach ($coupons as $coupon) {
    $weightedUsed += ($coupon['percent_used'] * $coupon['limit']);
}

$overallProgress = min(100, round($weightedUsed / 250, 1));

// $subscriberUsers = get_users([
//     'role' => 'subscriber',
//     'fields' => ['display_name'],
//     'number' => 600,
// ]);

// $subscriberFirstNames = [];
// foreach ($subscriberUsers as $user) {
//     $name = trim($user->display_name ?? '');
//     if ($name === '') {
//         continue;
//     }

//     $firstName = preg_split('/\s+/', $name);
//     if (! empty($firstName[0])) {
//         $subscriberFirstNames[] = $firstName[0];
//     }
// }

// $subscriberFirstNames = array_values(array_unique($subscriberFirstNames));

// if (empty($subscriberFirstNames)) {
//     $subscriberFirstNames = ['Julia', 'Rafael', 'Marina', 'Carlos', 'Ana', 'Diego', 'Camila', 'Bruno', 'Patricia', 'Pedro', 'Helena'];
// }

$brazilianNames = [
    'Miguel', 'Arthur', 'Heitor', 'Bernardo', 'Davi', 'Gabriel', 'Ravi', 'Pedro', 'Lucas', 'Matheus',
    'Benjamin', 'Nicolas', 'Guilherme', 'Rafael', 'Joaquim', 'Samuel', 'Enzo', 'Murilo', 'João', 'Gustavo',
    'Lorenzo', 'Theo', 'Felipe', 'Noah', 'Henrique', 'Eduardo', 'Leonardo', 'Daniel', 'Bryan', 'Francisco',
    'Helena', 'Alice', 'Laura', 'Maria', 'Valentina', 'Sophia', 'Isabella', 'Heloísa', 'Manuela', 'Júlia',
    'Liz', 'Cecília', 'Maitê', 'Eloá', 'Antonella', 'Lorena', 'Lívia', 'Giovanna', 'Beatriz', 'Mariana',
    'Yasmin', 'Gabriela', 'Rebeca', 'Sarah', 'Ana', 'Clara', 'Melissa', 'Ester', 'Isis', 'Alícia',
    'Lavínia', 'Letícia', 'Amanda', 'Ayla', 'Bianca', 'Samanta', 'Sônia', 'Regina', 'Isadora', 'Rayssa'
];

$argentinianNames = [
    'Mateo', 'Bautista', 'Juan', 'Felipe', 'Bruno', 'Noah', 'Benjamín', 'Valentín', 'Joaquín', 'Santino',
    'Ian', 'Ciro', 'Dante', 'Tomás', 'Lautaro', 'Thiago', 'Francisco', 'Agustín', 'Santiago', 'Ignacio',
    'Sofía', 'Isabella', 'Catalina', 'Martina', 'Valentina', 'Emma', 'Mía', 'Alma', 'Olivia', 'Delfina',
    'Juana', 'Victoria', 'Emilia', 'Renata', 'Lucía', 'Facundo', 'Agustina', 'Julieta', 'Antonella', 'Benevidez'
];

$subscriberFirstNames = array_merge($brazilianNames, $argentinianNames);
shuffle($subscriberFirstNames);

$bookingConfig = [
    'idHotel' => 3159,
    'cHotel' => 8274,
    'minCheckIn' => $minCheckInDate,
    'maxCheckIn' => $maxCheckInDate,
    'maxCheckOut' => $maxCheckOutDate,
    'coupons' => $coupons,
    'overallProgress' => $overallProgress,
    'subscribers' => $subscriberFirstNames,
];
?>

<script>
    window.blackFridayBooking = <?php echo wp_json_encode($bookingConfig); ?>;
</script>
<div x-data="bookingApp()" class="relative text-foz-white">
    <nav
        class="fixed top-0 left-0 right-0 z-50 border-b border-white/5 backdrop-blur-md transition-colors duration-300"
        :class="navIsOpaque ? 'bg-foz-black/90 shadow-[0_20px_40px_rgba(5,7,12,0.4)]' : 'bg-transparent'"
    >
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-6">
                <a href="#reserva" class="flex items-center gap-3">
                    <img src="<?=THEME_PUBLIC?>logo_foz-plaza-hotel_horizontal-minimal.svg" alt="Foz Plaza Hotel" class="h-7 w-auto">
                </a>

                <div class="hidden md:flex items-center gap-6 text-xs lg:text-sm font-semibold uppercase text-foz-white/80">
                    <a href="#cupons" class="hover:text-foz-yellow transition-colors">Cupons ativos</a>
                    <a href="#beneficios" class="hover:text-foz-yellow transition-colors">Por que reservar</a>
                    <a href="#cadastro" class="hover:text-foz-yellow transition-colors">Participar do ranking</a>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-1">
                    <button
                        type="button"
                        @click="scrollToForm()"
                        class="text-sm sm:text-base gradient-yellow text-[#1a1f2c] font-semibold px-4 md:px-6 py-2 rounded-full transition-transform duration-200 hover:scale-105"
                    >
                        Reservar agora
                    </button>
                    <!-- <a
                        href="#cadastro"
                        class="text-sm sm:text-base font-semibold px-4 md:px-6 py-2 rounded-full transition-transform duration-200 hover:scale-105 border border-white/10 text-foz-white/80 hover:text-foz-white hover:border-foz-yellow/60"
                    >
                        Cadastre-se
                    </a> -->
                    <a 
                        href="/minha-area"
                        class="text-sm sm:text-base font-semibold px-4 md:px-6 py-2 rounded-full transition-transform duration-200 hover:scale-105"
                    >
                        Minha Área
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section id="reserva" class="pt-32 relative min-h-[50vh] flex items-center justify-center px-4 pb-16 overflow-hidden">
        <video
            class="fixed inset-0 h-[80vh] w-screen object-cover -z-1"
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
        <div class="absolute inset-0 bg-linear-to-b from-foz-black/98 via-foz-black/65 to-foz-black/90"></div>

        <div class="relative z-10 container mx-auto max-w-7xl">
            <div class="grid lg:grid-cols-5 gap-8 items-start">
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                        <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-foz-yellow/90 px-4 py-2 text-xs font-semibold text-foz-black tracking-widest sm:uppercase shadow-glow w-fit">
                            Black Friday 2025
                        </p>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-xs uppercase tracking-widest text-foz-white/60 font-semibold">Acaba em:</span>
                            <div class="flex items-center gap-2">
                                <div class="text-center">
                                    <span class="block text-lg font-bold text-foz-yellow leading-none" x-text="String(countdown.days).padStart(2, '0')"></span>
                                    <span class="text-[9px] uppercase text-foz-white/40" x-text="countdown.days === 1 ? 'Dia' : 'Dias'"></span>
                                </div>
                                <span class="text-foz-white/20 text-lg font-light">:</span>
                                <div class="text-center">
                                    <span class="block text-lg font-bold text-foz-yellow leading-none" x-text="String(countdown.hours).padStart(2, '0')"></span>
                                    <span class="text-[9px] uppercase text-foz-white/40" x-text="countdown.hours === 1 ? 'Hora' : 'Horas'"></span>
                                </div>
                                <span class="text-foz-white/20 text-lg font-light">:</span>
                                <div class="text-center">
                                    <span class="block text-lg font-bold text-foz-yellow leading-none" x-text="String(countdown.minutes).padStart(2, '0')"></span>
                                    <span class="text-[9px] uppercase text-foz-white/40">Min</span>
                                </div>
                                <span class="text-foz-white/20 text-lg font-light">:</span>
                                <div class="text-center">
                                    <span class="block text-lg font-bold text-foz-yellow leading-none" x-text="String(countdown.seconds).padStart(2, '0')"></span>
                                    <span class="text-[9px] uppercase text-foz-white/40">Seg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1 class="heading-display text-2xl md:text-4xl text-foz-white leading-tight">
                        250 reservas com desconto para todo o ano de 2026
                    </h1>
                    <p class="text-lg md:text-lg text-foz-white/80 max-w-3xl">
                        Escolha o desconto, garanta sua data e finalize no nosso motor de reservas em segundos.
                    </p>

                    <div class="space-y-3 bg-white/5 border border-white/10 rounded-3xl p-5 shadow-elevated backdrop-blur">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm uppercase tracking-[0.25em] text-foz-yellow">Progresso da promoção</span>
                            <span class="text-lg font-semibold text-foz-yellow" x-text="overallProgress + '%'" aria-live="polite"></span>
                        </div>
                        <div class="h-3 w-full bg-white/10 rounded-full overflow-hidden" role="progressbar" :aria-valuenow="overallProgress" aria-valuemin="0" aria-valuemax="100">
                            <div class="h-full bg-linear-to-r from-foz-yellow via-foz-orange to-foz-red" :style="`width: ${overallProgress}%`"></div>
                        </div>
                        <p class="text-sm text-foz-white/70">São <strong class="text-foz-yellow">250 reservas disponíveis</strong> na promoção. Quando um cupom esgota, vamos para o próximo.</p>
                    </div>

                    <div class="hidden lg:grid sm:grid-cols-2 gap-3">
                        <!-- <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                            <p class="text-sm text-foz-white/70">Maior desconto</p>
                            <p class="text-3xl font-bold text-foz-yellow">50% OFF</p>
                            <p class="text-xs text-foz-white/60 mt-1">Primeiras 50 reservas (BF50)</p>
                        </div> -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                            <p class="text-sm text-foz-white/70">Parcelamento</p>
                            <p class="text-3xl font-bold text-foz-white">Em até 10x</p>
                            <p class="text-xs text-foz-white/60 mt-1">Sem juros no motor de reservas</p>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                            <p class="text-sm text-foz-white/70">Flexibilidade</p>
                            <p class="text-3xl font-bold text-foz-white">Remarcação</p>
                            <p class="text-xs text-foz-white/60 mt-1">Crédito garantido se precisar remarcar</p>
                        </div>
                    </div>

                    <div class="hidden lg:flex flex-col sm:flex-row gap-3">
                        <a
                            href="#cadastro"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full border border-foz-yellow/60 text-foz-yellow font-semibold hover:bg-foz-yellow/10 transition"
                        >
                            Ainda não se cadastrou? Entre no ranking
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-3">
                    <div class="bg-linear-to-b from-foz-black/70 via-foz-brown/70 to-foz-black/90 border border-white/10 rounded-3xl shadow-glow p-6 md:p-7 backdrop-blur animate-pulse-glow">
                        
                        <h2 class="text-2xl sm:text-3xl font-black uppercase text-center tracking-widest bg-linear-to-r from-foz-yellow via-foz-yellow-light to-foz-orange bg-clip-text text-transparent animate-pulse">
                            Buscar tarifas Black 😎
                        </h2>

                        <div class="space-y-4 mt-6">
                            <input type="text" class="hidden" x-ref="dateRangePicker" style="display: none;">
                            <div>
                                <label class="flex items-center text-base text-foz-white mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-foz-yellow text-foz-black text-xs font-bold mr-2">1</span>
                                    Período da viagem
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-ref="periodInput"
                                        placeholder="Selecione o período Checkin/Checkout"
                                        class="w-full rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-foz-white focus:outline-none focus:ring-2 focus:ring-foz-yellow cursor-pointer text-left placeholder-white/30"
                                        readonly
                                    >
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-foz-white/50 flex items-center gap-2">
                                        <span x-show="nights > 0" class="text-xs font-semibold text-foz-yellow bg-foz-yellow/10 px-2 py-1 rounded-md" x-text="nights + (nights === 1 ? ' noite' : ' noites')"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-foz-yellow/50 mt-2 pl-2">Válidade da promoção: <strong>02/01 a 20/12/<?=$bookingYear?></strong></p>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-foz-yellow text-foz-black text-xs font-bold mr-2">2</span>
                                            <span class="text-base text-foz-white font-semibold">Quartos e hóspedes</span>
                                        </div>
                                        <p class="text-[11px] text-foz-white/60 pl-8">Até 5 hóspedes por quarto</p>
                                    </div>
                                    <span class="text-xs text-foz-white/50 hidden sm:inline-flex">Personalize cada quarto</span>
                                </div>

                                <template x-for="(room, index) in form.rooms" :key="room.uid">
                                    <div class="border border-white/10 rounded-2xl p-4 bg-white/5 shadow-inner shadow-black/20 space-y-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-0.5">
                                                    <p class="text-sm uppercase tracking-[0.2em] text-foz-white">
                                                        Quarto <span x-text="index + 1"></span>
                                                    </p>
                                                </div>
                                                <p class="mt-4 text-sm text-foz-yellow font-semibold" x-text="guestSummary(room)"></p>
                                                <p class="text-xs text-foz-white/80">Defina quantidade de adultos e crianças abaixo</p>
                                            </div>
                                            <button
                                                type="button"
                                                class="text-xs font-light text-foz-white/60 hover:text-foz-orange transition-colors flex items-center gap-1"
                                                @click="removeRoom(index)"
                                                x-show="form.rooms.length > 1"
                                            >
                                                Remover
                                                <svg data-slot="icon" fill="currentColor" class="size-5" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid sm:grid-cols-2 gap-3">
                                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-semibold">Adultos</p>
                                                        <p class="text-[11px] text-foz-white/60">13 anos ou mais</p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button
                                                            type="button"
                                                            class="size-9 rounded-full border border-white/15 bg-white/5 text-lg font-bold text-foz-white hover:border-foz-yellow hover:text-foz-yellow transition-colors"
                                                            @click="adjustGuestCount(index, 'adults', -1)"
                                                        >
                                                            -
                                                        </button>
                                                        <span class="w-10 text-center text-lg font-bold" x-text="room.adults"></span>
                                                        <button
                                                            type="button"
                                                            class="size-9 rounded-full border border-white/15 bg-white/5 text-lg font-bold text-foz-white hover:border-foz-yellow hover:text-foz-yellow transition-colors"
                                                            @click="adjustGuestCount(index, 'adults', 1)"
                                                        >
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-semibold">Crianças</p>
                                                        <p class="text-[11px] text-foz-white/60">0 a 12 anos</p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button
                                                            type="button"
                                                            class="h-9 w-9 rounded-full border border-white/15 bg-white/5 text-lg font-bold text-foz-white hover:border-foz-yellow hover:text-foz-yellow transition-colors"
                                                            @click="adjustGuestCount(index, 'children', -1)"
                                                        >
                                                            -
                                                        </button>
                                                        <span class="w-10 text-center text-lg font-bold" x-text="room.children"></span>
                                                        <button
                                                            type="button"
                                                            class="h-9 w-9 rounded-full border border-white/15 bg-white/5 text-lg font-bold text-foz-white hover:border-foz-yellow hover:text-foz-yellow transition-colors"
                                                            @click="adjustGuestCount(index, 'children', 1)"
                                                        >
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="room.children > 0" class="space-y-2 pt-1">
                                            <p class="text-xs text-foz-white/80 font-semibold">Idade de cada criança</p>
                                            <div class="grid grid-cols-2 gap-2">
                                                <template x-for="childIndex in room.children" :key="childIndex">
                                                    <select
                                                        class="w-full rounded-xl bg-white/5 border border-white/10 px-3 py-2 text-foz-white focus:outline-none focus:ring-2 focus:ring-foz-yellow [&>option]:bg-[#18181b] [&>option]:text-white"
                                                        x-model.number="form.rooms[index].childrenAges[childIndex - 1]"
                                                    >
                                                        <option value="">Selecione</option>
                                                        <option value="0">0 anos</option>
                                                        <option value="1">1 ano</option>
                                                        <option value="2">2 anos</option>
                                                        <option value="3">3 anos</option>
                                                        <option value="4">4 anos</option>
                                                        <option value="5">5 anos</option>
                                                        <option value="6">6 anos</option>
                                                        <option value="7">7 anos</option>
                                                        <option value="8">8 anos</option>
                                                        <option value="9">9 anos</option>
                                                        <option value="10">10 anos</option>
                                                        <option value="11">11 anos</option>
                                                        <option value="12">12 anos</option>
                                                        <option value="13">13 anos</option>
                                                        <option value="14">14 anos</option>
                                                        <option value="15">15 anos</option>
                                                        <option value="16">16 anos</option>
                                                        <option value="17">17 anos</option>
                                                    </select>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 text-xs text-amber-200 bg-amber-500/10 border border-amber-500/20 rounded-xl px-3 py-2" x-show="totalGuests(room) === maxGuestsPerRoom">
                                            <span>Capacidade máxima por quarto atingida (5 hóspedes).</span>
                                        </div>
                                        <div class="mt-1 text-[11px] text-foz-white/60" x-show="totalGuests(room) > maxGuestsPerRoom">
                                            Reduza para até 5 hóspedes neste quarto.
                                        </div>
                                    </div>
                                </template>

                                <button
                                    type="button"
                                    class="w-full flex items-center justify-center gap-2 rounded-xl border border-dashed border-foz-yellow/70 bg-foz-yellow/10 px-4 py-3 text-sm font-semibold text-foz-yellow hover:bg-foz-yellow/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    @click="addRoom()"
                                    :disabled="form.rooms.length >= maxRooms"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    <span>Adicionar quarto</span>
                                    <span class="text-[11px] text-foz-white/60" x-show="form.rooms.length >= maxRooms">(limite atingido)</span>
                                </button>
                            </div>

                            <div class="space-y-2">
                                <p class="flex items-center text-lg text-foz-white font-semibold mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-foz-yellow text-foz-black text-xs font-bold mr-2">3</span>
                                    Escolha o cupom
                                </p>
                                <div class="grid grid-cols-1 gap-2">
                                    <template x-for="coupon in coupons" :key="coupon.code">
                                        <label
                                            x-bind:disabled="couponSoldOut(coupon)"
                                            class="flex items-center gap-3 rounded-2xl border p-3 transition-all"
                                            :class="couponSoldOut(coupon) ? 'border-white/10 bg-foz-black/10 cursor-not-allowed' : (form.couponCode === coupon.code ? 'border-foz-yellow border-2 bg-foz-yellow/10 shadow-glow animate-pulse-glow' : 'cursor-pointer border-white/10 bg-white/5 hover:border-foz-yellow/50')"
                                        >
                                            <input
                                                type="radio"
                                                class="sr-only"
                                                :value="coupon.code"
                                                x-model="form.couponCode"
                                                :disabled="couponSoldOut(coupon)"
                                            >
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-lg font-bold" x-text="coupon.code + ' - ' + coupon.label"
                                                        :class="couponSoldOut(coupon) ? 'text-white/40' : ''"
                                                    ></span>
                                                    
                                                    <span class="text-xs px-2 py-1 rounded-full" :class="couponSoldOut(coupon) ? 'bg-red-700 text-foz-yellow/80 uppercase font-bold ' : 'bg-foz-yellow text-foz-black'">
                                                        <span x-text="couponSoldOut(coupon) ? 'Esgotado!' : 'Disponível'"></span>
                                                    </span>
                                                </div>
                                                <p
                                                    class="text-xs text-foz-white/70" x-text="coupon.description"
                                                    :class="couponSoldOut(coupon) ? 'text-white/40' : ''"
                                                ></p>
                                                <div class="mt-2">
                                                    <div class="flex items-center justify-between text-[11px] text-foz-white/60">
                                                        <span>Uso do cupom</span>
                                                        <span x-text="coupon.percent_used + '%'" aria-live="polite"></span>
                                                    </div>
                                                    <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden" role="progressbar" :aria-valuenow="coupon.percent_used" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="h-full bg-linear-to-r from-foz-yellow via-foz-orange to-foz-red" :style="`width: ${coupon.percent_used}%`"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div x-show="formError" class="bg-red-500/15 border border-red-500/40 text-red-100 text-sm rounded-2xl px-4 py-3" x-text="formError"></div>
                            <div x-show="formWarning" class="bg-amber-500/15 border border-amber-500/40 text-amber-100 text-sm rounded-2xl px-4 py-3" x-text="formWarning"></div>

                            <button
                                type="button"
                                @click="submitReservation()"
                                class="w-full gradient-yellow text-foz-black px-6 py-4 text-lg font-bold rounded-full shadow-glow hover:scale-105 transition-transform"
                            >
                                Buscar tarifas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <section id="cupons" class="bg-[#0d0d12] border-t border-white/5 py-16 px-4"> -->
    <section id="cupons" class="bg-linear-to-b from-foz-black/90 via-foz-black to-foz-black py-16 px-4">
        <div class="container mx-auto max-w-6xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h2 class="heading-display text-3xl md:text-4xl text-foz-white">Escolha seu desconto agora</h2>
                    <p class="text-foz-white/70 mt-2">Cupons liberados. Quando uma faixa esgota, seguimos para a próxima.</p>
                </div>
                <button
                    type="button"
                    @click="scrollToForm()"
                    class="gradient-yellow text-foz-black px-6 py-3 font-semibold rounded-full hover:scale-105 transition-transform"
                >
                    Reservar com desconto
                </button>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <template x-for="coupon in coupons" :key="coupon.code">
                    <div
                        class="relative border border-white/10 rounded-3xl p-5"
                        :class="couponSoldOut(coupon) ? 'bg-white/3' : 'bg-white/5 shadow-elevated'"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm text-foz-white/70">Cupom</p>
                                <p
                                    class="text-2xl font-bold text-foz-yellow" x-text="coupon.code"
                                    :class="couponSoldOut(coupon) ? 'text-white/40' : ''"
                                ></p>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full" :class="couponSoldOut(coupon) ? 'bg-red-700 text-foz-yellow/80 uppercase font-bold' : 'bg-foz-yellow text-foz-black'">
                                <span x-text="couponSoldOut(coupon) ? 'Esgotado!' : 'Ativo'"></span>
                            </span>
                        </div>
                        <p class="text-lg font-semibold mt-2" :class="couponSoldOut(coupon) ? 'text-white/40' : ''" x-text="coupon.label"></p>
                        <p class="text-sm text-foz-white/70" :class="couponSoldOut(coupon) ? 'text-white/40' : ''" x-text="coupon.description"></p>

                        <div class="mt-4 space-y-2">
                            <div class="flex items-center justify-between text-xs text-foz-white/60">
                                <span>Uso do cupom</span>
                                <span x-text="coupon.percent_used + '%'" aria-live="polite"></span>
                            </div>
                            <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden" role="progressbar" :aria-valuenow="coupon.percent_used" aria-valuemin="0" aria-valuemax="100">
                                <div class="h-full bg-linear-to-r from-foz-yellow via-foz-orange to-foz-red" :style="`width: ${coupon.percent_used}%`"></div>
                            </div>
                            <button
                                type="button"
                                class="w-full mt-2 rounded-full px-4 py-2 text-sm font-semibold border border-white/15 hover:border-foz-yellow not-disabled:hover:text-foz-yellow transition-colors"
                                :class="couponSoldOut(coupon) ? 'cursor-not-allowed opacity-50 hover:border-white/15 hover:text-foz-white' : ''"
                                :disabled="couponSoldOut(coupon)"
                                @click="selectCoupon(coupon.code); scrollToForm()"
                            >
                                Usar este cupom
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>
    <!-- cupons -->

    <?php
    $lead_registration_config = [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('lead_registration'),
        'successRedirect' => home_url('/minha-area'),
    ];

    $lead_form_config = [
        'title' => __('Ainda não se cadastrou?', 'fozplaza-black25'),
        'subtitle' => __('Ative sua participação para continuar pontuando no ranking enquanto as vendas estão abertas.', 'fozplaza-black25'),
        'cta_label' => __('Quero me cadastrar e concorrer', 'fozplaza-black25'),
        'success_title' => __('Cadastro concluído!', 'fozplaza-black25'),
        'success_message' => __('Cadastro feito. Vamos te levar para a sua área exclusiva.', 'fozplaza-black25'),
    ];
    ?>
    <section id="cadastro" class="bg-foz-black/85 py-16 px-4 border-t border-white/5">
        <div class="container mx-auto max-w-6xl">
            <div class="grid lg:grid-cols-2 gap-8 items-start">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-foz-yellow/30 bg-foz-yellow/15 px-4 py-2 text-xs font-semibold text-foz-yellow tracking-[0.3em] uppercase">
                        Ranking e indicações
                    </span>
                    <h2 class="heading-display text-3xl md:text-4xl text-foz-white">
                        Continue ganhando pontos mesmo com as vendas abertas
                    </h2>
                    <p class="text-foz-white/70 text-base md:text-lg">
                        Cadastre-se em segundos, compartilhe seu link exclusivo e acompanhe sua posição no ranking enquanto garante reservas com desconto.
                    </p>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div class="flex items-start gap-3 bg-white/5 border border-white/10 rounded-2xl p-4">
                            <span class="text-foz-yellow">★</span>
                            <div>
                                <p class="text-sm font-semibold text-foz-white">1 cadastro = 1 ponto</p>
                                <p class="text-xs text-foz-white/60">Indique amigos e suba no ranking.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-white/5 border border-white/10 rounded-2xl p-4">
                            <span class="text-foz-yellow">⏱️</span>
                            <div>
                                <p class="text-sm font-semibold text-foz-white">Cadastro rápido</p>
                                <p class="text-xs text-foz-white/60">Valida e já libera sua área exclusiva.</p>
                            </div>
                        </div>
                    </div>
                    <a href="#reserva" class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.25em] text-foz-white/70 hover:text-foz-yellow transition-colors">
                        Ver descontos disponíveis
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <?php require locate_template('components/lead-registration-card.php'); ?>
            </div>
        </div>
    </section>

    <!-- Beneficios -->
    <section id="beneficios" class="py-16 md:py-20 px-4 bg-linear-to-b from-foz-black via-[#0e0f16] to-foz-black border-t border-white/5">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="heading-display text-3xl md:text-4xl text-foz-white">Motivos para reservar agora</h2>
                <p class="text-foz-white/70 mt-2">Hospedagens para todo 2026 com o maior desconto da nossa história.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
                    <p class="text-sm text-foz-yellow font-semibold mb-1">Top 10% do mundo</p>
                    <p class="text-foz-white font-semibold">Traveller's Choice TripAdvisor</p>
                    <p class="text-foz-white/70 text-sm mt-2">Hotel premiado, localização central e estrutura completa.</p>
                </div>
                <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
                    <p class="text-sm text-foz-yellow font-semibold mb-1">Transfer in incluso</p>
                    <p class="text-foz-white font-semibold">Do aeroporto IGU para o hotel</p>
                    <p class="text-foz-white/70 text-sm mt-2">Chegue com conforto e sem custo extra durante a Black.</p>
                </div>
                <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
                    <p class="text-sm text-foz-yellow font-semibold mb-1">Criança free</p>
                    <p class="text-foz-white font-semibold">1 criança até 7 anos grátis</p>
                    <p class="text-foz-white/70 text-sm mt-2">Aproveite o desconto e traga a família.</p>
                </div>
                <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
                    <p class="text-sm text-foz-yellow font-semibold mb-1">Programas temáticos</p>
                    <p class="text-foz-white font-semibold">Hotel se transforma o ano inteiro</p>
                    <p class="text-foz-white/70 text-sm mt-2">Eventos e recreação infantil na alta e feriados.</p>
                </div>
                <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
                    <p class="text-sm text-foz-yellow font-semibold mb-1">Pagamento facilitado</p>
                    <p class="text-foz-white font-semibold">Em até 10x sem juros</p>
                    <p class="text-foz-white/70 text-sm mt-2">Feche agora, pague aos poucos e com o super desconto.</p>
                </div>
                <div class="p-5 rounded-2xl border border-white/10 bg-white/5">
                    <p class="text-sm text-foz-yellow font-semibold mb-1">Remarcação flexível</p>
                    <p class="text-foz-white font-semibold">Use como crédito se precisar remarcar</p>
                    <p class="text-foz-white/70 text-sm mt-2">Garante o desconto hoje e mantém a tranquilidade.</p>
                </div>
            </div>

            <div class="mt-12 text-center">
                <button
                    type="button"
                    @click="scrollToForm()"
                    class="gradient-yellow text-foz-black px-10 py-4 rounded-full font-bold text-lg shadow-glow hover:scale-105 transition-transform"
                >
                    Escolher datas e reservar
                </button>
            </div>
        </div>
    </section>
    <!-- Beneficios -->

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
                    @click="scrollToForm()"
                    class="gradient-yellow text-foz-black px-8 md:px-10 py-4 font-bold text-lg rounded-full shadow-glow hover:scale-105 transition-transform w-full sm:w-auto text-center"
                >
                    Reservar com desconto
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

    <section id="ranking" class="py-16 md:py-24 px-4 bg-linear-to-b bg-foz-black">
        <?php require_once __DIR__ . '/../components/ranking-frontend.php'; ?>
    </section>

    <!-- FAQ -->
    <section class="py-16 md:py-24 px-4 bg-[#0B0B0F]">
        <?php require_once __DIR__ . '/../components/faq.php'; ?>
    </section>
    <!-- FAQ -->

    <!-- Foot fase2 -->
    <?php require_once __DIR__ . '/../components/foot-fase2.php'; ?>
    <!-- Foot fase2 -->

    <!-- <div class="fixed bottom-4 left-4 z-50 w-[calc(100%- 2rem)] max-w-sm space-y-2"> -->
    <div class="fixed bottom-4 left-4 z-50 w-auto max-w-[60vw] space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="bg-foz-yellow text-foz-black rounded-2xl shadow-xl px-4 py-3 border border-foz-yellow/40">
                <p class="text-sm font-semibold" x-text="toast.message"></p>
                <!-- <p class="text-[11px] text-foz-black/60 mt-1">Black Friday Foz Plaza</p> -->
            </div>
        </template>
    </div>
    <script>
        function bookingApp() {
            const config = window.blackFridayBooking || {};
            const generateUid = () => {
                if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                    return window.crypto.randomUUID();
                }
                return `uid-${Date.now()}-${Math.random().toString(16).slice(2)}`;
            };

            return {
                navIsOpaque: false,
                limits: {
                    minCheckIn: config.minCheckIn || '',
                    maxCheckIn: config.maxCheckIn || '',
                    maxCheckOut: config.maxCheckOut || '',
                },
                coupons: config.coupons || [],
                overallProgress: Number(config.overallProgress || 0),
                hotel: {
                    id: config.idHotel,
                    code: config.cHotel,
                },
                form: {
                    checkIn: '',
                    checkOut: '',
                    couponCode: '',
                    rooms: [
                        { uid: generateUid(), adults: 2, children: 0, childrenAges: [] },
                    ],
                },
                maxRooms: 5,
                maxGuestsPerRoom: 5,
                maxChildrenPerRoom: 4,
                minAdultsPerRoom: 1,
                formError: '',
                formWarning: '',
                toasts: [],
                toastLoopStarted: false,
                subscribers: config.subscribers || [],
                fp: null,
                nights: 0,
                countdown: { days: 0, hours: 0, minutes: 0, seconds: 0 },

                init() {
                    this.prefillDates();
                    this.preselectCoupon();
                    this.startToastLoop();
                    this.startCountdown();
                    this.handleScroll();
                    this.initDatepicker();
                    window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
                },

                startCountdown() {
                    const endDate = new Date('2025-11-30T23:59:59-03:00');
                    const update = () => {
                        const now = new Date();
                        const diff = endDate - now;
                        if (diff > 0) {
                            this.countdown.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            this.countdown.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            this.countdown.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            this.countdown.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        } else {
                            this.countdown = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                        }
                    };
                    update();
                    setInterval(update, 1000);
                },

                initDatepicker() {
                    const self = this;
                    const minDate = this.parseDate(this.limits.minCheckIn);
                    const maxDate = this.parseDate(this.limits.maxCheckOut);

                    this.fp = flatpickr(this.$refs.dateRangePicker, {
                        mode: 'range',
                        minDate: minDate,
                        maxDate: maxDate,
                        dateFormat: 'Y-m-d',
                        locale: 'pt',
                        showMonths: window.innerWidth < 768 ? 1 : 2,
                        disableMobile: "true",
                        positionElement: this.$refs.periodInput,
                        onChange: function(selectedDates, dateStr, instance) {
                            if (selectedDates.length > 0) {
                                self.form.checkIn = instance.formatDate(selectedDates[0], 'Y-m-d');
                            }
                            
                            if (selectedDates.length > 1) {
                                self.form.checkOut = instance.formatDate(selectedDates[1], 'Y-m-d');
                                
                                // Update display input
                                const d1 = selectedDates[0];
                                const d2 = selectedDates[1];
                                self.$refs.periodInput.value = `${instance.formatDate(d1, 'd/m')} a ${instance.formatDate(d2, 'd/m/Y')}`;
                                
                                // Calculate nights
                                const diffTime = Math.abs(d2 - d1);
                                self.nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            } else {
                                self.form.checkOut = '';
                                self.$refs.periodInput.value = instance.formatDate(selectedDates[0], 'd/m') + ' até ...';
                                self.nights = 0;
                            }
                        }
                    });

                    this.$refs.periodInput.addEventListener('click', () => {
                        this.fp.open();
                    });
                },

                handleScroll() {
                    this.navIsOpaque = window.scrollY >= 60;
                },

                scrollToForm() {
                    const target = document.querySelector('#reserva');
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }

                    this.$refs.periodInput.focus();
                    setTimeout(() => {
                        this.$refs.periodInput.click();
                    }, 400);
                },

                preselectCoupon() {
                    const available = this.coupons.find((coupon) => Number(coupon.percent_used) < 100);
                    if (available) {
                        this.form.couponCode = available.code;
                    }
                },

                prefillDates() {
                    // No default dates selected
                },

                alignCheckout() {
                    if (!this.form.checkIn) {
                        return;
                    }

                    const checkInDate = this.parseDate(this.form.checkIn);
                    const nextDay = new Date(checkInDate.getTime());
                    nextDay.setDate(nextDay.getDate() + 1);

                    let checkOutDate = this.form.checkOut ? this.parseDate(this.form.checkOut) : nextDay;
                    const maxCheckout = this.parseDate(this.limits.maxCheckOut || this.limits.maxCheckIn);

                    if (checkOutDate <= checkInDate) {
                        checkOutDate = nextDay;
                    }

                    if (checkOutDate > maxCheckout) {
                        checkOutDate = maxCheckout;
                    }

                    this.form.checkOut = this.formatDateInput(checkOutDate);
                },

                flashWarning(message, duration = 2200) {
                    this.formWarning = message;
                    setTimeout(() => {
                        if (this.formWarning === message) {
                            this.formWarning = '';
                        }
                    }, duration);
                },

                addRoom() {
                    if (this.form.rooms.length >= this.maxRooms) {
                        this.formWarning = 'Para mais quartos, finalize esta busca e repita para novas datas.';
                        return;
                    }
                    this.formWarning = '';
                    this.form.rooms.push({
                        uid: generateUid(),
                        adults: 2,
                        children: 0,
                        childrenAges: [],
                    });
                },

                removeRoom(index) {
                    if (this.form.rooms.length <= 1) {
                        return;
                    }
                    this.form.rooms.splice(index, 1);
                },

                adjustGuestCount(index, type, delta) {
                    const room = this.form.rooms[index];
                    if (!room) {
                        return;
                    }

                    const limits = {
                        adults: { min: this.minAdultsPerRoom, max: this.maxGuestsPerRoom },
                        children: { min: 0, max: this.maxChildrenPerRoom },
                    };

                    const limit = limits[type];
                    if (!limit) {
                        return;
                    }

                    const currentValue = Number(room[type] || 0);
                    const current = Number.isFinite(currentValue) ? currentValue : 0;
                    let next = current + delta;
                    next = Math.max(limit.min, Math.min(limit.max, next));

                    const totalWithoutCurrent = this.totalGuests(room) - current;
                    const allowedForType = this.maxGuestsPerRoom - totalWithoutCurrent;
                    if (next > allowedForType) {
                        next = Math.max(limit.min, allowedForType);
                    }

                    if (next === current) {
                        if (delta > 0 && totalWithoutCurrent + current >= this.maxGuestsPerRoom) {
                            this.flashWarning('Capacidade máxima de 5 hóspedes por quarto.');
                        }
                        return;
                    }

                    room[type] = next;

                    if (type === 'children') {
                        this.syncChildren(room);
                    }
                },

                syncChildren(room) {
                    const count = Math.max(0, Math.min(this.maxChildrenPerRoom, Number(room.children || 0)));
                    room.children = count;
                    room.childrenAges = (room.childrenAges || []).slice(0, count);
                    while (room.childrenAges.length < count) {
                        room.childrenAges.push('');
                    }
                },

                totalGuests(room) {
                    return Number(room.adults || 0) + Number(room.children || 0);
                },

                guestSummary(room) {
                    const total = this.totalGuests(room);
                    const label = total === 1 ? 'hóspede' : 'hóspedes';
                    return `${total} ${label} neste quarto`;
                },

                couponSoldOut(coupon) {
                    return Number(coupon.percent_used || 0) >= 100;
                },

                selectCoupon(code) {
                    const coupon = this.coupons.find((item) => item.code === code);
                    if (!coupon || this.couponSoldOut(coupon)) {
                        return;
                    }
                    this.form.couponCode = code;
                    this.formError = '';
                },

                validateForm() {
                    const errors = [];

                    if (!this.form.checkIn || !this.form.checkOut) {
                        errors.push('Selecione o período da viagem.');
                    }

                    if (this.form.checkIn) {
                        const checkInDate = this.parseDate(this.form.checkIn);
                        const min = this.parseDate(this.limits.minCheckIn);
                        const max = this.parseDate(this.limits.maxCheckIn);
                        if (checkInDate < min || checkInDate > max) {
                            errors.push('Check-in fora do período da promoção (02/01 a 20/12/<?=$bookingYear?>).');
                        }
                    }

                    if (this.form.checkOut) {
                        const checkInDate = this.parseDate(this.form.checkIn);
                        const checkOutDate = this.parseDate(this.form.checkOut);
                        const maxCheckout = this.parseDate(this.limits.maxCheckOut);
                        if (checkOutDate <= checkInDate) {
                            errors.push('O check-out deve ser após o check-in.');
                        }
                        if (checkOutDate > maxCheckout) {
                            errors.push('Check-out ultrapassa o limite de datas disponíveis.');
                        }
                    }

                    this.form.rooms.forEach((room, index) => {
                        const total = this.totalGuests(room);
                        if (Number(room.adults || 0) < this.minAdultsPerRoom) {
                            errors.push(`Quarto ${index + 1}: informe pelo menos 1 adulto.`);
                        }
                        if (total === 0) {
                            errors.push(`Quarto ${index + 1}: adicione hóspedes.`);
                        }
                        if (total > this.maxGuestsPerRoom) {
                            errors.push(`Quarto ${index + 1}: máximo de ${this.maxGuestsPerRoom} hóspedes por quarto.`);
                        }
                        if (Number(room.children || 0) > this.maxChildrenPerRoom) {
                            errors.push(`Quarto ${index + 1}: máximo de ${this.maxChildrenPerRoom} crianças por quarto.`);
                        }
                        if (room.children > 0) {
                            if (room.childrenAges.length < room.children || room.childrenAges.some((age) => age === '')) {
                                errors.push(`Quarto ${index + 1}: informe a idade de cada criança.`);
                            }
                        }
                    });

                    const coupon = this.coupons.find((item) => item.code === this.form.couponCode);
                    if (!coupon) {
                        errors.push('Escolha um cupom para aplicar o desconto.');
                    } else if (this.couponSoldOut(coupon)) {
                        errors.push('O cupom selecionado está esgotado. Selecione outro.');
                    }

                    this.formError = errors[0] || '';
                    return errors.length === 0;
                },

                submitReservation() {
                    this.formError = '';
                    this.formWarning = '';

                    if (!this.validateForm()) {
                        return;
                    }

                    const adultsList = [];
                    const childrenList = [];
                    const agesList = [];

                    this.form.rooms.forEach((room, index) => {
                        const rawAdults = Number(room.adults);
                        const rawChildren = Number(room.children);

                        const adults = Math.max(1, Math.min(5, Number.isFinite(rawAdults) ? rawAdults : 1));
                        const children = Math.max(0, Math.min(5, Number.isFinite(rawChildren) ? rawChildren : 0));
                        const ages = (room.childrenAges || [])
                            .slice(0, children)
                            .map((age) => {
                                const numeric = Number(age);
                                return Number.isFinite(numeric) ? numeric : 0;
                            });

                        while (ages.length < children) {
                            ages.push(0);
                        }

                        // atualiza o estado para refletir o que vai ser enviado
                        this.form.rooms[index].adults = adults;
                        this.form.rooms[index].children = children;
                        this.form.rooms[index].childrenAges = ages;

                        adultsList.push(adults);
                        childrenList.push(children);
                        agesList.push(children ? ages.join(';') : '');
                    });

                    const setRooms = this.form.rooms.length;
                    const adults = adultsList.join(',');
                    const children = childrenList.join(',');
                    const childrenAges = agesList.join(',');

                    const url = this.buildTargetUrl({
                        checkIn: this.form.checkIn,
                        checkOut: this.form.checkOut,
                        rooms: setRooms,
                        adults,
                        children,
                        childrenAges,
                        coupon: this.form.couponCode,
                    });

                    const paramsToTrack = {
                        hotel_id: this.hotel.id,
                        hotel_code: this.hotel.code,
                        check_in: this.form.checkIn.replace(/[^0-9]/g, ''),
                        check_out: this.form.checkOut.replace(/[^0-9]/g, ''),
                        rooms: setRooms,
                        num_adults: adultsList.reduce((a, b) => a + b, 0),
                        num_children: childrenList.reduce((a, b) => a + b, 0),
                        destination: 'Foz do Iguaçu, Paraná', // Fixo conforme solicitado
                        region: 'Foz do Iguaçu, PR',        // Fixo conforme solicitado
                        city: 'Foz do Iguaçu',
                        country: 'Brazil'
                    }
                    INKTRACK.track('BuscaData', paramsToTrack);
                    INKTRACK.track('Search', paramsToTrack);
                    INKTRACK.track('ViewContent', paramsToTrack);

                    window.location.href = url;
                },

                buildTargetUrl(data) {
                    const checkIn = this.formatDateParam(data.checkIn);
                    const checkOut = this.formatDateParam(data.checkOut);

                    return `https://book.omnibees.com/hotelresults?c=${this.hotel.code}&q=${this.hotel.id}&currencyId=16&lang=pt-BR&version=4&mob-curr=16` +
                        `&NRooms=${data.rooms}` +
                        `&CheckIn=${checkIn}` +
                        `&CheckOut=${checkOut}` +
                        `&Code=${encodeURIComponent(data.coupon || '')}` +
                        `&ad=${data.adults}` +
                        `&ch=${data.children}` +
                        `&ag=${data.childrenAges}`;
                },

                parseDate(dateStr) {
                    return new Date(`${dateStr}T00:00:00`);
                },

                formatDateInput(dateObj) {
                    const year = dateObj.getFullYear();
                    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const day = String(dateObj.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                },

                formatDateParam(dateStr) {
                    if (!dateStr || dateStr.length < 10) {
                        return '';
                    }
                    const day = dateStr.slice(8, 10);
                    const month = dateStr.slice(5, 7);
                    const year = dateStr.slice(0, 4);
                    return `${day}${month}${year}`;
                },

                startToastLoop() {
                    if (this.toastLoopStarted || !this.subscribers.length) {
                        return;
                    }
                    this.toastLoopStarted = true;

                    const schedule = () => {
                        // intervalo entre 4s and 19s
                        const delay = 3000 + Math.random() * 15000;
                        setTimeout(() => {
                            const name = this.subscribers[Math.floor(Math.random() * this.subscribers.length)];
                            this.pushToast(name);
                            schedule();
                        }, delay);
                    };

                    schedule();
                },

                pushToast(name) {
                    const toast = {
                        id: generateUid(),
                        message: `${name} garantiu reserva com desconto da Black! 😎`,
                    };

                    this.toasts.push(toast);

                    // intervalo entre 5s and 6s
                    const delayNext = 5200 + Math.random() * 800;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter((item) => item.id !== toast.id);
                    }, delayNext);
                },
            };
        }
    </script>
</div>

<?php get_footer(); ?>
