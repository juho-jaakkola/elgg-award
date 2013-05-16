<?php
/**
 * Award
 *
 * @package Award
 */

elgg_register_event_handler('init', 'system', 'award_init');

/**
 * Initialize the plugin.
 */
function award_init() {
	elgg_extend_view('object/blog', 'output/themes', 1);

	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'award_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'award_entity_menu');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'award_icon_url_override');

	$actions_path = elgg_get_plugins_path() . 'award/actions/award/';
	elgg_register_action('award/save', $actions_path . 'save.php', 'admin');
	elgg_register_action('award/delete', $actions_path . 'delete.php', 'admin');
	elgg_register_action('award/grant', $actions_path . 'grant.php', 'admin');
	elgg_register_action('award/revoke', $actions_path . 'revoke.php', 'admin');

	elgg_register_page_handler('awardicon', 'award_icon_handler');
	elgg_register_page_handler('award', 'award_page_handler');

	elgg_extend_view('css/elgg', 'award/css');
}

/**
 * Add menu items to user hover menu
 */
function award_user_hover_menu ($hook, $type, $return, $params) {
	$user = $params['entity'];

	if (elgg_is_admin_logged_in() && $user->guid != elgg_get_logged_in_user_guid()) {
		$awards = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'award',
			'limit' => false,
		));

		foreach ($awards as  $key => $award) {
			if (check_entity_relationship($award->guid, 'award', $user->guid)) {
				$return[] = ElggMenuItem::factory(array(
					'name' => "award-$key",
					'text' => elgg_echo('award:revoke', array($award->title)),
					'href' => "action/award/revoke?guid={$user->guid}&award={$award->getGUID()}",
					'is_action' => true,
				));
			} else {
				$return[] = ElggMenuItem::factory(array(
					'name' => "award-$key",
					'text' => elgg_echo('award:grant', array($award->title)),
					'href' => "action/award/grant?guid={$user->guid}&award={$award->getGUID()}",
					'is_action' => true,
				));
			}
			
		}
	}

	return $return;
}

/**
 * Set up entity menu for an award
 */
function award_entity_menu ($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];

	if ($params['handler'] != 'award') {
		return $return;
	}

	foreach ($return as $key => $item) {
		if (!in_array($item->getName(), array('delete'))) {
			unset($return[$key]);
		}
	}

	if (elgg_is_admin_logged_in()) {
		$return[] = ElggMenuItem::factory(array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "admin/award/save?guid={$entity->getGUID()}",
		));
	}

	return $return;
}

/**
 * Override the default entity icon for award entity
 *
 * @return string Relative URL
 */
function award_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	$size = $params['size'];

	$icontime = $entity->icontime;

	if ($icontime) {
		// return thumbnail
		return "awardicon/$entity->guid/$size/$icontime.jpg";
	}
}

/**
 * Handle award icons.
 *
 * @param array $page
 * @return void
 */
function award_icon_handler($page) {

	if (isset($page[0])) {
		set_input('guid', $page[0]);
	}
	if (isset($page[1])) {
		set_input('size', $page[1]);
	}

	$plugin_dir = elgg_get_plugins_path();
	include("$plugin_dir/award/icon.php");
	return true;
}

function award_page_handler ($page) {
	$award = get_entity($page[1]);

	if (elgg_instanceof($award, 'object', 'award')) {
		echo elgg_view('award/layout', array('entity' => $award));
		return true;
	}

	return false;
}
