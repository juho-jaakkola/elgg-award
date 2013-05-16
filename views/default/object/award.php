<?php

$entity = elgg_extract('entity', $vars);

if (elgg_in_context('gallery')) {
	$icon = elgg_view_entity_icon($entity, 'small', array(
		'href' => "award/view/{$entity->getGUID()}",
		'link_class' => 'elgg-lightbox',
	));
	echo $icon;
} else {
	$metadata = elgg_view_menu('entity', array(
		'entity' => $entity,
		'handler' => 'award',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
	
	$params = array(
		'entity' => $entity,
		'title' => $entity->title,
		'metadata' => $metadata,
		'subtitle' => $entity->description,
	);
	$params = $params + $vars;
	$body = elgg_view('object/elements/summary', $params);

	$icon = elgg_view_entity_icon($entity, 'medium');

	echo elgg_view_image_block($icon, $body, $vars);
}