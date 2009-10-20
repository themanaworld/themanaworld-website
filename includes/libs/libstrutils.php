<?php

	define("BAD_STRING_DESC", "Only printable characters (except spaces and \") are allowed.");

	function check_chars($string)
	{
		return ctype_graph($string) && (strpos($string, '"') === FALSE);
	}
	
?>