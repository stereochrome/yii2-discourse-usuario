<?php

namespace Stereochrome\Discourse\Controllers;

use \yii\web\Controller;
use \Yii;

class DiscourseController extends Controller {
	
	public function actionNotApproved() {
		return $this->render("not_approved");
	}

	public function actionLogout() {
		return $this->render("logout");	
	}

	public function actionSso() {
		$request = Yii::$app->getRequest();
		$sso = Yii::$app->discourseSso;
		
		$payload = $request->get('sso');
		$sig = $request->get('sig');

		if(!($sso->validate($payload, $sig))){
			// invaild, deny
			throw new ForbiddenHttpException('Bad SSO request');
		}
		
		$nonce = $sso->getNonce($payload);
		
		if(Yii::$app->getUser()->isGuest){
			// We add session variable to track it after we log the user in so we can redirect them back
			// This method works well with custom login methods like social networks
			Yii::$app->getSession()->set('sso', ['sso' => $payload, 'sig' => $sig]);
			return $this->redirect(['user/login']);
		}else{
			$user = Yii::$app->user->identity;
		}
		
		Yii::$app->getSession()->remove('sso');
		
		// We send over the data
		$userparams = [
	    	"nonce" => $nonce,
	    	"external_id" => (String)$user->id,
	    	"email" => $user->email,
	    	
	    	// Optional - feel free to delete these two
	    	"username" => $user->username,
	    	"name" => $user->profileName,
	    	
	    	//'avatar_url' => Url::to(['image/profile-image', 'id' => (String)$user->_id], 'http')
		];
		
		$q = $sso->buildLoginString($userparams);
		
		/// We redirect back
		return $this->redirect(Yii::getAlias('@discourse') . '/session/sso_login?' . $q);


	}



}