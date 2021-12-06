<?php
/*
    Plugin Name: My New Plugin
    Description: A new Plugin
    Version: 1.0
    Author: Fazle Bari
    Author URI: https://chitabd.com
*/

class WordCountAndTimePlugin{

    function __construct()
    {
        add_action( 'admin_menu' , array( $this , 'adminPage') );
        add_action( 'admin_init' , array( $this , 'settings') );
    }

    function settings(){
        add_settings_section( 'wcp_first_section' , null , null , 'word-count-settings-page');

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

        // For Character Count
        add_settings_field('wcp_readtime' , 'Read Time' , array($this , 'checkboxtHTML') , 'word-count-settings-page' , 'wcp_first_section' , array('theName' => 'wcp_readtime') );
        register_setting('wordcountplugin' , 'wcp_readtime' , array('sanitize_callback' => 'sanitize_text_field' , 'default' => '1') );
    }

    function checkboxtHTML( $argc ){
        ?>
            <input type="checkbox" name="<?php echo $argc['theName'] ?>" value="1" <?php checked(get_option( $argc['theName'] ) , '1') ?> >
        <?php
    }


    function headlineHTML(){
        ?>
            <input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option('wcp_headline') ) ?>">
        <?php
    }
    function locationHTML(){
        ?>
            <select name="wcp_location">
                <option value="0" <?php selected(get_option('wcp_location') , '0' ) ?> >Begening of post</option>
                <option value="1" <?php selected(get_option('wcp_location') , '1' ) ?> >End of post</option>
            </select>
        <?php
    }

    function adminPage(){
        add_options_page( 'Word Count Settings' , 'Word Count' , 'manage_options' , 'word-count-settings-page' , array( $this , 'pageHTML') );
    }
    
    function pageHTML(){
        ?>
           <div class="warp">
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
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin;
