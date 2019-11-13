<?php

namespace SovereignStack\SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'ABSPATH' ) ) { die; }

/**
 * Class AdminPage
 * @package SecuritySafe
 */
class AdminPage {

    public $title = 'Page Title';
    public $description = 'Description of page.';
    protected $settings = [];
    public $slug = '';
    public $tabs = [];

    /**
     * Contains all the admin message values for the page.
     * @var array
     */
    public $messages = [];

    /**
     * AdminPage constructor.
     * @param $settings
     */
    function __construct( $settings ) {

        $this->settings = $settings;

        // Prevent Caching
        $this->prevent_caching();

        // Set page variables
        $this->set_page();

    } // __construct()


    /**
     * Placeholder intended to be used by pages to override variables.
     * @since  0.1.0
     */ 
    protected function set_page() {

        // This is overwritten by specific page.
    
    } // set_page()


    /**
     * Prevent plugins like WP Super Cache and W3TC from caching any data on this page.
     * @since  2.0.0
     */ 
    private function prevent_caching() {

        /** 
         * This functionality has moved to Janitor
         * @todo  Update all references to this private method to point ot Janitor instead 
         * and then delete this method.
         */

        Janitor::prevent_caching();

    } // prevent_caching()


    /** 
     * Displays all the tabs set by the specific page
     * @since  0.2.0
     * @return html
     */
    public function display_tabs() {

        if ( ! empty( $this->tabs ) ){

            $html = '<h2 class="nav-tab-wrapper">';
            $num = 1;

            foreach ( $this->tabs as $t ){

                if ( is_array( $t ) ) {

                    $classes = 'nav-tab';
                    
                    // Add Active Class To Active Tab : Default First Tab
                    if( ( isset( $_GET['tab'] ) && $_GET['tab'] == $t['id'] ) || ( ! isset( $_GET['tab'] ) && $num == 1 ) ) {
                        
                        $classes .= ' nav-tab-active';

                    }

                    $html .= '<a href="?page=' . esc_html( $this->slug ) . '&tab=' . esc_html( $t['id'] ) . '" class="' . esc_html( $classes ) . '">' . esc_html( $t['label'] ) . '</a>';
                
                    $num++;

                } // is_array()

            } // foreach()

            $html .= '</h2>';

            echo $html;

        } // $this->tabs

    } // display_tabs()


    /**
     * Display All Tabbed Content
     * @since  0.2.0
     * @return  html
     */  
    public function display_tabs_content() {

        if ( ! empty( $this->tabs ) ) {

            $num = 1;

            $html = '';

            foreach ( $this->tabs as $t ) {

                if ( ( isset( $_GET['tab'] ) && $_GET['tab'] == $t['id'] ) || ( ! isset( $_GET['tab'] ) && $num == 1 ) ) {

                    $classes = 'tab-content';

                    // Add Active Class To Active Tab : Default First Tab Content
                    if ( ( isset( $_GET['tab'] ) && $_GET['tab'] == $t['id'] ) || ( ! isset( $_GET['tab'] ) && $num == 1 ) ) {
                        $classes .= ' active';
                    }

                    // Adds Custom Classes
                    if ( isset( $t['classes'] ) ) {

                        if ( is_array( $t['classes'] ) ) {

                            foreach ( $t['classes'] as $class ) {

                                $classes .= ' ' . $class;

                            } // foreach()

                        } else {

                            $classes .= ' ' . $t['classes'];

                        } // is_array()

                    } // isset()

                    $html .= '<div id="' . esc_html( $t['id'] ) . '" class="' . esc_html( $classes ) . '">';
                    
                    // Display Title
                    if ( isset( $t['title'] ) && $t['title'] ) {

                        $html .= '<h2>' . esc_html( $t['title'] ) . '</h2>';

                    }

                    // Display Heading Text
                    if ( isset( $t['heading'] ) && $t['heading'] ) {

                        $html .= '<p class="new-description description">' . esc_html( $t['heading'] ) . '</p>';

                    }

                    // Display Intro Text
                    if ( isset( $t['intro'] ) && $t['intro'] ) {

                        /**
                         * @todo Need to sanitize this in a way that doesn't break the intro
                         */ 
                        $html .= '<p>' . $t['intro'] . '</p>';

                    }

                    // Display Page Messages As A Log
                    $html .= $this->display_messages();

                    // Run Callback Method To Display Content
                    if ( isset( $t['content_callback'] ) && $t['content_callback'] ) {

                        $content = $t['content_callback'];
                        $html .= $this->$content();
                        
                    }

                    $html .= '</div><!-- #' . esc_html( $t['id'] ) . ' -->';

                    $num++;

                } // $_GET['tab']
                
            } // foreach

            echo $html;

        } // $this->tabs

    } // display_tabs_content()


