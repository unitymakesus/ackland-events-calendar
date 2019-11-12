import $ from 'jquery';

// Live Preview Controls
wp.customize('blogname', (value) => {
  value.bind(to => $('.brand').text(to));
});

wp.customize('theme_fonts', (value) => {
  value.bind(to => $('body').attr('data-font', to));
});

wp.customize('theme_color', (value) => {
  value.bind(to => $('body').attr('data-color', to));
});
