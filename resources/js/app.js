import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import LikeButton from './components/LikeButton.vue';


window.Alpine = Alpine;

Alpine.start();

if (document.querySelector('#like-button')) {
  const like = createApp({});
  like.component('like-button', LikeButton);
  like.mount('#like-button');
}

