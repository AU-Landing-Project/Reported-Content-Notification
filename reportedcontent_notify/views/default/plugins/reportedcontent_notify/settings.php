<?php

// get an array of admins
$options = array(
    'type' => 'user',
    'limit' => 0,
    'joins' => array("JOIN " . elgg_get_config('dbprefix') . "users_entity u ON u.guid = e.guid"),
    'wheres' => array("u.admin = 'yes'"),
    'order_by' => 'u.name asc'
);

$all_admins = elgg_get_entities($options);
$selected_admins = $vars['entity']->recipients ? $vars['entity']->recipients : serialize(array());

$value = unserialize($selected_admins);

echo elgg_echo('reportedcontent_notify:description') . "<br><br>";

foreach($all_admins as $admin){
  $options = array(
      'name' => 'params[recipients][]',
      'default' => FALSE,
      'value' => $admin->guid,
  );
  
  if(in_array($admin->guid, $value)){
    $options['checked'] = "checked";
  }
  
  echo '<div class="reportedcontent_notify_adminlist">';
  echo '<table><tr><td style="vertical-align:middle">';
  echo elgg_view('input/checkbox', $options);
  echo '</td><td style="vertical-align:middle">';
  echo elgg_view_entity_icon($admin, 'small');
  echo '</td></tr></table>';
  echo '</div>';
}

echo "<br><br>";