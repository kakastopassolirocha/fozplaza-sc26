# INKTRACK - Meta Tracking (Refactored)

Biblioteca simplificada e robusta para rastreamento de eventos Meta (Facebook) Pixel e Conversions API (CAPI).

## Funcionalidades

1.  **PageView Automático**: Disparado em todas as páginas do front-end (exceto admin e AJAX).
2.  **Deduplicação**: Usa `eventID` único gerado para sincronizar eventos Client-Side e Server-Side.
3.  **Advanced Matching**: Suporte para envio de dados de usuário (email, telefone, nome) para melhorar a correspondência.
4.  **Conversions API (CAPI)**: Envio automático de eventos disparados via JS para o servidor Meta, garantindo rastreamento mesmo com bloqueadores de anúncios.
5.  **Integração WordPress**: Puxa dados do usuário logado automaticamente (incluindo campos ACF de telefone).

## Configuração

A configuração é feita via filtro `inktrack/config` no `functions.php` do tema:

```php
add_filter('inktrack/config', function ($config) {
    $config['pixel_id'] = 'SEU_PIXEL_ID';
    $config['access_token'] = 'SEU_TOKEN_CAPI';
    $config['api_version'] = 'v24.0';
    $config['debug'] = true; // Ativa logs no console e debug.log
    return $config;
});
```

## Uso

### 1. Via JavaScript (Programático)

Use `INKTRACK.track` para disparar eventos. A função cuida de atualizar o Advanced Matching, disparar o Pixel e enviar para a CAPI.

```javascript
// Exemplo: Compra
const userData = {
    em: 'cliente@email.com', // Email
    ph: '5541999999999',     // Telefone (DDI + DDD + Número, apenas dígitos)
    fn: 'joao',              // Primeiro nome
    ln: 'silva'              // Sobrenome
};

const params = {
    value: 150.00,
    currency: 'BRL',
    content_name: 'Reserva Hotel'
};

INKTRACK.track('Purchase', params, userData);
```

### 2. Via Atributos HTML (Simples)

Adicione atributos `data-inktrack-*` em qualquer elemento clicável.

```html
<button
    data-inktrack-event="Lead"
    data-inktrack-params='{"currency": "BRL", "value": 10}'
    data-inktrack-user='{"em": "cliente@teste.com"}'
>
    Cadastrar
</button>
```

### 3. Via Atributos HTML (Múltiplos Eventos)

Para disparar vários eventos em um único clique:

```html
<button
    data-inktrack-events='[
        {
            "eventName": "InitiateCheckout",
            "params": { "value": 100 }
        },
        {
            "eventName": "Purchase",
            "params": { "value": 100 },
            "userData": { "em": "email@teste.com" }
        }
    ]'
>
    Comprar Agora
</button>
```

## Estrutura de Arquivos

*   `src/InkTrack.php`: Lógica Server-Side. Renderiza o Pixel, gerencia CAPI e endpoints REST.
*   `assets/js/inktrack.js`: Lógica Client-Side. Gerencia `fbq`, gera `eventID` e comunica com o backend.

## Notas Técnicas

*   **Hashing**: O PHP cuida automaticamente do hash SHA256 dos dados sensíveis (email, telefone, etc.) antes de enviar para a CAPI. No Client-Side (Pixel), os dados são enviados em texto claro (o Pixel cuida do hash internamente ou via HTTPS).
*   **Cookies**: O plugin gerencia cookies `_fbc`, `_fbp` e `INKTRACK_external_id` para manter a consistência do usuário.
*   **IP e User Agent**: Coletados e enviados automaticamente para a CAPI.
