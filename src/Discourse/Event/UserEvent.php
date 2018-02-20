<?php

namespace Stereochrome\Discourse\Event;

use Da\User\Model\User;
use yii\base\Event;
use Da\User\Event\UserEvent as BaseEvent;

class UserEvent extends BaseEvent {
	
	const EVENT_BEFORE_DISCOURSE_SSO = 'beforeDiscourseSSO';
	const EVENT_AFTER_DISCOURSE_SSO = 'beforeDiscourseSSO';
	const EVENT_BEFORE_DISCOURSE_LOGOUT = 'beforeDiscourseLogout';
	

}