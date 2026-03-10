(function (window, document) {
    'use strict';

    const INKTRACK = window.INKTRACK || {};
    window.INKTRACK = INKTRACK;

    // Configuração vinda do PHP
    const CONFIG = window.INKTRACK_CONFIG || {};

    /**
     * Log de debug
     */
    const log = (message, data) => {
        if (CONFIG.debug) {
            console.log(`[INKTRACK] ${message}`, data || '');
        }
    };

    /**
     * Gera um ID de evento único (Client-Side)
     */
    const generateEventId = () => {
        return `client.${Date.now()}.${Math.random().toString(36).slice(2, 10)}`;
    };

    /**
     * Envia evento para o backend (CAPI)
     */
    const sendToCapi = (eventName, eventID, params, userData) => {
        if (!CONFIG.endpoint) return;

        const payload = {
            eventName: eventName,
            eventID: eventID,
            params: params || {},
            userData: userData || {}
        };

        // Usa sendBeacon se disponível para garantir envio ao sair da página
        if (navigator.sendBeacon) {
            const blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
            navigator.sendBeacon(CONFIG.endpoint, blob);
            log('Event sent via Beacon (CAPI)', payload);
        } else {
            fetch(CONFIG.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': CONFIG.nonce
                },
                body: JSON.stringify(payload),
                keepalive: true
            }).then(res => {
                log('Event sent via Fetch (CAPI)', res.status);
            }).catch(err => {
                console.error('[INKTRACK] CAPI Error:', err);
            });
        }
    };

    /**
     * Função principal de rastreamento
     * @param {string} eventName Nome do evento (ex: 'Purchase', 'Lead')
     * @param {object} params Parâmetros do evento (ex: { value: 10, currency: 'BRL' })
     * @param {object} userData Dados do usuário para Advanced Matching (ex: { em: '...', ph: '...' })
     */
    INKTRACK.track = function (eventName, params = {}, userData = {}) {
        if (!eventName) {
            console.error('[INKTRACK] Event name is required');
            return;
        }

        const eventID = generateEventId();
        
        // 1. Atualiza Advanced Matching no Pixel (se houver dados novos)
        // A lógica é: se passarmos userData aqui, atualizamos o init antes de disparar o track.
        if (userData && Object.keys(userData).length > 0 && CONFIG.pixelId && typeof fbq === 'function') {
            log('Updating Advanced Matching (init)', userData);
            fbq('init', CONFIG.pixelId, userData);
        }

        // 2. Dispara evento no Pixel (Client-Side)
        if (typeof fbq === 'function') {
            // Adiciona eventID para desduplicação
            const pixelParams = Object.assign({}, params, { eventID: eventID });
            
            // Verifica se é evento padrão ou customizado
            // Lista básica de eventos padrão da Meta
            const standardEvents = [
                'AddPaymentInfo', 'AddToCart', 'AddToWishlist', 'CompleteRegistration', 
                'Contact', 'CustomizeProduct', 'Donate', 'FindLocation', 
                'InitiateCheckout', 'Lead', 'Purchase', 'Schedule', 
                'Search', 'StartTrial', 'SubmitApplication', 'Subscribe', 
                'ViewContent', 'PageView'
            ];
            
            const method = standardEvents.includes(eventName) ? 'track' : 'trackCustom';
            
            fbq(method, eventName, pixelParams);
            log(`Pixel fired: ${method} -> ${eventName}`, pixelParams);
        } else {
            console.warn('[INKTRACK] Facebook Pixel (fbq) not found');
        }

        // 3. Envia para CAPI (Server-Side)
        sendToCapi(eventName, eventID, params, userData);
    };

    /**
     * Inicializa ouvintes de eventos via atributos data-inktrack-*
     */
    const initListeners = () => {
        document.addEventListener('click', (e) => {
            const target = e.target.closest('[data-inktrack-event], [data-inktrack-events]');
            if (!target) return;

            // Caso 1: Múltiplos eventos
            const eventsAttr = target.getAttribute('data-inktrack-events');
            if (eventsAttr) {
                try {
                    const events = JSON.parse(eventsAttr);
                    if (Array.isArray(events)) {
                        events.forEach(evt => {
                            INKTRACK.track(evt.eventName, evt.params || {}, evt.userData || {});
                        });
                    }
                } catch (err) {
                    console.error('[INKTRACK] Invalid JSON in data-inktrack-events', err);
                }
                return;
            }

            // Caso 2: Evento único
            const eventName = target.getAttribute('data-inktrack-event');
            if (eventName) {
                let params = {};
                let userData = {};

                try {
                    const paramsAttr = target.getAttribute('data-inktrack-params');
                    if (paramsAttr) params = JSON.parse(paramsAttr);

                    const userAttr = target.getAttribute('data-inktrack-user');
                    if (userAttr) userData = JSON.parse(userAttr);
                } catch (err) {
                    console.error('[INKTRACK] Invalid JSON in data attributes', err);
                }

                INKTRACK.track(eventName, params, userData);
            }
        });
    };

    // Inicializa
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initListeners);
    } else {
        initListeners();
    }

})(window, document);
