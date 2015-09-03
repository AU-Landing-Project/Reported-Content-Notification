<?php

namespace AU\ReportedContentNotify;

echo '<label>' . elgg_echo('reportedcontent_notify:description') . "<label>";
echo elgg_view('input/plaintext', array(
	'name' => 'params[notify_list]',
	'value' => $vars['entity']->notify_list
));
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('reportedcontent_notify:description:help'),
	'class' => 'elgg-subtext'
));

