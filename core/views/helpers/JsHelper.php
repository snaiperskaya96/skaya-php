<?php
class JsHelper extends Helper{
	/**
	 * Link the $fileName js - usually found inside public/res/js
	 * @param string $fileName Script's name including the extension
	 * @return string
	 */
	public function link($fileName){
		if(is_array($fileName)){
			$return = "";
			foreach($fileName as $f){
				$return .= "<script type='text/javascript' src='".BASEPATH."res/js/$f'></script>";
			}
			$return.="\n";
			return $return;
		} else {
			return "<script type='text/javascript' src='".BASEPATH."res/js/$fileName'></script>\n";
		}
	}
}
?>