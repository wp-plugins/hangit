<?php
/*
Plugin Name: Hangit
Plugin URI: http://www.velvary.com.au/vanilla-beans/wordpress/hangit
Description: Addon for products and posts that would benefit from their featured image being dragged and resized onto background images
Version: 1.01
Author: vsmash
Author URI: http://www.velvary.com.au
License: GPLv2
*/

            // If this file is called directly, abort.
            if ( ! defined( 'WPINC' ) ) {
                    die;
            }

        if ( !defined( 'VBEANHANGIT_PLUGIN_DIR' ) ) {
                    define( 'VBEANHANGIT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            }
            if ( !defined( 'VBEANHANGIT_PLUGIN_URL' ) ) {
                    define( 'VBEANHANGIT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            }
            if ( !defined( 'VBEANHANGIT_PLUGIN_FILE' ) ) {
                    define( 'VBEANHANGIT_PLUGIN_FILE', __FILE__ );
            }
            if ( !defined( 'VBEANHANGIT_PLUGIN_VERSION' ) ) {
                    define( 'VBEANHANGIT_PLUGIN_VERSION', '1.01' );
            }

            /*===========================================
                    Define Includes
            ===========================================*/
            $includes = array(
                'functions.php'
            );

            $frontend_includes = array(
                'wall-placement.php'
            );


            $adminincludes= array(
                'settings.php'
                
            );

            /*===========================================
                    Load Includes
            ===========================================*/
            // Common
            foreach ( $includes as $include ) {
                    require_once( dirname( __FILE__ ) . '/inc/'. $include );
            }
            if(is_admin()){		
            //load admin part
                foreach ( $adminincludes as $admininclude ) {
                    require_once( dirname( __FILE__ ) . '/inc/admin/'. $admininclude );
                }
            }else{
            //load front part
                foreach ( $frontend_includes as $include ) {
                        require_once( dirname( __FILE__ ) . '/inc/'. $include );
                }
            }


            add_action('admin_menu', 'vbean_hangit_create_menu');
            
            
            
            
            
            

            if(!function_exists('vbean_hangit_create_menu')){

                
                
                
                
                
            function vbean_hangit_create_menu() {


            if ( empty ( $GLOBALS['admin_page_hooks']['vanillabeans-settings'] ) ){
            //create new top-level menu
        	add_menu_page('Vanilla Beans', 'Vanilla Beans', 'administrator', 'vanillabeans-settings', 'VanillaBeans\LiveSettings', VBEANHANGIT_PLUGIN_URL.'vicon.png', 4);
            }
            
            
            
            $vbean_hook = add_submenu_page('vanillabeans-settings', 'Hangit', 'Hangit', 'administrator', __FILE__,'VanillaBeans\hangit\SettingsPage');
            
            
                    //call register settings function
                    add_action( 'admin_init', 'VanillaBeans\hangit\RegisterSettings' );
                }
            }
            
            
            