<?php
	require_once(ABSPATH . 'wp-admin/upgrade.php');

	global $wpdb;

	$cur_ver = get_option("xhanch_my_prayer_time_version");
	if($cur_ver == ''){
		$cur_ver = '1.0.0';
		add_option("xhanch_my_prayer_time_version", $cur_ver);
	}
?>