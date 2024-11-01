<?
	require_once(dirname(__FILE__).'/xhanch_my_prayer_time.function.php');	

	$client_info = xhanch_my_prayer_time_get_client_info();		

	$date_mm = intval(xhanch_my_prayer_time_form_get('date_mm'));
	$date_yy = intval(xhanch_my_prayer_time_form_get('date_yy'));
		
	$prayer_time = xhanch_my_prayer_time_calculate(
		$date_mm, 
		$date_yy
	);
	$time_table = '';
	foreach($prayer_time as $dd=>$val){
		$time_table .= '
			<tr>
				<td>'.$dd.'</td>
				<td>'.$val['fajr'].'</td>
				<td>'.$val['sunrise'].'</td>
				<td>'.$val['zuhr'].'</td>
				<td>'.$val['asr'].'</td>
				<td>'.$val['maghrib'].'</td>
				<td>'.$val['isha'].'</td>
			</tr>
		';
	}

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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Prayer Time</title>
		<link rel="stylesheet" href="css.css" type="text/css"/>
	</head>
	<body onload="window.print()">
		<table style="font:inherit" cellpadding="0" cellspacing="4" width="539px">
			<tr>
				<td width="33%">Country: <b><?php echo $client_info['country_name']; ?></b></td>
				<td width="33%" align="center">City: <b><?php echo $client_info['city']; ?></b></td>
				<td width="33%" align="right">Month: <b><?php echo $db_month[$date_mm];?> <?php echo $date_yy; ?></b></td>
			</tr>
		</table>
		<br/>
		<table style="font: inherit; cellpadding="0" cellspacing="4" class="xhanch_my_prayer_time_table" id="xhanch_my_prayer_time_table">
			<tbody>
				<tr>
					<td class="header" width="50px">Day</td>
					<td class="header" width="75px">Fajr</td>
					<td class="header" width="75px">Sunrise</td>
					<td class="header" width="75px">Dhuhr</td>
					<td class="header" width="75px">Asr</td>
					<td class="header" width="75px">Maghrib</td>
					<td class="header" width="75px">Isha</td></tr>
				<?php echo $time_table; ?>
			</tbody>
		</table>		
	</body>
</html>
