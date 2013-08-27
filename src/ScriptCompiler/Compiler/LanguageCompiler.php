<?php

namespace ScriptCompiler\Compiler;

use ScriptCompiler\Cache\DiscCache;
use ScriptCompiler\Exception\CompilerException;
use ScriptCompiler\Exception\MissingCompilerException;
use ScriptCompiler\Resource;

abstract class LanguageCompiler {
	protected $baseLanguage;
	protected $defaults = array();
	protected $options;
	protected $flags = '';

	public function __construct() {
		$this->newestTime = time();
		$this->setOptions($this->defaults);
		$this->parseFlags();
		$this->init();
	}

	protected function init() {}
	protected function prepResource($resource) {}

	abstract public function compile(Resource $resource);

	public function setOptions($options) {
		$clean = array();
		// Normalize the options array
		foreach ($options as $key => $value) {
			if (is_numeric($key)) {
				$clean[$value] = null;
			} else {
				$clean[$key] = $value;
			}
		}
		$this->options = $clean;
	}

	protected function parseFlags() {
		$flags = array();
		foreach ($this->options as $flag => $value) {
			$option = "--{$flag}";
			if ($value) $option .= " {$value}";
			$flags[] = $option;
		}
		$this->flags = implode(" ", $flags);
	}

	public function compileResource(Resource $resource, DiscCache $cache) {
		$resource->base = $this->baseLanguage;
		$this->prepResource($resource);
		$modified = $resource->getModifiedTime();

		$isModified = $resource->setHash($cache->buildFilename($resource->path.$modified));
		if ($isModified) {

			if ($resource->isMinified()) {

				if ($resource->isRemote) {
					$resource->setHash($resource->path);
				} elseif (!copy($resource->path, $resource->hash)) {
					throw new \Exception("Resource could not be copied over");
				}

			} else {
				$this->compile($resource);
			}

		}
		return $isModified;
	}

	protected function execute($command) {
		ob_start();
		passthru($command . " 2>&1", $return);
		$output = ob_get_contents();
		ob_end_clean();

		switch ($return) {
			case 0:
				return;
			case 127:
				throw new MissingCompilerException(get_called_class() . ": Resource compiler not found", $command);
			default:
				$this->processError($return, $output, $command);
		}
	}

	protected function processError($return, $output, $command) {
		$error = new CompilerException(get_called_class() . ": Resource compiler error");
		$error->setCompilerDetails($return, $output, $command);
		throw $error;
	}

}
