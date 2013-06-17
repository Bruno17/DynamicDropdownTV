<div id="tv-input-properties-form{$tv}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};

MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,cls: 'form-with-labels'
    ,autoHeight: true
    ,border: false
    ,labelAlign: 'top'
    ,labelSeparator: ''
    ,items: [{
        xtype: 'combo'
        ,store : {/literal}{$tvlist}{literal}
        ,fieldLabel: '{/literal}{$dynamicdropdowntv.parent}{literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.parent_desc}{literal}'
        ,name: 'inopt_parent'
        ,hiddenName: 'inopt_parent'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,triggerAction: 'all'
        ,id: 'inopt_parent{/literal}{$tv}{literal}'
        ,value: params['parent'] || ''
        ,width: 600
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_parent{/literal}{$tv}{literal}'
        ,html: '{/literal}{$dynamicdropdowntv.parent_desc}{literal}'
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$dynamicdropdowntv.group}{literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.group_desc}{literal}'
        ,name: 'inopt_group'
        ,hiddenName: 'inopt_group'
        ,id: 'inopt_group{/literal}{$tv}{literal}'
        ,value: params['group']
        ,width: 600
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_group{/literal}{$tv}{literal}'
        ,html: '{/literal}{$dynamicdropdowntv.group_desc}{literal}'
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: MODx.expandHelp ? '' : _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,value: params['allowBlank'] == 0 || params['allowBlank'] == 'false' ? false : true
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('combo_listwidth')
        ,description: MODx.expandHelp ? '' : _('combo_listwidth_desc')
        ,name: 'inopt_listWidth'
        ,id: 'inopt_listWidth{/literal}{$tv}{literal}'
        ,value: params['listWidth'] || ''
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_listWidth{/literal}{$tv}{literal}'
        ,html: _('combo_listwidth_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('combo_typeahead')
        ,description: MODx.expandHelp ? '' : _('combo_typeahead_desc')
        ,name: 'inopt_typeAhead'
        ,hiddenName: 'inopt_typeAhead'
        ,id: 'inopt_typeAhead{/literal}{$tv}{literal}'
        ,value: params['typeAhead'] == 1 || params['typeAhead'] == 'true' ? true : false
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_typeAhead{/literal}{$tv}{literal}'
        ,html: _('combo_typeahead_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('combo_typeahead_delay')
        ,description: MODx.expandHelp ? '' : _('combo_typeahead_delay_desc')
        ,name: 'inopt_typeAheadDelay'
        ,id: 'inopt_typeAheadDelay{/literal}{$tv}{literal}'
        ,value: params['typeAheadDelay'] || 250
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_typeAheadDelay{/literal}{$tv}{literal}'
        ,html: _('combo_typeahead_delay_desc')
        ,cls: 'desc-under'
        
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('combo_forceselection')
        ,description: MODx.expandHelp ? '' : _('combo_forceselection_desc')
        ,name: 'inopt_forceSelection'
        ,hiddenName: 'inopt_forceSelection'
        ,id: 'inopt_forceSelection{/literal}{$tv}{literal}'
        ,value: params['forceSelection'] == 1 || params['forceSelection'] == 'true' ? true : false
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_forceSelection{/literal}{$tv}{literal}'
        ,html: _('combo_forceselection_desc')
        ,cls: 'desc-under'
        
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$dynamicdropdowntv.firstText}{literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.firstText_desc}{literal}'
        ,name: 'inopt_firstText'
        ,id: 'inopt_firstText{/literal}{$tv}{literal}'
        ,value: params['firstText'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_firstText{/literal}{$tv}{literal}'
        ,html: '{/literal}{$dynamicdropdowntv.firstText_desc}{literal}'
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel:'{/literal}{$dynamicdropdowntv.clearOnRefresh}{literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.clearOnRefresh_desc}{literal}'
        ,name: 'inopt_clearOnRefresh'
        ,hiddenName: 'inopt_clearOnRefresh'
        ,id: 'inopt_clearOnRefresh{/literal}{$tv}{literal}'
        ,value: params['clearOnRefresh'] || true
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_clearOnRefresh{/literal}{$tv}{literal}'
        ,html: '{/literal}{$dynamicdropdowntv.clearOnRefresh_desc}{literal}'
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel:'{/literal}{$dynamicdropdowntv.valueDelimiter}{literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.valueDelimiter_desc}{literal}'
        ,name: 'inopt_valueDelimiter'
        ,id: 'inopt_valueDelimiter{/literal}{$tv}{literal}'
        ,value: params['valueDelimiter'] || '||'
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_valueDelimiter{/literal}{$tv}{literal}'
        ,html:'{/literal}{$dynamicdropdowntv.valueDelimiter_desc}{literal}'
        ,cls: 'desc-under'
    },{
        xtype: 'fieldset'
        ,fieldLabel: '{/literal}{$dynamicdropdowntv.package}{literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.package_desc}{literal}'
        ,labelAlign: 'top'
		,items: [{
			xtype: 'textarea'
			,fieldLabel: '{/literal}{$dynamicdropdowntv.where}{literal}'
			,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.where_desc}{literal}'
			,name: 'inopt_where'
			,hiddenName: 'inopt_where'
			,id: 'inopt_where{/literal}{$tv}{literal}'
			,value: params['where']
			,width: 600
			,height: 150
			,listeners: oc
		},{
			xtype: MODx.expandHelp ? 'label' : 'hidden'
			,forId: 'inopt_where{/literal}{$tv}{literal}'
			,html: '{/literal}{$dynamicdropdowntv.where_desc}{literal}'
			,cls: 'desc-under'
		},{
			xtype: 'textfield'
			,fieldLabel: '{/literal}{$dynamicdropdowntv.packagename}{literal}'
			,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.packagename_desc}{literal}'
			,name: 'inopt_packagename'
			,id: 'inopt_packagename{/literal}{$tv}{literal}'
			,value: params['packagename']
			,anchor: '100%'
			,listeners: oc
		},{
			xtype: MODx.expandHelp ? 'label' : 'hidden'
			,forId: 'inopt_packagename{/literal}{$tv}{literal}'
			,html: '{/literal}{$dynamicdropdowntv.packagename_desc}{literal}'
			,cls: 'desc-under'
		},{
			xtype: 'textfield'
			,fieldLabel: '{/literal}{$dynamicdropdowntv.classname}{literal}'
			,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.classname_desc}{literal}'
			,name: 'inopt_classname'
			,id: 'inopt_classname{/literal}{$tv}{literal}'
			,value: params['classname']
			,anchor: '100%'
			,listeners: oc
		},{
			xtype: MODx.expandHelp ? 'label' : 'hidden'
			,forId: 'inopt_classname{/literal}{$tv}{literal}'
			,html: '{/literal}{$dynamicdropdowntv.classname_desc}{literal}'
			,cls: 'desc-under'
		},{
			xtype: 'textfield'
			,fieldLabel: '{/literal}{$dynamicdropdowntv.idfield}{literal}'
			,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.idfield_desc}{literal}'
			,name: 'inopt_idfield'
			,id: 'inopt_idfield{/literal}{$tv}{literal}'
			,value: params['idfield']
			,anchor: '100%'
			,listeners: oc
		},{
			xtype: MODx.expandHelp ? 'label' : 'hidden'
			,forId: 'inopt_idfield{/literal}{$tv}{literal}'
			,html: '{/literal}{$dynamicdropdowntv.idfield_desc}{literal}'
			,cls: 'desc-under'
		},{
			xtype: 'textfield'
			,fieldLabel: '{/literal}{$dynamicdropdowntv.namefield}{literal}'
			,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.namefield_desc}{literal}'
			,name: 'inopt_namefield'
			,id: 'inopt_namefield{/literal}{$tv}{literal}'
			,value: params['namefield']
			,anchor: '100%'
			,listeners: oc
		},{
			xtype: MODx.expandHelp ? 'label' : 'hidden'
			,forId: 'inopt_namefield{/literal}{$tv}{literal}'
			,html: '{/literal}{$dynamicdropdowntv.namefield_desc}{literal}'
			,cls: 'desc-under'
		}]
    },{
        xtype: 'fieldset'
        ,fieldLabel: '{/literal}{$dynamicdropdowntv.debugfields} ({$dynamicdropdowntv.readonly}){literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.debugfields_desc}{literal}'
        ,labelAlign: 'top'
		,items: [{
			xtype: 'textfield'
			,fieldLabel: '{/literal}{$dynamicdropdowntv.childs}{literal}'
			,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.childs_desc}{literal}'
			,name: 'inopt_childs'
			,hiddenName: 'inopt_childs'
			,id: 'inopt_childs{/literal}{$tv}{literal}'
			,value: params['childs']
			,width: 600
			,listeners: oc
			,readOnly: true
		},{
			xtype: MODx.expandHelp ? 'label' : 'hidden'
			,forId: 'inopt_childs{/literal}{$tv}{literal}'
			,html: '{/literal}{$dynamicdropdowntv.childs_desc}{literal}'
			,cls: 'desc-under'
		},{
			xtype: 'textfield'
			,fieldLabel: '{/literal}{$dynamicdropdowntv.parents}{literal}'
			,description: MODx.expandHelp ? '' : '{/literal}{$dynamicdropdowntv.parents_desc}{literal}'
			,name: 'inopt_parents'
			,hiddenName: 'inopt_parents'
			,id: 'inopt_parents{/literal}{$tv}{literal}'
			,value: params['parents']
			,width: 600
			,listeners: oc
			,readOnly: true
		},{
			xtype: MODx.expandHelp ? 'label' : 'hidden'
			,forId: 'inopt_parents{/literal}{$tv}{literal}'
			,html: '{/literal}{$dynamicdropdowntv.parents_desc}{literal}'
			,cls: 'desc-under'
		}]
	}]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}