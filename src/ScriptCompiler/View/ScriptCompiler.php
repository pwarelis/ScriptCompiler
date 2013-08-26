<?php

namespace ScriptCompiler\View;

use Zend\View\Helper\AbstractHelper;
use ScriptCompiler\ScriptCompiler as SC;

class ScriptCompiler extends AbstractHelper {
	private $_compiler;

	public function __construct($config = array()) {
		$this->_compiler = new SC($config);
	}

	public function __invoke($resources = null) {
		if (!$resources) return $this->_compiler;
		$this->_compiler->add($resources);
	}

}
