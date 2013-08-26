<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class CoffeeScript extends LanguageCompiler {
	protected $baseLanguage = "js";
	protected $defaults = array(
	);

	public function compile(Resource $resource) {
		throw new \Exception("Implement this!");
	}

}
