<?php

namespace Orm;

class OrmPropertyPrivate extends \Exception {}

class Model_Private extends Model
{
	protected static $_private_properties = array();

	private $can_touch_this;

	public function __get($property)
	{
		if(
	}
}
