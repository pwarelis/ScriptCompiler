<?php

namespace ScriptCompiler;

class PathManager {
	protected $aliases = array();
	protected $docRoot;

	public function __construct($aliasMap = array(), $documentRoot = null) {
		$this->addAliasMap($aliasMap);
		$this->setDocumentRoot($documentRoot);
	}

	public function addAliasMap($map) {
		foreach ($map as $alias => $path) {
			$check = realpath($path);
			if (!$check) throw new \Exception("Alias path does not exist: {$path}");
			$this->aliases[$alias] = $check;
		}
	}

	public function setDocumentRoot($path) {
		if (!$path) $path = $_SERVER["DOCUMENT_ROOT"];
		$this->docRoot = realpath($path);
		if (!$this->docRoot) {
			throw new \Exception("Document root is not a valid path: {$path}");
		}
	}

	public function getPath($url) {
		foreach ($this->aliases as $alias => $path) {
			if (strpos($url, $alias) !== 0) continue;
			return $path . basename($url);
		}
		return $this->docRoot . $url;
	}
}
