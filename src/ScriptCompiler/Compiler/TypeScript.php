<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Resource;

class TypeScript extends LanguageCompiler {
	protected $baseLanguage = "js";
	protected $defaults = array(
		"removeComments"
	);

	public function compile(Resource $resource) {
		$command = "tsc {$this->flags} --out {$resource->hash} {$resource->path}";
		$this->execute($command);
	}

}
