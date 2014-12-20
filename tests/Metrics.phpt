<?php

	use Tester\Assert;
	
	# load tester libraries and tested class
	require __DIR__ . '/../vendor/autoload.php';
	require __DIR__ . '/../src/Metrics.php';
	
	Tester\Environment::setup();
	
	class MetricsTest extends Tester\TestCase {
							
		public function testBasicMetrics() {
			
			$srcFilePath = './../tokens/';
			$srcFileName = 'HelloWorld.json';
			
			$object = new Metrics();
			
			// set filepath and filename to file HelloWorld.json located at ./../tokens/
			Assert::true($object->setFile($srcFilePath . $srcFileName));
			
			Assert::same($srcFileName, $object->getFileName());
			Assert::same($srcFilePath, $object->getFilePath());
			
			// get and test metrics
			$object->getMetrics();
			
			Assert::same(1, $object->getFunctionCount());
			Assert::same(0, $object->getGlobalVarCount());
			Assert::same(0, $object->getAtUsageCount());
			Assert::same(0, $object->getEvalCount());
			Assert::same(0, $object->getGotoCount());
			
			// test complicated file
			$srcFileName = 'Disaster.json';
			
			Assert::true($object->setFile($srcFilePath . $srcFileName));
			
			Assert::same($srcFileName, $object->getFileName());
			Assert::same($srcFilePath, $object->getFilePath());
			
			// get and test metrics
			$object->getMetrics();
			
			Assert::same(2, $object->getFunctionCount());
			Assert::same(2, $object->getGlobalVarCount());
			Assert::same(0, $object->getAtUsageCount());
			Assert::same(1, $object->getEvalCount());
			Assert::same(2, $object->getGotoCount());
			
		}
		
		public function testBasicMetricsComplex() {
			
			$srcFilePath = './../tokens/';
			$srcFileName = 'IPP.json';
			
			$object = new Metrics();
			
			// set filepath and filename to file HelloWorld.json located at ./../tokens/
			Assert::true($object->setFile($srcFilePath . $srcFileName));
			
			// get and test metrics
			$object->getMetrics();
			
			Assert::same(10, $object->getFunctionCount());
			Assert::same(1, $object->getGlobalVarCount());
			Assert::same(1, $object->getAtUsageCount());
			Assert::same(0, $object->getEvalCount());
			Assert::same(0, $object->getGotoCount());
			
		}
		
		public function testBasicHalstead() {
			
			$srcFilePath = './../tokens/';
			$srcFileName = 'Function.json';
			
			$object = new Metrics();
			
			// set filepath and filename to file HelloWorld.json located at ./../tokens/
			Assert::true($object->setFile($srcFilePath . $srcFileName));
			
			$object->getMetrics();
			$halstead = $object->getHalsteadMetrics();
			
			Assert::equal(20.26466250649, $halstead[0]->getProgramLength());
			Assert::equal(88.757900040385, $halstead[0]->getVolume());
			Assert::equal(5.5, $halstead[0]->getDifficulty());
			
		}
	}
	
	# run tests
	$testCase = new MetricsTest();
	$testCase->run();
	
?>
