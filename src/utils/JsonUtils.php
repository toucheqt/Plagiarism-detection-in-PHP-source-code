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
				throw new InvalidArgumentException();
			}
			
			if (!file_put_contents($path . $filename, @json_encode($data), FILE_USE_INCLUDE_PATH)) {
				Logger::error('File ' . $filename . ' can not be created.');
				throw new RuntimeException();
			}			
		}
		
		/**
		 * 
		 * Returns content of given json file
		 * @param string $path
		 * @throws InvalidArgumentException
		 * @throws RuntimeException
		 */
		public static function getJsonFromFile($path) {
			if (is_null($filename)) {
				Logger::error('Filename can not be null.');
				throw new InvalidArgumentException();
			}
			
			$content = file_get_contents($path);
			if (!$content) {
				Logger::error('File ' . $path . ' can not be opened.');
				throw new RuntimeException();
			}
			
			return json_decode($content);
		}
		
	}

?>