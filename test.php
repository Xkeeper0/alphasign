<?php

	date_default_timezone_set('America/Los_Angeles');
/*
*/
	require "secrets/honk.php";
	require "junk/home.php";
	$house	= getHome();
	$househumidity	= sprintf("%3d%%", $house['humidity']);
	$housetemp		= sprintf("%4.1f", $house['temperature']);

	$weather	= getWeather();
	$condition	= ucwords($weather->weather[0]->description);
	$temp		= sprintf("%4.1f", $weather->main->temp);
	$humidity	= sprintf("%3d%%", $weather->main->humidity);
	$windspeed	= sprintf("%4.1f", $weather->wind->speed);
	$winddir1	= floor(($weather->wind->deg + 11.25) / (360 / 16)) % 16;
	$winddirA	= ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
	$winddir	= $winddirA[$winddir1];


	$forecast	= getForecast();
	$forecast15	= "\x1b p\x1a1* Weather Forecast *\x1a3\r\n15-hour outlook\r\n\x1b j";
	foreach ($forecast['15-hour'] as $ft) {
		$forecast15	.=
				"\x1a3". sprintf("%8s", $ft['time']) ."   \x1a1Temp \x1a3". sprintf("%4.1f", $ft['temp']) ."\x1a1\xa9F\x1a3\r\n".
				"$ft[weather]\r\n";
	}

	$forecast3	= "\x1b p\x1a1* Weather Forecast *\x1a3\r\n3-day forecast\r\n";
	foreach ($forecast['3-day'] as $ft) {
		$forecast3	.=
				"\x1b\"e\x1a1$ft[date]\x1a3\x09".
				"\x1b&e$ft[weather]\r\n".
				"\x1a1High \x1a3". sprintf("%4.1f", $ft['temp']['max']) ."\x1a1\xa9F  ".
				"\x1a1Low \x1a3". sprintf("%4.1f", $ft['temp']['min']) ."\x1a1\xa9F\x1a3\r\n\x09".
				"";
	}

    print "done building stuff, sending\n";


	require "src/include.php";
	use X\AlphaSign\AlphaSign;
	use X\AlphaSign\Connection\Network;
	use X\AlphaSign\Command as Command;

	$sign	= new AlphaSign(new Network("localhost", 6003));

	$sign->sendRaw("E;". date("mdy"));
	$sign->sendRaw("E ". date("Hi"));
	$sign->sendRaw("E&". (date("w") + 1));
	/*
		$sign->sendRaw(
			"AAdeploy smooth jazz when ready".
			""
		);


	die();
	*/
	$sign->sendRaw(
		"AA\x1b j\x18\x1a1* Current time *\x1a3\r\n\x0b0 \x13\r\n".
		"\x1b p\x1a1* Current Conditions *\x1a3\r\nHenderson, NV\r\n".
		"\x1b e$condition\r\n\x1a1Winds\x1a3   {$windspeed} \x1a1mph\x1a3   $winddir\r\n".
		"\x1a1Temperature\x1a3    $temp\x1a1\xa9F\x1a3\r\n\x1a1Rel.Humidity\x1a3    $humidity  \r\n".
		$forecast15 .
		$forecast3 .
		"\x1b p\x1a1* Inside Conditions *\x1a3\r\nThe Romhaus\r\n".
		"\x1b e\x1a1Temperature\x1a3    $housetemp\x1a1\xa9F\x1a3\r\n\x1a1Rel.Humidity\x1a3    $househumidity  \r\n".
		"Data updated\r\n". date("m/d/y g:i A") ."\r\n".
		""
	);


	die();

	var_dump($sign->sendRaw("E,"));
	die();

	$functions	= [
/*		0x20	=> "time of day",
		0x21	=> "speaker status",
		0x22	=> "'general information'",
		0x23	=> "memory pool size",
		0x24	=> "memory configuration",
		0x25	=> "memory dump",
		0x26	=> "day of week",
		0x27	=> "time format",
		0x29	=> "run time table",
		0x2a	=> "serial status register",
		0x2d	=> "network query",
		0x2e	=> "run sequence",
		0x32	=> "run day table",
		0x35	=> "counter",
		#0x38	=> "large dots picture memory config",
		#0x3a	=> "run file times",
		0x3b	=> "date",
		#0x3e	=> "automode table",
		#0x43	=> "color correction",
		#0x4c	=> "temperature log",
		#0x54	=> "temperature offset",
		#0x55	=> "unit columns and rows + extended sequences ...",
		#0x76	=> "firmware versions",
*/
		0x23	=> "memory pool size",
//		0x24	=> "memory configuration",
//		0x25	=> "memory dump",

	];

	foreach ($functions as $func => $desc) {
		printf("%02X - %s\n    ", $func, $desc);
		$c	= new Command\ReadSpecialFunction(chr($func));
		$sign->sendCommand($c);

		$in	= $sign->receive();
		if ($func !== 0x25) {
			print nonprintables(getData($in)) ."\n";
		} else {
			print nonprintables($in) ."\n";
		}
		sleep(1);

	}


	function getData($s) {
		$posS	= strpos($s, "\x02");
		$posE	= strpos($s, "\x03");
		$out1	= substr($s, $posS + 1, 2);
		$out2	= substr($s, $posS + 3, $posE - $posS - 3);
		return "[$out1] [$out2]";
	}

#	$c	= new Command\ReadSpecialFunction("\x20");
#	$sign->sendCommand($c);

#	var_dump(nonprintables($sign->receive()));

	/*
	$np	= getnp();
	while (true) {
		var_dump($sign->writeCommand("AA\x1b\"bNow Playing:\x1b&a". $np));

		$oldnp	= $np;
		while (($np = getnp()) === $oldnp) {
			sleep(1);
			print ".";
		}

	}

	function getnp() {
		return substr(file_get_contents("../../np.txt"), 3);
	}
	*/
