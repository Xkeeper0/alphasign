<?php


	function d(&$v, $r = null) {
		return isset($v) ? $v : $r;
	}


	function nonprintables($s) {
		return preg_replace_callback("/[[:^print:]]/", "hexescape", $s);
	}

	function hexescape($np) {
		return sprintf("<%02X>", ord($np[0]));

	}
