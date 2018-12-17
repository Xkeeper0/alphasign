<?php

	namespace X\AlphaSign\Protocol;
	use X\AlphaSign\Connection\Connection;


	trait ConnectionTrait {

		protected $connection	= null;

		public function __construct(Connection $connection) {
			$this->connection	= $connection;
		}

	}
