<?php
$login_errors = $login_context['errors'] ?? [];
$login_values = $login_context['values'] ?? [];

$login_errors = array_merge([
	'general' => '',
	'email' => '',
	'whatsapp' => '',
], $login_errors);

$login_values = array_merge([
	'email' => '',
	'dial_code' => '+55',
	'whatsapp' => '',
], $login_values);

$phone_options_source = $phone_country_options ?? [];
$phone_options_formatted = array_map(
	static function ($option) {
		return [
			'code' => $option['code'],
			'name' => $option['name'],
			'dialCode' => $option['dial_code'],
			'flag' => $option['flag'],
		];
	},
	$phone_options_source
);

$selected_dial_code = $login_values['dial_code'] !== '' ? $login_values['dial_code'] : '+55';
$selected_dial_code = strpos($selected_dial_code, '+') === 0 ? $selected_dial_code : '+' . $selected_dial_code;
$whatsapp_value = $login_values['whatsapp'];

$lead_registration_config = $lead_registration_config ?? [
	'ajaxUrl' => admin_url('admin-ajax.php'),
	'nonce' => wp_create_nonce('lead_registration'),
	'successRedirect' => home_url('/minha-area'),
];

$lead_form_config = $lead_form_config ?? [
	'title' => __('Quero participar da Black Friday', 'fozplaza-black25'),
	'subtitle' => __('Cadastre-se para continuar na disputa do ranking e receber os cupons.', 'fozplaza-black25'),
	'cta_label' => __('Quero me cadastrar', 'fozplaza-black25'),
];
?>

