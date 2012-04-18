<?php
	// Filter out all languages not supported
	$langAvail = KEA::get_availableLangs();
	$langInstalled = get_installed_translations();
	foreach ($langInstalled as $langid => $description)
	{
		if (!in_array($langid, $langAvail))
		{
			unset($langInstalled[$langid]);
		}
	}
	
	$langID = $vars['entity']->langID;
	if (!$langID) $langID = 'none';
	
	$weight1 = $vars['entity']->weight1;
	if (!$weight1) $weight1 = 1;
	
	$weight2 = $vars['entity']->weight2;
	if (!$weight2) $weight2 = 2;

	$weight3 = $vars['entity']->weight3;
	if (!$weight3) $weight3 = 3;

	$maxReturn = $vars['entity']->maxReturn;
	if (!$maxReturn) $maxReturn = 5;
	
	$minFreq = $vars['entity']->minFreq;
	if (!$minFreq) $minFreq = 2;

	$minWordLen = $vars['entity']->minWordLen;
	if (!$minWordLen) $minWordLen = 2;
?>
<table> 
	<tr>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:langid:label').' :'; ?>
			<br>
			<select name='params[langID]'>
				<option value='none' <?php if ($langID == 'none') echo "selected='yes'";?>><?php echo elgg_echo('autotag:adminsettings:langid:none'); ?></option>
					<?php foreach($langInstalled as $lang => $trans){ ?>
						<option value='<?php echo $lang; ?>' <?php if ($langID == $lang) echo "selected='yes'";?>><?php echo elgg_echo($lang); ?></option>
					<?php } ?>
			</select>
			<br>&nbsp;
		</td>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:langid:description'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:weightarray:label').' :'; ?>
			<br>
			1 :
			<select name='params[weight1]'>
				<option value='1' <?php if ($weight1 == 1) echo "selected='yes'";?>>1</option>
				<option value='2' <?php if ($weight1 == 2) echo "selected='yes'";?>>2</option>
				<option value='3' <?php if ($weight1 == 3) echo "selected='yes'";?>>3</option>
				<option value='4' <?php if ($weight1 == 4) echo "selected='yes'";?>>4</option>
				<option value='5' <?php if ($weight1 == 5) echo "selected='yes'";?>>5</option>
			</select>
			2 :		
			<select name='params[weight2]'>
				<option value='1' <?php if ($weight2 == 1) echo "selected='yes'";?>>1</option>
				<option value='2' <?php if ($weight2 == 2) echo "selected='yes'";?>>2</option>
				<option value='3' <?php if ($weight2 == 3) echo "selected='yes'";?>>3</option>
				<option value='4' <?php if ($weight2 == 4) echo "selected='yes'";?>>4</option>
				<option value='5' <?php if ($weight2 == 5) echo "selected='yes'";?>>5</option>
			</select>
			3 :		
			<select name='params[weight3]'>
				<option value='1' <?php if ($weight3 == 1) echo "selected='yes'";?>>1</option>
				<option value='2' <?php if ($weight3 == 2) echo "selected='yes'";?>>2</option>
				<option value='3' <?php if ($weight3 == 3) echo "selected='yes'";?>>3</option>
				<option value='4' <?php if ($weight3 == 4) echo "selected='yes'";?>>4</option>
				<option value='5' <?php if ($weight3 == 5) echo "selected='yes'";?>>5</option>
			</select>
			<br>&nbsp;
		</td>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:weightarray:description'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:maxreturn:label').' :'; ?>
			<br>
			<select name='params[maxReturn]'>
				<option value='1' <?php if ($maxReturn == 1) echo "selected='yes'";?>>1</option>
				<option value='2' <?php if ($maxReturn == 2) echo "selected='yes'";?>>2</option>
				<option value='3' <?php if ($maxReturn == 3) echo "selected='yes'";?>>3</option>
				<option value='4' <?php if ($maxReturn == 4) echo "selected='yes'";?>>4</option>
				<option value='5' <?php if ($maxReturn == 5) echo "selected='yes'";?>>5</option>
				<option value='10' <?php if ($maxReturn == 10) echo "selected='yes'";?>>10</option>
				<option value='25' <?php if ($maxReturn == 25) echo "selected='yes'";?>>25</option>
				<option value='50' <?php if ($maxReturn == 50) echo "selected='yes'";?>>50</option>
			</select>
			<br>&nbsp;
		</td>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:maxreturn:description'); ?>
		</td>
	<tr>
	<tr>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:minfreq:label').' :'; ?>
			<br>
			<select name='params[minFreq]'>
				<option value='1' <?php if ($minFreq == 1) echo "selected='yes'";?>>1</option>
				<option value='2' <?php if ($minFreq == 2) echo "selected='yes'";?>>2</option>
				<option value='3' <?php if ($minFreq == 3) echo "selected='yes'";?>>3</option>
				<option value='4' <?php if ($minFreq == 4) echo "selected='yes'";?>>4</option>
				<option value='5' <?php if ($minFreq == 5) echo "selected='yes'";?>>5</option>
				<option value='10' <?php if ($minFreq == 10) echo "selected='yes'";?>>10</option>
				<option value='25' <?php if ($minFreq == 25) echo "selected='yes'";?>>25</option>
			</select>
			<br>&nbsp;
		</td>	
		<td>
			<?php echo elgg_echo('autotag:adminsettings:minfreq:description'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:minwordlen:label').' :'; ?>
			<br>
			<select name='params[minWordLen]'>
				<option value='1' <?php if ($minWordLen == 1) echo "selected='yes'";?>>1</option>
				<option value='2' <?php if ($minWordLen == 2) echo "selected='yes'";?>>2</option>
				<option value='3' <?php if ($minWordLen == 3) echo "selected='yes'";?>>3</option>
				<option value='4' <?php if ($minWordLen == 4) echo "selected='yes'";?>>4</option>
				<option value='5' <?php if ($minWordLen == 5) echo "selected='yes'";?>>5</option>
				<option value='6' <?php if ($minWordLen == 6) echo "selected='yes'";?>>6</option>
				<option value='7' <?php if ($minWordLen == 7) echo "selected='yes'";?>>7</option>
				<option value='8' <?php if ($minWordLen == 8) echo "selected='yes'";?>>8</option>
				<option value='9' <?php if ($minWordLen == 9) echo "selected='yes'";?>>9</option>
				<option value='10' <?php if ($minWordLen == 10) echo "selected='yes'";?>>10</option>
			</select>
			<br>&nbsp;
		</td>	
		<td>
			<?php echo elgg_echo('autotag:adminsettings:minwordlen:description'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('autotag:adminsettings:activation:label').' :'; ?>
			<br>
			<table>
				<tr>
					<td>
						<?php echo elgg_echo('blog'); ?> :
					</td>
					<td>
						<select name="params[blogactivate]">
							<option value="yes" <?php if ($vars['entity']->blogactivate != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
							<option value="no" <?php if ($vars['entity']->blogactivate == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
						</select>
						<br>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo elgg_echo('groups:forum'); ?> :
					</td>
					<td>
						<select name="params[forumactivate]">
							<option value="yes" <?php if ($vars['entity']->forumactivate != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
							<option value="no" <?php if ($vars['entity']->forumactivate == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
						</select>
						<br>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo elgg_echo('pages'); ?> :
					</td>
					<td>
						<select name="params[pagesactivate]">
							<option value="yes" <?php if ($vars['entity']->pagesactivate != 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
							<option value="no" <?php if ($vars['entity']->pagesactivate == 'no') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
						</select>
						<br>&nbsp;
					</td>
				</tr>
			</table>	
		</td>	
		<td>
			<?php echo elgg_echo('autotag:adminsettings:activation:description'); ?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<?php echo elgg_echo('autotag:adminsettings:footertext'); ?>
			<br>&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<a target="_blank" href="<?php echo $CONFIG->wwwroot; ?>mod/autotag/readme.txt">Readme.txt</a>
		</td>
	</tr>
</table>