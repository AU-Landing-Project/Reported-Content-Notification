<?php

namespace AU\ReportedContentNotify;

const PLUGIN_ID = 'reportedcontent_notify';
const PLUGIN_VERSION = 20150903;

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');

function init(){
	// register plugin hook handler to send notifications
	elgg_register_plugin_hook_handler('reportedcontent:add', 'system', __NAMESPACE__ . '\\reported_content_created');

	elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrades');
}

function reported_content_get_users_to_notify() {
	$recipients_email_str = elgg_get_plugin_setting('notify_list', 'reportedcontent_notify');
	$searches = preg_split('~\s+~', $recipients_email_str, -1, PREG_SPLIT_NO_EMPTY);

	$map = function ($search) {
		$search = trim($search);
		if (!$search) {
			return false;
		}

		$users = get_user_by_email($search);
		if ($users) {
			return $users[0];
		}

		$user = get_user_by_username($search);
		return $user;
	};
	$users = array_map($map, $searches);
	$users = array_filter($users, function ($user) {
		return $user instanceof \ElggUser;
	});

	return $users;
}

// this triggers when content is reported
function reported_content_created($hook, $type, $returnvalue, $params){
	foreach (reported_content_get_users_to_notify() as $user) {
		notify_user(
			$user->guid,
			$user->guid,
			elgg_echo('reportedcontent_notify:subject', array($params['report']->title)),
			elgg_echo('reportedcontent_notify:message', array($params['report']->description, $params['report']->address))
		);
	}
}

function upgrades() {
	if (!elgg_is_admin_logged_in()) {
		return;
	}

	require_once __DIR__ . '/lib/upgrades.php';

	run_function_once(__NAMESPACE__ . '\\upgrade20150930');
}
