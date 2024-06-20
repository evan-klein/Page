<?php

namespace evan_klein\page;

class Page {
	// Default values
	private $cfg = [
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL,
		'' => NULL
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