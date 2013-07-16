<?php

use \Eloquent;

class CmsElement extends Eloquent {

	public static $table = 'elements';

	public static $timestamps = true;

	public function user()
	{
		return $this->belongs_to('CmsUser', 'author_id');
	}

	public function pages()
	{
		return $this->has_many_and_belongs_to('CmsPage', 'elements_pages')		
		->order_by('zone', 'asc')
		->order_by('elements_pages.order_id', 'asc');
	}

	//PAGE SETTINGS POSITION DROPDOWN
	public static function select_zone($page_id = '')
	{
		$zones = array(0 => LL('cms::form.select', CMSLANG));

		if(!empty($page_id)) {
		
			$rs = CmsPage::find($page_id);

			$layout = $rs->layout;

			if(!empty($layout)) {

				$zone_arr = Config::get('cms::theme.layout_'.$layout);

				foreach ($zone_arr as $key => $value) {
					$zones[$key] = $value;
				}

			}

		}

		return $zones;

	}
	

}