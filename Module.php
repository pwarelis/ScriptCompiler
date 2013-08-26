<?php
/**
 * @link      https://github.com/pwarelis/ScriptCompiler
 * @copyright Copyright (c) 2013 Paul Warelis
 * @package   ScriptCompiler
 */

namespace ScriptCompiler;

/**
 * ZF2 Module
 */
class Module {

	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				)
			)
		);
	}

	public function getViewHelperConfig() {
		return array(
			'invokables' => array(
				'scriptCompiler' => 'ScriptCompiler\View\ScriptCompiler'
			)
		);
	}
}
