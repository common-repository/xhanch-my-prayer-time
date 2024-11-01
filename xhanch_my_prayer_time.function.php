<?php
	function xhanch_my_prayer_time_form_get($str){
		if(!isset($_GET[$str]))
			return false;
		return urldecode($_GET[$str]);
	}

	function xhanch_my_prayer_time_read_var($str){
		$res = $str;
		$res = str_replace('\\\'','\'',$res);
		$res = str_replace('\\\\','\\',$res);
		$res = str_replace('\\"','"',$res);
		return $res;
	}

	function xhanch_my_prayer_time_form_post($str, $parse = true){
		if(!isset($_POST[$str]))
			return false;
		if($parse)
			return xhanch_my_prayer_time_read_var($_POST[$str]);
		return $_POST[$str];
	}

	function xhanch_my_prayer_time_ajax_sct($api_sct_name, $api_sct_content, $api_sct_content_param = array()){
?>
		<script language="javascript">
			xhanch_my_prayer_time_ajax_sct_reg("<?php echo $api_sct_name; ?>");
		</script>
		<div class="ajax_sct">	
			<div 
				id="sct_ajax_<?php echo $api_sct_name; ?>_prg" 
				class="xmpt_ajax_progress" 
				style="display:none" 
				align="center"
			></div>
			<div id="sct_ajax_<?php echo $api_sct_name; ?>">
				<div id="sct_ajax_<?php echo $api_sct_name; ?>_msg" class="xmpt_ajax_message" style="display:none"></div>
				<? call_user_func($api_sct_content, $api_sct_content_param); ?>
			</div>
		</div>
<?php
	}

	function xhanch_my_prayer_time_get_dir($type) {
		if ( !defined('WP_CONTENT_URL') )
			define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
		if ( !defined('WP_CONTENT_DIR') )
			define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		if ($type=='path') { return WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)); }
		else { return WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)); }
	}

	function xhanch_my_prayer_time_get_file($name){
		$res = '';
		$res = @file_get_contents($name);
		if($res === false){
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $name);
			curl_setopt($ch, CURLOPT_AUTOREFERER, 0);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$res = curl_exec($ch);
			curl_close($ch);
		}
		return $res;
	}

	function xhanch_my_prayer_time_get_client_info(){
		$client_id = $_SERVER['REMOTE_ADDR'];

		$api_url = 'http://api.xhanch.com/ip-get-detail.php?ip='.$client_id.'&m=json';
		
		$req = xhanch_my_prayer_time_get_file($api_url); 
		$res = array();
		if ($req)
			$res = json_decode($req);
	 
		return array(
			'country_code' => (string)$res->country_code,
			'country_name' => (string)$res->country_name,
			'region' => (string)$res->region,
			'city' => (string)$res->city,
			'postal_code' => (string)$res->postal_code,
			'lat' => $res->latitude,
			'long' => $res->longitude,
			'gmt' => $res->gmt
		);
	}
	
	function xhanch_my_prayer_time_calculate($m, $y){		
		require_once(dirname(__FILE__).'/xhanch_my_prayer_time.class.php');
		$client_info = xhanch_my_prayer_time_get_client_info();

		$cls = new cls_xhanch_my_prayer_time(
			$m,
			$y,
			108410,
			0
		);

		$times = $cls->compute_day_times();		
		return $times;
	}
?>