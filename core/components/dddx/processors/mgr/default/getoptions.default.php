<?php

$query = $modx->getOption('query', $scriptProperties, '');

$tv = $modx->getObject('modTemplateVar', array('name' => $scriptProperties['tvname']));
$inputProperties = $tv->get('input_properties');

$firstText = $modx->getOption('firstText', $inputProperties, '-- choose one --');
$where = $modx->getOption('where', $inputProperties, '');

$sps = $scriptProperties;
foreach ($sps as $key => $sp) {
    if ($sp == '') {
        $scriptProperties[$key] = '999999999999999';
    }
}

if (!empty($where)) {
    $chunk = $modx->newObject('modChunk');
    $chunk->setCacheable(false);
    $chunk->setContent($where);
    $where = $chunk->process($scriptProperties);

}

$classname = $modx->getOption('classname', $inputProperties, '');
$classname = empty($classname) ? 'modResource' : $classname;

$idfield = $modx->getOption('idfield', $inputProperties, '');
$idfield = empty($idfield) ? 'id' : $idfield;

$namefield = $modx->getOption('namefield', $inputProperties, '');
$namefield = empty($namefield) ? 'pagetitle' : $namefield;

$packageName = $modx->getOption('packagename', $inputProperties, '');

if (!empty($packageName)) {
    $packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
    $modelpath = $packagepath . 'model/';

    $modx->addPackage($packageName, $modelpath, $prefix);
}

$c = $modx->newQuery($classname);

if (!empty($where)) {
    $c->where($modx->fromJson($where));
}

$options = array();
if (!empty($query)) {
    $c->where(array($namefield . ':LIKE' => $query . '%'));
} else {
    $options[] = array('id' => '', 'name' => $firstText);
}

//$c->prepare();echo $c->toSql();
if ($collection = $modx->getCollection($classname, $c)) {
    foreach ($collection as $object) {
        $option['id'] = $object->get($idfield);
        $option['name'] = $object->get($namefield);
        $rows[strtolower($option['name'])] = $option;
    }
    ksort($rows);

    foreach ($rows as $option) {
        $options[] = $option;
    }
}

return $this->outputArray($options, $count);
