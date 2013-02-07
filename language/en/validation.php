<?php

return array(

	"required" 		=> "Empty field.",	
	"unique" 		=> "Item already present.",	
	"email" 		=> "E-mail address not valid.",
	"confirmed"     => "Password doesn't match.",
	"not_in" 		=> "Not selected fied.",	
	"alpha_dash" 	=> "Name cannot contain spaces.",

	"max"            => array(
		"numeric" => "The :attribute must be less than :max.",
		"file"    => "The :attribute must be less than :max kilobytes.",
		"string"  => "The :attribute must be less than :max characters.",
	),
	"min"            => array(
		"numeric" => "The :attribute must be at least :min.",
		"file"    => "The :attribute must be at least :min kilobytes.",
		"string"  => "The :attribute must be at least :min characters.",
	),
	"between"        => array(
		"numeric" => "The :attribute must be between :min - :max.",
		"file"    => "The :attribute must be between :min - :max kilobytes.",
		"string"  => "The :attribute must be between :min - :max characters.",
	),


	//CUSTOM

	"page_not_set" => "Page not yet created!",
	"mimes_not_valid" => "File type not allowed.",
	"max_file_size" => "File size exceeds max size.",
	"unique_file" => "This file name already exists.",
	"valid_datetime" 	=> "Date/time format not valid.",
	"alpha_slug" 	=> "Short URL not valid.",
	"unique_slug" 	=> "Short URL already present.",
	"unique_lang" 	=> "Item already present in this language.",
	"unique_filename" 	=> "File name already in use.",
	"unique_element_page" 	=> "Name already present in this page.",
	"unique_account" => "Account already present.",
	"confirmed" => "Password doesn't match.",
	"required_slug" => "Short URL not set.",


);