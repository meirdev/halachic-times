<?php

class HalachicTimes
{
	private $date;
	
	private $latitude, $longitude;
	
	private $zenith = 90+50/60;
	private $offset = 2;
	
	private $times;
	
	public function __construct($time_zone)
	{
		date_default_timezone_set($time_zone);
	}
	
	public function __toString()
	{
		return json_encode($this->getTimes());
	}
	
	public function setDate($day, $month, $year)
	{
		$this->date = mktime(0, 0, 0, $month, $day, $year);
	}
	
	public function setLocation($latitude, $longitude)
	{
		$this->latitude  = $latitude;
		$this->longitude = $longitude;
	}
	
	public function setZenith($zenith)
	{
		$this->zenith = $zenith;
	}
	
	public function setOffset($offset)
	{
		$this->offset = $offset;
	}
	
	public function addTime($name, $title, $value)
	{
		$this->times[$name] = [$title, $value];
	}
	
	public function removeTime($name)
	{
		unset($this->times[$name]);
	}
	
	public function getTimes()
	{
		$this->addTime('sunrise', '#זריחה', $this->sunrise($this->date));
		
		$this->addTime('sunset', '#שקיעה', $this->sunset($this->date));
		
		$_times = [];
		
		foreach ($this->times as $var => &$time)
		{
			if ($time[1][0] == '=')
			{
				$time[1] = $this->calculate($time[1]);
			}
			
			if ($time[0][0] != '#')
			{
				$_times[$time[0]] = $time[1];
			}
		}
		
		return $_times;
	}
	
	private function sunrise()
	{
		return date_sunrise($this->date, SUNFUNCS_RET_TIMESTAMP, $this->latitude, $this->longitude, $this->zenith, $this->offset);
	}
	
	private function sunset()
	{
		return date_sunset($this->date, SUNFUNCS_RET_TIMESTAMP, $this->latitude, $this->longitude, $this->zenith, $this->offset);
	}
	
	private function calculate($expression)
	{
		$value = preg_replace_callback('/\[(.*?)\]/', function($match) {
			
			$var = $match[1];
			
			if (isset($this->times[$var]))
			{
				return $this->times[$var][1];
			}
			
		}, substr($expression, 1) );
		
		eval("\$value = intval($value);");
		
		return $value;
	}
}

?>