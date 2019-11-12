<?php

namespace App;

/**
* Theme customizer
*/
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
   $theme_colors = array(
     '#000000' => array( 'label' => 'Black', 'colors' => array( '#000000' ) ),
     // Bright
     '#2E827C' => array( 'label' => 'Dark Teal', 'colors' => array( '#2E827C' ) ),
     '#2EC4B6' => array( 'label' => 'Bright Teal', 'colors' => array( '#2EC4B6' ) ),
     '#FF9F1C' => array( 'label' => 'Bright Orange', 'colors' => array( '#FF9F1C' ) ),
     '#FFBF69' => array( 'label' => 'Light Orange', 'colors' => array( '#FFBF69' ) ),
     '#FFF7ED' => array( 'label' => 'Cream', 'colors' => array( '#FFF7ED' ) ),
     // Chrome
     '#47474C' => array( 'label' => 'Darker Grey', 'colors' => array( '#47474C' ) ),
     '#66666E' => array( 'label' => 'Dark Grey', 'colors' => array( '#66666E' ) ),
     '#9999A1' => array( 'label' => 'Grey', 'colors' => array( '#9999A1' ) ),
     '#C5C5CC' => array( 'label' => 'Light Grey', 'colors' => array( '#C5C5CC' ) ),
     '#F9F9FC' => array( 'label' => 'White-ish', 'colors' => array( '#F9F9FC' ) ),
     // Executive
     '#001514' => array( 'label' => 'Black-ish', 'colors' => array( '#001514' ) ),
     '#6B0504' => array( 'label' => 'Dark Red', 'colors' => array( '#6B0504' ) ),
     '#A3320B' => array( 'label' => 'Red', 'colors' => array( '#A3320B' ) ),
     '#E6AF2E' => array( 'label' => 'Gold', 'colors' => array( '#E6AF2E' ) ),
     '#FBFFFE' => array( 'label' => 'White-ish', 'colors' => array( '#FBFFFE' ) ),
     // Grass
     '#114B5F' => array( 'label' => 'Dark Green', 'colors' => array( '#114B5F' ) ),
     '#1A936F' => array( 'label' => 'Medium Green', 'colors' => array( '#1A936F' ) ),
     '#88D498' => array( 'label' => 'Light Green', 'colors' => array( '#88D498' ) ),
     '#C6DABF' => array( 'label' => 'Very Light Green', 'colors' => array( '#C6DABF' ) ),
     '#F3E9D2' => array( 'label' => 'Cream', 'colors' => array( '#F3E9D2' ) ),
     // Ocean
     '#00171F' => array( 'label' => 'Black Blue', 'colors' => array( '#00171F' ) ),
     '#003459' => array( 'label' => 'Dark Blue', 'colors' => array( '#003459' ) ),
     '#007EA7' => array( 'label' => 'Medium Blue', 'colors' => array( '#007EA7' ) ),
     '#00A8E8' => array( 'label' => 'Bright Blue', 'colors' => array( '#00A8E8' ) ),
     '#CEE0E8' => array( 'label' => 'Light Blue', 'colors' => array( '#CEE0E8' ) ),
     // Sunrise
     '#CC5803' => array( 'label' => 'Dark Orange', 'colors' => array( '#CC5803' ) ),
     '#E2711D' => array( 'label' => 'Medium Orange', 'colors' => array( '#E2711D' ) ),
     '#FF9505' => array( 'label' => 'Light Orange', 'colors' => array( '#FF9505' ) ),
     '#FFB627' => array( 'label' => 'Gold', 'colors' => array( '#FFB627' ) ),
     '#FFF1DB' => array( 'label' => 'Cream', 'colors' => array( '#FFF1DB' ) ),
     '#FFFFFF' => array( 'label' => 'White', 'colors' => array( '#FFFFFF' ) ),
     'transparent' => array( 'label' => 'Transparent', 'colors' => array('transparent') ),
   );

  // Add postMessage support
  $wp_customize->get_setting('blogname')->transport = 'postMessage';
  $wp_customize->selective_refresh->add_partial('blogname', [
    'selector' => '.brand',
    'render_callback' => function () {
      bloginfo('name');
    }
  ]);

  /**
   * Add logo resizing setting
   */
  $wp_customize->add_setting( 'header_logo_width',
    array(
      'default' => 200,
      'capability'  => 'edit_theme_options',
      'transport'  => 'refresh'
    )
  );

  $wp_customize->add_control( new \O2_Customizer_Range_Slider_Control(
    $wp_customize,
    'simple_logo_width', //Set a unique ID for the control
    array(
      'label'      => __( 'Logo Width', 'simple' ), //Admin-visible name of the control
      'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      'settings'   => 'header_logo_width', //Which setting to load and manipulate (serialized is okay)
      'input_attrs' => array(
        'priority'   => 10, //Determines the order this control appears in for the specified section
        'min'        => 100,
        'max'        => 300,
        'step'       => 1,
      )
    )
  ) );

  /**
   * Add Simple Theme Settings
   */
  $wp_customize->add_section( 'simple_settings' , array(
    'title'      => __( 'Simple Settings', 'simple' ),
    'priority'   => 30,
    'capability'  => 'edit_theme_options',
  ) );

  $wp_customize->add_setting( 'theme_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
      'default'    => 'bright', //Default setting/value to save
      'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
      'transport'  => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );

  $wp_customize->add_control(new \O2_Customizer_Color_Palette_Control(
    $wp_customize,
    'simple_theme_color', //Set a unique ID for the control
    array(
      'label'      => __( 'Color Scheme', 'simple' ), //Admin-visible name of the control
      'section'    => 'simple_settings', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      'settings'   => 'theme_color', //Which setting to load and manipulate (serialized is okay)
      'priority'   => 15, //Determines the order this control appears in for the specified section
      'choices'    => array(
        'bright' => array(
          'label' => 'Bright',
          'colors' => array( '#2E827C', '#2EC4B6', '#FF9F1C', '#FFBF69', '#FFF7ED' )
        ),
        'chrome' => array(
          'label' => 'Chrome',
          'colors' => array( '#47474C', '#66666E', '#9999A1', '#C5C5CC', '#F9F9FC' )
        ),
        'executive' => array(
          'label' => 'Executive',
          'colors' => array( '#001514', '#6B0504', '#A3320B', '#E6AF2E', '#FBFFFE' )
        ),
        'grass' => array(
          'label' => 'Grass',
          'colors' => array( '#114B5F', '#1A936F', '#88D498', '#C6DABF', '#F3E9D2' )
        ),
        'ocean' => array(
          'label' => 'Ocean',
          'colors' => array( '#00171F', '#003459', '#007EA7', '#00A8E8', '#CEE0E8' )
        ),
        'sunrise' => array(
          'label' => 'Sunrise',
          'colors' => array( '#CC5803', '#E2711D', '#FF9505', '#FFB627', '#FFF1DB' )
        ),
      ),
    )
  ));

  $wp_customize->add_setting( 'theme_fonts', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
      'default'    => '1', //Default setting/value to save
      'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
      'transport'  => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );

  $wp_customize->add_control(
    'simple_theme_fonts', //Set a unique ID for the control
    array(
      'label'      => __( 'Font Pairing', 'simple' ), //Admin-visible name of the control
      'section'    => 'simple_settings', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      'settings'   => 'theme_fonts', //Which setting to load and manipulate (serialized is okay)
      'priority'   => 10, //Determines the order this control appears in for the specified section
      'type'       => 'select',
      'choices'    => array(
        '1'     => 'Option 1 (Bitter - Source Sans Pro)',
        '2'     => 'Option 2 (Didact Gothic - Arimo)',
        '3'     => 'Option 3 (Fjalla One - Crimson Text)',
        '4'     => 'Option 4 (Dancing Script - Open Sans)',
        '5'     => 'Option 5 (Open Sans - Libre Baskerville)',
        '6'     => 'Option 6 (Ovo - Muli)',
      ),
    )
  );

  $wp_customize->add_setting( 'button_font',
    array(
      'default'   => 'Body',
      'type'      => 'theme_mod',
      'transport' => 'refresh',
    )
  );

  $wp_customize->add_control(
    'simple_button_font',
    array(
      'label'     => __( 'Button Font', 'simple' ),
      'section'   => 'simple_settings',
      'settings'  => 'button_font',
      'priority'  => 20,
      'type'      => 'radio',
      'choices'   => array('Body', 'Header')
    )
  );

  /**
   * Customize colors based on selected color scheme
   */
  $wp_customize->add_section( 'simple_header' , array(
    'title'      => __( 'Header Settings', 'simple' ),
    'priority'   => 40,
    'capability'  => 'edit_theme_options',
    )
  );

  $wp_customize->add_setting( 'header_logo_align', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
     'default'    => 'float-left', //Default setting/value to save
     'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
     'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );

  $wp_customize->add_control(
    'simple_header_logo_align', //Set a unique ID for the control
    array(
     'label'      => __( 'Logo Alignment', 'simple' ), //Admin-visible name of the control
     'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
     'settings'   => 'header_logo_align', //Which setting to load and manipulate (serialized is okay)
     'priority'   => 10, //Determines the order this control appears in for the specified section
     'type'       => 'radio',
     'choices'    => array(
       'inline-left'     => 'Inline Left',
       'float-left'      => 'Float Left',
       'float-center'    => 'Float Center',
       'no-logo'         => 'Hide Logo',
     )
    )
  );

  $wp_customize->add_setting( 'header_cta_headline', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
     'default'    => '', //Default setting/value to save
     'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
     'transport'  => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );

  $wp_customize->add_control(
    'simple_header_cta_headline', //Set a unique ID for the control
    array(
     'label'      => __( 'CTA Headline (optional)', 'simple' ), //Admin-visible name of the control
     'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
     'settings'   => 'header_cta_headline', //Which setting to load and manipulate (serialized is okay)
     'priority'   => 10, //Determines the order this control appears in for the specified section
     'type'       => 'text'
    )
  );

  $wp_customize->add_setting( 'header_cta_text', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
     'default'    => '', //Default setting/value to save
     'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
     'transport'  => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );

  $wp_customize->add_control(
    'simple_header_cta_text', //Set a unique ID for the control
    array(
     'label'      => __( 'CTA Button Text', 'simple' ), //Admin-visible name of the control
     'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
     'settings'   => 'header_cta_text', //Which setting to load and manipulate (serialized is okay)
     'priority'   => 10, //Determines the order this control appears in for the specified section
     'type'       => 'text'
    )
  );

  $wp_customize->add_setting( 'header_cta_link', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
     'default'    => '', //Default setting/value to save
     'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
     'transport'  => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );

  $wp_customize->add_control(
    'simple_header_cta_link', //Set a unique ID for the control
    array(
     'label'      => __( 'CTA Button Link', 'simple' ), //Admin-visible name of the control
     'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
     'settings'   => 'header_cta_link', //Which setting to load and manipulate (serialized is okay)
     'priority'   => 10, //Determines the order this control appears in for the specified section
     'type'       => 'url'
    )
  );

  $wp_customize->add_setting( 'header_cta_target',
    array(
      'default'   => '',
      'type'      => 'theme_mod',
      'transport' => 'refresh',
    )
  );

  $wp_customize->add_control(
    'simple_header_cta_target',
    array(
      'label'     => __( 'Open CTA in New Tab?', 'simple' ),
      'section'   => 'simple_header',
      'settings'  => 'header_cta_target',
      'priority'  => 10,
      'type'      => 'checkbox'
    )
  );

  $wp_customize->add_setting( 'header_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
      'default'    => 'transparent', //Default setting/value to save
      'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
      'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      // 'sanitize_callback' => 'sanitize_hex_color',
    )
  );

  $wp_customize->add_control(new \O2_Customizer_Color_Palette_Control(
    $wp_customize,
    'simple_header_color', //Set a unique ID for the control
    array(
      'label'      => __( 'Header Color', 'simple' ), //Admin-visible name of the control
      'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      'settings'   => 'header_color', //Which setting to load and manipulate (serialized is okay)
      'priority'   => 15, //Determines the order this control appears in for the specified section
      'type'       => 'select',
      'choices'    => $theme_colors
    )
  ));

  $wp_customize->add_setting( 'header_nav_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
   array(
     'default'    => 'transparent', //Default setting/value to save
     'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
     'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
     // 'sanitize_callback' => 'sanitize_hex_color',
   )
  );

  $wp_customize->add_control(new \O2_Customizer_Color_Palette_Control(
    $wp_customize,
    'simple_header_nav_color', //Set a unique ID for the control
    array(
      'label'      => __( 'Header Navbar Color', 'simple' ), //Admin-visible name of the control
      'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      'settings'   => 'header_nav_color', //Which setting to load and manipulate (serialized is okay)
      'priority'   => 20, //Determines the order this control appears in for the specified section
      'type'       => 'select',
      'choices'    => $theme_colors
    )
  ));

  $wp_customize->add_setting( 'header_text_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
      'default'    => 'dark', //Default setting/value to save
      'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
      'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
    )
  );

  $wp_customize->add_control(
    'simple_header_text_color', //Set a unique ID for the control
    array(
      'label'      => __( 'Text Color', 'simple' ), //Admin-visible name of the control
      'section'    => 'simple_header', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      'settings'   => 'header_text_color', //Which setting to load and manipulate (serialized is okay)
      'priority'   => 18, //Determines the order this control appears in for the specified section
      'type'       => 'radio',
      'choices'    => array(
        'dark'     => 'Dark',
        'light'    => 'Light',
      )
    )
  );

  $wp_customize->add_section( 'simple_footer' , array(
    'title'      => __( 'Footer Settings', 'simple' ),
    'priority'   => 40,
    'capability'  => 'edit_theme_options',
    )
  );

  $wp_customize->add_setting( 'footer_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
    array(
      'default'    => 'transparent', //Default setting/value to save
      'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
      'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      // 'sanitize_callback' => 'sanitize_hex_color',
    )
  );

  $wp_customize->add_control(new \O2_Customizer_Color_Palette_Control(
    $wp_customize,
    'simple_footer_color', //Set a unique ID for the control
    array(
      'label'      => __( 'Footer Color', 'simple' ), //Admin-visible name of the control
      'section'    => 'simple_footer', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
      'settings'   => 'footer_color', //Which setting to load and manipulate (serialized is okay)
      'priority'   => 15, //Determines the order this control appears in for the specified section
      'type'       => 'select',
      'choices'    => $theme_colors
    )
  ));

});

/**
* Customizer JS
*/
add_action('customize_preview_init', function () {
  wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});

add_action('customize_controls_enqueue_scripts', function () {
  wp_enqueue_script('sage/customizer-panel.js', asset_path('scripts/customizer-panel.js'), [], null, true);
});
