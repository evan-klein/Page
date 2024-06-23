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

		'templates' => [],
		'js' => []
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

		$lang = !empty($cfg['lang']) ? " lang=\"{$cfg['lang']}\"":'';
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
			if( !empty($cfg[$name]) ){
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
		if( !empty($cfg['viewport']) ){
			$html.=<<<HTML
	<meta name="viewport" content="{$cfg['viewport']}">

HTML;
		}

		// css
		foreach($cfg['css'] as $href){
			$html.=<<<HTML
	<link rel="stylesheet" href="$href">

HTML;
		}

		// noscript
		if( !empty($cfg['noscript']) ){
			$html.=<<<HTML
	<noscript>
		<meta http-equiv="refresh" content="0;url={$cfg['noscript']}">
	</noscript>

HTML;
		}

		// plausible

		// manifest
		if( !empty($cfg['manifest']) ){
			$html.=<<<HTML
	<link rel="manifest" href="{$cfg['manifest']}">

HTML;
		}

		// og:title and og:image
		foreach(['og:title', 'og:image'] as $property){
			if( !empty($cfg[$property]) ){
				$content = \htmlspecialchars($cfg[$property]);
				$html.=<<<HTML
	<meta property="$property" content="$content">

HTML;
			}
		}

		// rss
		foreach($cfg['rss'] as $title=>$url){
			$title = \htmlspecialchars($title);
			$html.=<<<HTML
	<link rel="alternate" type="application/rss+xml" title="$title" href="$url">

HTML;
		}

		// canonical
		if( !empty($cfg['canonical']) ){
			$html.=<<<HTML
	<link rel="canonical" href="{$cfg['canonical']}">

HTML;
		}

		// custom
		if( !empty($cfg['custom']) ) $html.="\t" . $cfg['custom'] . "\n";

		$html.="</head>\n";

		return $html;
	}


	public function tail(): string {
		// Shortcut
		$cfg = $this->cfg;

		$html = '';

		// templates
		foreach($cfg['templates'] as $id=>$file_path){
			$template = \file_get_contents($file_path);
			$html.=<<<HTML
<script id="template_$id" type="text/html">
$template
</script>

HTML;
		}

		// js

		return $html;
	}
}

?>