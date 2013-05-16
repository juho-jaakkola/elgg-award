<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'award') && $entity->canEdit()) {
	// delete icons
	$owner_guid = $entity->owner_guid;
	$prefix = "award/" . $entity->guid;
	$imagenames = array('tiny.jpg', 'small.jpg', 'medium.jpg', 'large.jpg', 'original.jpg');
	$img = new ElggFile();
	$img->owner_guid = $owner_guid;
	foreach ($imagenames as $name) {
		$img->setFilename($prefix . $name);
		$img->delete();
	}

	if ($entity->delete()) {
		system_message(elgg_echo('award:delete:success'));
	} else {
		register_error(elgg_echo('award:delete:failed'));
	}
} else {
	register_error(elgg_echo('noaccess'));
}

forward('admin/award/list');