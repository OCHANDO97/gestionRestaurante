import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
  server: {
    host: '0.0.0.0',    // ⬅️ escucha en todas las interfaces del contenedor
    port: 5173,         // ⬅️ puerto interno
    strictPort: true,   // ⬅️ falla si 5173 ya está ocupado
    origin: 'http://localhost:5173',    
  },
  plugins: [
    laravel({
      input: 'resources/js/app.jsx',
      refresh: true,
    }),
    react(),
  ],
});