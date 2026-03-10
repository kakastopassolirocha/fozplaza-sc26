<!-- FAQ Section -->
<div>
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
</div>
<!-- FAQ Section -->