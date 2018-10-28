<?php
/**
 * ProRemont eSputnik Integration
 *
 * Plugin Name: ProRemont eSputnik Integration
 * Description: eSputnik Integration.
 * Version:     0.1
 */

defined( 'ABSPATH' ) or die();

require(__DIR__ . '/includes/eSputnikApi.php');
require(__DIR__ . '/includes/hooks.php');

register_activation_hook(__FILE__, 'pror_esputnik_install');
register_deactivation_hook(__FILE__, 'pror_esputnik_uninstall');

global $eSputnikApi;
$eSputnikApi = new eSputnikApi();
$eSputnikApi->auth(get_option('esputnik_login'), get_option('esputnik_password'));

function pror_esputnik_install() {
    global $wpdb;

	$table_name = $wpdb->prefix . 'pror_esputnik';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		create_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		action varchar(255) NOT NULL,
		data text NOT NULL,
		in_progress tinyint DEFAULT 0,
		done_time datetime DEFAULT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);


	if (!wp_next_scheduled('pror_esputnik_sync')) {
        wp_schedule_event( time(), '5min', 'pror_esputnik_sync' );
    }
}

function pror_esputnik_uninstall() {
    wp_clear_scheduled_hook('pror_esputnik_sync');

    // Do nothing
}

add_filter('cron_schedules', function ( $schedules ) {
	$schedules['5min'] = array('interval' => 5*60, 'display' => __('5 Minutes'));
	return $schedules;
});

add_filter('init', function ( $schedules ) {
	$schedules['5min'] = array('interval' => 5*60, 'display' => __('5 Minutes'));
	return $schedules;
});


add_action('admin_menu', function() {
    add_options_page('eSputnik Setup', 'eSputnik Setup', 'administrator', __FILE__, 'pror_esputnik_display_admin_page');
});

add_action('admin_init', function() {
    register_setting('esputnik-group', 'esputnik_login');
    register_setting('esputnik-group', 'esputnik_password');
});

function pror_esputnik_display_admin_page() {
    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
    do_action('pror_esputnik_sync');



	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
<div class="wrap">
    <h1>eSputnik Setup</h1>

    <form method="post" action="options.php">

        <?php settings_fields('esputnik-group'); ?>
        <?php do_settings_sections('esputnik-group'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">eSputnik Login</th>
                <td><input type="text" name="esputnik_login" value="<?php echo esc_attr( get_option('esputnik_login') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">eSputnik Password</th>
                <td><input type="password" name="esputnik_password" value="<?php echo esc_attr( get_option('esputnik_password') ); ?>" /></td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>

    <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'pror_esputnik';
        $res = $wpdb->get_results("SELECT * FROM $table_name ORDER BY create_time DESC LIMIT 100");
    ?>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <td class="manage-column">ID</td>
                <td class="manage-column">Create Time</td>
                <td class="manage-column">Action</td>
                <td class="manage-column">Data</td>
                <td class="manage-column">Done Time</td>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($res as $task): ?>
            <tr>
                <td class="manage-column"><?php echo $task->id; ?></td>
                <td class="manage-column"><?php echo $task->create_time; ?></td>
                <td class="manage-column"><?php echo $task->action; ?></td>
                <td class="manage-column"><?php echo $task->data; ?></td>
                <td class="manage-column"><?php echo $task->done_time; ?></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</div>

<?php
}

function pror_esputnik_queue_action($action, $data) {
    global $wpdb;

	$table_name = $wpdb->prefix . 'pror_esputnik';
    $wpdb->insert($table_name,
        array(
            'create_time' => current_time('mysql', true),
            'action' => $action,
            'data' => json_encode($data),
        ),
        array(
            '%s',
            '%s',
            '%s',
        )
    );

    do_action('pror_esputnik_sync');
}

add_action('pror_esputnik_sync', function() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pror_esputnik';

    for ($i = 0; $i < 5; $i++) {
        $record = $wpdb->get_row("SELECT * FROM $table_name WHERE done_time IS NULL AND in_progress = 0 ORDER BY create_time LIMIT 1");

        if (!$record || !$record->id) {
            break;
        }

        $wpdb->query($wpdb->prepare("UPDATE $table_name SET in_progress = 1 WHERE id = {$record->id}"));

        $res = pror_esputnik_process_action($record->action, json_decode($record->data, true));
        if ($res) {
            $wpdb->query($wpdb->prepare("UPDATE $table_name SET done_time = %s WHERE id = {$record->id}", [current_time('mysql', true)]));
        }

        $wpdb->query($wpdb->prepare("UPDATE $table_name SET in_progress = 0 WHERE id = {$record->id}"));
    }
});

function pror_esputnik_process_action($action, $data) {
    global $eSputnikApi;

    switch ($action) {
        case 'CONTACT_POST':
            return $eSputnikApi->postContact((object)$data['contact'], $data['groups']);

        case 'EVENT_POST':
            return $eSputnikApi->postEvent((object)$data['event']);
    }

    return false;
}
