<?php
/**
 * The production database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host=localhost;dbname=momentum_production',
			'username'   => 'production',
			'password'   => 'wheels within wheels',
		),
	),
);
