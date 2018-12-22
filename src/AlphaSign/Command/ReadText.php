<?php

	namespace X\AlphaSign\Command;
	use X\AlphaSign\Utils;

	class ReadText extends Command {

		const RETURNS			= WriteText::class;

		protected $fileLabel	= null;

		public function __construct($fileLabel) {
			$this->setFileLabel($fileLabel);
		}

		public function setFileLabel($fileLabel) {
			Utils::validateFileLabel($fileLabel);
			$this->fileLabel	= $fileLabel;
		}

		public function output() {
			return "B" . $this->fileLabel;
		}
	}
