<?php

if (!function_exists('dddx_setParentChilds')) {
    function dddx_setParentChilds($tvname, $parentTvName, $dtvs_arr)
    {
        if (!empty($parentTvName) && array_key_exists($parentTvName, $dtvs_arr)) {
            $dtvs_arr[$parentTvName]['childs'][$tvname] = $tvname;
            if (!empty($dtvs_arr[$parentTvName]['properties']['parent'])) {
                $dtvs_arr = dddx_setParentChilds($tvname, $dtvs_arr[$parentTvName]['properties']['parent'], $dtvs_arr);
            }
        }
        return $dtvs_arr;
    }
}


$corePath = $modx->getOption('dddx.core_path', null, $modx->getOption('core_path') . 'components/dddx/');


switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath . 'elements/tv/input/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($corePath . 'elements/tv/inputoptions/');
        break;
    case 'OnTVFormSave':


        $tv = &$scriptProperties['tv'];
        $tvname = $tv->get('name');
        $type = $tv->get('type');
        if ($type == 'dynamic_dropdown') {
            $inputProperties = $tv->get('input_properties');
            // get all dynamic_dropdown-TVs
            $c = $modx->newQuery('modTemplateVar');
            $c->where(array('type' => 'dynamic_dropdown'));
            $dtvs = $modx->getCollection('modTemplateVar', $c);

            foreach ($dtvs as $key => $dtv) {
                $dtv_ips = $dtv->get('input_properties');
                // work only with them in the same dddx-group
                if ($dtv_ips['group'] == $inputProperties['group']) {
                    $dtvs_arr[$dtv->get('name')]['properties'] = $dtv->get('input_properties');
                } else {
                    unset($dtvs[$key]);
                }
            }

            $dtvs_result = $dtvs_arr;
            foreach ($dtvs_arr as $tvname => $dtv) {
                $dtv_ips = $dtv['properties'];
                $dtvs_result = dddx_setParentChilds($tvname, $dtv_ips['parent'], $dtvs_result);

            }

            $dtvs_arr = $dtvs_result;
            foreach ($dtvs_arr as $tvname => $dtv) {
                $dtv_ips = $dtv['properties'];
                if (count($dtv['childs'] > 0)) {
                    foreach ($dtv['childs'] as $child) {
                        $dtvs_result[$child]['parents'][$tvname] = $tvname;
                    }
                }
            }

            foreach ($dtvs as $key => $dtv) {

                $dtv_ips = $dtv->get('input_properties');
                $dtv_ips['childs'] = isset($dtvs_result[$dtv->get('name')]['childs']) ? implode(',', $dtvs_result[$dtv->get('name')]['childs']) : '';
                $dtv_ips['parents'] = isset($dtvs_result[$dtv->get('name')]['parents']) ? implode(',', $dtvs_result[$dtv->get('name')]['parents']) : '';
                $dtv->set('input_properties',$dtv_ips);
                $dtv->save();

            }

            /*
            $debug['dtvs_arr'] = $dtvs_result;
            $chunk = $modx->getObject('modChunk', array('name' => 'debug'));
            $chunk->setContent($chunk->getContent() . print_r($debug, 1));
            $chunk->save();
            */
        }

        break;


}
return;
