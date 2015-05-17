<?php

	/**
	 * 
	 * Logging class for keeping overview about script's workflow.
	 * @author Ondrej Krpec, xkrpec01@stud.fit.vutbr.cz
	 *
	 */
	class Logger {
		
		/**
		 * 
		 * Prints out an information message.
		 * @param $message Information message.
		 */
		public static function info($message) {
			if (!is_null($message)) {
				echo '[INFO] ' . $message . "\n";
			}
		}
		
		/**
		 * 
		 * Prints out a warning message/
		 * @param $message Warning message.
		 */
		public static function warning($message) {
			if (!is_null($message)) {
				echo '[WARNING] ' . $message . "\n";
			}
		}
		
		/**
		 * 
		 * Prints out an error message.
		 * @param $message Error message.
		 */
		public static function error($message) {
			if (!is_null($message)) {
				echo '[ERROR] ' . $message . "\n";
			}
		}
		
		/**
		 * 
		 * Prints out a fatal error message. Script should not continue afterwards.
		 * @param $message Fatal error message.
		 */
		public static function errorFatal($message) {
			if (!is_null($message)) {
				echo '[ERROR] ' . $message . "\n";
				echo 'Encountered fatal error. Could not continue.' . "\n";
			}
		}
		
	}

?>