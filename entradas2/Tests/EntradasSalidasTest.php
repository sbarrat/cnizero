<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../clases/EntradasSalidas.php';
/**
 * EntradasSalidas test case.
 */
class EntradasSalidasTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var EntradasSalidas
	 */
	private $EntradasSalidas;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated EntradasSalidasTest::setUp()
		

		$this->EntradasSalidas = new EntradasSalidas(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated EntradasSalidasTest::tearDown()
		

		$this->EntradasSalidas = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests EntradasSalidas->__construct()
	 */
	public function test__construct() {
		// TODO Auto-generated EntradasSalidasTest->test__construct()
		$this->markTestIncomplete ( "__construct test not implemented" );
		
		$this->EntradasSalidas->__construct(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->set_anyos()
	 */
	public function testSet_anyos() {
		// TODO Auto-generated EntradasSalidasTest->testSet_anyos()
		$this->markTestIncomplete ( "set_anyos test not implemented" );
		
		$this->EntradasSalidas->setAnyos(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->titulo()
	 */
	public function testTitulo() {
		// TODO Auto-generated EntradasSalidasTest->testTitulo()
		$this->markTestIncomplete ( "titulo test not implemented" );
		
		$this->EntradasSalidas->titulo(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->categorias()
	 */
	public function testCategorias() {
		// TODO Auto-generated EntradasSalidasTest->testCategorias()
		$this->markTestIncomplete ( "categorias test not implemented" );
		
		$this->EntradasSalidas->categorias(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->formulario()
	 */
	public function testFormulario() {
		// TODO Auto-generated EntradasSalidasTest->testFormulario()
		$this->markTestIncomplete ( "formulario test not implemented" );
		
		$this->EntradasSalidas->formulario(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->movimientos()
	 */
	public function testMovimientos() {
		// TODO Auto-generated EntradasSalidasTest->testMovimientos()
		$this->markTestIncomplete ( "movimientos test not implemented" );
		
		$this->EntradasSalidas->movimientos(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->movimientosTotales()
	 */
	public function testMovimientosTotales() {
		// TODO Auto-generated EntradasSalidasTest->testMovimientosTotales()
		$this->markTestIncomplete ( "movimientosTotales test not implemented" );
		
		$this->EntradasSalidas->movimientosTotales(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->entradasTotales()
	 */
	public function testEntradasTotales() {
		// TODO Auto-generated EntradasSalidasTest->testEntradasTotales()
		$this->markTestIncomplete ( "entradasTotales test not implemented" );
		
		$this->EntradasSalidas->entradasTotales(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->salidasTotales()
	 */
	public function testSalidasTotales() {
		// TODO Auto-generated EntradasSalidasTest->testSalidasTotales()
		$this->markTestIncomplete ( "salidasTotales test not implemented" );
		
		$this->EntradasSalidas->salidasTotales(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->detallesMovimientos()
	 */
	public function testDetallesMovimientos() {
		// TODO Auto-generated EntradasSalidasTest->testDetallesMovimientos()
		$this->markTestIncomplete ( "detallesMovimientos test not implemented" );
		
		$this->EntradasSalidas->detallesMovimientos(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->serviciosExternos()
	 */
	public function testServiciosExternos() {
		// TODO Auto-generated EntradasSalidasTest->testServiciosExternos()
		$this->markTestIncomplete ( "serviciosExternos test not implemented" );
		
		$this->EntradasSalidas->serviciosExternos(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->detallesServiciosExternos()
	 */
	public function testDetallesServiciosExternos() {
		// TODO Auto-generated EntradasSalidasTest->testDetallesServiciosExternos()
		$this->markTestIncomplete ( "detallesServiciosExternos test not implemented" );
		
		$this->EntradasSalidas->detallesServiciosExternos(/* parameters */);
	
	}
	
	/**
	 * Tests EntradasSalidas->cambiaf()
	 */
	public function testCambiaf() {
		// TODO Auto-generated EntradasSalidasTest->testCambiaf()
		//$this->markTestIncomplete ( "cambiaf test not implemented" );
		

		$this->EntradasSalidas->fecha->cambiaf ( "2011-01-01" );
	
	}
	
	/**
	 * Tests EntradasSalidas->verMes()
	 */
	public function testVerMes() {
		// TODO Auto-generated EntradasSalidasTest->testVerMes()
		//$this->markTestIncomplete ( "verMes test not implemented" );
		

		$this->EntradasSalidas->fecha->verMes ( "2011-01-01" );
	
	}
	
	/**
	 * Tests EntradasSalidas->verAnyo()
	 */
	public function testVerAnyo() {
		// TODO Auto-generated EntradasSalidasTest->testVerAnyo()
		//$this->markTestIncomplete ( "verAnyo test not implemented" );
		

		$this->EntradasSalidas->fecha->verAnyo ( "2011-01-01" );
	
	}
	public function testVerDia(){
		$this->EntradasSalidas->fecha->verDia("2011-01-01");
	}
	
	/**
	 * Tests EntradasSalidas->verListado()
	 */
	public function testVerListado() {
		// TODO Auto-generated EntradasSalidasTest->testVerListado()
		$this->markTestIncomplete ( "verListado test not implemented" );
		
		$this->EntradasSalidas->verListado(/* parameters */);
	
	}

}

