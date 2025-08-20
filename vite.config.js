import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate Vue and Inertia into their own chunk
                    "vue-vendor": ["vue", "@inertiajs/vue3"],
                    // You can add more chunks based on your app structure
                    // 'admin-pages': ['./resources/js/Pages/Admin/**/*.vue'],
                    // 'user-pages': ['./resources/js/Pages/User/**/*.vue']
                },
            },
        },
        // Optionally increase the warning threshold
        chunkSizeWarningLimit: 1000,
    },
});
