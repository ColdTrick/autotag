<?php
	require_once dirname(__FILE__) . "/vendors/php_kea/kea.php";

	function autotag_init()
	{
		register_plugin_hook('autotag_kea', 'kea', 'autotag_kea_hook');
		if (get_plugin_setting('blogactivate', 'autotag') != 'no')
		{
			if (is_plugin_enabled('blog'))
			{
				extend_view("blog/forms/edit", "autotag/blog");
			}
		}
		if (get_plugin_setting('forumactivate', 'autotag') != 'no')
		{
			if (is_plugin_enabled('groups'))
			{
				extend_view("forms/forums/addtopic", "autotag/forumtopic");
			}
		}
		if (get_plugin_setting('pagesactivate', 'autotag') != 'no')
		{
			if (is_plugin_enabled('pages'))
			{
				extend_view("forms/pages/edit", "autotag/pages");
			}
		}
	}
		
	function autotag_kea_hook($hook, $entity_type, $returnvalue, $params)
	{
		if (!empty($params))
		{ 
			if (array_key_exists('text', $params))
			{   			
				$text = strip_tags(htmlspecialchars_decode($params['text']));
				$kea = new KEA();
				
				if (array_key_exists('langID', $params))
				{
					$kea->set_langID($params['langID']); 
				} else {
					$langID = get_plugin_setting('langID', 'autotag');
					if (!empty($langID) && ($langID != 'none')) 
					{
						$kea->set_langID($langID);
					}						
				} 
				
				if (array_key_exists('weightArray', $params))
				{
					$kea->set_weightArray($params['weightArray']);
				} else {
					$weight1 = get_plugin_setting('weight1', 'autotag');
					$weight2 = get_plugin_setting('weight2', 'autotag');
					$weight3 = get_plugin_setting('weight3', 'autotag');
					if (!empty($$weight1) && !empty($$weight2) && !empty($$weight3))
					{
						$kea->set_weightArray(array($weight1, $weight2, $weight3));
					}
				}	
				if (array_key_exists('maxReturn', $params))
				{
					$kea->set_maxReturn($params['maxReturn']);
				} else {
					$maxReturn = get_plugin_setting('maxReturn', 'autotag');
					if (!empty($maxReturn)) 
					{
						$kea->set_maxReturn($maxReturn);
					}
				} 	
				if (array_key_exists('minFreq', $params))
				{
					$kea->set_minFreq($params['minFreq']);
				} else {
					$minFreq = get_plugin_setting('minFreq', 'autotag');
					if (!empty($minFreq))
					{					
						$kea->set_minFreq($minFreq);
					}						
				} 
				if (array_key_exists('minWordLen', $params))
				{
					$kea->set_minWordLen($params['minWordLen']);
				} else {
					$minWordLen = get_plugin_setting('minWordLen', 'autotag');
					if (!empty($minWordLen))
					{
						$kea->set_minWordLen($minWordLen);
					}
				} 
				
				return $kea->Autotag($text);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	// Register actions
	global $CONFIG;

	register_elgg_event_handler('init', 'system', 'autotag_init');
	register_action('autotag/parse', false, $CONFIG->pluginspath . 'autotag/actions/parse.php');
?>
