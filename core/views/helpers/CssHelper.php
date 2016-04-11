<?php
class CssHelper extends Helper{
	/**
	 * Link the $fileName css - usually found inside public/res/css
	 * @param string $fileName Stylesheet's name including the extension
	 * @return string
	 */
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