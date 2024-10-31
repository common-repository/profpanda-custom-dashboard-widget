<?php
/*
Plugin Name: ProfPanda Custom Dashboard Widget
Plugin URI:  http://profpanda.com.br/profpanda_custom_dashboard_widget
Description: This plugin allows you to create extra Dashboard Widgets Areas
Version:     1.0.0
Author:      Anderson Profpanda
Author URI:  http://profpanda.com.br
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: profpandacdw
Domain Path: /languages

ProfPanda Custom Dashboard Widget is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
ProfPanda Custom Dashboard Widget is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with ProfPanda Custom Dashboard Widget. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/* LOADING TEXTDOMAIN */
add_action( 'plugins_loaded', 'pcdw_load_plugin_textdomain' );
 
function pcdw_load_plugin_textdomain() {
    load_plugin_textdomain( 'profpandacdw', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/* ADDING STYLE AND SCRIPTS THINGS */
function load_custom_pcdw_admin_style($hook) {
        // Load only on ?page=mypluginname
		if($hook != 'settings_page_pcdw') {
                return;
        }
        wp_enqueue_style( 'pcdw-style', plugins_url('/css/pcdw-style.css', __FILE__) );
        wp_enqueue_script('pcdw-script', plugins_url('/js/pcdw-script.js', __FILE__),array('jquery'),'1.11.0', true);

}
add_action( 'admin_enqueue_scripts', 'load_custom_pcdw_admin_style' );

/* ACTIVATING PLUGIN */
function profpanda_custom_dashboard_widget_install()
{
        
}
register_activation_hook( __FILE__, 'profpanda_custom_dashboard_widget_install' );


/* DEACTIVATING PLUGIN */
function profpanda_custom_dashboard_widget_deactivation()
{
    function pcdw_remove_options_page()
	{
    	remove_submenu_page('options-general.php','profpanda_custom_dashboard_widget');
	}
	add_action('admin_menu', 'pcdw_remove_options_page', 99);
}
register_deactivation_hook( __FILE__, 'profpanda_custom_dashboard_widget_deactivation' );

/**
 * @internal    never define functions inside callbacks.
 *              these functions could be run multiple times; this would result in a fatal error.
 */

function pcdw_settings_init()
{
	// register a new setting for "pcdw" page
    register_setting('pcdw', 'pcdw_options');
    // register a new section in the "pcdw" page
    add_settings_section(
        'pcdw_section_dashboard',
        __('<span class="dash">Dashboard</span>', 'profpandacdw'),
        'pcdw_section_dashboard_cb',
        'pcdw'
    );

    add_settings_field(
        'pcdw_field_dash_title', 
        __('Dashboard Title', 'profpandacdw'),
        'pcdw_field_dash_title_cb',
        'pcdw',
        'pcdw_section_dashboard',
        [
            'label_for'         => 'pcdw_field_dash_title',
            'class'             => 'pcdw_row dtitle',
            'pcdw_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'pcdw_field_dash_content', 
        __('Dashboard Content', 'profpandacdw'),
        'pcdw_field_dash_content_cb',
        'pcdw',
        'pcdw_section_dashboard',
        [
            'label_for'         => 'pcdw_field_dash_content',
            'class'             => 'pcdw_row dcontent',
            'pcdw_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'pcdw_field_dash_position', 
        __('Dashboard Position', 'profpandacdw'),
        'pcdw_field_dash_position_cb',
        'pcdw',
        'pcdw_section_dashboard',
        [
            'label_for'         => 'pcdw_field_dash_position',
            'class'             => 'pcdw_row dposition',
            'pcdw_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'pcdw_field_dash_priority', 
        __('Widget Priority', 'profpandacdw'),
        'pcdw_field_dash_priority_cb',
        'pcdw',
        'pcdw_section_dashboard',
        [
            'label_for'         => 'pcdw_field_dash_priority',
            'class'             => 'pcdw_row dpriority',
            'pcdw_custom_data' => 'custom',
        ]
    );

}
/**
 * register our pht_settings_init to the admin_init action hook
 */
add_action('admin_init', 'pcdw_settings_init');


function pcdw_section_dashboard_cb($args)
{
    ?>
     <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Create your own Dashboard Widget.', 'profpandacdw'); ?></p>
    <?php
}

function pcdw_field_dash_title_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('pcdw_options');
    // output the field
    ?>
    <input type="text" id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['pcdw_custom_data']); ?>"
            name="pcdw_options[<?= esc_attr($args['label_for']); ?>]" 
            size="40" value="<?php echo $options['pcdw_field_dash_title']; ?>" />
        
    <p class="description">
        <?= esc_html__('Enter the Title of your Dashboard Widget.', 'profpandacdw'); ?>
    </p>
    <?php
}

function pcdw_field_dash_content_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('pcdw_options');
    // output the field
    ?>
    <textarea id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['pcdw_custom_data']); ?>"
            name="pcdw_options[<?= esc_attr($args['label_for']); ?>]" 
            cols="40" rows="10"  
            placeholder="<?php esc_html__('Type the content of the Widget, HTML is supported','profpandacdw');?>"/><?php echo $options['pcdw_field_dash_content']; ?>
    </textarea>
        
    <p class="description">
        <?= esc_html__('Enter content of your Dashboard Widget.', 'profpandacdw'); ?>
    </p>
    <?php
}

function pcdw_field_dash_position_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('pcdw_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['pcdw_custom_data']); ?>"
            name="pcdw_options[<?= esc_attr($args['label_for']); ?>]"
    >
        <option value="core" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'core', false)) : (''); ?>>
            <?= esc_html__('Core', 'profpandacdw'); ?>
        </option>
        <option value="side" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'side', false)) : (''); ?>>
            <?= esc_html__('Side', 'profpandacdw'); ?>
        </option>
    </select>
    <p class="description">
        <?= esc_html__('Allow you to choose to alocate your widget in the left (core) or the right (side) on the screen.', 'profpandacdw'); ?>
    </p>
    <?php
}

