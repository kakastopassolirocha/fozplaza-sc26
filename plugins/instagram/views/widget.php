<?php
if (!defined('ABSPATH')) {
    exit;
}

$is_embedded = !empty($embedded);
?>
<?php if ($is_embedded): ?>
<div class="mt-12 border-t border-zinc-200 pt-14" style="grid-column:1 / -1;">
<?php else: ?>
<article class="relative z-51 my-0! bg-white pb-28 pt-16">
    <div class="wrapper container-screen padding-x">
<?php endif; ?>

        <!-- Header -->
        <!-- <div class="flex mb-7 flex-wrap items-center justify-between gap-6"> -->
        <div class="hidden mb-7 flex-wrap items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <span class="flex size-13 shrink-0 items-center justify-center rounded-full text-2xl text-white shadow-[0_4px_16px_rgba(214,36,159,.35)]"
                      style="background:radial-gradient(circle at 30% 107%,#fdf497 0%,#fdf497 5%,#fd5949 45%,#d6249f 60%,#285AEB 90%)">
                    <i class="fab fa-instagram"></i>
                </span>
                <div class="flex flex-col gap-0.5">
                    <h2 class="text-xl font-bold uppercase leading-none tracking-[.08em] text-primary-800">
                        <?= esc_html($title); ?>
                    </h2>
                    <p class="text-[13px] leading-snug text-zinc-500">
                        <?= esc_html($description); ?>
                    </p>
                </div>
            </div>
            <a href="<?= esc_url($profile_url); ?>" target="_blank" rel="noopener noreferrer"
               class="btn-blue-square shrink-0 sm:w-55">
                <?= esc_html($follow_label); ?>
            </a>
        </div>

        <?php if (!empty($posts)): ?>
        <!-- Grade de fotos -->
        <div class="grid grid-cols-2 gap-0.75 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            <?php foreach ($posts as $post): ?>
            <a href="<?= esc_url($post['permalink']); ?>" target="_blank" rel="noopener noreferrer"
               class="group block overflow-hidden"
               aria-label="<?= esc_attr(!empty($post['caption']) ? wp_trim_words($post['caption'], 8, '') : 'Ver no Instagram'); ?>">
                <div class="relative aspect-square overflow-hidden bg-zinc-100">
                    <img src="<?= esc_url($post['image_url']); ?>"
                         alt="<?= esc_attr(!empty($post['caption']) ? wp_trim_words($post['caption'], 8, '') : 'Post do Instagram'); ?>"
                         loading="lazy"
                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.08]">
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 px-3.5 py-5 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                         style="background:linear-gradient(160deg,rgba(0,59,74,.82) 0%,rgba(0,84,105,.68) 100%)">
                        <i class="fab fa-instagram text-[28px] text-white/90"></i>
                        <?php if (!empty($post['caption'])): ?>
                        <p class="max-h-[4.5em] overflow-hidden text-center text-[12px] leading-[1.45] text-white">
                            <?= esc_html(wp_trim_words($post['caption'], 14, '...')); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($post['media_type']) && $post['media_type'] !== 'IMAGE'): ?>
                    <!-- <span class="absolute right-2.5 top-2.5 z-10 rounded bg-black/55 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white"> -->
                    <span class="hidden absolute right-2.5 top-2.5 z-10 rounded bg-black/55 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white">
                        <?= esc_html(fozplaza_instagram_media_label($post['media_type'])); ?>
                    </span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="flex flex-col items-center justify-center gap-4 rounded border border-dashed border-zinc-300 p-12 text-center">
            <i class="fab fa-instagram text-4xl text-zinc-400"></i>
            <p class="text-sm text-zinc-500"><?= esc_html($empty_message); ?></p>
            <a href="<?= esc_url($profile_url); ?>" target="_blank" rel="noopener noreferrer" class="btn-blue-square">
                <?= esc_html($follow_label); ?>
            </a>
        </div>
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
        <div class="mt-6 rounded-md border p-4 text-left text-sm leading-relaxed <?= esc_attr($debug_class); ?>">
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
        <?php endif; ?>

<?php if ($is_embedded): ?>
</div>
<?php else: ?>
    </div><!-- .container-screen -->
</article><!-- .instagram-home -->
<?php endif; ?>
