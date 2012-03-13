<?php
// Easily provides page render times
class utime
{
	private $start; // The time recorded at the class' construction

	public function __construct()
	{
		$this->start = $this->utime();
	}

	private function utime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	public function getTime($precision = 10, $seconds = false)
	{
		$current = $this->utime();
		$output = round($current - $this->start,$precision);
		if ($seconds)
			$output .= " seconds";

		return $output;
	}
}
/*
// Example code!
$timer = new utime();
// Sleep for a while
usleep(100);
// Get current render time
echo "usleep(100) / precision:12 - ".$timer->getTime(12,true)."<br />";
// wait for 2 seconds
usleep(2000000);
// Get current render time
echo "usleep(2000000) / precision:5 - ".$timer->getTime(5,true);//*/
?>