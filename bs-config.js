module.exports = {
  proxy: "https://fozplaza-sc26.inkweb.local",
  https: true,
  files: [
    "**/*.php",
    "tailwind/output.css",
    "components/**/*.php",
    "pages/**/*.php",
    "public/**/*"
  ],
  watchOptions: {
    ignoreInitial: true,
    ignored: [
      "node_modules/**",
      "*.json",
      "*.md"
    ]
  },
  port: 3000,
  ui: {
    port: 3001
  },
  open: false,
  notify: true,
  reloadDelay: 0,
  injectChanges: true,
  ghostMode: false
};
