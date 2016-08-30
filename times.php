<?php

header('Content-Type: application/json');

require './HalachicTimes.php';

$time = new HalachicTimes('Asia/Jerusalem');

if (
	isset($_GET['date']) &&
	isset($_GET['latitude']) &&
	isset($_GET['longitude']) &&
	($_GET['date'] != '') &&
	($_GET['latitude'] != '') &&
	($_GET['longitude'] != '')
	)
{
	$date = array_reverse(explode('-', $_GET['date']));
	
	$time->setDate($date[0], $date[1], $date[2]);
	
	$time->setLocation($_GET['latitude'], $_GET['longitude']);
	
	$times = [
		'3' => [
			'#שעה זמנית',
			'=([sunset]-[sunrise])/12'
		],
		'4' => [
			'#דקות זמניות',
			'=([sunset]-[sunrise])/720'
		],
		'5' => [
			'#דקות',
			60
		],
		'6' => [
			'#שעה',
			3600
		],
		'7' => [
			'#שעה זמנית מגן אברהם',
			'=(([sunset]+90*[4])-([sunrise]-90*[4]))/12'
		],
		'8' => [
			'#שעה זמנית מגן אברהם לחומרא',
			'=(([sunset]+25*[4])-([sunrise]-90*[4]))/12'
		],
		'9' => [
			'עמוד השחר א',
			'=[sunrise]-90*[4]'
		],
		'10' => [
			'עמוד השחר ב',
			'=[sunrise]-72*[4]'
		],
		'11' => [
			'זמן ציצית ותפילין',
			'=[sunrise]-[6]'
		],
		'12' => [
			'הנץ החמה המישורי',
			'=[sunrise]'
		],
		'13' => [
			'סוף זמן ק"ש מג"א',
			'=[sunrise]-90*[4]+3*[7]'
		],
		'14' => [
			'סוף זמן ק"ש מג"א לחומרא',
			'=[sunrise]-90*[4]+3*[8]'
		],
		'15' => [
			'סוף זמן תפילה מג"א',
			'=[sunrise]-90*[4]+4*[7]'
		],
		'16' => [
			'סוף זמן ק"ש גר"א',
			'=[sunrise]+3*[3]'
		],
		'17' => [
			'סוף זמן תפילה גר"א',
			'=[sunrise]+4*[3]'
		],
		'18' => [
			'חצות יום ולילה',
			'=[sunrise]+6*[3]'
		],
		'19' => [
			'מנחה גדולה',
			'=[sunrise]+6.5*[3]'
		],
		'20' => [
			'מנחה קטנה',
			'=[sunrise]+9.5*[3]'
		],
		'21' => [
			'פלג המנחה',
			'=[sunset]-1.25*[3]'
		],
		'22' => [
			'שקיעת החמה',
			'=[sunset]'
		],
		'23' => [
			'צאת הכוכבים 36 דקות',
			'=[sunset]+36*[4]'
		],
		'24' => [
			'צאת הכוכבים 40 דקות',
			'=[sunset]+40*[4]'
		],
		'25' => [
			'צאת הכוכבים 72 דקות',
			'=[sunset]+72*[5]'
		],
		'26' => [
			'הדלקת נרות',
			'=[sunset]-22*[4]'
		]
	];

	foreach ($times as $key => $value)
	{
		$time->addTime($key, $value[0], $value[1]);
	}
	
	echo $time;
}
else
{
	echo json_encode(['error' => true]);
}

?>