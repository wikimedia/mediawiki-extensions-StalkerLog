<?php
/**
 * Extension:StalkerLog - Log everytime someone logs in or logs out,
 * great for tracking productivity in offices (or stalking).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @author Chad Horohoe <innocentkiller@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$wgExtensionCredits['specialpage'][] = array(
	'path'           => __FILE__,
	'name'           => 'StalkerLog',
	'version'        => '0.7.0',
	'url'            => 'http://mediawiki.org/wiki/Extension:StalkerLog',
	'author'         => '[mailto:innocentkiller@gmail.com Chad Horohoe]',
	'descriptionmsg' => 'stalkerlog-desc',
);

# Basic setup
$wgMessagesDirs['StalkerLog'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['StalkerLog'] = dirname(__FILE__) . '/' . 'StalkerLog.i18n.php';
$wgAvailableRights[] = 'stalkerlog-view-log';
$wgGroupPermissions['*']['stalkerlog-view-log'] = true;
$wgHooks['UserLoginComplete'][] = 'wfStalkerLogin';
$wgHooks['UserLogoutComplete'][] = 'wfStalkerLogout';

# Log setup
$wgLogTypes[] = 'stalkerlog';
$wgLogHeaders['stalkerlog'] = 'stalkerlog-log-text';
$wgLogNames['stalkerlog'] = 'stalkerlog-log-type';
$wgLogRestrictions['stalkerlog'] = 'stalkerlog-view-log';
$wgLogActions['stalkerlog/login'] = 'stalkerlog-log-login';
$wgLogActions['stalkerlog/logout'] = 'stalkerlog-log-logout';

// add the log entry
function addLogEntry( $action, $user ) {
	$log = new LogPage( 'stalkerlog', false);
	$log->addEntry( $action, $user->getUserPage(), '', array(), $user );
}

# Login hook function
function wfStalkerLogin( &$user ) {
	addLogEntry( 'login', $user );
	return true;
}

# Logout hook function
function wfStalkerLogout( &$user, &$inject_html, $old_name ) {
	$user = User::newFromName( $old_name );
	addLogEntry( 'logout', $user );
	return true;
}
