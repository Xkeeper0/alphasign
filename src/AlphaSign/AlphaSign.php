<?php

	namespace X\AlphaSign;
	use X\AlphaSign\Connection\Connection;
	use X\AlphaSign\Protocol;
	use X\AlphaSign\Command\Command;

	class AlphaSign {

		protected $connection	= null;
		protected $address		= "Z00";

		public function __construct($connection, $protocol = Protocol\Standard::class) {
			$this->connection	= new $protocol($connection);
		}

		public function setAddress($type, $id) {
			// Z (all signs) or ! (all signs w/ "RECEIVED OK" message)
			// ID = two character hex string, ? = wildcard, 00=broadcasts
			$this->address	= $type . $id;
		}



		public function sendRaw($command) {
			return $this->connection->send("\x01" . $this->address . "\x02". $command);
		}

		public function sendCommand(Command $command) {
			return $this->connection->send("\x01" . $this->address . "\x02". $command->output());
		}

		public function receive() {
			return $this->connection->receive();
		}
	}
