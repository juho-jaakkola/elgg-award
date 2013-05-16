<?php

$guid = get_input('guid');

$values = array(
	'title' => null,
	'description' => null,
	'guid' => null,
	'icon' => null,
);

if ($guid) {
	$entity = get_entity($guid);

	foreach ($values as $name => $value) {
		if (!empty($entity->$name)) {
			$values[$name] = $entity->$name;
		}
	}
	
	if ($entity->icontime) {
		$values['icon'] = true;
	}
}

if (elgg_is_sticky_form('award')) {
	$sticky_values = elgg_get_sticky_values('award');
	foreach ($sticky_values as $key => $value) {
		$values[$key] = $value;
	}
}

elgg_clear_sticky_form('award');

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form-alt',
);

echo elgg_view_form('award/save', $form_vars, $values);