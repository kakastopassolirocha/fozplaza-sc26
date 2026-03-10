<?php
if (!defined('ABSPATH')) {
    exit;
}

$is_embedded = !empty($embedded);
$post_count = is_array($posts) ? count($posts) : 0;
$slider_id = 'foz-ig-slider-' . wp_unique_id();
?>

<?php
$has_title = !empty($title);
$has_description = !empty($description);
$has_follow = !empty($follow_label);
$has_fade = !empty($fade_edges);
?>

<?php if (!empty($posts)): ?>
<section class="foz-ig-fullwidth relative z-20 bg-white <?= $is_embedded ? '' : 'my-0' ?> <?= $has_follow ? 'pb-8 sm:pb-10 md:pb-12' : '' ?>">

    <!-- Setas de navegacao - topo direito, dentro do container -->
    <div class="pointer-events-none absolute inset-x-0 top-3 z-30 mx-auto w-full max-w-285 px-4 sm:top-4 sm:px-5 md:top-5">
        <div class="pointer-events-auto flex items-center justify-start pl-8 gap-2 [&_button]:cursor-pointer">
            <button type="button" class="js-ig-prev flex size-9 sm:size-16 items-center justify-center rounded-full border border-white/40 bg-white/80 text-primary-700 shadow-md backdrop-blur-sm transition-all hover:bg-primary-500 hover:text-white! hover:border-primary-500" aria-label="Anterior">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <button type="button" class="js-ig-next flex size-9 sm:size-16 items-center justify-center rounded-full border border-white/40 bg-white/80 text-primary-700 shadow-md backdrop-blur-sm transition-all hover:bg-primary-500 hover:text-white! hover:border-primary-500" aria-label="Próximo">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Fade edges -->
    <?php if ($has_fade): ?>
    <div class="pointer-events-none absolute inset-y-0 left-0 z-20 w-16 sm:w-20 md:w-32 bg-gradient-to-r from-white via-white/50 via-transparent to-transparent"></div>
    <div class="pointer-events-none absolute inset-y-0 right-0 z-20 w-16 sm:w-20 md:w-32 bg-gradient-to-l from-white via-white/50 via-transparent to-transparent"></div>
    <?php endif; ?>

    <!-- Swiper Carousel full-width -->
    <div id="<?= esc_attr($slider_id); ?>" class="swiper foz-ig-swiper w-full">
        <div class="swiper-wrapper">
            <?php foreach ($posts as $index => $post): ?>
            <div class="swiper-slide" style="width:auto;">
                <a href="<?= esc_url($post['permalink']); ?>" target="_blank" rel="noopener noreferrer"
                   class="group block overflow-hidden"
                   aria-label="<?= esc_attr(!empty($post['caption']) ? wp_trim_words($post['caption'], 8, '') : 'Ver no Instagram'); ?>">
                    <div class="relative h-[360px] w-[270px] overflow-hidden bg-zinc-100 sm:h-[420px] sm:w-[315px] md:h-[480px] md:w-[360px]">
                        <img src="<?= esc_url($post['image_url']); ?>"
                             alt="<?= esc_attr(!empty($post['caption']) ? wp_trim_words($post['caption'], 8, '') : 'Post do Instagram'); ?>"
                             loading="<?= $index < 6 ? 'eager' : 'lazy'; ?>"
                             class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">

                        <!-- Overlay hover -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 bg-gray-900/60 opacity-0 transition-all duration-500 group-hover:opacity-100">
                            <i class="fab fa-instagram text-3xl text-white drop-shadow-sm"></i>
                            <?php if (!empty($post['caption'])): ?>
                            <p class="mx-5 max-h-[4em] overflow-hidden text-center text-[12px] leading-[1.5] text-white/90">
                                <?= esc_html(wp_trim_words($post['caption'], 12, '...')); ?>
                            </p>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($post['media_type']) && $post['media_type'] === 'VIDEO'): ?>
                        <span class="absolute left-3 top-3 z-10 text-white/80 drop-shadow-md">
                            <i class="fas fa-play text-sm"></i>
                        </span>
                        <?php elseif (!empty($post['media_type']) && $post['media_type'] === 'CAROUSEL_ALBUM'): ?>
                        <span class="absolute right-3 top-3 z-10 text-white/80 drop-shadow-md">
                            <i class="fas fa-clone text-sm"></i>
                        </span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Botao follow - inferior direito, sobrepondo o carrossel -->
    <?php if ($has_follow): ?>
    <div class="pointer-events-none relative z-30 mx-auto -mt-7 w-full max-w-285 px-4 sm:-mt-9 sm:px-5 md:-mt-10">
        <div class="pointer-events-auto flex items-center justify-end gap-4">
            <a href="<?= esc_url($profile_url); ?>" target="_blank" rel="noopener noreferrer"
               class="group inline-flex items-center gap-2 bg-[#005469] px-4 py-2.5 font-family-fozplaza text-base font-semibold text-white shadow-lg transition-all duration-300 hover:bg-[#004c5f] sm:gap-3 sm:px-5 sm:py-3 sm:text-xl md:px-6 md:py-3 md:text-2xl">
                <span class="inline-flex items-center justify-center gap-2">
                    <i class="fab fa-instagram text-xl text-white drop-shadow-sm sm:text-2xl md:text-3xl"></i>
                    <?= esc_html($follow_label); ?>
                </span>
                <span class="transition-transform duration-300 group-hover:translate-x-0.5" aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Titulo e descricao - so aparece se informado -->
    <?php if ($has_title || $has_description): ?>
    <div class="mx-auto mt-6 flex max-w-285 flex-wrap items-center gap-3.5 px-5 sm:mt-8">
        <span class="flex size-11 shrink-0 items-center justify-center rounded-full text-xl text-white shadow-[0_4px_14px_rgba(214,36,159,.3)]"
              style="background:radial-gradient(circle at 30% 107%,#fdf497 0%,#fdf497 5%,#fd5949 45%,#d6249f 60%,#285AEB 90%)">
            <i class="fab fa-instagram"></i>
        </span>
        <div class="flex flex-col">
            <?php if ($has_title): ?>
            <span class="font-family-fozplaza text-[15px] font-semibold tracking-wide text-primary-800">
                <?= esc_html($title); ?>
            </span>
            <?php endif; ?>
            <?php if ($has_description): ?>
            <span class="text-[12px] leading-snug text-zinc-400">
                <?= esc_html($description); ?>
            </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('<?= esc_js($slider_id); ?>');
        if (!container || typeof Swiper === 'undefined') return;

        var section = container.closest('.foz-ig-fullwidth');
        var prevBtn = section ? section.querySelector('.js-ig-prev') : null;
        var nextBtn = section ? section.querySelector('.js-ig-next') : null;

        new Swiper(container, {
            slidesPerView: 'auto',
            spaceBetween: 4,
            loop: true,
            speed: 600,
            grabCursor: true,
            navigation: {
                prevEl: prevBtn,
                nextEl: nextBtn
            },
            breakpoints: {
                640: { spaceBetween: 6 },
                1024: { spaceBetween: 8 }
            }
        });
    });
    </script>
