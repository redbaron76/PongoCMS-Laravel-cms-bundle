<?php

class Cms_Login_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		$this->filter('before', 'cms_is_auth');
		//After post login, save credentials in session
		$this->filter('after', 'save_session_credentials')->on('post');
	}

	//ROUTES
	public function get_index()
	{

		$this->layout->header_data = array(
			'title' => 'Esegui il Login!'
		);

		$this->layout->content = View::make('cms::interface.pages.login');

	}

	public function post_index()
	{
		//POST LOGIN

		$credentials = array(
			'cms' => true,
			'username' => Input::get('username'),
			'password' => Input::get('password'),
			'remember' => false
		);

		//CHECK CREDENTIALS
		if(Auth::attempt($credentials)) {
			//SUCCESS NOTIFICATION
            return Redirect::to_action('cms::dashboard');
        } else {
        	//ERROR NOTIFICATION
        	Notification::error(LL('cms::alert.login_error', CMSLANG));
        	//BACK TO LOGIN
            return Redirect::to_action('cms::login')->with_input('only', array('username'));
        }
	}

	public function get_logout()
	{
		//GET LOGOUT

		Auth::logout();
		Session::flush();
		return Redirect::to_action('cms::login');

	}

}