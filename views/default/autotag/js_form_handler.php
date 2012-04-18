<?php
	$text_fieldname = $vars['text_fieldname'];
	$tags_fieldname = $vars['tags_fieldname'];
?>
<script language="javascript">

	$(document).ready(function(){
		
		$("form").submit(function(){
			var currentForm = $(this);
			if ($.trim($("[name='<?php echo $text_fieldname;?>']").val())){
				var form_text = encodeURIComponent($("[name='<?php echo $text_fieldname;?>']").val());
				var form_tags = encodeURIComponent($("[name='<?php echo $tags_fieldname;?>']").val());
			
				$.ajaxSetup({
				  async: false
				});
				$.post("<?php echo elgg_add_action_tokens_to_url($vars['url'] . "action/autotag/parse");?>", {text:form_text, tags:form_tags}, function(data){
					currentForm.find("[name='<?php echo $tags_fieldname;?>']").val(data);
					
				});
			} 
			return true;
		});
	});
	
</script>