</section>

<?php else: ?>
<section class="foz-ig-fullwidth relative z-20 bg-white <?= $is_embedded ? '' : 'py-16' ?>">
    <div class="mx-auto max-w-xl px-5">
        <div class="flex flex-col items-center justify-center gap-4 rounded-lg border border-dashed border-zinc-300 p-12 text-center">
            <i class="fab fa-instagram text-4xl text-zinc-400"></i>
            <p class="text-sm text-zinc-500"><?= esc_html($empty_message); ?></p>
            <a href="<?= esc_url($profile_url); ?>" target="_blank" rel="noopener noreferrer"
               class="group inline-flex items-center gap-2 bg-transparent px-5 py-2 font-family-fozplaza text-[13px] font-semibold text-primary-500 ring-1 ring-primary-500 transition-all duration-300 hover:bg-primary-500 hover:text-white">
                <?= esc_html($follow_label); ?>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($show_debug)): ?>
<?php
    $debug_status = isset($debug_context['status']) ? (string) $debug_context['status'] : 'info';
    $debug_message = isset($debug_context['message']) ? (string) $debug_context['message'] : '';
    $debug_details = (isset($debug_context['details']) && is_array($debug_context['details'])) ? $debug_context['details'] : array();
    $debug_timestamp = isset($debug_context['timestamp']) ? (string) $debug_context['timestamp'] : '';

    $debug_colors = array(
        'success' => 'border-emerald-300 bg-emerald-50 text-emerald-900',
        'warning' => 'border-amber-300 bg-amber-50 text-amber-900',
        'error' => 'border-rose-300 bg-rose-50 text-rose-900',
        'info' => 'border-sky-300 bg-sky-50 text-sky-900',
    );
    $debug_class = isset($debug_colors[$debug_status]) ? $debug_colors[$debug_status] : $debug_colors['info'];
?>
<div class="mx-auto mt-4 max-w-5xl px-5">
    <div class="rounded-md border p-4 text-left text-sm leading-relaxed <?= esc_attr($debug_class); ?>">
        <p class="font-semibold uppercase tracking-wide">Instagram Debug (super_admin)</p>
        <p class="mt-1"><strong>Status:</strong> <?= esc_html($debug_status); ?></p>
        <?php if ($debug_message !== ''): ?>
        <p><strong>Mensagem:</strong> <?= esc_html($debug_message); ?></p>
        <?php endif; ?>
        <?php if ($debug_timestamp !== ''): ?>
        <p><strong>Horario:</strong> <?= esc_html($debug_timestamp); ?></p>
        <?php endif; ?>
        <?php if (!empty($debug_details)): ?>
        <pre class="mt-3 overflow-auto rounded border border-current/20 bg-black/5 p-3 text-xs"><?= esc_html(wp_json_encode($debug_details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
        <?php endif; ?>
        <p class="mt-2 text-xs opacity-80">Controle em WP-Admin: menu <code>Instagram Widget</code> (switch "Debug visual").</p>
    </div>
</div>
<?php endif; ?>
