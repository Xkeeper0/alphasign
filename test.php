<?php

	require "src/include.php";
	use X\AlphaSign\AlphaSign;
	use X\AlphaSign\Connection\Network;
	$sign	= new AlphaSign(new Network("localhost", 6003));


//	$m		= "-";
//	$s		= "u";

//	var_dump($sign->writeCommand("AA\x1b b". $m . "\r\n- @\x1a1". $s));

		var_dump($sign->send("AAbbeep boop\r\nbep bop"));


//	var_dump($sign->writeCommand("AA\x1b b\x1a5bepis\x1a3\r\nfor \x1a1tv game"));

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


	$read		= "";
	print "R";
	while (!feof($f)) {
		$in		= fread($f, 8192);
		//var_dump($in);
		print ".". strlen($in);
		$read	.= $in;
		if (strpos($in, "\x04") !== false) {
			print "End\n";
			break;
		}
		if (strlen($in) == 0) break;
	}
	print "E\n";
	var_dump($read);
	var_dump(bin2hex($read));
	fclose($f);
	*/
