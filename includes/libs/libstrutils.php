<?php

	define("BAD_STRING_DESC", "Only alphanumeric characters are allowed.");

	function check_chars($string)
	{
		return ctype_alnum($string) && (strpos($string, '"') === FALSE);
	}
	
?>
