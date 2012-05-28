<?php

function reportedcontent_notify_init(){
  // add our css
  elgg_extend_view('css/admin', 'reportedcontent_notify/css');
  
  // register plugin hook handler to send notifications
  elgg_register_plugin_hook_handler('reportedcontent:add', 'system', 'reportedcontent_notify_reported');
  
  elgg_register_action('reportedcontent_notify/settings/save', elgg_get_plugins_path() . 'reportedcontent_notify/actions/reportedcontent_notify/settings/save.php', 'admin');
}

// this triggers when content is reported
function reportedcontent_notify_reported($hook, $type, $returnvalue, $params){
  $recipients_list = elgg_get_plugin_setting('recipients', 'reportedcontent_notify');
  
  if(!empty($recipients_list)){
    $recipients = unserialize($recipients_list);
    
    foreach($recipients as $guid){
      notify_user(
              $guid,
              elgg_get_site_entity()->guid,
              elgg_echo('reportedcontent_notify:subject', array($params['report']->title)),
              elgg_echo('reportedcontent_notify:message', array($params['report']->description, $params['report']->address))
      );
    }
  }
}

elgg_register_event_handler('init', 'system', 'reportedcontent_notify_init');