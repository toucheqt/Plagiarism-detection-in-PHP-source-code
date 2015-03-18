<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class Logger {
		
		public static function info($message) {
			if (!is_null($message)) {
				echo '[INFO] ' . $message . "\n";
			}
		}
		
		public static function warning($message) {
			if (!is_null($message)) {
				echo '[WARNING] ' . $message . "\n";
			}
		}
		
		public static function error($message) {
			if (!is_null($message)) {
				echo '[ERROR] ' . $message . "\n";
			}
		}
		
		public static function errorFatal($message) {
			if (!is_null($message)) {
				echo '[ERROR] ' . $message . "\n";
				echo 'Encountered fatal error. Could not continue.' . "\n";
			}
		}
		
	}

?>