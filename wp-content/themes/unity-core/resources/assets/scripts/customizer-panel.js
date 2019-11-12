import $ from 'jquery';

wp.customize.bind('ready', function() {
  function hideShowColorOptions() {
    var selectedColor = wp.customize.instance( 'theme_color' ).get();
    var neutral = ['sparent', '#000000', '#FFFFFF']
    var themes = {
      'bright'    : ['#2E827C', '#2EC4B6', '#FF9F1C', '#FFBF69', '#FFF7ED'],
      'chrome'    : ['#47474C', '#66666E', '#9999A1', '#C5C5CC', '#F9F9FC'],
      'executive' : ['#001514', '#6B0504', '#A3320B', '#E6AF2E', '#FBFFFE'],
      'grass'     : ['#114B5F', '#1A936F', '#88D498', '#C6DABF', '#F3E9D2'],
      'ocean'     : ['#00171F', '#003459', '#007EA7', '#00A8E8', '#CEE0E8'],
      'sunrise'   : ['#CC5803', '#E2711D', '#FF9505', '#FFB627', '#FFF1DB'],
    }

    $('.o2-color-palette-group label[for*="simple_header"], .o2-color-palette-group label[for*="simple_footer"]').each(function() {
      var hex = $(this).attr('for').slice(-7);

      if ($.inArray(hex, themes[selectedColor]) >= 0 || $.inArray(hex, neutral) >= 0) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  }

  function hideShowNavOptions() {
    var selectedHeader = wp.customize.instance( 'header_logo_align' ).get();

    if (selectedHeader !== 'inline-left') {
      $('#customize-control-simple_header_nav_color').show();
    } else {
      $('#customize-control-simple_header_nav_color').hide();
    }
  }

  // Call these functions on page load
  hideShowColorOptions();
  hideShowNavOptions();

  // ... and on radio button change
  $( 'input[name="o2_color_palette_simple_theme_color"]' ).on( 'change', hideShowColorOptions );
  $( 'input[name="_customize-radio-simple_header_logo_align"]' ).on( 'change', hideShowNavOptions );

});
