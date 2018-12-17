<?php

	namespace X\AlphaSign\Connection;


	class Network implements Connection {


		protected $socket;


		public function __construct($host, $port) {
			$this->socket	= fsockopen($host, $port);
			stream_set_timeout($this->socket, 5);
		}

		public function __destruct() {
			fclose($this->socket);
		}


		public function write($data) {
			return fwrite($this->socket, $data);
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
			return $read;
		}

	}
