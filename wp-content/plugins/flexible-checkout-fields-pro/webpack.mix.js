/* ---
  Docs: https://www.npmjs.com/package/mati-mix/
--- */
const mix = require('mati-mix');

mix.js([
	'assets-src/js/Core.js',
], 'assets/js/admin-new.js');

mix.sass(
	'assets-src/scss/Core.scss'
	, 'assets/css/admin-new.css');
