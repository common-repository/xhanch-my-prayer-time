<?
	require_once(dirname(__FILE__).'/../xhanch_my_prayer_time.function.php');	

	$date_mm = intval(xhanch_my_prayer_time_form_post('date_mm'));
	$date_yy = intval(xhanch_my_prayer_time_form_post('date_yy'));

	$prayer_time = xhanch_my_prayer_time_calculate(
		$date_mm, 
		$date_yy
	);

	header('Content-Type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	$response = '';
	echo '<response>';
	
	foreach($prayer_time as $dd=>$val){
		if($city == '_')
			continue;
		echo '<list>';
			echo '<date>'.$dd.'</date>';
			foreach($val as $typ=>$time)
				echo '<'.$typ.'>'.$time.'</'.$typ.'>';
		echo '</list>';
	}
	
	echo '</response>';
?>