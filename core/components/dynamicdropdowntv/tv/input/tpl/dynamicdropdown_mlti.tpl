<input type="hidden" id="original{$ddId}" name="original{$tv->id}" value="{$tv->get('value')|escape}" />

<div id="div_{$ddId}">

</div>


<script type="text/javascript">
// <![CDATA[
{literal}

MODx.combo.{/literal}{$ddId}{literal} = function(config) {
    config = config || {};
    Ext.applyIf(config,{{/literal}
        name: '{$ddId}'
        ,id: 'select_{$ddId}'
        ,width: 400
        ,hiddenName: 'tv{$tv->id}[]'
        ,renderTo: 'div_{$ddId}'
        ,triggerAction: 'all'
        ,mode: 'remote'
        ,children: Ext.util.JSON.decode('{$children}')
        ,parents: Ext.util.JSON.decode('{$parents}')
        ,clearBtnCls: 'x-form-trigger'
        ,expandBtnCls: 'x-form-trigger'    
        {if $params.title},title: '{$params.title}'{/if}
        {if $params.listWidth},listWidth: {$params.listWidth}{/if}
        ,maxHeight: {if $params.maxHeight}{$params.maxHeight}{else}300{/if}
        {if $params.typeAhead}
            ,editable: true
            ,typeAhead: true
            ,typeAheadDelay: {if $params.typeAheadDelay && $params.typeAheadDelay != ''}{$params.typeAheadDelay}{else}250{/if}
        {else}
            ,editable: false
            ,typeAhead: false
        {/if}
        {if $params.listEmptyText}
            ,listEmptyText: '{$params.listEmptyText}'
        {/if}
        ,forceSelection: {if $params.forceSelection && $params.forceSelection != 'false'}true{else}false{/if}
        ,initiated: false
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
		,resizable: false
        ,pageSize: 0		
        ,url: {$connector_path}
        ,fields: ['id','name']
        ,displayField: 'name'
        ,valueField: 'id'
        {if $params.valueDelimiter}
            ,valueDelimiter: '{$params.valueDelimiter}'
            ,queryValuesDelimiter: '{$params.valueDelimiter}'
        {/if}        
        ,clearOnRefresh: {if $params.clearOnRefresh == 1 || $params.clearOnRefresh == 'true'}true{else}false{/if} 
        {literal}
        ,baseParams: {
            {/literal}
		    action: '{$action}'
            ,resource_id: '{$resource.id}' 
            ,object_id: '{$object_id}'
            ,tvname: '{{$tv->name}}'
            {literal}
        }
        ,store: new Ext.data.JsonStore({
                        id:'id',
                        autoLoad: true,
                        root:'results',
                        fields: ['name', 'id'],
                        remoteSort: true,
                        url: {/literal}{$connector_path}{literal},
                        baseParams:{
                            action: '{/literal}{$action}{literal}'
                            ,resource_id: '{/literal}{$resource.id}{literal}' 
                            ,object_id : '{/literal}{$object_id}{literal}'
                            ,tvname : '{/literal}{{$tv->name}}{literal}'
                            {/literal}
                                {if $params.valueDelimiter}
                                    ,valueDelimiter: '{$params.valueDelimiter}',
                                {/if}
                            {literal}
                        }
                    }) 
        
        ,listeners: { 
		    'select': {fn:this.selectOption,scope:this}
            ,'change': {fn:this.selectOption,scope:this}
            ,'removeitem': {fn:this.selectOption,scope:this}
            ,'render': {fn:this.initSelect,scope:this}
		}
    });
    MODx.combo.{/literal}{$ddId}{literal}.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.{/literal}{$ddId}{literal},Ext.ux.form.SuperBoxSelect,{
	selectOption: function() {
        this.refreshChildren(true);
        MODx.fireResourceFormChange();	         
	}
    ,reload: function() {
        this.store.load({
            callback: function() {
                if (this.clearOnRefresh){
                    this.setValue('');
                }
                this.refreshChildren(true);
            },scope:this
       });
	}
    ,refreshChildren: function(reload) {
        var ddSelect = null;
        for(i = 0; i <  this.children.length; i++) {
 		    child = this.children[i];
            ddSelect = Ext.getCmp('select_'+child);
            if(typeof(ddSelect) != "undefined"){
                ddSelect.store.baseParams.{/literal}{$ddId}{literal} = this.getValue();
                if (reload){
                    ddSelect.reload();
                }
            }
        }
	}    
    ,initSelect: function() {
        var parent_field = null;
        for(i = 0; i <  this.parents.length; i++) {
 		    parent = this.parents[i];
            parent_field = Ext.get('original'+parent);
            if (parent_field){
                this.store.baseParams[parent] = parent_field.dom.value;
            }
        }
        var original_values = Ext.get('{/literal}original{$ddId}{literal}').dom.value;
        this.setValue(original_values);
        
        // .. won't fire ever
        this.store.load({
            callback: function() {
                this.setValue(Ext.get('{/literal}original{$ddId}{literal}').dom.value);
            },scope:this
       });
	}

});
Ext.reg('modx-combo-{/literal}{$ddId}{literal}',MODx.combo.{/literal}{$ddId}{literal});
Ext.onReady(function() {
    var fld = MODx.load({
        xtype: 'modx-combo-{/literal}{$ddId}{literal}'

    });
    
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
    {/literal}
});
// ]]>
</script>