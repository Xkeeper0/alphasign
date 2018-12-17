<?php

	namespace X\AlphaSign\Protocol;
	use X\AlphaSign\Connection\Connection;


	interface Protocol {

		public function __construct(Connection $connection);

		public function send($data);
		public function receive();

	}
