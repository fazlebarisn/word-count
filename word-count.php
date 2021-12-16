<?php
/*
    Plugin Name: My New Plugin
    Description: A new Plugin
    Version: 1.0
    Author: Fazle Bari
    Author URI: https://chitabd.com
*/

if( file_exists( dirname(__FILE__) . '/vendor/autoload.php') ){
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use Inc\Activate;
use Inc\Deactivate;
class WordCountAndTimePlugin{

    function register()
    {
        add_action( 'admin_menu' , array( $this , 'adminPage') );
        add_action( 'admin_init' , array( $this , 'settings') );
        add_action( 'the_content' , array( $this , 'ifWrap') );
    }

    function ifWrap( $content ){
        if( 
            ( is_main_query() AND is_single() )
             AND 
            (
                get_option('wcp_wordcount' , '1') OR
                get_option('wcp_charactorcount' , '1') OR
                get_option('wcp_readtime' , '1') 
            )
        ){
            return $this->createHTML($content);
        }

        // return default content
        return $content;
    }

    // Our extra content
    function createHTML( $content ){

        $html = '<h3>' . esc_html(get_option('wcp_headline' , 'Post Statistice')) . '</h3><p>';

        if( get_option('wcp_wordcount' , '1') OR get_option('wcp_readtime' , '1') ){
            $wordCount = str_word_count( strip_tags( $content ) );
        }

        if( get_option('wcp_wordcount' , '1') ){
            $html.= 'This post has ' . $wordCount . ' words.<br>';
        }

        if( get_option('wcp_charactorcount' , '1') ){
            $html.= 'This post has ' . strlen( strip_tags( $content ) ) . ' Characters.<br>';
        }

        if( get_option('wcp_readtime' , '1') ){
            $html.= 'This post will take ' . round( $wordCount/225 ) . ' min to read.';
        }

        $html.= '</p>';

        if( get_option('wcp_location' , '0') == '0' ){
            return $html . $content;
        }

        return $content . $html;
    }

    function settings(){
        // add a section on page
        add_settings_section( 'wcp_first_section' , 'Basic Settings' , null , 'word-count-settings-page');
        //add_settings_section( 'wcp_advance_section' , 'Advance Settings' , null , 'word-count-settings-page');

        // For location
        add_settings_field('wcp_location' , 'Display Location' , array($this , 'locationHTML') , 'word-count-settings-page' , 'wcp_first_section');
        register_setting('wordcountplugin' , 'wcp_location' , array('sanitize_callback' => 'sanitize_text_field' , 'default' => '0') );

        // For Heading
        add_settings_field('wcp_headline' , 'Headline Text' , array($this , 'headlineHTML') , 'word-count-settings-page' , 'wcp_first_section');
        register_setting('wordcountplugin' , 'wcp_headline' , array('sanitize_callback' => 'sanitize_text_field' , 'default' => 'Post Statistics') );

        // For Word Count
        add_settings_field('wcp_wordcount' , 'Word Count' , array($this , 'checkboxtHTML') , 'word-count-settings-page' , 'wcp_first_section' , array('theName' => 'wcp_wordcount') );
        register_setting('wordcountplugin' , 'wcp_wordcount' , array('sanitize_callback' => 'sanitize_text_field' , 'default' => '1') );

        // For Character Count
        add_settings_field('wcp_charactorcount' , 'Character Count' , array($this , 'checkboxtHTML') , 'word-count-settings-page' , 'wcp_first_section' , array('theName' => 'wcp_charactorcount') );
        register_setting('wordcountplugin' , 'wcp_charactorcount' , array('sanitize_callback' => 'sanitize_text_field' , 'default' => '0') );

        // For Read Time Count
        add_settings_field('wcp_readtime' , 'Read Time' , array($this , 'checkboxtHTML') , 'word-count-settings-page' , 'wcp_first_section' , array('theName' => 'wcp_readtime') );
        register_setting('wordcountplugin' , 'wcp_readtime' , array('sanitize_callback' => 'sanitize_text_field' , 'default' => '1') );
    }

    // All checkbox data will generate from here
    function checkboxtHTML( $argc ){
        ?>
            <input type="checkbox" name="<?php echo $argc['theName'] ?>" value="1" <?php checked(get_option( $argc['theName'] ) , '1') ?> >
        <?php
    }

    // All text field data will generate from here
    function headlineHTML(){
        ?>
            <input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option('wcp_headline') ) ?>">
        <?php
    }

    // All selectbox data will generate from here
    function locationHTML(){
        ?>
            <select name="wcp_location">
                <option value="0" <?php selected(get_option('wcp_location') , '0' ) ?> >Begening of post</option>
                <option value="1" <?php selected(get_option('wcp_location') , '1' ) ?> >End of post</option>
            </select>
        <?php
    }

    // Add a page to admin panel ( under settings menu )
    function adminPage(){
        add_options_page( 'Word Count Settings' , 'Word Count' , 'manage_options' , 'word-count-settings-page' , array( $this , 'pageHTML') );
    }
    
    function pageHTML(){
        ?>
           <div class="wrap">
                <h1> Word Count Settings</h1>
                <form action="options.php" method="POST">
                    <?php
                        settings_fields('wordcountplugin');
                        do_settings_sections('word-count-settings-page');
                        submit_button();
                    ?>
                </form>
           </div>
        <?php
    }

    public function activate(){
        Activate::activate();
    }

    public function deactivate(){
        Deactivate::deactivate();
    }
}

if( class_exists( 'WordCountAndTimePlugin' )){
    $wordCountAndTimePlugin = new WordCountAndTimePlugin;
    $wordCountAndTimePlugin->register();
}

