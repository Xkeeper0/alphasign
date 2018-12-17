<?php

	namespace X\AlphaSign;

	class Utils {


		public static function validateFileLabel($fileLabel, $isString = false) {
			if (strlen($fileLabel) !== 1) throw new \UnexpectedValueException("FileLabel must be one character");
			$o	= ord($fileLabel);
			if ($o < 0x20 || $o >= 0x7F) throw new \UnexpectedValueException("Invalid FileLabel");
			if ($isString && ($o === 0x30 || $o === 0x3F)) throw new \UnexpectedValueException("Cannot use 0 or ? as file labels for STRINGs");

			return true;
		}

	}
