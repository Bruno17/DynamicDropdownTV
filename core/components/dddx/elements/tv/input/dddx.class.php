<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderDddx extends modTemplateVarInputRender {
    public function getTemplate() {
        $path = 'components/dddx/';
        $corePath = $this->modx->getOption('dddx.core_path', null, $this->modx->getOption('core_path') . $path);
        return $corePath . 'elements/tv/dddx.tpl';        
    }

    public function process($value,array $params = array()) {
        /*
        require_once dirname(dirname(dirname(dirname(__file__)))) . '/model/dddx/dddx.class.php';
        $this->dddx = new Dddx($this->modx,$params);
        $this->dddx->loadConfigs();
        */
        $namespace = 'dddx';
        $this->modx->lexicon->load('tv_widget', $namespace . ':default');
        //print_r($this->dddx->customconfigs);
        
        //$properties = isset($params['columns']) ? $params : $this->getProperties();        
        
        $this->setPlaceholder('params', $params);
        $this->setPlaceholder('children', $this->modx->toJson(explode(',',$params['childs'])));
        $this->setPlaceholder('parents', $this->modx->toJson(explode(',',$params['parents'])));
        $this->setPlaceholder('dddx_id', $params['dddxid']);

    }
}    
return 'modTemplateVarInputRenderDddx';