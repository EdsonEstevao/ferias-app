import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
         {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ],
      build: {
        assetsDir: '',
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    // Mover fonts para a pasta webfonts/
                    if (/woff|woff2|ttf|eot/.test(assetInfo.name)) {
                        return `webfonts/[name]-[hash][extname]`;
                    }
                    // Outros assets vão para assets/
                    return `assets/[name]-[hash][extname]`;
                },
            },
        },
    },
});