    /**
     * Creates the opening and closing tags for the form-table
     * @since  0.2.0
     */
    protected function form_table( $rows ) {

        return '<table class="form-table">' . $rows . '</table>';

    } // form_table()

    /**
     * Creates a new section for a form-table
     * @since  0.2.0
     */
    
    protected function form_section( $title, $desc ) {

        // Create ID to allow links to specific areas of admin
        $id = str_replace( ' ', '-', trim( strtolower( $title ) ) );
        
        $html = '<h3 id="' . esc_html( $id ) . '">' . esc_html( $title ) . '</h3>';
        $html .= '<p>' . esc_html( $desc ) . '</p>';

        return $html;

    } // form_section()

    /** 
     * Displays form checkbox for a settings page.
     * @since  0.1.0
     * @param array $page_options An array of setting values specific to the particular page. This is not the full array of settings.
     * @param string $name The name of the checkbox which corresponds with the setting name in the database.
     * @param string $slug The value for the settings in the database.
     * @param string $short_desc The text that is displayed to the right on the checkbox.
     * @param string $long_desc The description text displayed below the title.
     */
    protected function form_checkbox( $page_options, $name, $slug, $short_desc, $long_desc, $classes = '', $disabled = false ) {

        $html = '<tr class="form-checkbox '. $classes .'">';

        if ( is_array( $page_options ) && $slug && $short_desc ) {
            
            $html .= $this->row_label( $name );
            $html .= '<td>';

            $checked = ( isset( $page_options[ $slug ] ) && $page_options[ $slug ] == '1' ) ? ' CHECKED' : '';
            $disabled = ( $disabled ) ? ' DISABLED' : '';
            
            /**
             * @todo  Fix: Had to remove esc_html for short desc
             */
            $html .= '<label><input name="' . esc_html( $slug ) . '" type="checkbox" value="1"' . $checked . $disabled . '/>' . $short_desc . '</label>';
            
            if ( $long_desc ) {
                /**
                 * @todo  Fix: Had to remove esc_html for long desc
                 */
                $html .= '<p class="description">' . $long_desc . '</p>';

            } // $long_desc

            // Testing Only
            //$html .= 'Value: ' . $page_options[ $slug ];
            
            $html .= '</td>';
        
        } else {
            
            $html .= '<td colspan="2"><p>' . __( 'Error: There are parameters missing to properly display checkbox.', SECSAFE_SLUG ) . '</p></td>';
            
        } //is_array()

        $html .= '</tr>';

        return $html;

    } //form_checkbox()


    protected function form_text( $message, $class = '', $classes = '' ) {

        $html = '<tr class="form-text '. esc_html( $classes ) .'">';

        // Need to make sure message is sanitized when form_text is called
        $html .= '<td colspan="2"><p class="' . esc_html( $class ) . '">' . $message . '</p></td>';

        $html .= '</tr>';

        return $html;
        
    } // form_text();


    protected function form_input( $page_options, $name, $slug, $placeholder, $long_desc, $styles = '', $classes = '', $required = false ) {
    
        $html = '<tr class="form-input '. esc_html( $classes ) .'">';

        if ( is_array( $page_options ) && $slug ) {

            $value = ( isset( $page_options[ $slug ] ) ) ? $page_options[ $slug ] : '';

            $html .= $this->row_label( $name );
            
            $html .= '<td><input type="text" name="' . esc_html( $slug ) . '" placeholder="' . esc_html( $placeholder ) . '" value="' . esc_html( $value ) . '" style="' . esc_html( $styles ) . '">';

            if ( $long_desc ) {

                $html .= '<p class="description">' . esc_html( $long_desc ) . '</p>';

            } // $long_desc

            $html .= '</td>';

        } else {

            $html .= '<td>' . sprintf( __( 'Error: There is an issue displaying this form field: %s.', SECSAFE_SLUG ), 'input' ) . '</td>';

        } // is_array( $options )

        $html .= '</tr>';

        return $html;

    } // form_input()


