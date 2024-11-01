<?
	class cls_xhanch_my_prayer_time{
		var $date_mm;
		var $date_yy;

		var $loc;
		var $gmt;

		function cls_xhanch_my_prayer_time($date_mm, $date_yy, $loc, $gmt){
			$this->date_mm = $date_mm;
			$this->date_yy = $date_yy;

			$this->loc = $loc;
			$this->gmt = $gmt; 
		}
			
		function compute_day_times(){
			$api_url = 'http://api.xhanch.com/islamic-get-prayer-time.php?loc='.$this->loc.'&yer='.$this->date_yy.'&mth='.$this->date_mm.'&gmt='.$this->gmt.'&mde=json';
			$html = xhanch_my_prayer_time_get_file($api_url);
			$res = json_decode($html);

			foreach($res as $date=>$val){
				$result[$date] = array(
					'fajr' => $val->fajr, 
					'sunrise' => $val->sunrise,
					'zuhr' => $val->zuhr, 
					'asr' => $val->asr, 
					'maghrib' => $val->maghrib, 
					'isha' => $val->isha, 
				);
			}
			return $result;
		}
	}
?>