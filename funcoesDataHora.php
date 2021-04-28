<?php
trait DateTimeFunctions {
	public $dateformat = "d/m/Y";
	public $timeformat = "H:i:s";
	public $datetimeformat = "d/m/Y H:i:s";
	
	// The function is_dbdate() tests if the parameter $date is or isn't a valid database date. If it's a string like '2015-5-15', it returns true, otherwise it returns false. 
	public function is_dbdate($date) {
		// The first condition for $date to be a valid database date is its length be equal to 10. 
		$dt = explode("-", $date);
		$year = (int) $dt[0];
		$month = (int) $dt[1];
		$day = (int) $dt[2];
		// If $year is an integer of four digits, $month is an integer less than 13 and $day is an integer less than 32, then $date is a valid database date.
		return checkdate($month, $day, $year);
	}
	// The function is_dbtime() tests if the parameter $time is or isn't a valid database time. If it's a string like '12:20:41', it returns true, otherwise it returns false. 
	public function is_dbtime($time) {
		return preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $time);
	}
	// The function is_dbdatetime() tests if the parameter $datetime is or isn't a valid database datetime. If it's a string like '2015-5-15 12:20:41', it returns true, otherwise it returns false. 
	public function is_dbdatetime ($datetime) {
		$is_dbdatetime = false; // the boolean variable that will be returned.
		$dttm = explode(' ', $datetime);
		$date = $dttm[0]; // the part of $datetime that is supposed to be a date is assigned to $date.
		$time = $dttm[1]; // the part of $datetime that is supposed to be a time is assigned to $time.
		// If $date and $time are respectively a valid database date and a valid database time, then $datetime is a valid database datetime.
		if ($this->is_dbdate($date) && $this->is_dbtime($time)) $is_dbdatetime = true;
		return $is_dbdatetime;
	}
	/* The function strtotime2() is like the PHP function strtotime(), but is able to transform into an integer time a datetime in any possible format. The parameter $datetime is the string datetime that will be turned into an integer time. */
	public function strtotime2($datetime) {
		$dateformat = $this->dateformat;
		$timeformat = $this->timeformat;
		$datetimeformat = "$dateformat $timeformat";
		/* The first thing to do to create a timestamp from a datetime in any format is turn $datetime into a DateTime object from the format we want. Since there is not a PHP function to get the format of a string date, we try to turn $datetime into a DateTime object from $datetimeformat. If this fails, we try to do it from $dateformat. If this also fails, we try to do it from $timeformat. If all these attempts fail, we try to create the timestamp from the original string $datetime using the function strtotime().*/
		$datetime2 = date_create_from_format($datetimeformat, $datetime);
		if ($datetime2 == false) $datetime2 = date_create_from_format($dateformat, $datetime);
		if ($datetime2 == false) $datetime2 = date_create_from_format($timeformat, $datetime);
		/* If the DateTime object was successfully created and assigned to the variable $datetime2, then we turn $datetime2 into a string date in the format "Y-m-d H:i:s", to create the timestamp from it. If this occurs, then we get to create a timestamp from a string date in the format we want.*/
		if ($datetime2 != false) {
			$datetime2 = date_format($datetime2, "Y-m-d H:i:s");
			return strtotime($datetime2);
		} else return strtotime($datetime);
		
	}
	/* The function below creates a date whose format is defined in the global variable $dateformat. This date is created from the parameter $date, which contains a date (and can also contain a time) that can be in any other format.*/
	public function std_date_create($date) {
		/* To make it simple to handle date formats, the variable $dateformat receives  $this->dateformat*/
		$dateformat = $this->dateformat;
		// Below the new date is created and returned. The new date is the same date in the variable $date in the format $dateformat.
		return date($dateformat, $this->strtotime2($date));
	}
	/* The function below creates a time whose format is defined in the global variable $timeformat. This time is created from the parameter $date, which contains a time (and can also contain a date) that can be in any other format. */
	public function std_time_create($date) {
		/* To make it simple to handle time formats, the variable $timeformat receives  $this->timeformat*/
		$timeformat = $this->timeformat;
		// Below the new time is created and returned. The new time is the same time in the variable $date in the format $timeformat.
		return date($timeformat, $this->strtotime2($date));
	}
	/* The function below creates a datetime whose format is defined in the variable $datetimeformat, created inside the function. This datetime is created from the parameter $date, which contains a datetime in any other format. */
	public function std_datetime_create($date) {
		$dateformat = $this->dateformat;
		$timeformat = $this->timeformat;
		$datetimeformat = "$dateformat $timeformat";
		/* Below the new datetime is created and returned. The new datetime is the same datetime in the variable $date with the format $datetimeformat. */
		return date($datetimeformat, $this->strtotime2($date));
	}
	/* The function db_date_create() creates a date in the format that databases accept, so that it can be stored in a database. This date is created from the parameter $date_str, which is a string containing a date whose format is $dateformat.*/
	public function db_date_create($date_str) {
		/* To make it simple to handle date formats, the variable $dateformat receives  $this->dateformat*/
		$dateformat = $this->dateformat;
		// Below the string parameter $date_str is turned into a date from the format $dateformat.
		$date = date_create_from_format($dateformat, $date_str);
		// $db_date receives $date in the format 'Y-m-d', which is the date format acceptable by the database.
		$db_date = date_format($date, "Y-m-d");
		//$db_date = date_create($db_date);
		// The database date is returned.
		return $db_date;
	}
	/* The function db_time_create() creates a time in the format 'H:i:s', which is acceptable by databases, so that it can be stored in a database. This time is created from the parameter $time_str, which is a string containing a time whose format is $timeformat.*/
	public function db_time_create($time_str) {
		/* To make it simple to handle time formats, the variable $timeformat receives  $this->timeformat*/
		$timeformat = $this->timeformat;
		// Below the string parameter $time_str is turned into a time from the format $timeformat.
		$time = date_create_from_format($timeformat, $time_str);
		// $db_time receives $time in the format 'H:i:s', which is the time format acceptable by the database.
		$db_time = date_format($time, "H:i:s");
		//$db_date = date_create($db_date);
		// The database time is returned.
		return $db_time;
	}
	/* The function db_datetime_create() creates a datetime in the format 'Y-m-d H:i:s', which is acceptable by databases, so that it can be stored in a database. This datetime is created from the parameter $datetime_str, which is a string containing a datetime whose format is the string in the variable $datetimeformat, defined inside the function below.*/
	public function db_datetime_create($datetime_str) {
		$dateformat = $this->dateformat;
		$timeformat = $this->timeformat;
		$datetimeformat = "$dateformat $timeformat";
		// Below the string parameter $datetime_str is turned into a datetime from the format $datetimeformat.
		$datetime = date_create_from_format($datetimeformat, $datetime_str);
		// $db_datetime receives $datetime in the format 'Y-m-d H:i:s', which is the datetime format acceptable by the database.
		$db_datetime = date_format($datetime, "Y-m-d H:i:s");
		//$db_date = date_create($db_date);
		// The database datetime is returned.
		return $db_datetime;
	}
	/* The function day_of_week() below is useful to get the day of week relative to a date or datetime string $date, which can be in any format. It returns the day of week in any language, according to global variables that the programmer must define.*/
	public function day_of_week($date) {
		/* To make it simple to handle date formats, the variable $dateformat receives  $this->dateformat*/
		$dateformat = $this->dateformat;
		// Below the string parameter $date is turned into a date from the format $dateformat.
		$date = date_create_from_format($dateformat, $date);
		/* $date receives $date in the format 'Y-m-d', so that it can be turned into an integer time and then we can get its day of week. */
		$date = date_format($date, "Y-m-d");
		/* The global variables below are the names of the days of week defined by the programmer, according to his or her language. If these variables were not defined, the days of week will be in English. */
		global $monday;
		global $tuesday;
		global $wednesday;
		global $thursday;
		global $friday;
		global $saturday;
		global $sunday;
		if (!isset($monday)) $monday = "Monday";
		if (!isset($tuesday)) $tuesday = "Tuesday";
		if (!isset($wednesday)) $wednesday = "Wednesday";
		if (!isset($thursday)) $thursday = "Thursday";
		if (!isset($friday)) $friday = "Friday";
		if (!isset($saturday)) $saturday = "Saturday";
		if (!isset($sunday)) $sunday = "Sunday";
		/* Below we get the day of week of the date in $date by turning it into an integer time. This day of week is a number that represents its name. */
		$day_of_week = date("w", strtotime($date));
		// Below, the variable $day_of week receives the name of a day of week according to the number that represents it.
		switch($day_of_week) {
			case"0": $day_of_week = $sunday; break;
			case"1": $day_of_week = $monday; break;
			case"2": $day_of_week = $tuesday; break;
			case"3": $day_of_week = $thursday; break;
			case"5": $day_of_week = $friday; break;
			case"6": $day_of_week = $saturday; break;
		}
		// The day of week of $date is returned.
		return $day_of_week;
	}
	// The function below forces the value in $value to be in the date, time or datetime format defined by the programmer, if value is a date, time or datetime.
	public function form_datetime_create ($value, $type) {
		if ($type === 'date') return $this->std_date_create($value);
		elseif ($type === 'time') return $this->std_time_create($value);
		elseif ($type === 'datetime') return $this->std_datetime_create($value); 
	}
}
/*$country = geoip_country_name_by_name('localhost');
	$date = NULL;
	$condition1 = ($country == 'Bhutan' || $country == 'China' || $country == 'Hungary' || $country == 'Japan' || strpos($country, 'Korea') !== FALSE || $country == 'Lithuania' || $country == 'Taiwan');
	$condition2 = ($country == 'American Samoa'|| strpos($country, 'Micronesia') !== FALSE || $country == 'Guam' || $country == 'Marshall Islands' || $country == 'Northern Mariana Islands' || strpos($country, 'United States') !== FALSE);
	if (!$condition1 && !$condition2) {
		$date = date_create_from_format("d/m/Y H:i:s", $date_str);
	} elseif ($condition1) {
		$date = date_create_from_format("Y/m/d H:i:s", $date_str);
	} else {
		$date = date_create_from_format("m/d/Y H:i:s", $date_str);
	}  */
	
?>
