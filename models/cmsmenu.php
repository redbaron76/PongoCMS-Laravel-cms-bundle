<?php

use \Eloquent;

class CmsMenu extends Eloquent {

	public static $table = 'menus';

	public static $timestamps = true;

	public function pages()
	{
		return $this->has_many_and_belongs_to('CmsPage', 'menus_pages')
		->order_by('menus_pages.order_id', 'asc');
	}

	//RECURSIVE PAGES IN MENU
    public static function recursive_pages_menu($parent_id, $menu_id)
    {

    	//GET PAGE DATA
		$data = self::with(array('pages' => function($query) use ($parent_id) {
						
							$query->where('parent_id', '=', $parent_id);

						}))
						->where_id($menu_id)
						->where_lang(SITE_LANG)
						->first();

		if(!empty($data)) {
			
			$menus = array();

			foreach ($data->pages as $page) {
				$recursive = call_user_func_array('self::recursive_pages_menu', array($page->id, $menu_id));
				$menus = ($data->pages + $recursive);
			}

			return $menus;

		}

    }





}