<?php
/*
 -------------------------------------------------------------------------
 MessengerBot plugin for GLPI
 Copyright (C) 2017 by the MessengerBot Development Team.

 https://github.com/jurinva/messengerbot
 -------------------------------------------------------------------------

 LICENSE

 This file is part of MessengerBot.

 MessengerBot is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 MessengerBot is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with MessengerBot. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
*  This class manages the sms notifications settings
*/
class PluginMessengerbotNotificationWebsocketSetting extends NotificationSetting {

   static function getTypeName($nb=0) {
      return __('Messenger followups configuration', 'messengerbot');
   }

   public function getEnableLabel() {
      return __('Enable followups via Messenger', 'messengerbot');
   }

   static public function getMode() {
      return Notification_NotificationTemplate::MODE_WEBSOCKET;
   }

   function showFormConfig($options = []) {
      global $CFG_GLPI;

      $bot_token = PluginMessengerbotBot::getConfig('token');
      $bot_username = PluginMessengerbotBot::getConfig('bot_username');

      $out = "<form action='" . Toolbox::getItemTypeFormURL(__CLASS__) . "' method='post'>";
      $out .= "<div>";
      $out .= "<table class='tab_cadre_fixe'>";
      $out .= "<tr class='tab_bg_1'>" . "<th colspan='4'>" . _n('Messenger notification', 'Messenger notifications', Session::getPluralNumber()) . "</th></tr>";

      $out .= "<tr class='tab_bg_2'>";
      $out .= "<td> " . __('Bot token') . "</td><td>";
      $out .= "<input type='text' name='token' value='" . $bot_token . "' style='width: 100%'>";
      $out .= "</td><td colspan='2'>&nbsp;</td></tr>";

      $out .= "<tr class='tab_bg_2'>";
      $out .= "<td> " . __('Bot username') . "</td><td>";
      $out .= "<input type='text' name='bot_username' value='" . $bot_username . "' style='width: 100%'>";
      $out .= "</td><td colspan='2'>&nbsp;</td></tr>";

      echo $out;
      $this->showFormButtons($options);
   }

}
