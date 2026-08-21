import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

const isGitHubPages = process.env.VITE_GITHUB_PAGES === 'true';

const vuePlugin = vue({
  template: {
    transformAssetUrls: {
      base: null,
      includeAbsolute: false,
    },
  },
});

const isStandalone = process.env.VITE_STANDALONE === 'true' || process.env.VITE_GITHUB_PAGES === 'true';

export default defineConfig(
  isStandalone
    ? {
        // Standalone SPA build using index.html as entry
        base: process.env.VITE_GITHUB_PAGES === 'true' ? '/Besmart/' : '/',
        plugins: [vuePlugin],
        resolve: {
          alias: {
            '@': path.resolve(__dirname, 'resources/js'),
          },
        },
        build: {
          outDir: '_site',
          emptyOutDir: true,
        },
      }
    : {
        // Laravel local dev: uses laravel-vite-plugin
        plugins: [
          laravel({
            input: ['resources/css/app.css', 'resources/js/main.js'],
            refresh: true,
          }),
          vuePlugin,
        ],
        resolve: {
          alias: {
            '@': '/resources/js',
          },
        },
      }
);
