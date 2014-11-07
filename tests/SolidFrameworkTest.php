<?php

namespace WordPressSolid;

class SolidFrameworkTest extends \PHPUnit_Framework_TestCase {
	/** @var SolidFramework */
	private $_solidFramework;

	protected function setUp() {
		parent::setUp();
		$this->_solidFramework = SolidFramework::instance();
	}

	protected function tearDown() {
		SolidFramework::reset();
		parent::tearDown();
	}

	public function testIsSetup_When_Namespace_Is_Not_Setup_Should_Return_False() {
		$this->assertFalse( $this->_solidFramework->isSetup( 'non_existing_namespace' ) );
	}

	public function testIsSetup_When_Namespace_Is_Setup_Should_Return_True() {
		$this->assertTrue( $this->_solidFramework->isSetup( SolidFramework::DEFAULT_NAMESPACE ) );

		$namespace = 'myNamespace';
		$this->_solidFramework->setup( $namespace, WP_SOLID_BASE_DIR . '/config/config.php' );
		$this->assertTrue( $this->_solidFramework->isSetup( $namespace ) );
	}

	public function testSwitchTo_When_Namespace_Does_Not_Exist_Should_Throw_RuntimeException() {
		$this->setExpectedException( '\RuntimeException' );
		$this->_solidFramework->switchTo( 'non_existing_namespace' );
	}

	public function testSwitchTo_When_Namespace_Does_Exist_Should_Change_Namespace() {
		$this->_solidFramework->switchTo( SolidFramework::DEFAULT_NAMESPACE );
		$this->assertEquals( SolidFramework::DEFAULT_NAMESPACE, $this->_solidFramework->getCurrentNamespace() );

		$namespace = 'myNamespace';
		$this->_solidFramework->setup( $namespace, WP_SOLID_BASE_DIR . '/config/config.php');
		$this->_solidFramework->switchTo( $namespace );
		$this->assertEquals( $namespace, $this->_solidFramework->getCurrentNamespace() );
	}

	public function testHasService_When_Service_Does_Not_Exist_Should_Return_False() {
		$this->assertFalse( $this->_solidFramework->hasService( 'non_existing_service' ) );
	}

	public function testHasService_When_Service_Exists_Should_Return_True() {
		$this->assertTrue( $this->_solidFramework->hasService( 'postManager' ) );
	}
}
