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

define('PLUGIN_MESSENGERBOT_VERSION', '2.0.0');

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_messengerbot() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['messengerbot'] = true;
   $PLUGIN_HOOKS['post_item_form']['messengerbot'] = 'add_username_field';
   $PLUGIN_HOOKS['item_add']['messengerbot'] = array('User' => array('PluginMessengerbotUser', 'item_add_user'));
   $PLUGIN_HOOKS['pre_item_update']['messengerbot'] = array('User' => array('PluginMessengerbotUser', 'item_update_user'));

   $plugin = new Plugin();

   if ($plugin->isActivated('messengerbot')) {
      Notification_NotificationTemplate::registerMode(
         Notification_NotificationTemplate::MODE_WEBSOCKET,
         __('Messenger', 'plugin_messengerbot'),
         'messengerbot'
      );
   }
}

/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_messengerbot() {
   return [
      'name'           => 'MessengerBot',
      'version'        => PLUGIN_MESSENGERBOT_VERSION,
      'author'         => '<a href="http://trulymanager.com" target="_blank">Truly Systems</a>',
      'license'        => 'GPLv2+',
      'homepage'       => 'https://github.com/jurinva/messengerbot',
      'minGlpiVersion' => '9.2'
   ];
}

/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_messengerbot_check_prerequisites() {
   // Strict version check (could be less strict, or could allow various version)
   if (version_compare(GLPI_VERSION, '9.2', 'lt')) {
      if (method_exists('Plugin', 'messageIncompatible')) {
         echo Plugin::messageIncompatible('core', '9.2');
      } else {
         echo "This plugin requires GLPI >= 9.2";
      }
      return false;
   }
   return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_messengerbot_check_config($verbose = false) {
   if (true) { // Your configuration check
      return true;
   }

   if ($verbose) {
      _e('Installed / not configured', 'messengerbot');
   }
   return false;
}
