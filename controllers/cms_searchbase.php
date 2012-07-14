<?php

class Cms_Searchbase_Controller extends Controller {

	//SET RESTFUL API TO TRUE
	public $restful = false;

	//SET COMMON LAYOUT
	public $layout = 'cms::interface.layouts.default';

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}
	
}