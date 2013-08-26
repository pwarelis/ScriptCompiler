<?php

namespace ScriptCompiler\Exception;

class MissingCompilerException extends \Exception {
	protected $command;

	final public function setCommand($command) {
		$this->command = $command;
	}

	final public function getCommand() {
		return $this->command;
	}

}
