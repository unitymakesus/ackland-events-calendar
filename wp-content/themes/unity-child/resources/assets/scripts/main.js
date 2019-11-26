// Import parent JS
import '../../../../unity-core/dist/scripts/main.js';

/** Import dependencies */
import 'lazysizes';

/** Import local dependencies */
import Router from './util/Router';
import common from './routes/common';
import archive from './routes/archive';

/** Populate Router instance with DOM routes */
const routes = new Router({
  common,
  archive,
});

/** Load Events */
jQuery(document).ready(() => routes.loadEvents());
