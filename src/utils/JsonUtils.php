<?php

	/**
	 * 
	 * Enter description here ...
	 * @author Ondrej Krpec, xkrpecqt@gmail.com
	 *
	 */
	class JsonUtils {
		
		/**
		 * 
		 * Converts given array to json and saves it into file.
		 * @param string $path
		 * @param string $filename
		 * @param array $data
		 * @throws InvalidArgumentException
		 * @throws RuntimeException
		 */
		public static function saveToJson($path, $filename, $data) {
			if (is_null($filename)) {
				Logger::error("Filename can not be null.");
				return;
			}
			
			if (!file_put_contents($path . $filename, json_encode($data), FILE_USE_INCLUDE_PATH)) {
				Logger::error('File ' . $filename . ' can not be created.');
				return;
			}			
			
			Logger::info('Saved ' . $filename . '.');
		}
		
		/**
		 * 
		 * Returns content of given json file
		 * @param string $path
		 * @param string $filename
		 * @throws InvalidArgumentException
		 * @throws RuntimeException
		 */
		public static function getJsonFromFile($path, $filename) {
			if (is_null($filename)) {
				Logger::error('Filename can not be null.');
				return null;
			}
			
			$content = file_get_contents($path . $filename);
			if (!$content) {
				Logger::error('File ' . $path . $filename . ' can not be opened.');
				return null;
			}
			
			return json_decode($content);
		}
		
	}

?>