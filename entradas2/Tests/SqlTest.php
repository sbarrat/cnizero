<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../clases/Sql.php';

/**
 * Sql test case.
 */
class SqlTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Sql
	 */
	private $Sql;
	private $_result = null;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SqlTest::setUp()
		

		$this->Sql = new Sql(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SqlTest::tearDown()
		

		$this->Sql = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	

	}
	
	/**
	 * Tests Sql->__construct()
	 */
	public function test__construct() {
		// TODO Auto-generated SqlTest->test__construct()
		//$this->markTestIncomplete ( "__construct test not implemented" );
		
		$this->Sql->__construct(/* parameters */);
	
	}
	
	/**
	 * Tests Sql->consulta()
	 */
	
	public function testConsulta() {
		
		// TODO Auto-generated SqlTest->testConsulta()
		//$this->markTestIncomplete ( "consulta test not implemented" );
		

		$this->Sql->consulta ( null );
	
	}
	
	/**
	 * Tests Sql->datos()
	 */
	public function testDatos() {
		// TODO Auto-generated SqlTest->testDatos()
		$this->markTestIncomplete ( "datos test not implemented" );
		
		$this->Sql->datos(/* parameters */);
	
	}
	
	/**
	 * Tests Sql->totalDatos()
	 */
	public function testTotalDatos() {
		// TODO Auto-generated SqlTest->testTotalDatos()
		//$this->markTestIncomplete ( "totalDatos test not implemented" );
		
		$this->Sql->totalDatos(/* parameters */);
	
	}
	
	/**
	 * Tests Sql->close()
	 */
	public function testClose() {
		// TODO Auto-generated SqlTest->testClose()
		//$this->markTestIncomplete ( "close test not implemented" );
		
		$this->Sql->close(/* parameters */);
	
	}

}

