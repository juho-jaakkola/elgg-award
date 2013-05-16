<?php

$user_guid = get_input('guid');
$award_guid = get_input('award');

$user = get_user($user_guid);
if (!$user) {
	register_error(elgg_echo('award:user_not_found'));
	forward(REFERER);
}

$award = get_entity($award_guid);
if (!elgg_instanceof($award, 'object','award')) {
	register_error(elgg_echo('award:award_not_found'));
	forward(REFERER);
}

if (add_entity_relationship($award_guid, 'award', $user_guid)) {
	$site = elgg_get_site_entity();

	$subject = elgg_echo('award:notification:subject');
	$message = elgg_echo('award:notification:message', array(
		$user->name,
		$award->title,
		$site->name,
		$user->getURL()
	));

	notify_user($user_guid, $site->getGUID(), $subject, $message);

	system_message(elgg_echo('award:grant:success'));
} else {
	register_error(elgg_echo('award:grant:failed'));
}

forward(REFERER);