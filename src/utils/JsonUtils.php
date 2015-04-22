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
			if (is_null($path)) {
				Logger::errorFatal('Path can not be null.');
				throw new InvalidArgumentException();
			}
			
			$content = file_get_contents($path);
			if (!$content) {
				Logger::errorFatal('File ' . $path . ' can not be opened.');
				throw new RuntimeException();
			}
			
			return json_decode($content);
		}
		
		/**
		 * Converts giver array and saves it as a CSV file.
		 * @throws InvalidArgumentException
		 * @throws RuntimeException
		 */
		public static function saveToCSV($path, $filename, $matchedPairs) {
			if (is_null($filename)) {
				Logger::error('Can not save CSV file. Filename can not be null. ');
				throw new InvalidArgumentException();
			}
			
			$fd = fopen($path . $filename . Constant::CSV_FILE_EXTENSION, 'w');
			if (!$fd) {
				Logger::error('Can not save CSV file. File can not be created. ');
				throw new RuntimeException();
			}

			foreach ($matchedPairs as $pair) {
				fputcsv($fd, $pair);
			}
			// TODO refaktorovat jsonUtils na fileUtils
			fclose($fd);
		}
		
		/**
		 * Returns specific page of matched pairs from given CSV file.
		 * @throws InvalidArgumentException
		 * @throws RuntimeException
		 */
		public static function getFromCSV($path, $startIndex, $count) {
			if (is_null($path)) {
				Logger::errorFatal('Path to CSV file can not be null. ');
				throw new InvalidArgumentException();
			}
			
			$fd = fopen($path, 'r');
			if (!$fd) {
				Logger::errorFatal('CSV file can not be opened. ');
				throw new RuntimeException();
			}
			
			// implements paging
			$skippedFiles = $startIndex * $count;
			try {
				for ($i = 0; $i < $skippedFiles; $i++) {
					fgetcsv($fd);
				}
			}
			catch (Exception $ex) {
				Logger::errorFatal('Error during creating matched pairs page. ');
				throw new RuntimeException();
			}
			
			$matchedPairs = array();
			for ($i = 0; $i < $count; $i++) {
				$matchedPairs[] = fgetcsv($fd);
			}
			
			fclose($fd);
			return $matchedPairs;
		}
		
	}

?>