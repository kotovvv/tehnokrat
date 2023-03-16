<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Modules;

use ReflectionClass;
use ReflectionException;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Abstract_Module {
	protected $version;
	protected $path;

	protected function __construct() {
	}

	protected function __clone() {
	}

	protected function __wakeup() {
	}

	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new static();
			$instance->init();
		}

		return $instance;
	}

	abstract protected function init();

	/**
	 * @return string
	 */
	public function get_name() {
		static $name = null;

		if ( is_null( $name ) ) {
			$path       = explode( '\\', static::class );
			$class_name = end( $path );
			$name       = strtolower( $class_name );
		}

		return $name;
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function get_path() {
		static $path = null;

		if ( is_null( $path ) ) {
			try {
				$reflector      = new ReflectionClass( static::class );
				$class_filename = $reflector->getFileName();
				$path           = dirname( $class_filename );
			} catch ( ReflectionException $e ) {
			}
		}

		return $path;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		static $url = null;

		if ( is_null( $url ) ) {
			$url = str_replace( ABSPATH, site_url( '/' ), $this->get_path() );
		}

		return $url;
	}
}
