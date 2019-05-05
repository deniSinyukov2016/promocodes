<?php

return [
	/**
	 * Promocodes table
	 */
	'table' => 'promocodes',


	/**
	 * Related users table
	 */
	'users_table' => 'users',


	/**
	 * Related table for user and promocode
	 */
	'relation_table' => 'promocode_user',

	/**
	 * Models USER class
	 */
	'user_model' => \App\User::class,
	'foreign_pivot_key' => 'promocode_id',
	'related_pivot_key' => 'user_id',

	/**
	 * Unique promocode for user
	 * Add primary keys for user and code 
	 */
	'unique_for_user' => false,
];