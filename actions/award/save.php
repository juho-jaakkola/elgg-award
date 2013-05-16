<?php

$guid = get_input('guid');
$title = get_input('title');
$description = get_input('description');

$site_guid = elgg_get_site_entity()->getGUID();

elgg_make_sticky_form('award');

if (empty($title)) {
	register_error(elgg_echo('award:missing_title'));
	forward(REFERER);
}

$has_uploaded_icon = (!empty($_FILES['icon']['type']) && substr_count($_FILES['icon']['type'], 'image/'));

if ($guid) {
	$award = get_entity($guid);

	if (!elgg_instanceof($award, 'object', 'award')) {
		register_error(elgg_echo('noaccess'));
		forward(REFERER);
	}
} else {
	// Icon is required
	if (!$has_uploaded_icon) {
		register_error(elgg_echo('award:missing_icon'));
		forward(REFERER);
	}

	$award = new ElggObject();
	$award->subtype = 'award';
	$award->owner_guid = $site_guid;
	$award->container_guid = $site_guid;
	$award->access_id = ACCESS_PUBLIC;
}

$award->title = $title;
$award->description = $description;

if (!$award->save()) {
	register_error(elgg_echo('award:save:failed'));
	forward(REFERER);
}

if ($has_uploaded_icon) {

	$icon_sizes = elgg_get_config('icon_sizes');

	$prefix = "award/" . $award->guid;

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $site_guid;
	$filehandler->setFilename($prefix . "original.jpg");
	$filehandler->open("write");
	$filehandler->write(get_uploaded_file('icon'));
	$filehandler->close();
	$filename = $filehandler->getFilenameOnFilestore();

	$sizes = array('tiny', 'small', 'medium', 'large');

	$thumbs = array();
	foreach ($sizes as $size) {
		$thumbs[$size] = get_resized_image_from_existing_file(
			$filename,
			$icon_sizes[$size]['w'],
			$icon_sizes[$size]['h'],
			true // Always use square icon
		);
	}

	if ($thumbs['small']) { // just checking if resize successful
		$thumb = new ElggFile();
		$thumb->owner_guid = $site_guid;
		$thumb->container_guid = $site_guid;
		$thumb->setMimeType('image/jpeg');

		foreach ($sizes as $size) {
			$thumb->setFilename("{$prefix}{$size}.jpg");
			$thumb->open("write");
			$thumb->write($thumbs[$size]);
			$thumb->close();
		}

		$award->icontime = time();
	}
}

elgg_clear_sticky_form('award');
system_message(elgg_echo('award:save:success'));
forward('admin/award/list');