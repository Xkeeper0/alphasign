<?php

	namespace X\AlphaSign\Command;
	use X\AlphaSign\Utils;

	class WriteText extends Command {

		protected $fileLabel	= null;
		protected $text			= null;

		public function __construct($fileLabel, $text = "") {
			$this->setFileLabel($fileLabel);
			$this->text			= $text;
		}

		public function setFileLabel($fileLabel) {
			Utils::validateFileLabel($fileLabel);
			$this->fileLabel	= $fileLabel;
		}

		public function setText($text) {
			$this->text			= $text;
		}

		public function output() {
			return "A" . $this->fileLabel . $this->text;
		}
	}
