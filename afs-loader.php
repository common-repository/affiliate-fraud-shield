<?php
/*
Plugin Name: AFS Loader
Description: Sends Form Data to Affiliate Fraud Shield's alert system
Version: 1.0.5
Author: Global Risk Technologies - Erik Olson = e.olson@chargebacks911.com
Author URI: http://chargebacks911.com
*/


//menu items

// Call afs_api_key_menu function to load plugin menu in dashboard
add_action( 'admin_menu', 'afs_api_key_menu' );

// Create WordPress admin menu
if( !function_exists("afs_api_key_menu") )
{
    function afs_api_key_menu(){

        $page_title = 'AFS API Key';
        $menu_title = 'AFS API Key';
        $capability = 'manage_options';
        $menu_slug  = 'afs-api-key';
        $function   = 'afs_api_key_page';
        $icon_url   = 'dashicons-media-code';
        $position   = 4;

        add_menu_page( $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            $position );

        // Call update_afs_api_key function to update database
        add_action( 'admin_init', 'update_afs_api_key' );

    }
}

// Create function to register plugin settings in the database
if( !function_exists("update_afs_api_key") )
{
    function update_afs_api_key() {
        register_setting( 'afs-api-key-settings', 'afs_api_key' );
        register_setting( 'afs-api-key-settings', 'afs_api_type' );
    }
}


if( !function_exists("afs_api_key_page") )
{
    function afs_api_key_page(){
        if (strpos(wp_get_referer(), 'page=afs-api-key')) {
            echo "<div id=\"message\" class=\"updated notice notice-success is-dismissible\"><p>API Key Updated.
</p><button type=\"button\" class=\"notice-dismiss\"></button></div>";
        }


        ?>
        <h1>AFS API Key</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'afs-api-key-settings' ); ?>
            <?php do_settings_sections( 'afs-api-key-settings' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">AFS API Key:</th>
                    <td><input type="text" name="afs_api_key" value="<?php echo get_option('afs_api_key'); ?>"/></td>
                    <td></td>
                </tr>
                <tr valign="top">
                    <th scope="row">AFS API Type:</th>
                    <td><input type="text" name="afs_api_type" value="<?php echo get_option('afs_api_type'); ?>"/></td>
                    <td></td>

                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <?php
    }
}
add_action( 'wp_enqueue_scripts','afs_api_key_init');

function afs_api_key_init() {
    if ( ! is_admin() ) {
    $afs_api_key = get_option('afs_api_key');
    $afs_api_type = get_option('afs_api_type');
    $deps=array();
    $ver = null;
    $in_footer=true;
    wp_enqueue_script(
        'aff-api-key-js',
        'https://afs.chargebacks911.com/js/shield.js?key=' . $afs_api_key . '&type=' . $afs_api_type,
        $deps,
        $ver,
        $in_footer );
    }
}
