
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 * 
 * Commande to install symfony/asset-mapper + asset component and twig: composer require symfony/asset-mapper symfony/asset symfony/twig-pack
 *
 * The AssetMapper component lets you write modern JavaScript and CSS without the complexity of using a bundler. 
 * Browsers already support many modern JavaScript features like the import statement and ES6 classes. 
 * And the HTTP/2 protocol means that combining your assets to reduce HTTP connections is no longer urgent. 
 * This component is a light layer that helps serve your files directly to the browser.
*/
import './bootstrap.js';
import './styles/app.css';
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');