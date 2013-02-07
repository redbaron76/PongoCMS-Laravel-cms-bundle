<?php

class Mail {

	/*
	*	Prepare mail
	*
	*	This leverages SwiftMailer bundle for Laravel
	*/
	public static function prepare($html, $subject, $to = array(), $bcc = array())
	{

		// Construct the message
		$message = Swift_Message::newInstance( $subject )
		->setFrom( Config::get('cms::theme.email') )
		->setTo( $to )
		->setBody( $html, 'text/html');

		if(!empty($bcc)) $message->setBcc( $bcc );

		return $message;

	}

}