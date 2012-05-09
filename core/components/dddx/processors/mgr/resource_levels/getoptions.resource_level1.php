<?php

$parent = $modx->getOption('resource_level0',$scriptProperties,'');
$query = $modx->getOption('query', $scriptProperties, '');
$tv = $modx->getObject('modTemplateVar', array('name' => $scriptProperties['tvname']));
$inputProperties = $tv->get('input_properties');
$firstText = $modx->getOption('firstText', $inputProperties, '-- choose one --');

$classname = 'modResource';
$c = $modx->newQuery($classname);
$c->where(array('parent' => $parent));
$options = array();
if (!empty($query)) {
    $c->where(array('pagetitle:LIKE' => $query . '%'));
}
else{
    $options[] = array('id' => '', 'name' => $firstText);
}


if ($parent != '' && $collection = $modx->getCollection($classname, $c)) {
    foreach ($collection as $object) {
        $option['id'] = $object->get('id');
        $option['name'] = $object->get('pagetitle');
        $rows[strtolower($option['name'])] = $option;
    }
    ksort($rows);

    foreach ($rows as $option) {
        $options[] = $option;
    }
}

return $this->outputArray($options, $count);
