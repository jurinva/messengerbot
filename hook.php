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

/**
 * Plugin install process
 *
 * @return boolean
 */
function plugin_messengerbot_install() {
   global $DB;

   $DB->runFile(GLPI_ROOT . '/plugins/messengerbot/db/install.sql');

   Config::setConfigurationValues('core', ['notifications_websocket' => 0]);
   Config::setConfigurationValues('plugin:messengerbot', ['token' => '', 'bot_username' => '']);

   CronTask::register(
      'PluginMessengerbotCron',
      'messagelistener',
      5 * MINUTE_TIMESTAMP,
      array('comment' => '', 'mode' => CronTask::MODE_EXTERNAL)
   );

   return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_messengerbot_uninstall() {
   global $DB;
   $DB->runFile(GLPI_ROOT . '/plugins/messengerbot/db/uninstall.sql');

   $config = new Config();
   $config->deleteConfigurationValues('core', ['notifications_websocket']);
   $config->deleteConfigurationValues('plugin:messengerbot', ['token', 'bot_username']);

   return true;
}

function add_username_field(array $params) {
   $item = $params['item'];

   if ($item->getType() == 'User') {
      PluginMessengerbotUser::showUsernameField($item);
   }
}
