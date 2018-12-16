<?php

	$sign	= new AlphaSign(6003);
	var_dump($sign->writeCommand("AAanother silly\r\ntest message"));


	/*
	$f	= fsockopen("localhost", 6003);
	stream_set_timeout($f, 5);
	print "ok\n";


	$init	= str_repeat("\0", 5);

	$start	= "\x01";
	$type	= "Z";
	$addr	= "00";
	$header	= $start . $type . $addr . "\x02";

#	$command	= "(";
#	$func	= "1";

//	$msg	= "E,";

	$msg	= "BA";
	$end	= "\x04";

	$packet	= $init . $header . $msg . $end;


	$out	= fwrite($f, $packet);
	var_dump($out);
	var_dump(bin2hex($packet));


//	die();

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

	class AlphaSign {

		protected $socket		= null;
		protected $header		= null;

		public function __construct($port) {
			$this->socket	= fsockopen("localhost", $port);
			stream_set_timeout($this->socket, 5);

			$this->header	= "\0\0\0\0\0\x01Z00\x02";
		}

		public function __destruct() {
			fclose($this->socket);
		}

		public function writeCommand($command) {
			$out	= fwrite($this->socket, $this->header . $command . "\x04");
			return $out;
		}

		public function read() {

			$read		= "";
			while (!feof($this->socket)) {
				$in		= fread($this->socket, 8192);
				$read	.= $in;
				if (strpos($in, "\x04") !== false) {
					break;
				}
				if (strlen($in) == 0) break;
			}
			$read	= ltrim($read, "\0");
			return $read;
		}
	}
