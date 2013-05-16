<?php

$awards = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'award',
	'limit' => false,
));

if ($awards) {
	echo $awards;
} else {
	$none = elgg_echo('award:none');
	echo "<p>$none</p>";
}

echo elgg_view('output/url', array(
	'href' => 'admin/award/save',
	'text' => elgg_echo('new'),
	'class' => 'elgg-button elgg-button-action',
));
