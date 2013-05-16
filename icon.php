<?php
/**
 * Icon display
 *
 * @package ElggGroups
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$guid = get_input('guid');

$entity = get_entity($guid);
if (!(elgg_instanceof($entity, 'object', 'award'))) {
	header("HTTP/1.1 404 Not Found");
	exit;
}

// If is the same ETag, content didn't changed.
$etag = $entity->icontime . $guid;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header("HTTP/1.1 304 Not Modified");
	exit;
}

$size = strtolower(get_input('size'));
if (!in_array($size, array('large', 'medium', 'small'))) {
	$size = "medium";
}

$success = false;

$filehandler = new ElggFile();
$filehandler->owner_guid = $entity->owner_guid;
$filehandler->setFilename("award/" . $entity->guid . $size . ".jpg");

//var_dump($filehandler->getFilenameOnFilestore());

$success = false;
if ($filehandler->open("read")) {
	if ($contents = $filehandler->read($filehandler->size())) {
		$success = true;
	}
}

header("Content-type: image/jpeg");
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));
header("ETag: \"$etag\"");
echo $contents;
