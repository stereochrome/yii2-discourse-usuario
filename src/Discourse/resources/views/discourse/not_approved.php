<?php
use \yii\helpers\Html;
use Yii;
?>

<h1><?= Yii::t('discourse', 'Login not approved') ?></h1>

<?=

Html::beginForm(['/user/logout'], 'post')
                . Html::submitButton(
                    Yii::t('discourse', 'Logout'),
                    ['class' => 'btn btn-danger btn-logout']
                )
                . Html::endForm();

?>
