<?php

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $vars['title'],
));

$description_label = elgg_echo('description');
$description_input = elgg_view('input/plaintext', array(
	'name' => 'description',
	'value' => $vars['description'],
));

$image_label = elgg_echo('award:icon');
$image_input = elgg_view('input/file', array(
	'name' => 'icon',
	'value' => $vars['icon'],
));

$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['guid'],
));

$submit_input = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
));

echo <<<FORM
	<div>
		<label>$title_label</label>
		$title_input
	</div>
	<div>
		<label>$description_label</label>
		$description_input
	</div>
	<div>
		<label>$image_label</label>
		$image_input
	</div>
	<div>
		$guid_input
		$submit_input
	</div>
FORM;
