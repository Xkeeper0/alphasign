<?php

	namespace X\AlphaSign\Command;
	use X\AlphaSign\Utils;

	class ReadSpecialFunction extends Command {

		protected $function		= null;

		public function __construct($function) {
			$this->function		= $function;
		}

		public function output() {
			return "F" . $this->function;
		}
	}
