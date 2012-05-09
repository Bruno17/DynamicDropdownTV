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
        
        $resource = is_object($this->modx->resource) ? $this->modx->resource->toArray() : array();
                
        //$properties = isset($params['columns']) ? $params : $this->getProperties();
        
        $dddx_id = $this->tv->get('name');
        $groupPath = 'mgr/'.$params['group'];
        $defaultPath = 'mgr/default';
        $actionPath = file_exists($corePath.'processors/'.$groupPath) ? $groupPath.'/' : $defaultPath.'/';
        
        $dddx_processor = 'getoptions.'.$dddx_id;
        $default_processor = 'getoptions.default';
        
        $action = file_exists($corePath.'processors/'.$actionPath.$dddx_processor.'.php') ? $actionPath.$dddx_processor : $actionPath.$default_processor;
                
        $this->setPlaceholder('resource', $resource);
        $this->setPlaceholder('object_id', $this->modx->getOption('object_id',$_REQUEST,''));        
        $this->setPlaceholder('params', $params);
        $this->setPlaceholder('action', $action);
        $this->setPlaceholder('children', $this->modx->toJson(explode(',',$params['childs'])));
        $this->setPlaceholder('parents', $this->modx->toJson(explode(',',$params['parents'])));
        //$this->setPlaceholder('dddx_id', $params['dddxid']);
        $this->setPlaceholder('dddx_id', $dddx_id);

    }
}    
return 'modTemplateVarInputRenderDddx';