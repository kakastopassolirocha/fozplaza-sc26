<?php
if (! defined('ABSPATH')) {
	exit;
}

$lead_form_config = array_merge([
	'title' => __('Quero participar da Black Friday', 'fozplaza-black25'),
	'subtitle' => __('Cadastre-se para concorrer aos prêmios e receber os cupons exclusivos.', 'fozplaza-black25'),
	'cta_label' => __('Quero me cadastrar', 'fozplaza-black25'),
	'success_title' => __('Cadastro concluído!', 'fozplaza-black25'),
	'success_message' => __('Você já está participando. Vamos te redirecionar para a sua área exclusiva.', 'fozplaza-black25'),
], $lead_form_config ?? []);

$lead_registration_config = array_merge([
	'ajaxUrl' => admin_url('admin-ajax.php'),
	'nonce' => wp_create_nonce('lead_registration'),
	'successRedirect' => home_url('/minha-area'),
], $lead_registration_config ?? []);

$default_phone_options = $lead_phone_options ?? $phone_country_options ?? [
	[ 'code' => 'BR', 'name' => 'Brasil', 'dial_code' => '+55', 'flag' => '🇧🇷' ],
	[ 'code' => 'AR', 'name' => 'Argentina', 'dial_code' => '+54', 'flag' => '🇦🇷' ],
	[ 'code' => 'BO', 'name' => 'Bolívia', 'dial_code' => '+591', 'flag' => '🇧🇴' ],
	[ 'code' => 'CL', 'name' => 'Chile', 'dial_code' => '+56', 'flag' => '🇨🇱' ],
	[ 'code' => 'CO', 'name' => 'Colômbia', 'dial_code' => '+57', 'flag' => '🇨🇴' ],
	[ 'code' => 'CR', 'name' => 'Costa Rica', 'dial_code' => '+506', 'flag' => '🇨🇷' ],
	[ 'code' => 'CU', 'name' => 'Cuba', 'dial_code' => '+53', 'flag' => '🇨🇺' ],
	[ 'code' => 'EC', 'name' => 'Equador', 'dial_code' => '+593', 'flag' => '🇪🇨' ],
	[ 'code' => 'SV', 'name' => 'El Salvador', 'dial_code' => '+503', 'flag' => '🇸🇻' ],
	[ 'code' => 'GT', 'name' => 'Guatemala', 'dial_code' => '+502', 'flag' => '🇬🇹' ],
	[ 'code' => 'HN', 'name' => 'Honduras', 'dial_code' => '+504', 'flag' => '🇭🇳' ],
	[ 'code' => 'MX', 'name' => 'México', 'dial_code' => '+52', 'flag' => '🇲🇽' ],
	[ 'code' => 'NI', 'name' => 'Nicarágua', 'dial_code' => '+505', 'flag' => '🇳🇮' ],
	[ 'code' => 'PA', 'name' => 'Panamá', 'dial_code' => '+507', 'flag' => '🇵🇦' ],
	[ 'code' => 'PY', 'name' => 'Paraguai', 'dial_code' => '+595', 'flag' => '🇵🇾' ],
	[ 'code' => 'PE', 'name' => 'Peru', 'dial_code' => '+51', 'flag' => '🇵🇪' ],
	[ 'code' => 'UY', 'name' => 'Uruguai', 'dial_code' => '+598', 'flag' => '🇺🇾' ],
	[ 'code' => 'VE', 'name' => 'Venezuela', 'dial_code' => '+58', 'flag' => '🇻🇪' ],
];

$phone_options_formatted = array_map(
	static function ($option) {
		return [
			'code' => $option['code'],
			'name' => $option['name'],
			'dialCode' => $option['dial_code'],
			'flag' => $option['flag'],
		];
	},
	$default_phone_options
);

$registration_payload = [
	'ajaxUrl' => $lead_registration_config['ajaxUrl'] ?? '',
	'nonce' => $lead_registration_config['nonce'] ?? '',
	'successRedirect' => $lead_registration_config['successRedirect'] ?? home_url('/minha-area'),
	'originUrl' => esc_url_raw(home_url(add_query_arg([], $_SERVER['REQUEST_URI'] ?? ''))),
	'phoneCountries' => $phone_options_formatted,
	'copy' => [
		'successTitle' => $lead_form_config['success_title'],
		'successMessage' => $lead_form_config['success_message'],
	],
];
?>

