<?php

use \yii\helpers\Html;
?>

<h1><?= \Yii::t("discourse", "Logout from your Profile?"); ?></h1>

<?=

Html::beginForm(['/user/logout'], 'post')
                . Html::submitButton(
                    \Yii::t("discourse", "Logout"),
                    ['class' => 'btn btn-danger btn-logout']
                )
                . Html::endForm();

?>
