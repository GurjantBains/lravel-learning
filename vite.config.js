import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ mode }) => {
    // Load only VITE_* variables from your .env files
    const env = loadEnv(mode, process.cwd(), 'VITE_');

    // Read optional hosts from environment, e.g. VITE_DETECT_TLS=learningproject.test
    const tlsHosts = (env.VITE_DETECT_TLS || '')
        .split(',')
        .map(h => h.trim())
        .filter(Boolean);

    // Preferred development host/port (matches your Herd site)
    const devHost = env.VITE_DEV_HOST || 'learningproject.test';
    const vitePort = Number(env.VITE_PORT || 5173);
    const useTls = tlsHosts.length > 0;

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
                // Enable Herd TLS only when explicitly configured
                ...(useTls ? { detectTls: tlsHosts } : {}),
            }),
            tailwindcss(),
        ],
        server: {
            host: devHost,
            port: vitePort,
            strictPort: true,
            // Force the HMR client to connect over the correct scheme/host/port
            hmr: {
                host: devHost,
                protocol: useTls ? 'wss' : 'ws',
                port: vitePort,
                clientPort: vitePort,
            },
        },
    };
});
