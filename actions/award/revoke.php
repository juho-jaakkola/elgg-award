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

if (remove_entity_relationship($award_guid, 'award', $user_guid)) {
	system_message(elgg_echo('award:revoke:success'));
} else {
	register_error(elgg_echo('award:revoke:failed'));
}

forward(REFERER);