<?php

class Notification {

	public static $notifications = array();

	public static function success($message, $time = 3000, $close = false)
	{
		static::add('success', $message, $time, $close);
	}

	public static function error($message, $time = 3000, $close = false)
	{
		static::add('error', $message, $time, $close);
	}

	public static function warning($message, $time = 3000, $close = false)
	{
		static::add('warning', $message, $time, $close);
	}

	public static function info($message, $time = 3000, $close = false)
	{
		static::add('info', $message, $time, $close);
	}

	public static function show()
	{
		$notifications = Session::get('notifications');
		if(count($notifications) > 0)
		{
			/*$msg =  '<div class="alert-messages">';
			foreach($notifications as $notification) {
				$msg .= '<div class="alert alert-'.$notification['type'].'">';
					if($notification['close']) $msg .= '<a class="close">×</a>';
					$msg .= $notification['message'];
				$msg .= '</div>';
			}
			$msg .= '</div>';*/

			//USE NOTY.JS

			$msg  = '<script>';
			foreach($notifications as $notification) {
				$msg .= 'noty({text: "'.$notification['message'].'", type : "'.$notification['type'].'", layout: "top", theme: "noty_theme_twitter", timeout: "'.$notification['time'].'", speed : 250});';
			}
			$msg .= '</script>';

			return $msg;
		}
	}

	protected static function add($type, $message, $time, $close)
	{
		static::$notifications[] = array(
			'type' => $type,
			'message' => $message,
			'time' => $time,
			'close' => $close
		);
		Session::flash('notifications', static::$notifications);
	}

}