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
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'combo'
        ,store : {/literal}{$tvlist}{literal}
        ,fieldLabel: '{/literal}{$dddx.parent}{literal}'
        ,description: '{/literal}{$dddx.parent_desc}{literal}'
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
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$dddx.group}{literal}'
        ,description: '{/literal}{$dddx.group_desc}{literal}'
        ,name: 'inopt_group'
        ,hiddenName: 'inopt_group'
        ,id: 'inopt_group{/literal}{$tv}{literal}'
        ,value: params['group']
        ,width: 600
        ,listeners: oc
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
        xtype: 'textfield'
        ,fieldLabel: _('combo_title')
        ,description: MODx.expandHelp ? '' : _('combo_title_desc')
        ,name: 'inopt_title'
        ,id: 'inopt_title{/literal}{$tv}{literal}'
        ,value: params['title'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_title{/literal}{$tv}{literal}'
        ,html: _('combo_title_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('combo_typeahead')
        ,description: MODx.expandHelp ? '' : _('combo_typeahead_desc')
        ,name: 'inopt_typeAhead'
        ,hiddenName: 'inopt_typeAhead'
        ,id: 'inopt_typeAhead{/literal}{$tv}{literal}'
        ,value: params['typeAhead'] || false
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
        ,value: params['forceSelection'] || false
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_forceSelection{/literal}{$tv}{literal}'
        ,html: _('combo_forceselection_desc')
        ,cls: 'desc-under'
        
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$dddx.firstText}{literal}'
        ,description: MODx.expandHelp ? '' : '{/literal}{$dddx.firstText_desc}{literal}'
        ,name: 'inopt_firstText'
        ,id: 'inopt_firstText{/literal}{$tv}{literal}'
        ,value: params['firstText'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_firstText{/literal}{$tv}{literal}'
        ,html: '{/literal}{$dddx.firstText_desc}{literal}'
        ,cls: 'desc-under'
    },{
        xtype: 'textarea'
        ,fieldLabel: 'where'
        ,description: '{/literal}{$mig.where_desc}{literal}'
        ,name: 'inopt_where'
        ,hiddenName: 'inopt_where'
        ,id: 'inopt_where{/literal}{$tv}{literal}'
        ,value: params['where']
        ,width: 600
        ,height: 150
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$dddx.childs}({$dddx.readonly}){literal}'
        ,description: '{/literal}{$dddx.childs_desc}{literal}'
        ,name: 'inopt_childs'
        ,hiddenName: 'inopt_childs'
        ,id: 'inopt_childs{/literal}{$tv}{literal}'
        ,value: params['childs']
        ,width: 600
        ,listeners: oc
        ,readOnly: true
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$dddx.parents}({$dddx.readonly}){literal}'
        ,description: '{/literal}{$dddx.parents_desc}{literal}'
        ,name: 'inopt_parents'
        ,hiddenName: 'inopt_parents'
        ,id: 'inopt_parents{/literal}{$tv}{literal}'
        ,value: params['parents']
        ,width: 600
        ,listeners: oc
        ,readOnly: true
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}