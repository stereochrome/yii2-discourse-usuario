<?php

namespace Stereochrome\Discourse;

class Module extends \yii\base\Module
{
	public $prefix = 'discourse';
	
	public $routes = [
        'sso' => 'discourse/sso',
        'not-approved' => 'discourse/not-approved',
        'logout' => 'discourse/logout',
    ];

}