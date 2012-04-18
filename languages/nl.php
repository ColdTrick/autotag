<?php

	$dutch = array(
		// Settings
		"autotag:adminsettings:langid:none" => "geen",
		"autotag:adminsettings:langid:label" => "De te gebruiken taal",
		"autotag:adminsettings:langid:description" => "Het algoritme maakt gebruik van een stammer en stopwoorden lijst in het geval van een geselecteerde taal.",
		"autotag:adminsettings:weightarray:label" => "De gewichten voor enkele woorden, 2 en 3 woord combinaties",
		"autotag:adminsettings:weightarray:description" => "Deze gewichten worden vermenigvuldigd met de frequentie van de gevonden woorden of woordcombinaties.",
		"autotag:adminsettings:maxreturn:label" => "Het maximaal aantal tags om terug te geven",
		"autotag:adminsettings:maxreturn:description" => "",
		"autotag:adminsettings:minfreq:label" => "Minimale tag frequentie",
		"autotag:adminsettings:minfreq:description" => "Het minimum aantal keren dat een woord moet voorkomen in de ingevoerde tekst om te worden teruggestuurd als tag.",
		"autotag:adminsettings:minwordlen:label" => "Minimale woord lengte",
		"autotag:adminsettings:minwordlen:description" => "Alle woorden die kleiner zijn worden niet mee geteld.",
		"autotag:adminsettings:activation:label" => "De modules die automatisch moeten worden voorzien van tags",
		"autotag:adminsettings:activation:description" => "",
		"autotag:adminsettings:vocabulary:label" => "Array van woorden die terug moeten worden gegeven als tags (indien gevonden)",
		"autotag:adminsettings:footertext" => "Deze instelling zijn de standaard instellingen, andere plugins kunnen deze overschrijven bij het aanroepen van het algoritme.",
	);
					
	add_translation("nl", $dutch);
?>
