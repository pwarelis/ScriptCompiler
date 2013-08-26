<?php

namespace ScriptCompiler\Cache;

class DiscCache {
	private $path;

	public function __construct($path) {
		if (!is_writable($path)) {
			throw new \Exception("The cache directory is not writable: {$path}");
		}
		$this->path = realpath($path) . "/";
	}

	public function buildFilename($seed) {
		return $this->path . md5($seed);
	}

}
