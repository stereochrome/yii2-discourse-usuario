<?php
namespace Stereochrome\Discourse;

use Da\User\Controller\SecurityController;
use Da\User\Event\FormEvent;
use Da\User\Model\User;
use yii\base\Event;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {


	public function bootstrap($app) {

		if (!$app->hasModule('user') && !$app->getModule('user') instanceof Module) 
		{
			throw new \Exception("User Modul usuario not found");
		}

		if ($app->hasModule('discourse') && $app->getModule('discourse') instanceof Module) {

			$this->initControllerNamespace($app);
			$this->initUrlRoutes($app);
			$this->initEvents($app);
		}
			die();
		
	}

	protected function initEvents() {

		// after login, redirect to discourse sso if session contains sso values
		Event::on(SecurityController::class, FormEvent::EVENT_AFTER_LOGIN, function (FormEvent $event) {

			if($sso = \Yii::$app->session->get('sso')){
		    	
		    	\Yii::$app->user->setReturnUrl([
					'discourse/sso', 
					'sso' => $sso['sso'], 
					'sig' => $sso['sig']
				]);
		    }

		});
	}

	protected function initUrlRoutes(WebApplication $app)
    {
        $module = $app->getModule('discourse');
        $config = [
            'class' => 'yii\web\GroupUrlRule',
            'prefix' => $module->prefix,
            'rules' => $module->routes,
        ];

        if ($module->prefix !== 'discourse') {
            $config['routePrefix'] = 'discourse';
        }

        $rule = Yii::createObject($config);
        $app->getUrlManager()->addRules([$rule], false);
    }
	
	protected function initControllerNamespace(WebApplication $app)
    {
        $app->getModule('discourse')->controllerNamespace = 'Stereochrome\Discourse\Controller';
        $app->getModule('discourse')->setViewPath('@Stereochrome/Discourse/resources/views');
    }
}
