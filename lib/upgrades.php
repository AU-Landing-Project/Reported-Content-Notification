<?php

namespace AU\ReportedContentNotify;

function upgrade20150930() {
	$version = (int) elgg_get_plugin_setting('version', PLUGIN_ID);
	if ($version >= 20150903) {
		return;
	}
	
	$plugin = elgg_get_plugin_from_id(PLUGIN_ID);
	
	if ($plugin->recipients && !$plugin->notify_list) {
		$recipients = unserialize($plugin->recipients);
		if (is_array($recipients)) {
			$notify_list = array();
			foreach ($recipients as $guid) {
				$user = get_user($guid);
				if (!$user) {
					continue;
				}
				$notify_list[] = $user->email;
			}
			
			$plugin->setSetting('notify_list', implode("\n", $notify_list));
		}
	}
	
	elgg_set_plugin_setting('version', 20150903, PLUGIN_ID);
}