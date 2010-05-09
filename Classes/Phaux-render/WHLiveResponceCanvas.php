<?php

class WHLiveResponceCanvas extends WHHtmlCanvas {
	public function __construct(){
		parent::__construct();
		$this->setDocType('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		<!DOCTYPE xsl:stylesheet [ <!ENTITY nbsp "&#160;"> ]>');

		$this->setMimeType("text/xml");
	}
}