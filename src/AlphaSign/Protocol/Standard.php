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
			$x	= ltrim($this->connection->read());

			return substr($x, strpos($x, "\x02") + 1, strpos($x, "\x03") - strpos($x, "\x02") - 1);

		}

	}
