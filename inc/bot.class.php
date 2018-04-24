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

require GLPI_ROOT . '/plugins/messengerbot/vendor/autoload.php';
use Longman\MessengerBot\Request;

class PluginMessengerbotBot {

   static public function getConfig($key) {
      return Config::getConfigurationValues('plugin:messengerbot')[$key];
   }

   static public function setConfig($key, $value) {
      Config::setConfigurationValues('plugin:messengerbot', [$key => $value]);
   }

   static public function sendMessage($to, $content) {
      $chat_id = self::getChatID($to);
      $messenger = self::getMessengerInstance();
      $result = Request::sendMessage(['chat_id' => $chat_id, 'text' => $content]);
   }

   static public function getUpdates() {
      $response = 'ok';

      try {
         $messenger = self::getMessengerInstance();
         $messenger->enableMySql(self::getDBCredentials(), 'glpi_plugin_messengerbot_');
         $messenger->handleGetUpdates();
      } catch (Longman\MessengerBot\Exception\MessengerException $e) {
         $response = $e->getMessage();
      }

      return $response;
   }

   static public function getChatID($user_id) {
      global $DB;

      $chat_id = null;

      $result = $DB->request([
         'FROM' => 'glpi_plugin_messengerbot_users',
         'INNER JOIN' => [
            'glpi_plugin_messengerbot_user' => [
               'FKEY' => [
                  'glpi_plugin_messengerbot_users' => 'username',
                  'glpi_plugin_messengerbot_user' => 'username'
               ]
            ]
         ],
         'WHERE' => ['glpi_plugin_messengerbot_users.id' => $user_id]
      ]);

      if ($row = $result->next()) {
         $chat_id = $row['id'];
      }

      return $chat_id;
   }

   static private function getMessengerInstance() {
      $bot_api_key  = self::getConfig('token');
      $bot_username = self::getConfig('bot_username');

      return new Longman\MessengerBot\Messenger($bot_api_key, $bot_username);
   }

   static private function getDBCredentials() {
      global $DB;

      return array(
         'host'     => $DB->dbhost,
         'user'     => $DB->dbuser,
         'password' => $DB->dbpassword,
         'database' => $DB->dbdefault,
      );
   }

}
