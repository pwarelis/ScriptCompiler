<?php
namespace ScriptCompiler;

use ScriptCompiler\Cache\CacheInterface;
use ScriptCompiler\Cache\DiscCache;

class ScriptCompiler {
	protected $_paths;

	public function __construct($config = array()) {
		$config = $this->mergeConfigs($config);

		if ($config["cache"] instanceof DiscCache) {
			$cache = $config["cache"];
		} else {
			$cache = new DiscCache($config["cache"]);
		}

		$paths = new PathManager($config["aliases"], $config["doc_root"]);

		$this->_resources = new ResourceManager(array(
			"types" => $config["types"],
			"cache_remote" => $config["cache_remote"]
		));
		$this->_resources->setCache($cache);
		$this->_resources->setPathManager($paths);
	}


	private function mergeRecursive(&$array1, &$array2) {
		$merged = $array1;
		foreach ($array2 as $key => &$value) {
			if (is_array($value) && isset ($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = $this->mergeRecursive($merged[$key], $value);
			} else {
				$merged[$key] = $value;
			}
		}
		return $merged;
	}

	protected function mergeConfigs($config) {

		if (!is_array($config)) {
			throw new \Exception("Configuration must be an array");
		}

/*
Implement the following:

https://www.dartlang.org
coffeescript.org
ckknight.github.io/gorillascript/
www.typescriptlang.org
www.cappuccino-project.org/learn/objective-j.html
rubyjs.org/index.html

 */

		$dc = $_SERVER['DOCUMENT_ROOT'];
		$defaults = array(
			"doc_root" => $dc,
			"cache" => "{$dc}/cache",
			"aliases" => array(),
			"cache_remote" => true,
			"types" => array(
				'css' => 'ScriptCompiler\Compiler\Sass',
				'scss' => 'ScriptCompiler\Compiler\Sass',
				'less' => 'ScriptCompiler\Compiler\Less',
				'js' => 'ScriptCompiler\Compiler\UglifyJs2',
				'cs' => 'ScriptCompiler\Compiler\CoffeeScript',
			)
		);
		return $this->mergeRecursive($defaults, $config);
	}

	public function add($resource) {
		$this->_resources->addResources($resource, ResourceManager::SCRIPT_APPEND);
	}

	public function prepend($resource) {
		$this->_resources->addResources($resource, ResourceManager::SCRIPT_PREPEND);
	}

	public function getResources($urlPath = null) {
		if (!$urlPath) $urlPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		return $this->_resources->compileResources($urlPath);
	}

	public function __toString() {
		try {
			$compressed = $this->getResources();
		} catch (\Exception $e) {
			die($e->getMessage());
		}

		$tags = "";
		foreach ($compressed as $base => $url) {
			switch ($base) {
				case "css":
					$tags .= "<link href=\"{$url}\" rel=\"stylesheet\" type=\"text/css\" />";
					break;
				case "js":
					$tags .= "<script src=\"{$url}\"></script>";
					break;
			}
		}
		return $tags;
	}

}
