<?php

$query = $modx->getOption('query', $scriptProperties, '');

$tv = $modx->getObject('modTemplateVar', array('name' => $scriptProperties['tvname']));
$inputProperties = $tv->get('input_properties');

$firstText = $modx->getOption('firstText', $inputProperties, '-- choose one --');
$where = $modx->getOption('where', $inputProperties, '');

$sps = $scriptProperties;
foreach ($sps as $key => $sp){
    if ($sp == ''){
        $scriptProperties[$key] = '999999999999999';    
    }
}

if (!empty($where)) {
    $chunk = $modx->newObject('modChunk');
    $chunk->setCacheable(false);
    $chunk->setContent($where);
    $where = $chunk->process($scriptProperties);

}

$classname = 'modResource';
$c = $modx->newQuery($classname);

if (!empty($where)) {
    $c->where($modx->fromJson($where));
}

$options = array();
if (!empty($query)) {
    $c->where(array('pagetitle:LIKE' => $query . '%'));
} else {
    $options[] = array('id' => '', 'name' => $firstText);
}

//$c->prepare();echo $c->toSql();
if ($collection = $modx->getCollection($classname, $c)) {
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
