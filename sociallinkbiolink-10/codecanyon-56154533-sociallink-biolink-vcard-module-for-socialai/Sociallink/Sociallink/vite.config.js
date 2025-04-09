import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  build: {
    outDir: '../../public/build-sociallink',
    emptyOutDir: true,
    manifest: true
  },
  plugins: [
    laravel({
      publicDirectory: '../../public',
      buildDirectory: 'build-sociallink',
      input: [__dirname + '/resources/js/app.js'],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ],
  resolve: {
    alias: {
      '@modules': path.resolve(__dirname, '../'),
      '@root': path.resolve(__dirname, './../../resources/js/'),
      '@resources': path.resolve(__dirname, './../../resources'),
      '@sociallink': path.resolve(__dirname, './resources/js/')
    }
  }
})
