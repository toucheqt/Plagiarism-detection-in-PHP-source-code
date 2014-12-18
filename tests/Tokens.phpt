<?php

	use Tester\Assert;
	
	# load tester libraries and tested class
	require __DIR__ . '/../vendor/autoload.php';
	require __DIR__ . '/../src/Tokens.php';
	
	Tester\Environment::setup();
	
	class TokensTest extends Tester\TestCase {
		
		const srcFilePath = './test-files/';
		const dscFilePath = './../tokens/';
			
		public function testTokens() {
			
			$srcFileName = 'HelloWorld.php';
			$dscFileName = 'HelloWorld.json'; 
			
			$object = new Tokenizer();
			
			// set filepath and filename to file HelloWorld.php located at ./test-files/
			$object->setFile(self::srcFilePath . $srcFileName);
			
			Assert::same($srcFileName, $object->getFileName());
			Assert::same(self::srcFilePath, $object->getFilePath());
			
			// get tokens from file and check if whitespaces has been removed
			Assert::true($object->getTokens());
			Assert::notContains('T_WHITESPACE', $object->getContent());
			
			// check end php tag
			Assert::contains('T_CLOSE_TAG', $object->getContent());
			
			Assert::true(file_exists(self::dscFilePath . $dscFileName));
	
			$srcFileName = 'Disaster.php';
			$dscFileName = 'Disaster.json';
			
			// set filepath and filename to file HelloWorld.php located at ./test-files/
			$object->setFile(self::srcFilePath . $srcFileName);
			
			Assert::same($srcFileName, $object->getFileName());
			Assert::same(self::srcFilePath, $object->getFilePath());
			
			// get tokens from file and check if whitespaces has been removed
			Assert::true($object->getTokens());
			Assert::notContains('T_WHITESPACE', $object->getContent());
			
			// check end php tag
			Assert::contains('T_CLOSE_TAG', $object->getContent());
			
			Assert::true(file_exists(self::dscFilePath . $dscFileName));
			
		}
		
		public function complexTest() {
			
			$srcFileName = 'Disaster.php';
			$dscFileName = 'Disaster.json';
			
			// set filepath and filename to file HelloWorld.php located at ./test-files/
			$object->setFile(self::srcFilePath . srcFileName);
			
			Assert::same(srcFileName, $object->getFileName());
			Assert::same(self::srcFilePath, $object->getFilePath());
			
			// get tokens from file and check if whitespaces has been removed
			Assert::true($object->getTokens());
			Assert::notContains('T_WHITESPACE', $object->getContent());
			
			// check end php tag
			Assert::contains('T_CLOSE_TAG', $object->getContent());
			
			Assert::true(file_exists(self::dscFilePath . dscFileName));
		
		}
	
	}
	
	# run tests
	$testCase = new TokensTest();
	$testCase->run();
	
?>

