<?php
	/**
	 * This class automatically extracts keywords from a (string of) text 
	 * @author Huib Biemond &lt;info@coldtrick.com&gt;
	 * @copyright ColdTrick 2008-2009
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link http://www.coldtrick.com/
	 * @package KEA
	 * @version 1.0
	 */
  
	class KEA
	{        
		private $langID = false;
		private $stopwords = array();
		private $weightArray = array(1, 2, 3);
		private $maxReturn = 5;
		private $minFreq = 2;
		private $minWordLen = 2;
		private $vocabulary = array();
		
		/**
		 * Setter for language id; returns false if it is not a string
		 *
		 * @param  string $langID The language (used by the stemmer and stopword list)
		 * @return bool        	  Result
		 */
		public function set_langID($langID)
		{
			$this->stopwords = array();
			if (is_string($langID))
			{
				if ($langID)
				{
					$this->langID = $langID;
				
					$stemmer = dirname(__FILE__).'/languages/'.$langID.'/stemmer_'.$langID.'.php'; 
					if (file_exists($stemmer))	
					{
						require_once $stemmer;
					}
				
					$stopword_file = dirname(__FILE__).'/languages/'.$langID.'/stopwords.txt'; 
					$stopword_str = file_get_contents($stopword_file);
					if (!empty($stopword_str))
					{
						preg_match_all('/(\w+)\s+\|/', $stopword_str, $matches);
						$this->stopwords = preg_replace('/\s+\|/', '', $matches[0]);
					}
				}	
				return true;
			} else {
				return false;
			}
		}
		
		/**
		 * Setter for weightArray; returns false if it is not an 3-array of numbers
		 *
		 * @param  string $weights 3-array of weights for single words and 2 and 3 word combinations
		 * @return bool        	   Result
		 */
		public function set_weightArray($weights)
		{
			if (is_array($weights))
			{
				$invalid = (is_nan($weights[0]) || is_nan($weights[1]) || is_nan($weights[2]));
				if (!$invalid)
				{
					$this->weightArray = $weights;
					return true;
				} else {
					return false;
				}
			}
		}

		/**
		 * Setter for maxReturn; returns false if it is not a positive integer
		 *
		 * @param  string $maxReturn The maximum number of tags to return
		 * @return bool        	     Result
		 */
		public function set_maxReturn($maxReturn)
		{
			$maxReturn = (int) $maxReturn;
			
			if (is_int($maxReturn) && ($maxReturn > 0))
			{
				$this->maxReturn = $maxReturn;
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Setter for minFreq; returns false if it is not a positive integer
		 *
		 * @param  string $minFreq The minimum frequency of the returned tags
		 * @return bool        	   Result
		 */
		public function set_minFreq($minFreq)
		{
			$minFreq = (int) $minFreq;
		
			if (is_int($minFreq) && ($minFreq > 0))
			{
				$this->minFreq = $minFreq;
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Setter for minWordLen; returns false if it is not a positive integer
		 *
		 * @param  string $minWordLen The minimum word length of the returned tags
		 * @return bool        	   	  Result
		 */
		public function set_minWordLen($minWordLen)
		{
			$minWordLen = (int) $minWordLen;
			
			if (is_int($minWordLen) && ($minWordLen > 0))
			{
				$this->minWordLen = $minWordLen;
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Setter for vocabulary; returns false if it is not an array
		 *
		 * @param  string[] $voc Array of words which should be returned as tags (if found)
		 * @return bool          Result
		 */
		public function set_vocabulary($voc)
		{
			if (is_array($voc))
			{
				$this->vocabulary = $voc;
				return true;
			} else {
				return false;
			}
		}
		
		public function KEA()
		{
			
		}
		
		/**
		 * Getter for the available languages
		 *
		 * @return string[] Array of available languages as two-letter language ids
		 */
		public static function get_availableLangs()
		{
			$dirs = scandir(dirname(__FILE__).'/languages');
			
			foreach ($dirs as $index => $dir)
			{
				if (($dir == '.') || ($dir == '..'))
				{
					unset($dirs[$index]); 
				}
			}
			return $dirs;
		}
		
		/**
		* Returns an array of all the (possibly utf8) words in $str
		*
		* @param  string   $str String containing the words
		* @return string[]      array of words
		*/
		private static function str_utf8_words($str)
		{
			$UTF8_WORDS_MASK = "/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u";
			preg_match_all($UTF8_WORDS_MASK, $str, $matches);
			return $matches[0];
		}

		/**
		 * Determines (case-insensitivly) whether <code>word</code> is a stopword according to <code>$this->stopwords</code> array
		 *
		 * @param  string $word Word to check
		 * @return bool         Result           
		 */
		private function is_stopword($word)
		{ 
			if (strlen($word) < $this->minWordLen)
			{
				return true;
			} else {
				return in_array(strtolower($word), $this->stopwords);
			}	
		}
 
		/**
		 * Returns stemmed form of <code>word</code> if a stemmer function exists. Otherwise returns <code>word</code>
		 *
		 * @param  string $word Word to stem
		 * @return string       (Possibly) stemmed word
		 */
		private function stem($word)
		{
			if (function_exists('stemmer_stem_'.$this->langID)) 
			{
				return call_user_func('stemmer_stem_'.$this->langID, $word); 
			} else {  
				return $word;
			} 
		}
				
		/**
		 * Returns an array of the tags generated from <code>text</code>
		 *
		 * @param  string   $text The text to (auto)tag
		 * @return string[]       Array of tags; <code>array(..tag =&gt; score..)</code> 
		 */
		public function Autotag($text)
		{
			$this->text = strtolower($text);
		
			$wordfreq1 = array(); 
			$wordfreq2 = array(); 
			$wordfreq3 = array(); 
			$stemfreq = array();
					
			$words = self::str_utf8_words($this->text);
			$count = count($words);
			// Populate the wordfreq and stemfreq arrays
			for($n = 0; $n < $count; $n++)
			{
				$wordcomb1 = $words[$n];
				
				if (!$this->is_stopword($wordcomb1))
				{
					$stemword = $this->stem($wordcomb1);
					// Count occurences all single (possibly stemmed) words
					$wordfreq1[$stemword]++;
					// Count per stemword the frequencies of all its (unstemmed) originals
					$stemfreq[$stemword][$wordcomb1]++;
			
					if ($n > 0)
					{
						$wordcomb2 = $words[$n - 1].' '.$words[$n];
						// Count occurences all 2-word comb. that don't contain stopwords
						if (!($this->is_stopword($words[$n - 1])))
						{
							$wordfreq2[$wordcomb2]++;
						}
					}

					if ($n > 1)
					{
						$wordcomb3 = $words[$n - 2].' '.$words[$n - 1].' '.$words[$n];
						// Count occurences all 3-word comb. with possibly a stemword in the middle 
						if (!($this->is_stopword($words[$n - 2])))
						{
							$wordfreq3[$wordcomb3]++;
						}
					}
				}
			}
			
			foreach ($wordfreq1 as $stemword => $freq) 
			{
				// Get all stem(med)words from the current stemword
				$stemwords = $stemfreq[$stemword];
			
				// Get the stem(med)words in descending order, according to their frequencies 
				arsort($stemwords, SORT_NUMERIC);
				$stemwords_ordered = array_keys($stemwords);
			    
				// Set frequency of most frequent (unstemmed) word to frequency of its stem
				$wordfreq1[$stemwords_ordered[0]] = $wordfreq1[$stemword];
			
				// Remove (reference to) $stemword if it isn't originally in $text 
				if (!array_key_exists($stemword, $stemwords))
				{
					unset($wordfreq1[$stemword]);
				}
			}
			
			// Discard word(combination)s occuring less than the minimum frequency
			foreach ($wordfreq1 as $word => $freq)
			{
				if ($freq < $this->minFreq)
				{
					unset($wordfreq1[$word]);
				} 
			}
			foreach ($wordfreq2 as $wordcomb => $freq)
			{
				if ($freq < $this->minFreq)
				{
					unset($wordfreq2[$wordcomb]);
				} 		
			}
			foreach ($wordfreq3 as $wordcomb => $freq)
			{
				if ($freq < $this->minFreq)
				{
					unset($wordfreq3[$wordcomb]);
				} 		
			}
     
			// Calculate the score by applying the weights
			$wordfreq1 = $this->calcScore($wordfreq1, $weightArray[0]);
			$wordfreq2 = $this->calcScore($wordfreq2, $weightArray[1]);
			$wordfreq3 = $this->calcScore($wordfreq3, $weightArray[2]);
			
			// Sort all found words and word combinations according to their scores ..
			$wordscore = array_merge($wordfreq1, $wordfreq2, $wordfreq3);
			arsort($wordscore, SORT_NUMERIC); 
			
			// .. and limit the number to return by $maxReturn
			$tags = array_slice($wordscore, 0, $this->maxReturn); 

			// Return the tags array
			return $tags;
		}
		
		private function calcScore($words, $weight)
		{
			foreach($words as $word => $freq)
			{
				$words[$word] = $freq * $weight;
			}
			return $words;
		}
	}
?>
