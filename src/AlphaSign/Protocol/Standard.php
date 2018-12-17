<?php

	namespace X\AlphaSign\Protocol;
	use X\AlphaSign\Connection\Connection;


	class Standard implements Protocol{

		use ConnectionTrait;

		public function send($data) {
			$data	= str_repeat("\0", 5) . $data . "\x04";
			return $this->connection->write($data);
		}

		public function receive() {
			return ltrim($this->connection->read());
		}

	}
