<?php

namespace Stereochrome\Discourse\Controller;

use \yii\web\Controller;
use \Yii;
use \yii\web\ForbiddenHttpException;
use \Stereochrome\Discourse\Event\UserEvent;
use Da\User\Traits\ContainerAwareTrait;

class DiscourseController extends Controller {
	
	use ContainerAwareTrait;
	
	public function actionNotApproved() {
		return $this->render("not_approved");
	}

	public function actionLogout() {
		
		$user = Yii::$app->user->identity;

		if(isset($user)) {
			$event = $this->make(UserEvent::class, [$user]);
			$this->trigger(UserEvent::EVENT_BEFORE_DISCOURSE_LOGOUT, $event);
		} else {
			\Yii::$app->session->setFlash('success', 'Sie wurden abgemeldet.');
			return $this->redirect('/');

		}

		return $this->render("logout");	
	}

	public function actionSso() {
		$request = Yii::$app->getRequest();
		$sso = Yii::$app->discourseSso;
		
		$payload = $request->get('sso');
		$sig = $request->get('sig');

		if(!($sso->validate($payload, $sig))){
			// invaild, deny
			throw new ForbiddenHttpException(Yii::t('discourse', 'Bad SSO request'));
		}
		
		$nonce = $sso->getNonce($payload);
		
		if(Yii::$app->getUser()->isGuest){
			// We add session variable to track it after we log the user in so we can redirect them back
			// This method works well with custom login methods like social networks
			Yii::$app->getSession()->set('sso', ['sso' => $payload, 'sig' => $sig]);
			return $this->redirect(['/user/login']);
		}else{
			$user = Yii::$app->user->identity;
		}

		$event = $this->make(UserEvent::class, [$user]);
		$this->trigger(UserEvent::EVENT_BEFORE_DISCOURSE_SSO, $event);

		Yii::$app->getSession()->remove('sso');
		
		$userparams = call_user_func($this->module->createSsoPayload, $nonce, $user);
		$q = $sso->buildLoginString($userparams);
		
		$this->trigger(UserEvent::EVENT_AFTER_DISCOURSE_SSO, $event);

		/// We redirect back
		return $this->redirect(Yii::getAlias('@discourse') . '/session/sso_login?' . $q);


	}



}