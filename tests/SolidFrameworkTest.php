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

	public function testSetup_Without_Config_Should_Use_Default() {
		$this->_setupTestNamespace();
		$postManager = $this->_solidFramework->getService( 'postManager' );
		$this->assertInstanceOf( '\WordPressSolid\Post\Service\PostManager', $postManager );
	}

	public function testSetup_With_Custom_Config_Should_Use_Custom() {
		$mockClassName = 'myMockClass';
		$this->getMockBuilder( 'nonexistant' )
		     ->setMockClassName( $mockClassName )
		     ->getMock();

		$this->_solidFramework->setup( 'myNamespace', array(
			'services' => array(
				'postManager' => array(
					'class'  => $mockClassName,
					'params' => null,
				),
			),
		) );

		$postManager = $this->_solidFramework->getService( 'postManager' );
		$this->assertInstanceOf( $mockClassName, $postManager );
	}

	public function testSetup_With_Custom_Config_Override_Post_Manager_Constructor_Arguments_Should_Use_New_Constructor_Args() {
		$this->getMockBuilder( '\WordPressSolid\Post\Factory\PostFactory' )
		     ->setMockClassName( 'myNewFactory' )
		     ->getMock();

		$this->_solidFramework->setup( 'myNamespace', array(
			'services' => array(
				'postManager' => array(
					'class'  => '\WordPressSolid\Post\Service\PostManager',
					'params' => array(
						array(
							'type'   => 'object',
							'class'  => 'myNewFactory',
							'params' => array(),
						),
					),
				),
			),
		) );

		$postManager = $this->_solidFramework->getService( 'postManager' );
		$this->assertInstanceOf( '\WordPressSolid\Post\Service\PostManager', $postManager );
	}

	public function testSetup_With_Added_Service_Should_Have_Both_Default_And_Custom() {
		$mockClassName = 'myMockClass';
		$this->getMockBuilder( 'nonexistant' )
		     ->setMockClassName( $mockClassName )
		     ->getMock();

		$this->_solidFramework->setup( 'myNamespace', array(
			'services' => array(
				'myCustomManager' => array(
					'class'  => $mockClassName,
					'params' => null,
				),
			),
		) );

		$postManager   = $this->_solidFramework->getService( 'postManager' );
		$customManager = $this->_solidFramework->getService( 'myCustomManager' );
		$pageManager   = $this->_solidFramework->getService( 'pageManager' );
		$this->assertInstanceOf( '\WordPressSolid\Post\Service\PostManager', $postManager );
		$this->assertInstanceOf( $mockClassName, $customManager );
		$this->assertInstanceOf( '\WordPressSolid\Post\Service\PageManager', $pageManager );
	}

	public function testIsSetup_When_Namespace_Is_Not_Setup_Should_Return_False() {
		$this->assertFalse( $this->_solidFramework->isSetup( 'non_existing_namespace' ) );
	}

	public function testIsSetup_When_Namespace_Is_Setup_Should_Return_True() {
		$this->assertTrue( $this->_solidFramework->isSetup( SolidFramework::DEFAULT_NAMESPACE ) );

		$namespace = $this->_setupTestNamespace();
		$this->assertTrue( $this->_solidFramework->isSetup( $namespace ) );
	}

	public function testSwitchTo_When_Namespace_Does_Not_Exist_Should_Throw_RuntimeException() {
		$this->setExpectedException( '\RuntimeException' );
		$this->_solidFramework->switchTo( 'non_existing_namespace' );
	}

	public function testSwitchTo_When_Namespace_Does_Exist_Should_Change_Namespace() {
		$this->_solidFramework->switchTo( SolidFramework::DEFAULT_NAMESPACE );
		$this->assertEquals( SolidFramework::DEFAULT_NAMESPACE, $this->_solidFramework->getCurrentNamespace() );

		$namespace = $this->_setupTestNamespace();
		$this->_solidFramework->switchTo( $namespace );
		$this->assertEquals( $namespace, $this->_solidFramework->getCurrentNamespace() );
	}

	public function testHasService_When_Service_Does_Not_Exist_Should_Return_False() {
		$this->assertFalse( $this->_solidFramework->hasService( 'non_existing_service' ) );
	}

	public function testHasService_When_Service_Exists_Should_Return_True() {
		$this->assertTrue( $this->_solidFramework->hasService( 'postManager' ) );
	}

	/**
	 * @return string
	 */
	private function _setupTestNamespace() {
		$namespace = 'myNamespace';
		$this->_solidFramework->setup( $namespace );

		return $namespace;
	}
}
