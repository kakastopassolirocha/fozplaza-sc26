# Desenvolvimento com Hot Reload

## Como usar

1. **Certifique-se que o LocalWP está rodando** com o site `fozplaza-sc26.inkweb.local`

2. **Execute o comando de desenvolvimento:**

    ```bash
    npm run dev
    ```

3. **Acesse o site através do Browser-Sync:**
    - URL: **`https://localhost:3000`** (use HTTPS)
    - UI do Browser-Sync: `http://localhost:3001`

⚠️ **IMPORTANTE:** Seu navegador vai avisar sobre certificado não seguro (porque é auto-assinado). **Clique em "Avançado" e depois em "Continuar para localhost"** para aceitar o certificado.

## O que acontece

- **Tailwind CSS**: Observa mudanças em arquivos e recompila automaticamente
- **Browser-Sync**:
    - Faz proxy do site WordPress local com HTTPS
    - Recarrega automaticamente quando arquivos `.php` ou CSS mudam
    - Injeta mudanças de CSS sem recarregar a página completa

## Arquivos monitorados

- `**/*.php` - Todos os arquivos PHP do tema
- `tailwind/output.css` - CSS compilado do Tailwind
- `components/**/*.php` - Componentes
- `pages/**/*.php` - Páginas
- `public/**/*` - Arquivos públicos (imagens, etc)

## Notas importantes

1. O Browser-Sync está configurado para fazer proxy de `https://fozplaza-sc26.inkweb.local`

2. Use **HTTPS** ao acessar `https://localhost:3000` (aceite o certificado auto-assinado)

3. Para produção, use `npm run build:css` para compilar o CSS final

## Troubleshooting

**Erro ERR_SSL_PROTOCOL_ERROR:**

- Certifique-se de usar `https://` (não `http://`) ao acessar localhost:3000
- Aceite o certificado não seguro quando o navegador avisar

**Browser-Sync não conecta:**

- Verifique se o LocalWP está rodando
- Confirme que a URL em `bs-config.js` está correta
- Teste acessar `fozplaza-sc26.inkweb.local` diretamente no navegador

**CSS não atualiza:**

- Verifique se o Tailwind está compilando (veja o terminal)
- Limpe o cache do navegador (Ctrl+Shift+R)
