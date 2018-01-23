<?php

class Sanitize extends Model {

    function Sanitize()
    {
        parent::Model();
    }
    function text($text)
    {	
		$text = trim($text);
		$text = strip_tags($text);
		return $text;
    }	
}

?>