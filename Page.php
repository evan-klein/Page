<?php

namespace evan_klein\page;

class Page {
	// Default values
	private $cfg = [
		'manifest' => NULL,
		'lang' => 'en-US',

		'title' => NULL,
		'description' => NULL,
		'keywords' => NULL,

		'shortcut icon' => NULL,
		'icon' => NULL,
		'apple-touch-icon' => NULL,
		'apple-touch-icon-precomposed' => NULL,

		'preconnect' => [],

		'viewport' => 'width=device-width, user-scalable=no, initial-scale=1.0',
		'css' => [],

		'js' => [],
		'templates' => [],
		'noscript' => NULL,

		'plausible' => NULL,

		'custom' => NULL,

		'og:title' => NULL,
		'og:image' => NULL,
		'rss' => [],

		'canonical' => NULL
	];


	public function __construct(array $cfg=[]){
		$this->setCFG($cfg);
	}


	public function getCFG(): array {
		return $this->cfg;
	}


	public function setCFG(array $cfg): self {
		$this->cfg = \array_merge($this->cfg, $cfg);
		return $this;
	}


	public function head(): string {
		$html = <<<HTML
TODO
HTML;

		// TODO

		return $html;
	}


	public function tail(): string {
		$html = '';

		// TODO

		return $html;
	}
}

?>