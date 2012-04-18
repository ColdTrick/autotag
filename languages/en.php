<?php

	$english = array(
	
		"autotag:adminsettings:langid:none" => "none",
		"autotag:adminsettings:langid:label" => "The language to use",
		"autotag:adminsettings:langid:description" => "The algorithm uses a stemmer and stopword list in case a language is selected.",
		"autotag:adminsettings:weightarray:label" => "The weights for single words and 2 and 3 word combinations",
		"autotag:adminsettings:weightarray:description" => "These weights are multiplied with the frequency of the found words or word combinations.",
		"autotag:adminsettings:maxreturn:label" => "The maximum number of tags to return",
		"autotag:adminsettings:maxreturn:description" => "",
		"autotag:adminsettings:minfreq:label" => "The minimum tag frequency",
		"autotag:adminsettings:minfreq:description" => "The minimum number of times a word is mentioned in the input text to be returned as tag.",
		"autotag:adminsettings:minwordlen:label" => "The minimum word length",
		"autotag:adminsettings:minwordlen:description" => "All words smaller in length than this value are filtered out.",
		"autotag:adminsettings:activation:label" => "The modules to hook with Autotag",
		"autotag:adminsettings:activation:description" => "",
		"autotag:adminsettings:vocabulary:label" => "Array of words which should be returned as tags (if found)",
		"autotag:adminsettings:footertext" => "These settings are the default values; they can be overruled by scripts using the tagging algorithm.",
	);
					
	add_translation("en", $english);
?>