function pcdw_field_dash_priority_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('pcdw_options');
    // output the field
    
    if (isset($options['pcdw_field_dash_priority']) and $options['pcdw_field_dash_priority']=="on"){
    ?>	
    <input type="checkbox" id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['pcdw_custom_data']); ?>"
            name="pcdw_options[<?= esc_attr($args['label_for']); ?>]" checked="checked">
    <?php
    } else {
    ?>
    <input type="checkbox" id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['pcdw_custom_data']); ?>"
            name="pcdw_options[<?= esc_attr($args['label_for']); ?>]">
    <?php
	}
    ?>
    <p class="description">
        <?= esc_html__('Show your widget on top of the Dashboard. ', 'profpandacdw'); ?>
    </p>
    <?php
}

/**
 * top level menu
 */
function pcdw_options_page()
{
    // add top level menu page
    add_submenu_page(
    	'options-general.php',
        'ProfPanda Custom Dashboard Widget',
        'ProfPanda Custom Dashboard Widget',
        'manage_options',
        'pcdw',
        'pcdw_options_page_html'
    );
}
 
/**
 * register our pht_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'pcdw_options_page');
 
/**
 * sub menu:
 * callback functions
 */
function pcdw_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
       // add_settings_error('pht_messages', 'pht_message', __('Settings Saved', 'profpanda'), 'updated');
    }
 
    // show error/update messages
    settings_errors('pcdw_messages');
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "pht"
            settings_fields('pcdw');
            // output setting sections and their fields
            // (sections are registered for "pht", each field is registered to a specific section)
            do_settings_sections('pcdw');
            // output save settings button
            submit_button(__('Save Settings','profpandacdw'));
            ?>
        </form>
    </div>
    <?php
}

function profpanda_custom_dashboard_widget() {
	$options = get_option('pcdw_options');
	$title = $options['pcdw_field_dash_title'];
	if(strlen($options['pcdw_field_dash_title']) > 0) {
		$title = stripslashes($options['pcdw_field_dash_title']);
	}
	if(current_user_can('level_10')) {
		$title .= "<span class='postbox-title-action'><a href='options-general.php?page=pcdw' class='edit-box open-box'>" . __('Configure','profpandacdw') . "</a></span>";
	}
	wp_add_dashboard_widget('profpanda_custom_dashboard_widget', $title, 'profpanda_custom_dashboard_widget_content');

	if (isset($options['pcdw_field_dash_priority']) and $options['pcdw_field_dash_priority']=="on"){
		global $wp_meta_boxes;
	 	// Get the regular dashboard widgets array 
	 	// (which has our new widget already but at the end)
	  	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	  	// Backup and delete our new dashboard widget from the end of the array
	  	$example_widget_backup = array( 'profpanda_custom_dashboard_widget' => $normal_dashboard['profpanda_custom_dashboard_widget'] );
	 	unset( $normal_dashboard['profpanda_custom_dashboard_widget'] );
	  	// Merge the two arrays together so our widget is at the beginning
	  	$sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );
	  	// Save the sorted array back into the original metaboxes 
	  	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
  	}
  	
  	if ($options['pcdw_field_dash_position']=='side'){
  		add_meta_box('profpanda_custom_dashboard_widget', $title, 'profpanda_custom_dashboard_widget_content', 'dashboard', 'side', 'high');
  	} 

}

// Hook it in to the dashboard setup action
add_action('wp_dashboard_setup', 'profpanda_custom_dashboard_widget');

function profpanda_custom_dashboard_widget_content() {
	$options = get_option('pcdw_options');
	$content = $options['pcdw_field_dash_content'];
	if(strlen($content) == 0) {
		if(current_user_can('level_10')) {
			$content = __("You should <a href='options-general.php?page=pcdw'>define</a> your custom content to go here.","profpandacdw");
		} else {
			$content = __("You should ask your admin to put some custom content here.","profpandacdw");
		}
	}
	echo stripslashes($content);
}



?>