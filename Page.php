<?php

namespace evan_klein\page;

class Page {
	// Default values
	private $cfg = [
		'lang' => 'en-US',

		'title' => 'Page title goes here',
		'description' => NULL,
		'keywords' => NULL,

		'icon' => NULL,
		'apple-touch-icon' => NULL,

		'preconnect' => [],

		'viewport' => 'width=device-width, user-scalable=no, initial-scale=1.0',
		'css' => [],

		'noscript' => NULL,

		'plausible' => NULL,

		'manifest' => NULL,

		'og:title' => NULL,
		'og:image' => NULL,
		'rss' => [],

		'canonical' => NULL,

		'custom' => NULL,

		'js' => [],
		'templates' => []
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

		// icon and apple-touch-icon
		foreach(['icon', 'apple-touch-icon'] as $type){
			if( \is_string($cfg[$type]) ) $cfg[$type] = [$cfg[$type]];
			if( \is_array($cfg[$type]) ){
				foreach($cfg[$type] as $size=>$href){
					$sizes = \stripos($size, 'x')===false ? '':" sizes=\"$size\"";
					$html.="\t<link rel=\"$type\"$sizes href=\"$href\">\n";
				}
			}
		}

		// preconnect

		// viewport
		if( !\empty($cfg['viewport']) ){
			$html.=<<<HTML
	<meta name="viewport" content="{$cfg['viewport']}">

HTML;
		}

		// css

		// noscript

		// plausible

		// manifest

		// og:title and og:image
		foreach(['og:title', 'og:image'] as $property){
			if( !\empty($cfg[$property]) ){
				$content = \htmlspecialchars($cfg[$property]);
				$html.=<<<HTML
	<meta property="$property" content="$content">

HTML;
			}
		}

		// rss
		if( \is_array($cfg['rss']) ){
			foreach($cfg['rss'] as $title=>$url){
				$title = \htmlspecialchars($title);
				$html.=<<<HTML
	<link rel="alternate" type="application/rss+xml" title="$title" href="$url">

HTML;
			}
		}

		// canonical

		// custom

		$html.="</head>\n";

		return $html;
	}


	public function tail(): string {
		// Shortcut
		$cfg = $this->cfg;

		$html = '';

		// js

		// templates

		return $html;
	}
}

?>