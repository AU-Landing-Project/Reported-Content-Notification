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

// this triggers when content is reported
function reported_content_created($hook, $type, $returnvalue, $params){
  $recipients_list = elgg_get_plugin_setting('recipients', 'reportedcontent_notify');
  
  if(!empty($recipients_list)){
    $recipients = unserialize($recipients_list);
    
    foreach($recipients as $guid){
      notify_user(
              $guid,
              $guid,
              elgg_echo('reportedcontent_notify:subject', array($params['report']->title)),
              elgg_echo('reportedcontent_notify:message', array($params['report']->description, $params['report']->address))
      );
    }
  }
}

function upgrades() {
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	
	require_once __DIR__ . '/lib/upgrades.php';
	
	run_function_once(__NAMESPACE__ . '\\upgrade20150930');
}