    protected function form_select( $page_options, $name, $slug, $options, $long_desc, $classes = '' ) {

        $html = '<tr class="form-select '. esc_html( $classes ) .'">';

        if ( is_array( $page_options ) && $slug && $options ) {
            
            $html .= $this->row_label( $name );
            
            $html .= '<td><select name="' . esc_html( $slug ) . '">';
            
            if ( is_array( $options ) ) {

                foreach ( $options as $value => $label ) {

                    $selected = ( isset( $page_options[ $slug ] ) && $page_options[ $slug ] == $value ) ? ' SELECTED' : '';
        
                    $html .= '<option value="' . esc_html( $value ) . '"' . esc_html( $selected ) . '>' . esc_html( $label ) . '</option>';

                } // foreach()

            } else {

                $html .= '<option>' . __( 'Error: Form field "select" is not an array.', SECSAFE_SLUG ) . '</option>';

            } // is_array( $options )

            $html .= '</select>';

            if ( $long_desc ) {

                $html .= '<p class="description">' . esc_html( $long_desc ) . '</p>';

            } // $long_desc

            $html .= '</td>';

        } else {

            $html .= '<td colspan="2">' . sprintf( __( 'Error: There is an issue displaying this form field: %s.', SECSAFE_SLUG ), 'select' ) . '</td>';

        } // is_array( $options ) && $slug ...

        $html .= '</tr>';

        return $html;

    } // form_select();


    protected function row_label( $name ) {

        $html = '<th scope="row">';

        if ( $name ) {

            $html .= '<label>' . esc_html( $name ) . '</label>';

        }

        $html .= '</th>';

        return $html;

    } //row_label()

    /** 
     * Creates a File Upload Field
     */
    protected function form_file_upload( $text, $name, $long_desc = '', $classes = '' ) {

        $html = '<tr class="form-file-upload '. esc_html( $classes ) .'">';
        $html .= '<div class="file-upload-wrap cf"><label>' . esc_html( $text ) . '</label><input name="' . esc_html( $name ) . '" id="' . esc_html( $name ) . '" type="file" class="file-input"><input type="button" class="file-select" value="' . __( 'Choose File', SECSAFE_SLUG ) . '"><span class="file-selected">' . __( 'No File Chosen', SECSAFE_SLUG ) . '</span>';
        $html .= '</div></tr>';

        return $html;

    } // form_file_upload()

    /**
     * Creates Table Row For A Button
     * @since  0.3.0
     */ 
    protected function form_button( $text, $type, $value, $long_desc = false, $classes = '', $label = true, $name = false ) {

        $html = '<tr class="form-button '. esc_html( $classes ) .'">';
        
        if ( $label ) {

            $html .= $this->row_label( $text );
            
        }

        $html .= '<td>';
        $html .= $this->button( $text, $type, $value, $name );

        if ( $long_desc ) {

            $html .= '<p class="description">' . esc_html( $long_desc ) . '</p>';

        } // $long_desc

        $html .= '</td>';
        $html .= '</tr>';
        
        return $html;

    } // form_button();


    /**
     * Return HTML for Submit Button
     * @since  0.3.0
     */ 
    protected function button( $text = '', $type = '', $value = false, $name = false ) {

        // Default Values
        $text = ( $text ) ? $text : __( 'Save Changes', SECSAFE_SLUG );
        $type = ( $type ) ? $type : 'submit';
        $value = ( $value ) ? $value : $text;
        $name = ( $name ) ? $name : $type;

        $html = '<p class="' . esc_html( $type ) . '">';
        $classes = 'button ';

        if ( $type == 'submit' ) {

            $classes .= 'button-primary';
            $html .= '<input type="' . esc_html( $type ) . '" name="' . esc_html( $name ) . '" id="' . esc_html( $type ) . '" class="' . esc_html( $classes ) . '" value="' . esc_html( $value ) . '" />';
        
        } elseif ( $type == 'link' ) {

            $classes .= 'button-secondary';
            $html .= '<a href="' . esc_html( $value ) . '" class="' . esc_html( $classes ) . '">' . esc_html( $text ) . '</a>';

        } elseif ( $type == 'link-delete' ) {

            $classes .= 'button-secondary button-link-delete';
            $html .= '<a href="' . esc_html( $value ) . '" class="' . esc_html( $classes ) . '">' . esc_html( $text ) . '</a>';

        } // $type

        $html .= '</p>';

        return $html;
    
    } // button()

    /**
     * Displays this page's messages in a log format.
     * @since 1.1.0
     */ 
    private function display_messages() {

        if ( ! empty( $this->messages ) ) {

            $html = '<h3>' . __( 'Process Log', SECSAFE_SLUG ) . '</h3>
            <p><textarea style="width: 100%; height: 120px;">';

            foreach ( $this->messages as $m ) {

                // Display Messages
                $html .= ( $m[1] == 3 ) ? "\n!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! \n" : '';
                $html .= '- ' . esc_html( $m[0] ) . "\n";
                $html .= ( $m[1] == 3 ) ? "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! \n\n" : '';

            } // foreach()

            $html .= '</textarea></p>';

            return $html;

        } // ! empty()

    } // display_messages()
    

} // Admin()
