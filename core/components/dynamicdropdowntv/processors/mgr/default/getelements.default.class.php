<?php
/**
 * DynamicDropdownTV — default getelements processor (MODX 2 + MODX 3 dual-compat).
 *
 * Returns options parsed from the TV's "Input Option Values" elements
 * string. Supports the legacy `parent##child` grouping syntax used by
 * chained dropdowns.
 *
 * @package dynamicdropdowntv
 */
if (!class_exists('DynamicDropdownTVGetElementsDefaultProcessor')) {

class DynamicDropdownTVGetElementsDefaultProcessor extends modProcessor
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
        $tvname = (string)$this->getProperty('tvname', '');

        $tv = $modx->getObject('modTemplateVar', array('name' => $tvname));
        if (!$tv) {
            return $this->outputArray(array(), 0);
        }

        $inputProperties = $tv->get('input_properties');
        if (!is_array($inputProperties)) {
            $inputProperties = array();
        }
        $elementsRaw = (string)$tv->get('elements');

        $lang = $modx->lexicon->fetch('dynamicdropdowntv.', true);
        $firstText = $modx->getOption(
            'firstText',
            $inputProperties,
            isset($lang['firstText_default']) ? $lang['firstText_default'] : '',
            true
        );

        $elementValues = array();
        foreach (explode('##', $elementsRaw) as $element) {
            $pair = explode('::', trim($element));
            if (count($pair) > 1) {
                $key = trim($pair[0]);
                $values = explode('||', $pair[1]);
            } else {
                $key = '#ROOT#';
                $values = explode('||', $pair[0]);
            }
            $elementValues[$key] = $values;
        }

        $parent = $modx->getOption('parent', $inputProperties, '');
        $parentValue = (isset($scriptProperties[$parent]) && $scriptProperties[$parent] !== '')
            ? $scriptProperties[$parent]
            : '#ROOT#';
        $current = isset($elementValues[$parentValue]) ? $elementValues[$parentValue] : array();

        $options = array();
        $options[] = array('id' => '', 'name' => $firstText);
        $count = 1 + count($current);
        $rows = array();
        foreach ($current as $elementValue) {
            $parts = explode('==', $elementValue, 2);
            $option = array('name' => $parts[0]);
            $option['id'] = (count($parts) === 1) ? $parts[0] : $parts[1];
            $rows[strtolower((string)$option['name'])] = $option;
        }
        ksort($rows);
        foreach ($rows as $option) {
            $options[] = $option;
        }

        return $this->outputArray($options, $count);
    }
}

}
return 'DynamicDropdownTVGetElementsDefaultProcessor';
