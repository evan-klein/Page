<?php

namespace evan_klein\page;

class Page {
	// Default values
	private $cfg = [
		'lang' => 'en-US',

		'title' => 'Page title goes here',
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

		'manifest' => NULL,

		'og:title' => NULL,
		'og:image' => NULL,
		'rss' => [],

		'canonical' => NULL,

		'custom' => NULL
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
		// Shortcut
		$cfg = $this->cfg;

		$lang = !\empty($cfg['lang']) ? " lang=\"{$cfg['lang']}\"":'';
		$title = \htmlspecialchars($cfg['title']);

		$html = <<<HTML
<!doctype html>
<html$lang>
<head>
	<meta charset="UTF-8">
	<title>$title</title>

HTML;

		// description and keywords
		foreach(['description', 'keywords'] as $name){
			if( !\empty($cfg[$name]) ){
				$content = \htmlspecialchars($cfg[$name]);
				$html.=<<<HTML
	<meta name="$name" content="$content">

HTML;
			}
		}

		$html.="</head>\n";

		return $html;
	}


	public function tail(): string {
		// Shortcut
		$cfg = $this->cfg;

		$html = '';

		// TODO

		return $html;
	}
}

?>