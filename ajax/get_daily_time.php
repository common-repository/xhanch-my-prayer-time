<?
	require_once(dirname(__FILE__).'/../xhanch_my_prayer_time.function.php');	

	$date_dd = intval(xhanch_my_prayer_time_form_post('date_dd'));
	$date_mm = intval(xhanch_my_prayer_time_form_post('date_mm'));
	$date_yy = intval(xhanch_my_prayer_time_form_post('date_yy'));

	$prayer_time_month = xhanch_my_prayer_time_calculate(
		$date_mm, 
		$date_yy
	);
	$prayer_time = $prayer_time_month[$date_dd];

	header('Content-Type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	$response = '';
	echo '<response>';	
	
	foreach($prayer_time as $type=>$val){
		if($city == '_')
			continue;
		echo '<'.$type.'>'.$val.'</'.$type.'>';
	}
	
	echo '</response>';
?>