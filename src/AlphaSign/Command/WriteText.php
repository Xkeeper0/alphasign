<?php

	namespace X\AlphaSign\Command;
	use X\AlphaSign\Utils;

	class WriteText extends Command {

		protected $fileLabel	= null;
		protected $text			= null;

		public function __construct($fileLabel = "A", $text = "") {
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

		public static function fromString($str) {
			if ($str{0} !== "A") {
				throw new \UnexpectedValueException("Unexpected packet type ". $str{0} .", expecting A");
			}
			
			$x	= new static();
			$x->fileLabel	= $str{1};
			$x->text		= substr($str, 2);
			return $x;
		}
	}
