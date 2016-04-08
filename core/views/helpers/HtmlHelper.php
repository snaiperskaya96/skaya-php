<?php
class HtmlHelper extends Helper{
	public function link($text = "", $href = "", $attributes = []){
		$link = "<a href='$href'";
		foreach($attributes as $k=>$v)
			$link .= " " . $k . "='$v'";
		$link .= ">$text</a>\n";
		return $link;
	}
}
?>