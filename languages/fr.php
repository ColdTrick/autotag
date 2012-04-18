<?php

	$french = array(
	
		"autotag:adminsettings:langid:none" => "Aucun",
		"autotag:adminsettings:langid:label" => "La langue à utiliser",
		"autotag:adminsettings:langid:description" => "L'algorithme utilise un outil de conjugaison et une liste de mots interdits dans les cas où une langue est sélectionnée.",
		"autotag:adminsettings:weightarray:label" => "Le poids des mots simples ou composés de 2 ou 3 mots",
		"autotag:adminsettings:weightarray:description" => "Ces poids sont multipliés par la fréquence des mots trouvés ou combinaisons de mots.",
		"autotag:adminsettings:maxreturn:label" => "Le nombre maximum de mots-clés pour un retour",
		"autotag:adminsettings:maxreturn:description" => "",
		"autotag:adminsettings:minfreq:label" => "La fréquence minimum de mots-clés",
		"autotag:adminsettings:minfreq:description" => "La fréquence minimum où un mot est mentionné dans le texte pour être repris comme mot-clé.",
		"autotag:adminsettings:minwordlen:label" => "La longueur minimum d'un mot",
		"autotag:adminsettings:minwordlen:description" => "Tous les mots plus courts que cette donnée sont supprimés",
		
		"autotag:adminsettings:activation:description" => "",
		"autotag:adminsettings:vocabulary:label" => "Serie de mots qui doivent être concidérés comme mots-clés",
		"autotag:adminsettings:footertext" => "Ces paramètres sont les valeurs par défaut, elles peuvent être modifiées par des scripts qui utilisent l'algorithme de mots-clés",
	);
					
	add_translation("fr", $french);
?>
