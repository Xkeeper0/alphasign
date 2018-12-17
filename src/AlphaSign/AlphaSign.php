<?php

	namespace X\AlphaSign;
	use X\AlphaSign\Connection\Connection;
	use X\AlphaSign\Protocol\Standard;

	class AlphaSign {

		protected $connection	= null;
		protected $address		= "Z00";

		public function __construct($connection, $protocol = Standard::class) {
			$this->connection	= new $protocol($connection);
		}

		public function setAddress($type, $id) {
			// Z (all signs) or ! (all signs w/ "RECEIVED OK" message)
			// ID = two character hex string, ? = wildcard, 00=all
			$this->address	= $type . $id;
		}


		public function send($command) {
			return $this->connection->send("\x01" . $this->address . "\x02". $command);
		}

		public function receive() {
			return $this->connection->receive();
		}
	}