<div class="min-h-screen bg-linear-to-br from-foz-black via-foz-dark-red/20 to-foz-black text-foz-white flex flex-col">
	<header class="border-b border-foz-white/10 bg-foz-black/70 backdrop-blur">
		<div class="container mx-auto px-4 py-4 flex items-center justify-between gap-4">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center gap-2 text-xs md:text-sm uppercase tracking-[0.25em] text-foz-white/70 hover:text-foz-yellow transition-colors">
				<svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
				<?php esc_html_e('Voltar', 'fozplaza-black25'); ?>
			</a>

			<img src="<?=THEME_PUBLIC?>logo_foz-plaza-hotel_horizontal-minimal.svg" alt="Foz Plaza Hotel" class="h-7 w-auto">

			<a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center gap-2 text-xs md:text-sm uppercase tracking-[0.25em] text-foz-white/70 hover:text-foz-yellow transition-colors">
				<?php esc_html_e('Site da Campanha', 'fozplaza-black25'); ?>
			</a>
		</div>
	</header>

	<main class="flex-1">
		<section class="py-16 md:py-24 px-4">
			<div class="container mx-auto max-w-6xl">
				<div class="text-center mb-10 space-y-3">
					<span class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.35em] text-foz-white/50">
						<?php esc_html_e('Área do participante', 'fozplaza-black25'); ?>
					</span>
					<h1 class="heading-display text-3xl md:text-4xl text-foz-yellow">
						<?php esc_html_e('Acesse ou ative sua participação', 'fozplaza-black25'); ?>
					</h1>
					<p class="text-sm md:text-base text-foz-white/70 max-w-3xl mx-auto">
						<?php esc_html_e('Entre com seus dados ou faça um novo cadastro para continuar disputando o ranking e liberar os descontos.', 'fozplaza-black25'); ?>
					</p>
				</div>

				<div class="grid gap-6 lg:grid-cols-2 items-start">
					<div class="bg-white/5 border border-white/10 rounded-3xl shadow-elevated p-8 md:p-10" x-data="minhaAreaLoginForm()" x-init="init()">
						<div class="mb-6 space-y-2">
							<span class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.35em] text-foz-white/50">
								<?php esc_html_e('Já tenho cadastro', 'fozplaza-black25'); ?>
							</span>
							<h2 class="heading-display text-2xl md:text-3xl text-foz-yellow">
								<?php esc_html_e('Entrar na minha área', 'fozplaza-black25'); ?>
							</h2>
							<p class="text-sm md:text-base text-foz-white/70">
								<?php esc_html_e('Use o e-mail e o WhatsApp cadastrados para acessar seus indicadores e posição no ranking.', 'fozplaza-black25'); ?>
							</p>
						</div>

						<form method="post" class="space-y-6">
							<?php wp_nonce_field('minha_area_login', 'minha_area_nonce'); ?>
							<input type="hidden" name="minha_area_login" value="1">
							<input type="hidden" name="login_whatsapp_dial_code" value="<?php echo esc_attr($selected_dial_code); ?>" :value="selectedDialCode">

							<?php if ($login_errors['general'] !== '') : ?>
								<div class="rounded-2xl border border-foz-orange/40 bg-foz-orange/10 px-4 py-3 text-sm text-foz-yellow">
									<?php echo esc_html($login_errors['general']); ?>
								</div>
							<?php endif; ?>

							<div>
								<label for="minha-area-login-email" class="block text-sm font-semibold mb-2 text-foz-yellow">
									<?php esc_html_e('E-mail cadastrado', 'fozplaza-black25'); ?>
								</label>
								<input
									type="email"
									id="minha-area-login-email"
									name="login_email"
									value="<?php echo esc_attr($login_values['email']); ?>"
									required
									class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
									placeholder="email@exemplo.com"
								>
								<?php if ($login_errors['email'] !== '') : ?>
									<p class="mt-2 text-sm text-foz-yellow"><?php echo esc_html($login_errors['email']); ?></p>
								<?php endif; ?>
							</div>

							<div>
								<label class="block text-sm font-semibold mb-2 text-foz-yellow">
									<?php esc_html_e('WhatsApp cadastrado', 'fozplaza-black25'); ?>
								</label>
								<div class="flex flex-col sm:flex-row gap-3">
									<div class="relative sm:w-60" @click.outside="dropdownOpen = false" @keydown.escape.window="dropdownOpen = false">
										<button
											type="button"
											class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-left text-foz-white focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50 flex items-center justify-between gap-3"
											@click="dropdownOpen = !dropdownOpen"
											:aria-expanded="dropdownOpen"
											aria-haspopup="listbox"
										>
											<span class="flex items-center gap-2">
												<span class="text-lg" x-text="currentCountry().flag" aria-hidden="true"></span>
												<span class="text-sm" x-text="currentCountry().name"></span>
											</span>
											<span class="text-sm text-foz-white/60" x-text="currentCountry().dialCode"></span>
										</button>

										<div
											x-show="dropdownOpen"
											x-transition
											class="absolute left-0 right-0 mt-2 bg-foz-black/95 border border-white/10 rounded-xl shadow-elevated max-h-64 overflow-y-auto z-30"
											role="listbox"
											style="display: none;"
										>
											<template x-for="country in phoneCountryOptions" :key="country.code">
												<button
													type="button"
													class="w-full flex items-center justify-between px-4 py-2 text-sm text-foz-white/80 hover:bg-foz-yellow/10 hover:text-foz-white"
													@click="selectCountry(country)"
													role="option"
												>
													<span class="flex items-center gap-2">
														<span class="text-base" x-text="country.flag" aria-hidden="true"></span>
														<span x-text="country.name"></span>
													</span>
													<span x-text="country.dialCode"></span>
												</button>
											</template>
										</div>
									</div>
									<div class="flex-1">
										<input
											type="tel"
											id="minha-area-login-whatsapp"
											name="login_whatsapp_number"
											x-model="whatsappNumber"
											value="<?php echo esc_attr($whatsapp_value); ?>"
											required
											inputmode="tel"
											class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
											placeholder="<?php esc_attr_e('Número com DDD', 'fozplaza-black25'); ?>"
										>
									</div>
								</div>

								<?php if ($login_errors['whatsapp'] !== '') : ?>
									<p class="mt-2 text-sm text-foz-yellow"><?php echo esc_html($login_errors['whatsapp']); ?></p>
								<?php endif; ?>
							</div>

							<div class="pt-2">
								<button
									type="submit"
									class="w-full gradient-yellow text-foz-black px-8 py-4 text-lg font-bold rounded-full shadow-glow hover:scale-105 transition-transform"
								>
									<?php esc_html_e('Entrar na minha área', 'fozplaza-black25'); ?>
								</button>
							</div>

							<p class="text-xs text-center text-foz-white/50">
								<?php esc_html_e('Em caso de dúvidas, entre em contato com nossa equipe pelo WhatsApp oficial do Foz Plaza Hotel.', 'fozplaza-black25'); ?>
							</p>
						</form>
					</div>

					<?php require locate_template('components/lead-registration-card.php'); ?>
				</div>
			</div>
		</section>
	</main>
</div>

<script>
	function minhaAreaLoginForm() {
		return {
			phoneCountryOptions: <?php echo wp_json_encode($phone_options_formatted); ?>,
			dropdownOpen: false,
			selectedDialCode: '<?php echo esc_js($selected_dial_code); ?>',
			whatsappNumber: '<?php echo esc_js($whatsapp_value); ?>',
			init() {
				if (!this.phoneCountryOptions.length) {
					this.phoneCountryOptions.push({ code: 'BR', name: 'Brasil', dialCode: '+55', flag: '🇧🇷' });
				}
				if (!this.selectedDialCode) {
					this.selectedDialCode = this.phoneCountryOptions[0].dialCode;
				}
			},
			currentCountry() {
				return this.phoneCountryOptions.find(option => option.dialCode === this.selectedDialCode) || { flag: '🏳', name: '<?php echo esc_js(__('Selecione o DDI', 'fozplaza-black25')); ?>', dialCode: this.selectedDialCode || '+55' };
			},
			selectCountry(option) {
				this.selectedDialCode = option.dialCode;
				this.dropdownOpen = false;
			},
		};
	}
</script>
