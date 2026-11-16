import Alpine from 'alpinejs';

import availability from './components/availability';
import photoGallery from './components/photoGallery';
import priceCalculator from './components/priceCalculator';

window.Alpine = Alpine;

Alpine.data('availability', availability);
Alpine.data('photoGallery', photoGallery);
Alpine.data('priceCalculator', priceCalculator);

Alpine.start();
