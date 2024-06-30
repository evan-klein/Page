<?php

namespace evan_klein\page;
use evan_klein\ek as ek;

require_once('ek.php');

class Page {
	// Default values
	private $cfg = [
		'lang' => 'en-US',

		'title' => 'Page title goes here',
		'description' => NULL,
		'keywords' => NULL,
		'author' => NULL,

		'icon' => NULL,
		'apple-touch-icon' => NULL,

		'preconnect' => [],
		'google_fonts' => false,

		'viewport' => 'width=device-width, user-scalable=no, initial-scale=1.0',
		'format-detection' => NULL,
		'css' => [],
		'apple' => [
			'apple-mobile-web-app-capable' => NULL,
			'apple-mobile-web-app-status-bar-style' => NULL,
			'!apple-mobile-web-app-title' => NULL
		],

		'noscript' => NULL,

		'plausible' => NULL,

		'manifest' => NULL,

		'og' => [
			'!og:title' => NULL,
			'og:image' => NULL,
			'og:type' => NULL,
			'og:url' => NULL,
			'!og:image:alt' => NULL,
			'!og:description' => NULL,
			'!og:determiner' => NULL,
			'!og:locale' => NULL,
			'!og:site_name' => NULL,
			'og:video' => NULL,
			'og:audio' => NULL
		],
		'twitter' => [
			'twitter:card' => NULL,
			'!twitter:title' => NULL,
			'!twitter:description' => NULL,
			'twitter:image' => NULL,
			'!twitter:image:alt' => NULL,
			'!twitter:site' => NULL,
			'!twitter:site:id' => NULL,
			'!twitter:creator' => NULL,
			'!twitter:creator:id' => NULL,
			'twitter:player' => NULL,
			'twitter:player:width' => NULL,
			'twitter:player:height' => NULL,
			'twitter:player:stream' => NULL,
			'!twitter:app:name:iphone' => NULL,
			'!twitter:app:id:iphone' => NULL,
			'twitter:app:url:iphone' => NULL,
			'!twitter:app:name:ipad' => NULL,
			'!twitter:app:id:ipad' => NULL,
			'twitter:app:url:ipad' => NULL,
			'!twitter:app:name:googleplay' => NULL,
			'!twitter:app:id:googleplay' => NULL,
			'twitter:app:url:googleplay' => NULL
		],
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


	public function addPreconnect($preconnect): self {
		$this->cfg['preconnect'][] = $preconnect;
		return $this;
	}


	public function addCSS($css): self {
		$this->cfg['css'][] = $css;
		return $this;
	}


	public function addApple(string $name, string $content): self {
		$this->cfg['apple'][$name] = $content;
		return $this;
	}


	public function addOG(string $property, string $content): self {
		$this->cfg['og'][$property] = $content;
		return $this;
	}


	public function addTwitter(string $name, string $content): self {
		$this->cfg['twitter'][$name] = $content;
		return $this;
	}


	public function addRSS(string $title, string $url): self {
		$this->cfg['rss'][$title] = $url;
		return $this;
	}


	public function addTemplate(string $id, string $file_path): self {
		$this->cfg['templates'][$id] = $file_path;
		return $this;
	}


	public function addJS($js): self {
		$this->cfg['js'][] = $js;
		return $this;
	}


	/*
	This internal helper function makes it possible to provide simplified array representations of CSS/JS files, preconnects, etc, i.e.:

	[
		'script.js',
		'ek.js'
	]

	-or-

	[
		['script.js'],
		['ek.js']
	]

	...instead of more complex, verbose representations like:

	[
		[
			'src' => 'script.js'
		],
		[
			'src' => 'ek.js'
		]
	]

	If the array provided is a simple array, it converts it to a complex array using the values from $attr and $default_vals. If it is a complex array, it returns the original array with no modifications
	*/
	private function arrayHelper(string|array $input, string $attr, array $default_vals=[]): array {
		if( \is_string($input) ) $input = [$input];

		if(
			!\count($input)==1
			||
			!\array_key_first($input)==0
		) return $input;

		return \array_merge(
			$default_vals,
			[
				$attr => $input[0]
			]
		);
	}


	public function head(): string {
		// Shortcut
		$cfg = $this->cfg;

		$lang = !empty($cfg['lang']) ? " lang=\"{$cfg['lang']}\"":'';
		$title = ek\htmlSafe($cfg['title']);

		$html = <<<HTML
<!doctype html>
<html$lang>
<head>
	<meta charset="UTF-8">
	<title>$title</title>

HTML;

		// description, keywords and author
		foreach(['description', 'keywords', 'author'] as $name){
			if( !empty($cfg[$name]) ){
				$content = ek\htmlSafe($cfg[$name]);
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

		// Google Fonts
		if($cfg['google_fonts']){
			$cfg['preconnect'][]='https://fonts.googleapis.com';
			$cfg['preconnect'][]=[
				'rel' => 'preconnect',
				'href' => 'https://fonts.gstatic.com',
				'crossorigin' => NULL
			];
		}

		// preconnect
		foreach($cfg['preconnect'] as $preconnect){
			$html.=ek\buildHTMLElem(
				'link',
				$this->arrayHelper(
					$preconnect,
					'href',
					['rel' => 'preconnect']
				),
				'',
				"\n",
				"\t"
			);
		}

		// viewport
		if( !empty($cfg['viewport']) ){
			$html.=<<<HTML
	<meta name="viewport" content="{$cfg['viewport']}">

HTML;
		}

		// format-detection
		if( !empty($cfg['format-detection']) ){
			$html.=<<<HTML
	<meta name="format-detection" content="{$cfg['format-detection']}">

HTML;
		}

		// css
		foreach($cfg['css'] as $css){
			$html.=ek\buildHTMLElem(
				'link',
				$this->arrayHelper(
					$css,
					'href',
					['rel' => 'stylesheet']
				),
				'',
				"\n",
				"\t"
			);
		}

		// apple
		foreach($cfg['apple'] as $name=>$content){
			if( !empty($content) ){
				// If the attribute name starts with a "!", then use ek\htmlSafe() to escape it
				if( \substr($name, 0, 1)=='!' ){
					$name = \substr($name, 1);
					$content = ek\htmlSafe($content);
				}

				$html.=<<<HTML
	<meta name="$name" content="$content">

HTML;
			}
		}

		// noscript
		if( !empty($cfg['noscript']) ){
			$html.=<<<HTML
	<noscript>
		<meta http-equiv="refresh" content="0; url={$cfg['noscript']}">
	</noscript>

HTML;
		}

		// plausible
		if( !empty($cfg['plausible']) ){
			$html.=<<<HTML
	<script defer data-domain="{$cfg['plausible']}" src="https://plausible.io/js/script.js"></script>

HTML;
		}

		// manifest
		if( !empty($cfg['manifest']) ){
			$html.=<<<HTML
	<link rel="manifest" href="{$cfg['manifest']}">

HTML;
		}

		// og
		foreach($cfg['og'] as $property=>$content){
			if( !empty($content) ){
				// If the attribute name starts with a "!", then use ek\htmlSafe() to escape it
				if( \substr($property, 0, 1)=='!' ){
					$property = \substr($property, 1);
					$content = ek\htmlSafe($content);
				}

				$html.=<<<HTML
	<meta property="$property" content="$content">

HTML;
			}
		}

		// twitter
		foreach($cfg['twitter'] as $name=>$content){
			if( !empty($content) ){
				// If the attribute name starts with a "!", then use ek\htmlSafe() to escape it
				if( \substr($name, 0, 1)=='!' ){
					$name = \substr($name, 1);
					$content = ek\htmlSafe($content);
				}

				$html.=<<<HTML
	<meta name="$name" content="$content">

HTML;
			}
		}

		// rss
		foreach($cfg['rss'] as $title=>$url){
			$title = ek\htmlSafe($title);
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
		foreach($cfg['js'] as $js){
			$html.=ek\buildHTMLElem(
				'script',
				$this->arrayHelper(
					$js,
					'src'
				),
				$js['--content'] ?? '',
				"\n",
				"\t"
			);
		}

		return $html;
	}
}

?>