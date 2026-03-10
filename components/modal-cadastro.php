<!-- Registration Modal -->
<div 
    x-show="showRegistrationModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-120 overflow-y-auto"
    style="display: none;"
    @keydown.escape.window="closeRegistrationModal()">
    <div class="flex items-center justify-center min-h-dvh px-4 py-8 md:py-12">
        <div 
            class="fixed inset-0 z-10 bg-foz-black/95 backdrop-blur-sm transition-opacity"
            @click="closeRegistrationModal()"
        ></div>

        <div class="relative z-20 bg-linear-to-br from-foz-black via-foz-dark-red to-foz-black rounded-2xl border border-white/10 shadow-glow max-w-3xl w-full p-6 md:p-10 max-h-[90dvh] overflow-y-auto">
            <button 
                type="button"
                @click="closeRegistrationModal()"
                class="absolute top-4 right-4 text-foz-white/60 hover:text-foz-white transition-colors"
                aria-label="Fechar modal de cadastro"
            >
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="text-center mb-6">
                <div class="text-sm uppercase tracking-[0.35em] text-foz-white/50 mb-2">Cadastro antecipado</div>
                <h2 class="heading-display text-3xl md:text-4xl text-foz-white mb-3">
                    Garanta seu <span class="text-foz-white/60">acesso exclusivo</span>
                </h2>
                <p class="text-sm md:text-base text-foz-white/60">
                    Cadastre-se agora e receba os <strong class="text-foz-yellow">cupons de desconto</strong> em primeira mão!
                </p>
            </div>

            <div class="bg-white/5 p-6 md:p-8 rounded-3xl border border-white/10 shadow-elevated">
                <form @submit.prevent="submitForm()" class="space-y-6">
                    <div 
                        x-show="formErrors.general"
                        x-transition
                        role="alert"
                        class="rounded-2xl border border-foz-orange/40 bg-foz-orange/10 px-4 py-3 text-sm text-foz-yellow"
                        x-text="formErrors.general"
                    ></div>
                    <div>
                        <label for="modal-name" class="block text-sm font-semibold mb-2 text-foz-yellow">
                            Nome completo *
                        </label>
                        <input 
                            type="text" 
                            id="modal-name" 
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

                    <div>
                        <label for="modal-email" class="block text-sm font-semibold mb-2 text-foz-yellow">
                            E-mail *
                        </label>
                        <input 
                            type="email" 
                            id="modal-email" 
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
                        <label for="modal-whatsapp-number" x-text="selectedPhoneCountry().code === 'BR' ? 'Número WhatsApp (DDD + Número) *' : 'Telefono WhatsApp *'" class="block text-sm font-semibold mb-2 text-foz-yellow">
                            
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div 
                                class="relative w-46"
                                @click.outside="phoneDropdown.modal = false"
                                @keydown.escape.window="phoneDropdown.modal = false"
                            >
                                <button
                                    type="button"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-left text-foz-white focus:outline-none focus:border-foz-yellow focus:ring-2 focus:ring-foz-yellow/50 flex items-center justify-between gap-3"
                                    @click="phoneDropdown.modal = !phoneDropdown.modal"
                                    :aria-expanded="phoneDropdown.modal"
                                    aria-haspopup="listbox"
                                >
                                    <span class="flex items-center gap-2">
                                        <span class="text-lg" x-text="selectedPhoneCountry().flag" aria-hidden="true"></span>
                                        <span class="text-sm" x-text="selectedPhoneCountry().name"></span>
                                    </span>
                                    <span class="text-sm text-foz-white/60" x-text="selectedPhoneCountry().dialCode"></span>
                                </button>

                                <div
                                    x-show="phoneDropdown.modal"
                                    x-transition
                                    class="absolute left-0 right-0 mt-2 bg-foz-black/95 border border-white/10 rounded-xl shadow-elevated max-h-48 w-60 overflow-y-auto z-30"
                                    role="listbox"
                                >
                                    <template x-for="country in phoneCountryOptions" :key="country.code">
                                        <button
                                            type="button"
                                            class="w-full px-4 py-2 flex items-center justify-between gap-3 text-sm hover:bg-white/10 focus:bg-white/10 text-foz-white"
                                            @click="formData.whatsappDialCode = country.dialCode; phoneDropdown.modal = false"
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
                                    id="modal-whatsapp-number" 
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
                    <!-- WhatsApp -->

                    <!-- Cpf -->
                    <div x-show="selectedPhoneCountry().code === 'BR'" x-transition class="bg-foz-black/30 p-4 rounded-lg border border-foz-yellow/20">
                        <label for="modal-ranking" class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="modal-ranking"
                                x-model="formData.participateInRanking"
                                class="mt-1 size-5 text-foz-yellow bg-foz-black border-white/15 rounded focus:ring-foz-yellow focus:ring-2"
                                checked
                            >
                            <span class="text-sm text-foz-white">
                                Aceito participar da dinâmica <strong class="text-foz-yellow">"Indique e Ganhe"</strong> e <strong class="text-foz-yellow">"Ranking de Prêmios"</strong> (recomendado - você ganha prêmios ao indicar amigos!)
                            </span>
                        </label>

                        <div x-show="formData.participateInRanking" x-transition class="mt-4">
                            <label for="modal-cpf" class="block text-sm font-semibold mb-2 text-foz-yellow">
                                CPF (necessário para o ranking) *
                            </label>
                            <input 
                                type="text" 
                                id="modal-cpf" 
                                x-model="formData.cpf"
                                :required="formData.participateInRanking && selectedPhoneCountry().code === 'BR'"
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
                    <!-- Cpf -->

                    <div class="bg-foz-black/30 p-4 rounded-lg border border-foz-orange/20">
                        <label for="modal-communication" class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="modal-communication"
                                x-model="formData.acceptCommunication"
                                required
                                class="mt-1 size-5 text-foz-yellow bg-foz-black border-white/15 rounded focus:ring-foz-yellow focus:ring-2"
                            >
                            <span class="text-sm text-foz-white">
                                Concordo em receber comunicações do <strong class="text-foz-yellow">Foz Plaza Hotel</strong> sobre a campanha da Black Friday 2025, incluindo a revelação dos cupons, avisos de abertura de vendas e ofertas exclusivas. *
                            </span>
                        </label>
                    </div>

                    <button 
                        type="submit"
                        :disabled="formStatus.isLoading"
                        :aria-busy="formStatus.isLoading"
                        class="w-full gradient-yellow text-foz-black px-8 py-5 text-xl md:text-2xl font-bold rounded-full shadow-glow hover:scale-105 transition-transform flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span x-text="formStatus.isLoading ? 'Enviando...' : 'CADASTRAR'"></span>
                        <svg x-show="!formStatus.isLoading" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>

                    <p class="text-xs text-center text-foz-white/60">
                        Ao preencher o formulário, você concorda com nossa Política de Privacidade e Termos de Uso.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>