<?php
class CssHelper extends Helper{
	public function link($fileName){
		if(is_array($fileName)){
			$return = "";
			foreach($fileName as $f){
				$return .= "<link type='text/css' rel='stylesheet' href='".BASEPATH."res/css/$f'>";
			}
			$return.="\n";
			return $return;
		} else {
			return "<link type='text/css' rel='stylesheet' href='".BASEPATH."res/css/$fileName'>\n";
		}
	}
}
?>