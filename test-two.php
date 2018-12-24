<?php

	date_default_timezone_set('America/Los_Angeles');

	require "src/include.php";
	use X\AlphaSign\AlphaSign;
	use X\AlphaSign\Connection\Network;
	use X\AlphaSign\Command as Command;

	$sign	= new AlphaSign(new Network("localhost", 6003));


	$sign->sendRaw('E$' . 'AAL1000FF00' . 'BDL07081000' . 'CDL07081000' . 'DDU070A1000');
//	die();
//	sleep(2);

	$cmd	= new Command\WriteText("A", "\x1b bhonk \x14D burp");
//	$cmd	= new Command\WriteText("A", "\x1d41\x14B empty box\r\n\x14C filled box");
	$txt	= $sign->sendCommand($cmd);
//	die();

	sleep(2);

	$sign->sendRaw('ID070A'.
			"1111100001" . "\r".
			"0000100001" . "\r".
			"0000100001" . "\r".
			"0000100001" . "\r".
			"0000100001" . "\r".
			"0000100001" . "\r".
			"0000111111" . "\r".
			""
		);
	die();

//	sleep(2);
	$sign->sendRaw('IB0708'.
			"01111111" . "\r".
			"01000001" . "\r".
			"01000001" . "\r".
			"01000001" . "\r".
			"01000001" . "\r".
			"01000001" . "\r".
			"01111111" . "\r".
			""
		);
	sleep(2);

	$sign->sendRaw('IC0708'.
			"01111111" . "\r".
			"01000001" . "\r".
			"01010101" . "\r".
			"01001001" . "\r".
			"01010101" . "\r".
			"01000001" . "\r".
			"01111111" . "\r".
			""
		);
	die();

/*
	$cmd	= new CommandWriteText("A", "test message");
	$txt	= $sign->sendCommand($cmd);
	var_dump($txt);
	die();
*/
//	$cmd	= new Command\ReadText("A");
//	$txt	= $sign->sendCommand($cmd);
//	var_dump($txt);
//	die();
	// 0x14 + file
	// E$ + FTPSIZEQQQQ (write mem)
	// F = file, T = A/B/D (text/string/dots), P = L/U (locked/unlocked, just use L)
	// QQQQ = 0000 (text) 1000 (dots)

//	$sign->sendRaw("E!FF");
//	print nonprintables($sign->receive());
//	die();

	//$sign->sendRaw("E,");
//	$sign->sendRaw("E(1");
//	$sign->sendRaw("E(24051");
//	die();

//	$sign->sendRaw("E,");
//	die();
//	print "cleared memory...\r\n";
//	sleep(2);
	/*
	$sign->sendRaw("E,");
	print "reset...\r\n";
	sleep(20);
	print "ok, doin the thing.\r\n";
*/
	$sign->sendRaw("F$");

	$data	= $sign->receive();
//	$memR	= substr(getmsg($data), 2);
	$memR	= substr($data, 2);
	$mem	= mem2array($memR);

	print "File  Type  Lock  Size  Flags\r\n";
	print "----- ----- ----- ----- -----\r\n";
	foreach ($mem as $file) {
		printf("%-5s %-5s %-5s %-5s %-5s\r\n", $file['file'], $file['type'], $file['lock'], $file['size'], $file['flag']);
	}
	print "\r\n";



	function getmsg($m) {
		$m2	= substr($m, strpos($m, "\x02") + 1, strpos($m, "\x03") - strpos($m, "\x02") - 1);
		return $m2;
	}

	function mem2array($memText) {
		$split	= str_split($memText, 11);
		$mem	= [];
		foreach ($split as $s) {
			$mem[]	= [
					'file'	=> $s{0},
					'type'	=> $s{1},
					'lock'	=> $s{2},
					'size'	=> substr($s, 3, 4),
					'flag'	=> substr($s, 7, 4),
			];
		}

		return $mem;
	}

	//$message	= new Command\WriteText("A", $msg);
	//$sign->sendCommand($message);
