<?php
	/**
	 * Elgg autotag: parse action
	 * 
	 * @package ElggAutoTag
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author ColdTrick <info@coldtrick.com>
	 * @copyright ColdTrick 2008-2009
	 * @link http://www.coldtrick.com/
	 */
	 
	$text = urldecode(get_input('text'));
	$tags = urldecode(get_input('tags'));
	
	if (!empty($tags))
	{
		$tags_curr = preg_split('/\s*,\s*/', $tags);
	} else {
		$tags_curr = array();
	}
	
	$params['text'] = $text;
	$tags_extra = trigger_plugin_hook('autotag_kea', 'kea', $params);
	
	foreach ($tags_extra as $tag => $score)
	{
		if (!in_array($tag, $tags_curr))
		{
			$tags_curr[] = $tag;
		}
	}
	
	echo implode(', ', $tags_curr);
	exit();
?>
