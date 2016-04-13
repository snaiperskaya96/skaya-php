<?php
class Component{
	protected $parent;

	function __construct($parent){
		$this->parent = $parent;
		$this->init();
	}

	/**
	 * This is the first action the component does when initialized
     */
	public function init(){

	}
}