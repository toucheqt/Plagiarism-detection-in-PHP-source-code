<?php

	use Tester\Assert;
	
	# load tester libraries and tested class
	require __DIR__ . '/../vendor/autoload.php';
	require __DIR__ . '/../src/Tokens.php';
	
	Tester\Environment::setup();
	
	class TokensTest extends Tester\TestCase {
		
		const srcFilePath = './test-files/';
		const srcFileName = 'HelloWorld.php';
		
		const dscFilePath = './../tokens/';
		const dscFileName = 'HelloWorld.json';
			
		public function testTokens() {
			
			$object = new Tokenizer();
			
			// set filepath and filename to file HelloWorld.php located at ./test-files/
			$object->setFile(self::srcFilePath . self::srcFileName);
			
			Assert::same(self::srcFileName, $object->getFileName());
			Assert::same(self::srcFilePath, $object->getFilePath());
			
			// get tokens from file and check if whitespaces has been removed
			Assert::true($object->getTokens());
			Assert::notContains('T_WHITESPACE', $object->getContent());
			
			// check end php tag
			Assert::contains('T_CLOSE_TAG', $object->getContent());
			
			Assert::true(file_exists(self::dscFilePath . self::dscFileName));
			
		}
	
	}
	
	# run tests
	$testCase = new TokensTest();
	$testCase->run();
	
?>

