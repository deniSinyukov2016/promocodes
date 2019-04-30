<?php

return [
	'table' => 'promocodes',
	'users_table' => 'users',
	'relation_table' => 'promocode_user',
	'user_model' => \App\User::class,
	'foreign_pivot_key' => 'promocode_id',
	'related_pivot_key' => 'user_id',
];