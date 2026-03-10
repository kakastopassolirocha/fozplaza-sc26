<?php
$leaderboard_config = [
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce'   => wp_create_nonce('lead_registration'),
];
?>

<script>
    window.blackFridayLead = window.blackFridayLead || {};
    window.blackFridayLead.ajaxUrl = window.blackFridayLead.ajaxUrl || <?php echo wp_json_encode($leaderboard_config['ajaxUrl']); ?>;
    window.blackFridayLead.nonce = window.blackFridayLead.nonce || <?php echo wp_json_encode($leaderboard_config['nonce']); ?>;
</script>

<div
    x-data='leaderboardWidget(<?php echo wp_json_encode($leaderboard_config); ?>)'
    x-init="init()"
>
    <div class="container mx-auto max-w-6xl">
        <div class="text-center mb-12">
            <img src="<?=THEME_PUBLIC?>selo-ranking.png" alt="Ranking de Prêmios" class="w-64 mx-auto mb-6 animate-float">
            <h2 class="heading-display text-4xl md:text-6xl mb-4">
                <span class="text-gradient">RANKING DE PRÊMIOS</span>
            </h2>
            <p class="text-lg md:text-xl text-foz-white/80">
                Dispute com outros participantes e leve prêmios incríveis!
            </p>

            <a href="#cadastro">
                <button
                    type="button"
                    class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-foz-yellow text-foz-black font-semibold hover:scale-105 transition-transform"
                >
                    Quero participar!
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </a>
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
                <span class="font-semibold text-foz-yellow">Regra de validade:</span> As premiações só são válidas a partir de 5 pontos.
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
        
        <div class="mt-12 text-center flex flex-col gap-4">
            <div class="inline-block bg-foz-orange/10 border border-foz-orange/30 rounded-xl p-6">
                <p class="text-foz-white/80 text-left text-xs md:text-sm">
                    ⚠️ <strong class="text-foz-orange">Importante:</strong><br><br> 
                    Todos os prêmios devem ser retirados <strong class="text-foz-yellow">presencialmente no hotel</strong>. O Foz Plaza Hotel não realiza envio de prêmios.
                    <br><br>As premiações no ranking são válidas a partir de 5 pontos!
                </p>
            </div>

            <a href="#cadastro">
                <button
                    type="button"
                    class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-foz-yellow text-foz-black font-semibold hover:scale-105 transition-transform"
                >
                    Quero participar do Ranking!
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </a>
        </div>
    </div>
</div>

<script>
    if (!window.leaderboardWidget) {
        window.leaderboardWidget = function(config = {}) {
            return {
                leaderboard: [],
                leaderboardStatus: {
                    isLoading: false,
                    error: ''
                },
                leaderboardTimer: null,
                ajaxConfig: {
                    ajaxUrl: config.ajaxUrl || '',
                    nonce: config.nonce || ''
                },

                syncAjaxConfig() {
                    const globalConfig = window.blackFridayLead || {};

                    if (!this.ajaxConfig.ajaxUrl && globalConfig.ajaxUrl) {
                        this.ajaxConfig.ajaxUrl = globalConfig.ajaxUrl;
                    }

                    if (!this.ajaxConfig.nonce && globalConfig.nonce) {
                        this.ajaxConfig.nonce = globalConfig.nonce;
                    }
                },

                init() {
                    this.syncAjaxConfig();
                    this.loadLeaderboard();

                    if (!this.leaderboardTimer) {
                        this.leaderboardTimer = setInterval(() => this.loadLeaderboard(true), 30000);

                        window.addEventListener('beforeunload', () => {
                            if (this.leaderboardTimer) {
                                clearInterval(this.leaderboardTimer);
                                this.leaderboardTimer = null;
                            }
                        }, { once: true });
                    }
                },

                async loadLeaderboard(silent = false) {
                    this.syncAjaxConfig();

                    const ajaxUrl = this.ajaxConfig.ajaxUrl;

                    if (!ajaxUrl) {
                        return;
                    }

                    if (!silent) {
                        this.leaderboardStatus.isLoading = true;
                        this.leaderboardStatus.error = '';
                    }

                    const payload = new FormData();
                    payload.append('action', 'get_referral_leaderboard');

                    if (this.ajaxConfig.nonce) {
                        payload.append('nonce', this.ajaxConfig.nonce);
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
                }
            };
        };
    }
</script>
