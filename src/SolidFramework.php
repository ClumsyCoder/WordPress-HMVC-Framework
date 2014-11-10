<?php

namespace WordPressSolid;

use WordPressSolid\Config\Config;
use WordPressSolid\Config\Factory as ConfigFactory;
use WordPressSolid\DI\Container;

/**
 * Class SolidFramework
 *
 * The core class of the WordPress Solid Framework.  This is a DIC container for managing all the different parts
 * of the framework.  After setting up a namespace for your work you can modify the configuration of the system as you
 * see fit to allow you to develop plugins or themes that meet your needs.
 *
 * @package WordPressSolid
 */
final class SolidFramework {
	const DEFAULT_NAMESPACE = 'WordPressSolid';

	/** @var SolidFramework */
	private static $_instance;
	/** @var string The current namespace being used */
	private $_currentNamespace;
	/** @var array List of workspaces */
	private $_workspaces = array();
	/** @var Config */
	private $_defaultConfig;

	/**
	 * Setup the default workspace for the framework
	 */
	private function __construct() {
		$this->_defaultConfig = ConfigFactory::fromFile( WP_SOLID_BASE_DIR . '/config/config.php', true );
		$this->setup( self::DEFAULT_NAMESPACE, WP_SOLID_BASE_DIR . '/config/config.php' );
	}

	/**
	 * @return SolidFramework
	 */
	public static function instance() {
		if ( ! static::$_instance instanceof DIC ) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

	/**
	 * Reset the framework.  This will remove all workspaces and configuration setup.  This should not normally be
	 * called.  This is intended for use in unit testing only.
	 */
	public static function reset() {
		static::$_instance = null;
	}

	/**
	 * @return string
	 */
	public function getCurrentNamespace() {
		return $this->_currentNamespace;
	}

	/**
	 * Set up the solid framework for a given namespace.  This should always be called before anything else when using
	 * this for your plugin or theme.  This will create a copy of the framework for your given namespace allowing you
	 * to modify configuration without clashing with other developers also using the framework.
	 *
	 * @param string       $namespace The namespace for the plugin or theme
	 * @param string|array $config    Optional. The configuration to be used for this namespace.  Can be array config
	 *                                or path to config.php file.
	 */
	public function setup( $namespace, $config = array() ) {
		$this->_currentNamespace = $namespace;
		$this->_setupWorkspace( $config );
	}

	/**
	 * Is a workspace setup for the given namespace
	 *
	 * @param string $namespace Namespace to check if a workspace is setup for
	 *
	 * @return bool
	 */
	public function isSetup( $namespace ) {
		return isset( $this->_workspaces[ $namespace ] );
	}

	/**
	 * Switch to an existing workspace.  This can be helpful if your plugin or theme wants to switch context between
	 * different namespaces for different function calls.
	 *
	 * @param string $namespace The namespace to switch to
	 */
	public function switchTo( $namespace ) {
		if ( ! $this->isSetup( $namespace ) ) {
			throw new \RuntimeException( "The namespace '{$namespace}' does not exist" );
		}
		$this->_currentNamespace = $namespace;
	}

	/**
	 * @param string $service Name of the service
	 *
	 * @return mixed
	 */
	public function getService( $service ) {
		if ( ! $this->hasService( $service ) ) {
			throw new \RuntimeException( "The service '{$service}' does not exist in '{$this->_currentNamespace}'" );
		}

		return $this->_workspaces[ $this->_currentNamespace ]["service_{$service}"];
	}

	/**
	 * Does a service exist
	 *
	 * @param string $service Name of the service
	 *
	 * @return bool
	 */
	public function hasService( $service ) {
		return isset( $this->_workspaces[ $this->_currentNamespace ]["service_{$service}"] );
	}

	/**
	 * Setup workspace for the current namespace.  This will create a new container if one does not exist.  It will
	 * throw an exception if the workspace is already in use
	 *
	 * @param string $config
	 */
	private function _setupWorkspace( $config ) {
		if ( $this->isSetup( $this->_currentNamespace ) ) {
			throw new \RuntimeException( "The namespace '{$this->_currentNamespace}' is already set up" );
		}

		$this->_workspaces[ $this->_currentNamespace ]                   = new Container();
		$this->_workspaces[ $this->_currentNamespace ]['solidFramework'] = $this;
		$this->_setupServices( $this->_getConfig( $config ) );
	}

	/**
	 * @param $config
	 *
	 * @return Config
	 */
	private function _getConfig( $config ) {
		$configToUse = clone $this->_defaultConfig;
		$newConfig   = is_array( $config ) ? new Config( $config ) : ConfigFactory::fromFile( $config, true );
		$configToUse->merge( $newConfig );

		return $configToUse;
	}

	/**
	 * Setup the services for a given configuration in the current namespace
	 *
	 * @param Config $configToUse
	 */
	private function _setupServices( Config $configToUse ) {
		if ( ! empty( $configToUse->services ) ) {
			foreach ( $configToUse->services as $serviceName => $serviceOptions ) {
				$this->_assertServiceOptions( $serviceOptions, $serviceName );
				$this->_workspaces[ $this->_currentNamespace ]["service_{$serviceName}_options"] = $serviceOptions;

				/**
				 * @param Container $c
				 *
				 * @return string
				 */
				$this->_workspaces[ $this->_currentNamespace ]["service_{$serviceName}"] = function ( $c ) {
					/** @var SolidFramework $solidFramework */
					$options        = $c[ $c->getLastCalledId() . '_options' ];
					$solidFramework = $c['solidFramework'];

					return $solidFramework->_createInstance( $options->class, $options->params );
				};
			}
		}
	}

	/**
	 * Assert the service options are valid
	 *
	 * @param $serviceOptions
	 * @param $serviceName
	 *
	 * @return mixed
	 */
	private function _assertServiceOptions( $serviceOptions, $serviceName ) {
		if ( ! isset( $serviceOptions['class'] ) ) {
			throw new \RuntimeException( "No class provided for the service {$serviceName}" );
		} elseif ( ! class_exists( $serviceOptions['class'] ) ) {
			throw new \RuntimeException( "The class {$serviceOptions['class']} does not exist" );
		}

		if ( ! isset( $serviceOptions['params'] ) ) {
			throw new \RuntimeException( "No params provided for the service {$serviceName}" );
		}
	}

	/**
	 * Create an instance for the given class name and parameters
	 *
	 * @param $className
	 * @param $params
	 *
	 * @return object
	 */
	private function _createInstance( $className, $params ) {
		$reflection = new \ReflectionClass( $className );
		if ( ! empty( $params ) ) {
			$constructorArgs = array();
			foreach ( $params as $param ) {
				if ( isset( $param['type'] ) && $param['type'] == 'object' ) {
					$constructorArgs[] = $this->_createInstance( $param['class'], $param['params'] );
				} else {
					$constructorArgs[] = $param;
				}
			}
		}

		return ! empty( $constructorArgs ) ? $reflection->newInstanceArgs( $constructorArgs ) : $reflection->newInstance();
	}
}