<?php
/**
 * DynamicDropdownTV — default getoptions processor (MODX 2 + MODX 3 dual-compat).
 *
 * Originally a legacy include-style processor (.php). MODX 3 only loads
 * processors as Processor subclasses in .class.php files, so this is the
 * canonical version. The original .php file is kept in place for sites
 * that may reference it directly via custom code.
 *
 * @package dynamicdropdowntv
 */
if (!class_exists('DynamicDropdownTVGetOptionsDefaultProcessor')) {

class DynamicDropdownTVGetOptionsDefaultProcessor extends modProcessor
{
    public function checkPermissions()
    {
        if (!$this->modx->user) {
            return false;
        }
        return $this->modx->user->isAuthenticated(
            $this->modx->getOption('manager_context_key', null, 'mgr')
        );
    }

    public function getLanguageTopics()
    {
        return array('tv_widget', 'dynamicdropdowntv:inputoptions');
    }

    public function process()
    {
        $modx = $this->modx;
        $scriptProperties = $this->getProperties();

        $query = (string)$this->getProperty('query', '');
        $tvname = (string)$this->getProperty('tvname', '');

        $tv = $modx->getObject('modTemplateVar', array('name' => $tvname));
        if (!$tv) {
            return $this->outputArray(array(), 0);
        }

        $inputProperties = $tv->get('input_properties');
        if (!is_array($inputProperties)) {
            $inputProperties = array();
        }

        $lang = $modx->lexicon->fetch('dynamicdropdowntv.', true);
        $firstText = $modx->getOption(
            'firstText',
            $inputProperties,
            isset($lang['firstText_default']) ? $lang['firstText_default'] : '',
            true
        );
        $where = $modx->getOption('where', $inputProperties, '');

        /* PHP 8: normalize empty scriptProperties so chunk-processed where
         * clauses always resolve to something concrete. */
        foreach ($scriptProperties as $key => $val) {
            if ($val === '' || $val === null) {
                $scriptProperties[$key] = '999999999999999';
            }
        }

        if (!empty($where)) {
            $chunk = $modx->newObject('modChunk');
            $chunk->setCacheable(false);
            $chunk->setContent($where);
            $where = $chunk->process($scriptProperties);
            // Sanitize: round-trip through JSON to strip any executable tags
            $decoded = json_decode($where, true);
            $where = is_array($decoded) ? json_encode($decoded) : '';
        }

        $classname   = $modx->getOption('classname',  $inputProperties, 'modResource', true);
        $idfield     = $modx->getOption('idfield',    $inputProperties, 'id', true);
        $namefield   = $modx->getOption('namefield',  $inputProperties, 'pagetitle', true);
        $packageName = $modx->getOption('packagename', $inputProperties, '');
        $prefix      = $modx->getOption('prefix',     $inputProperties, '');
        $prefix      = ($prefix === '') ? null : $prefix;

        if (!empty($packageName)) {
            $modelpath = $modx->getOption('core_path') . 'components/' . $packageName . '/model/';
            $modx->addPackage($packageName, $modelpath, $prefix);
        }

        $c = $modx->newQuery($classname);
        if (!empty($where)) {
            $decoded = json_decode($where, true);
            if (is_array($decoded)) {
                $c->where($decoded);
            }
        }

        $options = array();
        $rows = array();
        $count = 1;

        if ($query !== '') {
            $c->where(array($namefield . ':LIKE' => $query . '%'));
        } else {
            $options[] = array('id' => '', 'name' => $firstText);
        }

        $collection = $modx->getCollection($classname, $c);
        if ($collection) {
            $count += $modx->getCount($classname);
            foreach ($collection as $object) {
                $option = array(
                    'id'   => $object->get($idfield),
                    'name' => $object->get($namefield),
                );
                $rows[strtolower((string)$option['name'])] = $option;
            }
            ksort($rows);
            foreach ($rows as $option) {
                $options[] = $option;
            }
        }

        return $this->outputArray($options, $count);
    }
}

}
return 'DynamicDropdownTVGetOptionsDefaultProcessor';
