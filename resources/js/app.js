import Alpine from 'alpinejs';

import beschikbaarheid from './components/beschikbaarheid';
import fotogalerij from './components/fotogalerij';
import prijsCalculator from './components/prijsCalculator';

window.Alpine = Alpine;

Alpine.data('beschikbaarheid', beschikbaarheid);
Alpine.data('fotogalerij', fotogalerij);
Alpine.data('prijsCalculator', prijsCalculator);

Alpine.start();
