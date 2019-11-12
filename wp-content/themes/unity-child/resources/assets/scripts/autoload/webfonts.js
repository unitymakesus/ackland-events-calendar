// Web Font Loader
var WebFont = require('webfontloader');

var fontFamilies = ['Bitter:700', 'Source Sans Pro:400,400i,700,700i', 'Material Icons'];

WebFont.load({
 google: {
   families: fontFamilies
 }
});