<div
	class="bg-white/5 border border-white/10 rounded-3xl shadow-elevated p-6 md:p-8 space-y-6"
	x-data="leadRegistrationForm(<?php echo esc_attr(wp_json_encode($registration_payload)); ?>)"
	x-init="init()"
>
	<div class="space-y-2">
		<span class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.35em] text-foz-white/50">
			<?php esc_html_e('Novo por aqui?', 'fozplaza-black25'); ?>
		</span>
		<h2 class="heading-display text-2xl md:text-3xl text-foz-yellow">
			<?php echo esc_html($lead_form_config['title']); ?>
		</h2>
		<p class="text-sm md:text-base text-foz-white/70">
			<?php echo esc_html($lead_form_config['subtitle']); ?>
		</p>
	</div>

	<div x-show="status.success" class="rounded-2xl border border-foz-yellow/30 bg-foz-yellow/10 px-5 py-6 space-y-3" style="display: none;">
		<h3 class="text-xl font-semibold text-foz-yellow" x-text="copy.successTitle || 'Cadastro confirmado!'"></h3>
		<p class="text-sm text-foz-white/80" x-text="status.message || copy.successMessage || 'Você já está participando. Estamos redirecionando para sua área.'"></p>
		<button
			type="button"
			class="mt-2 inline-flex items-center gap-2 px-5 py-3 rounded-full bg-foz-yellow text-foz-black font-semibold hover:scale-105 transition-transform"
			@click="redirectToArea()"
		>
			<?php esc_html_e('Ir para minha área', 'fozplaza-black25'); ?>
			<svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
			</svg>
		</button>
	</div>

	<form @submit.prevent="submitRegistration()" class="space-y-5" x-show="!status.success" style="display: none;">
		<div x-show="errors.general" class="rounded-2xl border border-foz-orange/40 bg-foz-orange/10 px-4 py-3 text-sm text-foz-yellow" x-text="errors.general"></div>

		<div>
			<label class="block text-sm font-semibold mb-2 text-foz-yellow" for="lead-register-name">
				<?php esc_html_e('Nome completo', 'fozplaza-black25'); ?>
			</label>
			<input
				type="text"
				id="lead-register-name"
				x-model="formData.name"
				required
				class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
				placeholder="<?php esc_attr_e('Digite seu nome completo', 'fozplaza-black25'); ?>"
				:aria-invalid="errors.name ? 'true' : 'false'"
			>
			<p x-show="errors.name" x-text="errors.name" class="mt-2 text-sm text-foz-yellow"></p>
		</div>

		<div>
			<label class="block text-sm font-semibold mb-2 text-foz-yellow" for="lead-register-email">
				<?php esc_html_e('E-mail', 'fozplaza-black25'); ?>
			</label>
			<input
				type="email"
				id="lead-register-email"
				x-model="formData.email"
				required
				class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
				placeholder="seu@email.com"
				:aria-invalid="errors.email ? 'true' : 'false'"
			>
			<p x-show="errors.email" x-text="errors.email" class="mt-2 text-sm text-foz-yellow"></p>
		</div>

		<div>
			<label class="block text-sm font-semibold mb-2 text-foz-yellow">
				<?php esc_html_e('WhatsApp', 'fozplaza-black25'); ?>
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
						id="lead-register-whatsapp"
						x-model="formData.whatsapp"
						required
						inputmode="tel"
						class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
						:placeholder="currentCountry().code === 'BR' ? '(00) 00000-0000' : '<?php esc_attr_e('DDD + nmero', 'fozplaza-black25'); ?>'"
						:aria-invalid="errors.whatsapp ? 'true' : 'false'"
						:maxlength="currentCountry().code === 'BR' ? 15 : 20"
						x-mask:dynamic="currentCountry().code === 'BR' ? '(99) 99999-9999' : '99999999999999'"
					>
				</div>
			</div>
			<p x-show="errors.whatsapp" x-text="errors.whatsapp" class="mt-2 text-sm text-foz-yellow"></p>
		</div>

		<div class="bg-foz-black/30 p-4 rounded-lg border border-foz-yellow/20 space-y-3">
			<label for="lead-register-ranking" class="flex items-start gap-3 cursor-pointer">
				<input
					type="checkbox"
					id="lead-register-ranking"
					x-model="formData.participateInRanking"
					class="mt-1 size-5 text-foz-yellow bg-foz-black border-white/15 rounded focus:ring-foz-yellow focus:ring-2"
					checked
				>
				<span class="text-sm text-foz-white">
					<?php esc_html_e('Quero participar do ranking de prêmios (recomendado)', 'fozplaza-black25'); ?>
				</span>
			</label>

			<div x-show="formData.participateInRanking && currentCountry().code === 'BR'" x-transition>
				<label class="block text-sm font-semibold mb-2 text-foz-yellow" for="lead-register-cpf">
					<?php esc_html_e('CPF para validar no ranking', 'fozplaza-black25'); ?>
				</label>
				<input
					type="text"
					id="lead-register-cpf"
					x-model="formData.cpf"
					:required="formData.participateInRanking && currentCountry().code === 'BR'"
					class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-foz-white placeholder-foz-white/40 focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50"
					placeholder="000.000.000-00"
					maxlength="14"
					x-mask="999.999.999-99"
				>
				<p x-show="errors.cpf" x-text="errors.cpf" class="mt-2 text-sm text-foz-yellow"></p>
			</div>
		</div>

		<div class="bg-foz-black/30 p-4 rounded-lg border border-foz-orange/20">
			<label for="lead-register-communication" class="flex items-start gap-3 cursor-pointer">
				<input
					type="checkbox"
					id="lead-register-communication"
					x-model="formData.acceptCommunication"
					required
					class="mt-1 size-5 text-foz-yellow bg-foz-black border-white/15 rounded focus:ring-foz-yellow focus:ring-2"
				>
				<span class="text-sm text-foz-white">
					<?php esc_html_e('Quero receber os avisos da campanha (requerido para liberar os cupons).', 'fozplaza-black25'); ?>
				</span>
			</label>
		</div>

		<div class="space-y-3">
			<div x-show="ipWarning.open" class="rounded-2xl border border-foz-orange/40 bg-foz-orange/10 px-4 py-3 text-sm text-foz-yellow space-y-3">
				<p x-text="ipWarning.message || '<?php echo esc_js(__('Detectamos outro cadastro com este IP. Deseja continuar mesmo assim?', 'fozplaza-black25')); ?>'"></p>
				<div class="grid sm:grid-cols-2 gap-2">
					<button type="button" class="w-full px-4 py-2 rounded-full border border-white/20 text-foz-white hover:bg-white/10 transition" @click="cancelIpWarning()">
						<?php esc_html_e('Revisar dados', 'fozplaza-black25'); ?>
					</button>
					<button type="button" class="w-full px-4 py-2 rounded-full gradient-yellow text-foz-black font-semibold hover:scale-105 transition" @click="confirmIpWarning()">
						<?php esc_html_e('Continuar cadastro', 'fozplaza-black25'); ?>
					</button>
				</div>
			</div>

			<button
				type="submit"
				:disabled="status.loading"
				class="w-full gradient-yellow text-foz-black px-6 py-4 text-lg font-bold rounded-full shadow-glow hover:scale-105 transition-transform disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2"
			>
				<span x-text="status.loading ? '<?php echo esc_js(__('Enviando...', 'fozplaza-black25')); ?>' : '<?php echo esc_js($lead_form_config['cta_label']); ?>'"></span>
				<svg x-show="!status.loading" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
				</svg>
			</button>

			<p class="text-xs text-center text-foz-white/60">
				<?php esc_html_e('Ao enviar você aceita a política de privacidade e autoriza receber comunicações da campanha.', 'fozplaza-black25'); ?>
			</p>
		</div>
	</form>
