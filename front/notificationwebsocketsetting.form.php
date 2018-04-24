<?php

include ('../../../inc/includes.php');

Session::checkRight('config', UPDATE);
$notificationwebsocket = new PluginMessengerbotNotificationWebsocketSetting();

// TODO
if (!empty($_POST['test_webhook_send'])) {
   PluginMessengerbotNotificationWebsocket::testNotification();
   Html::back();
} else if (!empty($_POST['update'])) {
   PluginMessengerbotBot::setConfig('token', $_POST['token']);
   PluginMessengerbotBot::setConfig('bot_username', $_POST['bot_username']);
   Html::back();
}

Html::header(
   Notification::getTypeName(Session::getPluralNumber()),
   $_SERVER['PHP_SELF'],
   'config',
   'notification', 'config'
);

$notificationwebsocket->display(array('id' => 1));

Html::footer();
