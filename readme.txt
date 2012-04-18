INSTALLATION
==========================

Put it into the mod directory and enable it under Tool Administration


HOW TO USE (METHOD 1)
==========================
   
The (auto)tagging is provided by means of a hook and is typically invoked as follows.

    $tags = trigger_plugin_hook('autotag_kea', 'kea', $params); 

The associative array $params is used to provide the input text

    $params['text'] = $text (a string)

and several settings; $params[<setting-string>] = <setting>, where <setting-string> can be: 

- 'langID'      : The language to use; e.g. 'en' for English.
- 'weightArray' : The 3 weights for single words and 2 and 3 word combinations; e.g. array(1, 2, 3).
- 'maxReturn'   : The maximum number of tags to return; e.g. 5.
- 'minFreq'     : The minimum number of times a word is mentioned in the input text to be returned as tag; e.g. 2.
- 'minWordLen'  : All words smaller in length than this value are filtered out; e.g. 2

The found tags are returned as an associative array where every tag is associated
with its score. The score of a tag (a word or word combination) is calculated with the 
following formula.

    score[tag] = freq[tag] * weight[tag]

with

- freq[tag]   : The number of times the tag was found in the input text.
- weight[tag] : The weight depending on whether tag is a single word or 2 or 3 word combination.


HOW TO USE (METHOD 2)
==========================

The class used by the hook mentioned above is KEA (Keyword Extractor Algorithm),
which you can alternatively directly use in your PHP-code. 
After instantiation ($kea = new KEA()), use the following methods to provide the
several settings mentioned in the previous section. 

- $kea->set_langID()
- $kea->set_weightArray()
- $kea->set_maxReturn()
- $kea->set_minFreq()
- $kes->set_minWordLen()

KEA has one main method Autotag() which expects the input text/string as its sole 
argument and returns the tags-array described under "METHOD 1". 
				
PRECONFIGURED APPLICATIONS
==========================

As an application of these tagging routines the autotag hook is used in three Elgg
mods (blog, groups(forum) and pages) to augment the existing tagging in these mods. 
				