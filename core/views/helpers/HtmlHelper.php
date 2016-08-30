<?php

namespace SkayaPHP\Views\Helpers;

class HtmlHelper extends Helper{

    /**
     * Generate an hyperlink tag
     * @param string $text
     * @param string $href
     * @param array $attributes Array with the [$attribute => $value] format
     * @return string
     */
    public function link($text = "", $href = "", $attributes = []){
		$link = "<a href='$href'";
		foreach($attributes as $k=>$v)
			$link .= " " . $k . "='$v'";
		$link .= ">$text</a>\n";
		return $link;
	}
}
?>