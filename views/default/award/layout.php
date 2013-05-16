<?php

elgg_push_context('widgets');

$entity = elgg_extract('entity', $vars);

$entity_view = elgg_view_entity($entity, array(
	'full_view' => true
));

$title = elgg_view_title($entity->title);
$body = elgg_view('output/longtext', array('value' => $entity->description));
$icon = elgg_view_entity_icon($entity, 'medium', array(
	'href' => false,
	'img_class' => 'mrs',
));
$body = elgg_view_image_block($icon, $title . $body);

echo <<<HTML
<div class="award-wrapper">
	$body
</div>
HTML;
