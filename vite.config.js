import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/auth.css",
                "resources/css/dashboard.css",
                "resources/css/profile.css",
                "resources/js/app.js",
                "resources/js/auth.js",
                "resources/js/profile.js",
                "resources/css/donatur.css",
            ],
            refresh: true,
        }),
    ],
});