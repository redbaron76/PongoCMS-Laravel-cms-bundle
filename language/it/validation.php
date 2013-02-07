<?php

return array(

	"required" 		=> "Campo non inserito.",	
	"unique" 		=> "Dato già presente.",	
	"email" 		=> "Indirizzo e-mail non valido.",
	"confirmed"     => "Password non corrispondente.",
	"not_in" 		=> "Campo non selezionato.",	
	"alpha_dash" 	=> "Il nome non può contenere spazi.",

	"min"            => array(
		"numeric" => "Questo dato deve essere più di :min.",
		"file"    => "Questo file deve essere più di :min kilobytes.",
		"string"  => "Questo campo deve contenere almeno :min caratteri.",
	),
	"max"            => array(
		"numeric" => "Questo dato deve essere meno di :max.",
		"file"    => "Questo file deve essere meno di :max kilobytes.",
		"string"  => "Questo campo deve contenere massimo :max caratteri.",
	),
	"between"        => array(
		"numeric" => "Questo dato deve essere compreso tra :min - :max.",
		"file"    => "Questo file deve essere compreso tra :min - :max kilobytes.",
		"string"  => "Questo campo deve essere compreso tra :min - :max characters.",
	),


	//CUSTOM

	"page_not_set" => "Pagina non ancora creata!",
	"mimes_not_valid" => "Tipo di file non consentito.",
	"max_file_size" => "Peso del file superiore ai 10Mb.",
	"unique_file" => "Un file con lo stesso nome è già presente.",
	"valid_datetime" 	=> "Il formato data/ora non è valido.",
	"alpha_slug" 	=> "URL breve non valido.",
	"unique_slug" 	=> "URL breve già presente.",
	"unique_lang" 	=> "Dato già presente in questa lingua.",
	"unique_filename" 	=> "Il nome del file è già in uso.",
	"unique_element_page" 	=> "Nome già presente in questa pagina.",
	"unique_account" => "Account già presente",
	"confirmed" => "Password non corrispondente",
	"required_slug" => "URL breve non inserito.",


);
