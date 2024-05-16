import './bootstrap';
import { createApp } from 'vue';
import URLShortenerForm from './components/URLShortenerForm.vue';

const app = createApp({});
app.component('url-shortener-form', URLShortenerForm);
app.mount('#app');
