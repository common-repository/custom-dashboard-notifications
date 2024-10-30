<?php

/*
Plugin Name: Custom Dashboard Notifications
Plugin URI:  https://ramachandrandotblog.wordpress.com/
Description: You can use this plugin to show notifications on the WordPress Dashboard page.
Version:     1.0
Author:      Rams
Author URI:  https://ramachandrandotblog.wordpress.com/about/
License:     CDNSGPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Last Updated: 8/26/2017 - Rams
*/


// Create custom plugin settings menu
function dashbd_notificns_admin_page() {

	//create new top-level menu
	add_menu_page('Custom Dashboard Notifications Settings', 'Custom Notifications', 'manage_options', 'dashbd-notificns-settings-page', 'dashbd_notificns_admin_settings_page');
}

// Create custom plugin settings page on the admin side
function dashbd_notificns_admin_settings_page() {
?>
<div class="custom-cdn-wrap">
<h2>Custom Notifications </h2>
<h4>Type the information that you want to show on the Dashboard page.</h4><br>

<form method='post'>
      <?php wp_nonce_field('dashbd_notificns_nonce_action', 'dashbd_notificns_nonce_field');

          $cstm_ntfns_content = get_option('cstm_ntfns_special_content');
          
          wp_editor( wpautop(stripslashes(wp_kses_post($cstm_ntfns_content))), 'cstm_ntfns_special_content' );

          submit_button('Save Changes', 'primary'); ?>
   </form>

<p style="text-align: right;">Plugin developed by <a href="http://www.raxcor.com/" target="_blank" title="Raxcor">Raxcor</a></p>
</div>
<?php }

// Save the content of WP editor inputs.
// add_action('admin_init', 'save_dashbd_notificns_text_editor', 10);
function save_dashbd_notificns_text_editor() {
  // check the nonce, update the option etc...
  if( isset($_POST['dashbd_notificns_nonce_field']) && 
      check_admin_referer('dashbd_notificns_nonce_action', 'dashbd_notificns_nonce_field')) {

   if(isset($_POST['cstm_ntfns_special_content'])) {
    $cstm_ntfns_save_content=sanitize_text_field( htmlentities($_POST['cstm_ntfns_special_content']) );

    update_option('cstm_ntfns_special_content', html_entity_decode(($cstm_ntfns_save_content)));
   }
  }
}


// Initiate the admin page option here:
function dashbd_notificns_register_dashboard_widget() {
	global $show_notificns_content;
	
	wp_add_dashboard_widget('widget_show_notifications', __('Important Notice', 'dashbd_notificns'), 'dashbd_notificns_show_notifications', 'high');

    wp_register_style('style', plugins_url('style.css',__FILE__ ));

}
add_action('wp_dashboard_setup', 'dashbd_notificns_register_dashboard_widget');

function dashbd_notificns_show_notifications() {
	// Get your output
	$show_notificns_content = get_option('cstm_ntfns_special_content');
	print_r(wpautop(stripslashes($show_notificns_content)));
}


// Inlcude a CSS file into the plugin
function dashbd_notificns_plugin_include_css() {
    wp_register_style( 'custom',  plugin_dir_url( __FILE__ ) . 'css/custom.css');
    wp_enqueue_style( 'custom' );
}
add_action( 'admin_print_styles', 'dashbd_notificns_plugin_include_css' );

// Call the appropriate hooks
add_action('admin_menu', 'dashbd_notificns_admin_page');
add_action('admin_init', 'save_dashbd_notificns_text_editor', 10); ?>