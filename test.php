<?php

	require "src/include.php";
	use X\AlphaSign\AlphaSign;
	use X\AlphaSign\Connection\Network;
	use X\AlphaSign\Command as Command;

	$sign	= new AlphaSign(new Network("localhost", 6003));

	$c	= new Command\WriteText("A", "Yet another test message, I guess");
	var_dump($sign->sendCommand($c));

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
