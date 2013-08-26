<?php

namespace ScriptCompiler\Exception;

class CompilerException extends \Exception {
	protected $command;
	protected $return;
	protected $output;

	final public function setCompilerDetails($return, $output, $command) {
		$this->return = $return;
		$this->output = $output;
		$this->command = $command;
	}

	final public function getReturn() {
		return $this->return;
	}

	final public function getOutput() {
		return $this->output;
	}

	final public function getCommand() {
		return $this->command;
	}

}
