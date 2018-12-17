<?php

	namespace X\AlphaSign\Protocol;
	use X\AlphaSign\Connection\Connection;
	use X\AlphaSign\Exception\UnimplementedException;


	class ASCII2Byte implements Protocol {

		protected $connection	= null;

		public function __construct(Connection $connection) {
			throw new UnimplementedException();
		}

		public function send($data) {
		}

		public function receive() {
		}

	}
