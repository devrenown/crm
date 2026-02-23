import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import collectModuleAssetsPaths from "./vite-module-loader.js";

async function getConfig() {
    const paths = [
        "resources/css/app.scss",
        "resources/assets/scss/main.scss",
        "resources/js/app.js",
        "resources/js/datatables.js",
        "resources/js/ckeditor.js",
        "resources/js/app/chat/chat-app.js",

        "resources/assets/css/bootstrap.min.css",
        "resources/assets/css/line-awesome.min.css",
        "resources/assets/css/material.css",
        "resources/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css",
        "resources/assets/css/style.css",
        "resources/assets/css/ckeditor.css",

        "resources/assets/js/bootstrap.bundle.min.js",
        "resources/assets/js/jquery.slimscroll.min.js",
        "resources/assets/plugins/jquery-repeater/jquery.repeater.min.js",
        "resources/assets/js/app.js",
        "Modules/Whiteboard/resources/apps/TlDraw/main.jsx"
    ];

    const allPaths = await collectModuleAssetsPaths(paths, "Modules");

    return defineConfig({
        plugins: [
            laravel({
                input: allPaths,
                refresh: true,
            }),
        ],

        define: {
            "process.env.IS_PREACT": JSON.stringify("true"),
        },

        optimizeDeps: {
            exclude: ["js-big-decimal"],
        },

        // Laravel build path
        base: process.env.APP_ENV === "production" ? "/build/" : "/",

        build: {
            outDir: "public/build",
            emptyOutDir: true,
            assetsInlineLimit: 0,
            manifest: true,
            chunkSizeWarningLimit: 1500,

            rollupOptions: {
                output: {
                    assetFileNames: (assetInfo) => {
                    if (/woff2|woff|ttf|eot|svg|otf$/i.test(assetInfo.name)) {
                        return "assets/fonts/[name][extname]"; // no hash
                    }
                    return "assets/[name]-[hash][extname]";
                    }
                }
            },

            minify: "esbuild",
            sourcemap: false,
        },
    });
}

export default getConfig();
