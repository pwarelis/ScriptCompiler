<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class Less extends LanguageCompiler {
	protected $baseLanguage = "css";
	protected $defaults = array(
	);

	public function compile(Resource $resource) {
		throw new \Exception("Implement this!");
	}
}
