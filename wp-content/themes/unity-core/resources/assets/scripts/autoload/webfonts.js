// Web Font Loader
var WebFont = require('webfontloader');

var simpleFont = simple_options.fonts,  // eslint-disable-line no-undef
    fontFamilies = ['Material Icons'];

switch (simpleFont) {
  case '1':
    fontFamilies = ['Bitter:700', 'Source Sans Pro:400,400i,700,700i', 'Material Icons'];
    break;

  case '2':
    fontFamilies = ['Didact Gothic:400', 'Arimo:400,400i,700,700i', 'Material Icons'];
    break;

  case '3':
    fontFamilies = ['Fjalla One:400', 'Crimson Text:400,400i,700,700i', 'Material Icons'];
    break;

  case '4':
    fontFamilies = ['Dancing Script:400', 'Open Sans:400,400i,700,700i', 'Material Icons'];
    break;

  case '5':
    fontFamilies = ['Open Sans:700,700i', 'Libre Baskerville:400,400i,700', 'Material Icons'];
    break;

  case '6':
    fontFamilies = ['Ovo:400', 'Muli:400,400i,700,700i', 'Material Icons'];
    break;
}

WebFont.load({
 google: {
   families: fontFamilies
 }
});
