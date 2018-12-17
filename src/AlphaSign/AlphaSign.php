<?php

	namespace X\AlphaSign;

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
