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

    public $createSsoPayload;

    public function init() {


	    if(!isset($this->createSsoPayload)) {

		    $this->createSsoPayload= function($nonce, $user) {

				return [
			    	"nonce" => $nonce,
			    	"external_id" => (String)$user->id,
			    	"email" => $user->email,
			    	
			    	"username" => $user->username,
			    	"name" => !empty($user->profile->name) ? $user->profile->name : $user->username,
			    	
			    	//'avatar_url' => Url::to(['image/profile-image', 'id' => (String)$user->_id], 'http')
				];

		    };
	    }
    } 

}