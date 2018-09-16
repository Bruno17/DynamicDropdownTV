<?php
/**
 * DynamicDropdownTV
 *
 * Copyright 2012-2013 by Bruno Perner <b.perner@gmx.de>
 *
 * DynamicDropdownTV is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * DynamicDropdownTV is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * DynamicDropdownTV; if not, write to the Free Software Foundation, Inc., 59
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package dynamicdropdowntv
 * @subpackage processor
 *
 * DynamicDropdownTV getelements default processor
 */
$query = $modx->getOption('query', $scriptProperties, '');

$tv = $modx->getObject('modTemplateVar', array('name' => $scriptProperties['tvname']));
$inputProperties = $tv->get('input_properties');
$elements = $tv->get('elements');

$modx->lexicon->load('tv_widget', 'dynamicdropdowntv:inputoptions');
$lang = $modx->lexicon->fetch('dynamicdropdowntv.', TRUE);

$firstText = $modx->getOption('firstText', $inputProperties, $lang['firstText_default'], TRUE);

$elementValues = array();
$elements = explode('##', $elements);
foreach ($elements as $element) {
    // Check for any binding in element string
    $regex = '/(.*)@(\w+)\W+(\w.+)/';

    if(preg_match($regex,$element,$mtch)) {    
        $prefix = $mtch[1];
        $type = $mtch[2]; //binding type
        $val = $mtch[3]; //binding value string
        $el;
        
        switch($type) {
            case 'CHUNK':
                $el = $modx->parseChunk($val,array());
                break;
            case 'FILE':
                $el = file_get_contents($modx->config['base_path'] . $val);
                break;        
            case 'SELECT':
                $sql = 'SELECT '. $val;

                $c = new xPDOCriteria($modx,$sql); // connect to the MODX DB
                $rows = array();
                if ($c->stmt && $c->stmt->execute())
                {
                    while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        if(count($row) > 1) {
                            $rows[] = $row['name']."==".$row['id'];    
                        } else {
                            $rows[] = $row['id'];
                        }
                    }
                    $el = implode('||',$rows);
                    break;
                }
                break;
            /*
            case 'INLINE':
                $chunk = $modx->newObject('modChunk');
                $chunk->setCacheable(false);
                $elements = $chunk->process();
                break;
            */    
            default:
                break;
        }
        $element = $prefix . $el;
    }
 
    $element = explode('::', trim($element));
	if (count($element) > 1) {
		$key = trim($element[0]);
		$values = explode('||', $element[1]);
	} else {
		$key = '#ROOT#';
		$values = explode('||', $element[0]);
	}
	$elementValues[$key] = $values;
}

$parent = $modx->getOption('parent', $inputProperties, '');
$parentValue = ($scriptProperties[$parent] != '') ? $scriptProperties[$parent] : '#ROOT#';
$elementValues = isset($elementValues[$parentValue]) ? $elementValues[$parentValue] : array();

$options = array();
$count = 1;
$rows = array();

$options[] = array('id' => '', 'name' => $firstText);

$count += count($elementValues);
foreach ($elementValues as $elementValue) {
	$elementValue = explode('==', $elementValue, 2);
	$option['name'] = $elementValue[0];
	if (count($elementValue) == 1) {
		$option['id'] = $elementValue[0];
	} else {
		$option['id'] = $elementValue[1];
	}
	$rows[strtolower($option['name'])] = $option;
}
ksort($rows);

foreach ($rows as $option) {
	$options[] = $option;
}

return $this->outputArray($options, $count);
