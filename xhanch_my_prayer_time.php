<?php
	/*
		Plugin Name: Xhanch - My Prayer Time
		Plugin URI: http://xhanch.com/wp-plugin-my-prayer-time/
		Description: Display Moslem/Islamic prayer time table based on visitor's IP (daily and monthly).
		Author: Susanto BSc (Xhanch Studio)
		Author URI: http://xhanch.com/
		Version: 1.0.2
	*/

	@define("MANAGEMENT_PERMISSION", "edit_themes");

	function xhanch_my_prayer_time_install () {
		require_once(dirname(__FILE__).'/installer.php');
	}
	register_activation_hook(__FILE__,'xhanch_my_prayer_time_install');
		
	require_once(dirname(__FILE__).'/xhanch_my_prayer_time.function.php');	

	//Post Inline

	function xhanch_my_prayer_time_advanced_table($content){
		$keyword = '[xhanch-my-prayer-time]';
		$replacement = '';

		if(strpos($content, $keyword) === false)
			return $content;

		$client_info = xhanch_my_prayer_time_get_client_info();		
		$cur_mm = date('n');
		$cur_yy = date('Y');
				
		$db_month = array(
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Aug',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dec'
		);

		$cbo_date_mm = '<select onchange="xhanch_my_prayer_time_page_time_load()" id="xhanch_my_prayer_time_page_date_mm" style="font:inherit;margin:0;width:64px">';
		foreach($db_month as $month_val=>$month_name)
			$cbo_date_mm .= '<option value="'.$month_val.'" '.($month_val==$cur_mm?'selected="selected"':'').'>'.$month_name.'</option>';		
		$cbo_date_mm .= '</select>';

		$cbo_date_yy = '<select onchange="xhanch_my_prayer_time_page_time_load()" id="xhanch_my_prayer_time_page_date_yy" style="font:inherit;margin:0;width:62px"">';
		for($i=date('Y');$i<=date('Y')+5;$i++)
			$cbo_date_yy .= '<option value="'.$i.'" '.($i==$cur_yy?'selected="selected"':'').'>'.$i.'</option>';		
		$cbo_date_yy .= '</select>';
		
		$replacement = '			
			<div id="xhanch_my_prayer_time_table">
				<table style="font:inherit" cellpadding="0" cellspacing="4" width="100%">
					<!--<tr>
						<td width="50px">Country</td>
						<td>'.$client_info['country_name'].'</td>
					</tr>
					<tr>
						<td>City</td>
						<td>'.$client_info['city'].'</td>
					</tr>-->
					<tr>
						<td>Month</td>
						<td>'.$cbo_date_mm.' '.$cbo_date_yy.'</td>
					</tr>
				</table>
				<script language="javascript">
					ajax_sct_reg("xhanch_my_prayer_time_page_time");
				</script>
				<div class="ajax_sct">	
					<div id="sct_ajax_xhanch_my_prayer_time_page_time_prg" class="ajax_progress" style="display: none;" align="center"></div>
					<div id="sct_ajax_xhanch_my_prayer_time_page_time">
						<div id="sct_ajax_xhanch_my_prayer_time_page_time_msg" class="ajax_message" style="display: none;"></div>
						<table style="font: inherit; cellpadding="0" cellspacing="4" width="100%" class="xhanch_my_prayer_time_monthly" id="xhanch_my_prayer_time_monthly">
							<tbody>
								<tr>
									<td class="header" width="10%">Day</td>
									<td class="header" width="15%">Fajr</td>
									<td class="header" width="15%">Sunrise</td>
									<td class="header" width="15%">Dhuhr</td>
									<td class="header" width="15%">Asr</td>
									<td class="header" width="15">Maghrib</td>
									<td class="header" width="15%">Isha</td>
								</tr>
							</tbody>
						</table>					
						<div align="right" style="margin-top:8px">
							<a id="lnk_xhanch_my_prayer_time_print" href="'.xhanch_my_prayer_time_get_dir('url').'/xhanch_my_prayer_time.print.php?date_mm='.$cur_mm.'&date_yy='.$cur_yy.'" target="_blank">Print This Table</a>
						</div>
					</div>
				</div>
				<div class="credit"><a href="http://xhanch.com/wp-plugin-my-prayer-time/" rel="section" title="My Prayer Time - A free WordPress plugin to display your Moslem Prayer Timr for your visitor">My Prayer Time</a>, <a href="http://xhanch.com/" rel="section" title="Developed by Xhanch Studio">by Xhanch</a></div>
				<script type="text/javascript">xhanch_my_prayer_time_page_time_load();</script>
			</div>
		';	

		return str_ireplace($keyword, $replacement, $content);
	}

	//Widget

	function widget_xhanch_my_prayer_time_ajax(){
		$client_info = xhanch_my_prayer_time_get_client_info();
		$db_city = xhanch_my_prayer_time_get_db('city/'.$client_info['country_code']);
		$cbo_city = '<select onchange="xhanch_my_prayer_time_widget_time_load()" width="130px" id="xhanch_my_prayer_time_widget_city" style="font:inherit;margin:0;width:130px">';
		foreach($db_city as $city=>$city_info){
			$city = utf8_encode($city);
			if($city == '_')
				continue;

			$sel = '';
			if(strtolower($client_info['city'])==strtolower($city))
				$sel = ' selected="selected" ';
			elseif(substr(strtolower($client_info['region']),0,strlen($city))  ==strtolower($city))
				$sel = ' selected="selected" ';

			$cbo_city .= '<option value="'.$city.'" '.$sel.'>'.$city.'</option>';
		}
		$cbo_city .= '</select>';
		echo $cbo_city;
	}

	function widget_xhanch_my_prayer_time_table_ajax($prm){
?>
		<table style="font:inherit" cellpadding="0" cellspacing="4" width="100%">
			<tr>
				<td><b>Fajr</b></td>
				<td width="50px" style="font-weight:bold"><span id="xhanch_my_prayer_time_widget_time_fajr"><?php echo $prm['fajr']; ?></span></td>
			</tr>
			<tr>
				<td>Sunrise</td>
				<td><span id="xhanch_my_prayer_time_widget_time_sunrise"></span></td>
			</tr>
			<tr bgcolor="gray" height="1px">
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td><b>Zuhr</b></td>
				<td style="font-weight:bold"><span id="xhanch_my_prayer_time_widget_time_zuhr"></span></td>
			</tr>
			<tr>
				<td><b>Asr</b></td>
				<td style="font-weight:bold"><span id="xhanch_my_prayer_time_widget_time_asr"></span></td>
			</tr>
			<tr bgcolor="gray" height="1px">
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td><b>Maghrib</b></td>
				<td style="font-weight:bold"><span id="xhanch_my_prayer_time_widget_time_maghrib"><?php echo $prm['maghrib']; ?></span></td>
			</tr>
			<tr>
				<td><b>Isha</b></td>
				<td style="font-weight:bold"><span id="xhanch_my_prayer_time_widget_time_isha"><?php echo $prm['isha']; ?></span></td>
			</tr>
		</table>
<?php
	}
	
	function widget_xhanch_my_prayer_time_daily($args) {
		extract($args);

		//$client_info = xhanch_my_prayer_time_get_client_info();
		$cur_dd = date('d');
		$cur_mm = date('n');
		$cur_yy = date('Y');
		
		$cbo_date_dd = '<select onchange="xhanch_my_prayer_time_widget_time_load()" id="xhanch_my_prayer_time_widget_date_dd" style="font:inherit;margin:0;width:38px"">';
		for($i=1;$i<=31;$i++)
			$cbo_date_dd .= '<option value="'.$i.'"'.($i==$cur_dd?'selected="selected"':'').'>'.$i.'</option>';		
		$cbo_date_dd .= '</select>';

		$db_month = array(
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Aug',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dec'
		);
		$cbo_date_mm = '<select onchange="xhanch_my_prayer_time_widget_time_load()" id="xhanch_my_prayer_time_widget_date_mm" style="font:inherit;margin:0;width:38px">';
		foreach($db_month as $month_val=>$month_name)
			$cbo_date_mm .= '<option value="'.$month_val.'" '.($month_val==$cur_mm?'selected="selected"':'').'>'.$month_val.'</option>';		
		$cbo_date_mm .= '</select>';

		$cbo_date_yy = '<select onchange="xhanch_my_prayer_time_widget_time_load()" id="xhanch_my_prayer_time_widget_date_yy" style="font:inherit;margin:0;width:46px"">';
		for($i=date('Y');$i<=date('Y')+5;$i++)
			$cbo_date_yy .= '<option value="'.$i.'" '.($i==$cur_yy?'selected="selected"':'').'>'.$i.'</option>';		
		$cbo_date_yy .= '</select>';				
?>
		<div id="xhanch_my_prayer_time">
			<?php echo $before_widget; ?>
			<?php echo $before_title.'Prayer Time'.$after_title; ?>	
			<table style="font:inherit" cellpadding="0" cellspacing="4" width="100%">
				<!--<tr>
					<td width="50px">Country</td>
					<td>'.$client_info['country_name'].'</td>
				</tr>
				<tr>
					<td>City</td>
					<td>'.$client_info['city'].'</td>
				</tr>-->
				<tr>
					<td>Date</td>
					<td><?php echo $cbo_date_dd; ?> <?php echo $cbo_date_mm; ?> <?php echo $cbo_date_yy; ?></td>
				</tr>
			</table>
			<?php xhanch_my_prayer_time_ajax_sct('xhanch_my_prayer_time_widget_time', 'widget_xhanch_my_prayer_time_table_ajax', $xhanch_my_prayer_time); ?>	
			<div class="credit"><a href="http://xhanch.com/wp-plugin-my-prayer-time/" rel="section" title="My Prayer Time - A free WordPress plugin to display your Moslem Prayer Timr for your visitor">My Prayer Time</a>, <a href="http://xhanch.com/" rel="section" title="Developed by Xhanch Studio">by Xhanch</a></div>	
		</div>
<?php		

		echo $after_widget;
		echo '<script language="javascript">xhanch_my_prayer_time_widget_time_load();</script>';	
	}

	function widget_xhanch_my_prayer_time_init(){
		register_sidebar_widget('Xhanch - My Prayer Time', 'widget_xhanch_my_prayer_time_daily');
		add_filter('the_content', 'xhanch_my_prayer_time_advanced_table');
	}
	add_action("plugins_loaded", "widget_xhanch_my_prayer_time_init");

	function xhanch_my_prayer_time_js() {
		echo '
			<script src="'.xhanch_my_prayer_time_get_dir('url').'/js.js" type="text/javascript"></script>
			<script language="javascript">
				xhanch_my_prayer_time_url = "'.xhanch_my_prayer_time_get_dir('url').'";
			</script>
		';
	}
	add_action('wp_print_scripts', 'xhanch_my_prayer_time_js');

	function xhanch_my_prayer_time_css() {
		echo '<link rel="stylesheet" href="'.xhanch_my_prayer_time_get_dir('url').'/css.css" type="text/css" media="screen" />';
	}
	add_action('wp_print_styles', 'xhanch_my_prayer_time_css');
?>