<?php

use \Eloquent;

class CmsRole extends Eloquent {

	public static $table = 'roles';

	public static $timestamps = false;

	public function users()
	{
		return $this->has_many('CmsUser');
	}

	public function pages()
	{
		return $this->has_many('CmsPage');
	}

	public function blogs()
	{
		return $this->has_many('CmsBlog');
	}

	//CHECK ROLE
	public static function role_fail($page_id)
	{

		$role = CmsPage::find($page_id)->role_level;

		if(!is_null($role)) {

			if($role > ROLE) return true;

		}

		return false;

	}

	//PAGE SETTINGS OWNERS DROPDOWN
	public static function select_edit_owners()
	{
		$roles = array(0 => LL('cms::form.select', CMSLANG));

		$data = self::where('level', '<=', ROLE)
					->where('level', '>=', Config::get('cms::settings.roles.editor'))
					->get();

		foreach ($data as $role) {
			$roles[$role->id] = ucfirst(LABEL('cms::role.', $role->name));
		}
		
		return $roles;

	}

	//PAGE SETTINGS ACCESS DROPDOWN
	public static function select_edit_access()
	{
		$roles = array(0 => LL('cms::form.all', CMSLANG));

		$data = self::all();

		foreach ($data as $role) {
			$roles[$role->level] = ucfirst(LABEL('cms::role.', $role->name));
		}

		return $roles;

	}

	//USER ACCOUNT ROLE DROPDOWN
	public static function select_user_roles()
	{
		$roles = array(0 => LL('cms::form.select', CMSLANG));

		$data = self::where('level', '<=', ROLE)
					->get();

		foreach ($data as $role) {
			$roles[$role->id] = ucfirst(LABEL('cms::role.', $role->name));
		}

		return $roles;

	}

	//ROLE LEVELS DROPDOWN
	public static function select_levels()
	{		
		$role = array(0 => LL('cms::form.select', CMSLANG));

		$max = ROLE;
        
        for ($i=2; $i<$max; $i++) {
            $role[$i] = $i;
        }
        
        return $role;
        
	}

	//GET ROLE LEVEL
	public static function get_role_level($role_id)
	{
		$role = self::find($role_id);

		return $role->level;
	}





}