</div>

<?php if (! defined('FOZPLAZA_LEAD_FORM_SCRIPT')) : ?>
	<?php define('FOZPLAZA_LEAD_FORM_SCRIPT', true); ?>
	<script>
		function leadRegistrationForm(config = {}) {
			const safeConfig = Object.assign({
				ajaxUrl: '',
				nonce: '',
				originUrl: '',
				successRedirect: '/minha-area',
				phoneCountries: [],
				copy: {
					successTitle: '',
					successMessage: ''
				}
			}, config || {});

			return {
				copy: safeConfig.copy || {},
				phoneCountryOptions: safeConfig.phoneCountries || [],
				dropdownOpen: false,
				formData: {
					name: '',
					email: '',
					whatsapp: '',
					dialCode: (safeConfig.phoneCountries && safeConfig.phoneCountries[0]?.dialCode) || '+55',
					cpf: '',
					participateInRanking: true,
					acceptCommunication: true
				},
				errors: {
					general: '',
					name: '',
					email: '',
					whatsapp: '',
					cpf: ''
				},
				status: {
					loading: false,
					success: false,
					message: '',
					redirectTimer: null
				},
				ipWarning: {
					open: false,
					message: ''
				},

				init() {
					if (!this.phoneCountryOptions.length) {
						this.phoneCountryOptions.push({ code: 'BR', name: 'Brasil', dialCode: '+55', flag: '🇧🇷' });
					}

					if (!this.formData.dialCode && this.phoneCountryOptions.length) {
						this.formData.dialCode = this.phoneCountryOptions[0].dialCode;
					}

					this.checkReferralFromUrl();

					window.addEventListener('beforeunload', () => this.cleanup(), { once: true });
				},

				currentCountry() {
					return this.phoneCountryOptions.find((country) => country.dialCode === this.formData.dialCode) || {
						code: 'BR',
						name: 'Brasil',
						dialCode: this.formData.dialCode || '+55',
						flag: '🇧🇷'
					};
				},

				selectCountry(country) {
					this.formData.dialCode = country.dialCode;
					this.dropdownOpen = false;
				},

				clearErrors() {
					this.errors.general = '';
					this.errors.name = '';
					this.errors.email = '';
					this.errors.whatsapp = '';
					this.errors.cpf = '';
				},

				validateForm() {
					let hasErrors = false;

					if (!this.formData.name || this.formData.name.trim().length < 3) {
						this.errors.name = 'Informe seu nome completo.';
						hasErrors = true;
					}

					const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
					if (!emailPattern.test((this.formData.email || '').trim())) {
						this.errors.email = 'Digite um e-mail válido.';
						hasErrors = true;
					}

					const phoneDigits = this.normalizeDigits(this.formData.whatsapp);
					if (phoneDigits.length < 8) {
						this.errors.whatsapp = 'Informe um número de WhatsApp válido.';
						hasErrors = true;
					}

					const cpfDigits = this.normalizeDigits(this.formData.cpf);
					if (this.formData.participateInRanking) {
						if (!this.validateCPF(cpfDigits)) {
							this.errors.cpf = 'Informe um CPF válido.';
							hasErrors = true;
						}
					} else if (cpfDigits && !this.validateCPF(cpfDigits)) {
						this.errors.cpf = 'Informe um CPF válido.';
						hasErrors = true;
					}

					if (!this.formData.acceptCommunication) {
						this.errors.general = 'É necessário aceitar os avisos para participar.';
						hasErrors = true;
					}

					if (hasErrors && !this.errors.general) {
						this.errors.general = 'Corrija os campos destacados para continuar.';
					}

					return !hasErrors;
				},

				submitRegistration(forceIp = false) {
					if (this.status.loading) {
						return;
					}

					this.ipWarning.open = false;
					this.ipWarning.message = '';
					this.clearErrors();

					if (!this.validateForm()) {
						return;
					}

					if (!safeConfig.ajaxUrl) {
						this.errors.general = 'Serviço temporariamente indisponível. Atualize a página e tente novamente.';
						return;
					}

					this.sendRequest(forceIp);
				},

				async sendRequest(forceIp) {
					const payload = this.buildPayload(forceIp);
					this.status.loading = true;

					try {
						const response = await fetch(safeConfig.ajaxUrl, {
							method: 'POST',
							credentials: 'same-origin',
							body: payload
						});

						let result = null;

						try {
							result = await response.json();
						} catch (parseError) {
							console.error('lead registration parse error', parseError);
							this.errors.general = 'Não foi possível interpretar a resposta. Tente novamente em instantes.';
							return;
						}

						if (!response.ok || !result?.success) {
							const data = result?.data || {};

							if (data.status === 'ip_warning') {
								if (forceIp) {
									this.handleServerError({
										field: 'general',
										message: data.message || 'Não foi possível concluir o cadastro com este IP.'
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
						console.error('lead registration error', error);
						this.errors.general = 'Não foi possível concluir o cadastro. Verifique sua conexão e tente novamente.';
					} finally {
						this.status.loading = false;
					}
				},

				buildPayload(forceIp) {
					const payload = new FormData();
					payload.append('action', 'register_lead');

					if (safeConfig.nonce) {
						payload.append('nonce', safeConfig.nonce);
					}

					payload.append('name', (this.formData.name || '').trim());
					payload.append('email', (this.formData.email || '').trim());
					payload.append('whatsapp_dial_code', this.normalizeDigits(this.formData.dialCode || ''));
					payload.append('whatsapp_number', this.normalizeDigits(this.formData.whatsapp || ''));
					payload.append('participate_in_ranking', this.formData.participateInRanking ? '1' : '');
					payload.append('accept_communication', this.formData.acceptCommunication ? '1' : '');
					payload.append('cpf', this.normalizeDigits(this.formData.cpf || ''));
					payload.append('origin_url', safeConfig.originUrl || window.location.href);

					const referralCode = this.getStoredReferralCode();
					if (referralCode) {
						payload.append('referral_code', referralCode);
					}

					if (forceIp) {
						payload.append('force_ip', '1');
					}

					return payload;
				},

				handleServerError(data) {
					const field = data.field || 'general';
					const message = data.message || 'Não foi possível concluir o cadastro. Tente novamente.';

					if (Object.prototype.hasOwnProperty.call(this.errors, field)) {
						this.errors[field] = message;
					} else {
						this.errors.general = message;
					}

					if (field !== 'general' && !this.errors.general) {
						this.errors.general = 'Corrija os campos destacados para continuar.';
					}
				},

				showIpWarning(message) {
					this.ipWarning.message = message || 'Detectamos outro cadastro com este IP. Deseja continuar mesmo assim?';
					this.ipWarning.open = true;
					this.errors.general = '';
				},

				confirmIpWarning() {
					this.ipWarning.open = false;
					this.ipWarning.message = '';
					this.submitRegistration(true);
				},

				cancelIpWarning() {
					this.ipWarning.open = false;
					this.ipWarning.message = '';
					this.errors.general = 'Cadastro não enviado. Revise os dados antes de continuar.';
				},

				handleSuccess(data) {
					this.status.success = true;
					this.status.message = (data && data.message) || this.copy.successMessage || 'Cadastro realizado com sucesso!';
					this.clearErrors();

					if (data?.user?.ref_id) {
						this.storeReferralCode(data.user.ref_id);
					}

					const target = safeConfig.successRedirect || '';
					if (target) {
						this.status.redirectTimer = setTimeout(() => {
							this.redirectToArea();
						}, 1400);
					}
				},

				redirectToArea() {
					this.cleanup();
					const target = safeConfig.successRedirect || '';
					if (target) {
						window.location.href = target;
					}
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

				normalizeDigits(value) {
					return (value || '').toString().replace(/[^0-9]/g, '');
				},

				getStoredReferralCode() {
					try {
						return localStorage.getItem('referral_code') || '';
					} catch (storageError) {
						console.error('referral storage read error', storageError);
						return '';
					}
				},

				storeReferralCode(value) {
					try {
						if (value) {
							localStorage.setItem('referral_code', value);
						}
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

				async validateReferralCode(refId, registerVisit) {
					if (!safeConfig.ajaxUrl || !refId) {
						return;
					}

					const payload = new FormData();
					payload.append('action', 'validate_referral');

					if (safeConfig.nonce) {
						payload.append('nonce', safeConfig.nonce);
					}

					payload.append('ref_id', refId);

					if (registerVisit) {
						payload.append('register_visit', '1');
					}

					try {
						const response = await fetch(safeConfig.ajaxUrl, {
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
							console.error('referral visit storage error', storageError);
						}
					} catch (error) {
						console.error('referral validate request error', error);
					}
				},

				checkReferralFromUrl() {
					let ref = '';

					try {
						const params = new URLSearchParams(window.location.search);
						ref = params.get('ref') || '';
					} catch (error) {
						ref = '';
					}

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

				cleanup() {
					if (this.status.redirectTimer) {
						clearTimeout(this.status.redirectTimer);
						this.status.redirectTimer = null;
					}
				}
			};
		}
	</script>
<?php endif; ?>
