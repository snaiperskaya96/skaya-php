<?php
class JsHelper extends Helper{
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