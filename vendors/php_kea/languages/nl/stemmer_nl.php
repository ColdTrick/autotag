<?php

	function _unicode_caseflip($matches) 
	{
		return $matches[0][0] . chr(ord($matches[0][1]) ^ 32);
	}

	function drupal_strtolower($text) 
	{
  		global $multibyte;
  		if ($multibyte == UNICODE_MULTIBYTE) 
		{
    		return mb_strtolower($text);
  		}
  		else 
		{
    		// Use C-locale for ASCII-only lowercase
    		$text = strtolower($text);
    		// Case flip Latin-1 accented letters
    		$text = preg_replace_callback('/\xC3[\x80-\x96\x98-\x9E]/', '_unicode_caseflip', $text);
    		return $text;
  		}
	}

	function drupal_substr($text, $start, $length = NULL) 
	{
  		global $multibyte;
  		if ($multibyte == UNICODE_MULTIBYTE) 
		{
    		return $length === NULL ? mb_substr($text, $start) : mb_substr($text, $start, $length);
  		}
  		else 
		{
    		$strlen = strlen($text);
    		// Find the starting byte offset
    		$bytes = 0;
    		if ($start > 0) 
			{
      			// Count all the continuation bytes from the start until we have found
      			// $start characters
      			$bytes = -1; $chars = -1;
      			while ($bytes < $strlen && $chars < $start) 
				{
        			$bytes++;
        			$c = ord($text[$bytes]);
        			if ($c < 0x80 || $c >= 0xC0) 
					{
          				$chars++;
        			}
      			}
    		}
    		else if ($start < 0) 
			{
      			// Count all the continuation bytes from the end until we have found
      			// abs($start) characters
      			$start = abs($start);
      			$bytes = $strlen; $chars = 0;
      			while ($bytes > 0 && $chars < $start) 
				{
        			$bytes--;
        			$c = ord($text[$bytes]);
        			if ($c < 0x80 || $c >= 0xC0) 
					{
          				$chars++;
        			}
      			}
    		}
    		$istart = $bytes;

    		// Find the ending byte offset
    		if ($length === NULL) 
			{
      			$bytes = $strlen - 1;
    		}
    		else if ($length > 0) 
			{
      			// Count all the continuation bytes from the starting index until we have
      			// found $length + 1 characters. Then backtrack one byte.
      			$bytes = $istart; $chars = 0;
      			while ($bytes < $strlen && $chars < $length) 
				{
        			$bytes++;
        			$c = ord($text[$bytes]);
        			if ($c < 0x80 || $c >= 0xC0) 
					{
          				$chars++;
        			}
      			}
      			$bytes--;
    		}
    		else if ($length < 0) 
			{
      			// Count all the continuation bytes from the end until we have found
      			// abs($length) characters
      			$length = abs($length);
      			$bytes = $strlen - 1; $chars = 0;
      			while ($bytes >= 0 && $chars < $length) 
				{
        			$c = ord($text[$bytes]);
        			if ($c < 0x80 || $c >= 0xC0) 
					{
          				$chars++;
        			}
        			$bytes--;
      			}
    		}
    		$iend = $bytes;

    		return substr($text, $istart, max(0, $iend - $istart + 1));
  		}
	}

	function stemmer_stem_nl($word) 
	{
  		global $_dutchstemmer_step2;

  		$_dutchstemmer_step2 = FALSE;

  		// Lowercase
  		$word = drupal_strtolower($word);
		
		// Step 0: early (accented) suffix removal
  		$word = dutchstemmer_step0($word, $r1, $r2);
				
 		// Remove accents
  		$word = iconv('UTF-8', 'ASCII//TRANSLIT', $word);
      
  		// Put initial y, y after a vowel, and i between vowels into upper case (treat as consonants).
  		$word = preg_replace(array('/^y|(?<=[aeiouy])y/u', '/(?<=[aeiouy])i(?=[aeiouy])/u'), array('Y', 'I'), $word);
      
  		// R1 is the region after the first non-vowel following a vowel, or is the null region at the end of the word if there is no such non-vowel.
  		if (preg_match('/[aeiouy][^aeiouy]/u', $word, $matches, PREG_OFFSET_CAPTURE)) 
		{
    		$r1 = $matches[0][1] + 2;
  		}

  		// R2 is the region after the first non-vowel following a vowel in R1, or is the null region at the end of the word if there is no such non-vowel.
  		if (preg_match('/[aeiouy][^aeiouy]/u', $word, $matches, PREG_OFFSET_CAPTURE, $r1)) 
		{
    		$r2 = $matches[0][1] + 2;
  		}

  		// Steps 1-4: suffix removal
  		$word = dutchstemmer_step1($word, $r1, $r2);
  		$word = dutchstemmer_step2($word, $r1, $r2);
  		$word = dutchstemmer_step3($word, $r1, $r2);
  		$word = dutchstemmer_step4($word, $r1, $r2);

  		$word = str_replace(array('Y', 'I'), array('y', 'i'), $word);
  		return $word;
	}

	function dutchstemmer_undouble($word) 
	{
  		return preg_match('/(bb|dd|gg|kk|ll|mm|nn|pp|rr|ss|tt|zz)$/u', $word) ? substr($word, 0, -1) : $word;
	}

	function dutchstemmer_step0($word) 
	{
  		// Step 0: accented suffixes
    	$word = urlencode($word); 	
		
    	$word = str_replace('e%C3%ABn', 'e', $word);
		$word = str_replace('ieel', 'ie', $word);
		$word = str_replace('i%C3%ABle', 'ie', $word);
		$word = str_replace('ie%C3%ABn', 'ie', $word);
        
    	return urldecode($word);
	}

	function dutchstemmer_step1($word, $r1, $r2) 
	{
  		// Step 1:
  		// Search for the longest among the following suffixes, and perform the action indicated
  		if ($r1) 
		{
    		// -heden
    		if (preg_match('/heden$/u', $word, $matches, 0, $r1)) 
			{
      			return preg_replace('/heden$/u', 'heid', $word, -1, $count);
    		}
    		// -en(e)
    		else if (preg_match('/(?<=[^aeiouy]|gem)ene?$/u', $word, $matches, 0, $r1)) 
			{
      			return dutchstemmer_undouble(preg_replace('/ene?$/u', '', $word, -1, $count));
    		}
    		// -s(e)
    		else if (preg_match('/(?<=[^jaeiouy])se?$/u', $word, $matches, 0, $r1)) 
			{
      			return rtrim(preg_replace('/se?$/u', '', $word, -1, $count), "'");
    		}
  		}
  		return $word;
	}

	function dutchstemmer_step2($word, $r1, $r2) 
	{
  	// Step 2:
  		// Delete suffix e if in R1 and preceded by a non-vowel, and then undouble the ending
  		if ($r1) 
		{
    		if (preg_match('/(?<=[^aeiouy])e$/u', $word, $matches, 0, $r1)) 
			{
      			// TODO: this should be here to make any sense
      			// global $_dutchstemmer_step2;
      			$_dutchstemmer_step2 = TRUE;
      			return dutchstemmer_undouble(preg_replace('/e$/u', '', $word, -1, $count));
    		}
  		}
  		return $word;
	}

	function dutchstemmer_step3($word, $r1, $r2) 
	{
  		global $_dutchstemmer_step2;

  		// Step 3a: heid
  		// delete heid if in R2 and not preceded by c, and treat a preceding en as in step 1(b)
  		if ($r2) 
		{
    		if (preg_match('/(?<!c)heid$/u', $word, $matches, 0, $r2)) 
			{
      			$word = preg_replace('/heid$/u', '', $word, -1, $count);
      			if (preg_match('/en$/u', $word, $matches, 0, $r1)) 
				{
        			$word = dutchstemmer_undouble(preg_replace('/en$/u', '', $word, -1, $count));
      			}
    		}
  		}

  		// Step 3b: d-suffixes (*)
  		// Search for the longest among the following suffixes, and perform the action indicated.
  		if ($r2) 
		{
    		// -baar
    		if (preg_match('/baar$/u', $word, $matches, 0, $r2)) 
			{
      			$word = preg_replace('/baar$/u', '', $word, -1, $count);
    		}
    		// -lijk
    		else if (preg_match('/lijk$/u', $word, $matches, 0, $r2)) 
			{
      			$word = dutchstemmer_step2(preg_replace('/lijk$/u', '', $word, -1, $count), $r1, $r2);
    		}
    		// -end / -ing
    		else if (preg_match('/(end|ing)$/u', $word, $matches, 0, $r2)) 
			{
      			$word = preg_replace('/(end|ing)$/u', '', $word, -1, $count);
      			// -ig
      			if (preg_match('/(?<!e)ig$/u', $word, $matches, 0, $r2)) 
				{
        			$word = preg_replace('/ig$/u', '', $word, -1, $count);
      			}
    		}
    		// -ig
    		else if (preg_match('/(?<!e)ig$/u', $word, $matches, 0, $r2)) 
			{
      			$word = preg_replace('/ig$/u', '', $word, -1, $count);
    		}
    		// -bar
    		else if ($_dutchstemmer_step2 && preg_match('/bar$/u', $word, $matches, 0, $r2)) 
			{
      			$word = preg_replace('/bar$/u', '', $word, -1, $count);
    		}
  		}

  		return $word;
	}

	function dutchstemmer_step4($word, $r1, $r2) 
	{
  		// Step 4: undouble vowel
  		// If the words ends CVD, where C is a non-vowel, D is a non-vowel other than
  		// I, and V is double a, e, o or u, remove one of the vowels from V 
  		// (for example, maan -> man, brood -> brod).
  		if (preg_match('/[^aeiouy](aa|ee|oo|uu)[^Iaeiouy]$/u', $word)) 
		{
    		$word = drupal_substr($word, 0, -2) . str_replace(array('s', 'f'), array('z', 'v'), drupal_substr($word, -1));
  		}
  		return $word;
	}
?>