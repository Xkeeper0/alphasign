<?php

	namespace X\AlphaSign\Connection;


	interface Connection {

		public function write($data);
		public function read();

	}
