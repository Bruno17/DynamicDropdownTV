<input type="hidden" id="original{$dddx_id}" name="original{$tv->id}" value="{$tv->get('value')|escape}" />

<div id="div_{$dddx_id}">

</div>


<script type="text/javascript">
// <![CDATA[
{literal}

MODx.combo.{/literal}{$dddx_id}{literal} = function(config) {
    config = config || {};
    Ext.applyIf(config,{{/literal}
        name: '{$dddx_id}'
        ,triggerAction: 'all'
        ,id: 'select_{$dddx_id}'
        ,width: 400
        ,hiddenName: 'tv{$tv->id}'
        ,renderTo: 'div_{$dddx_id}'
        ,mode: 'remote'
        ,children: Ext.util.JSON.decode('{$children}')
        ,parents: Ext.util.JSON.decode('{$parents}')          
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
        ,url: MODx.config.assets_url+'components/dddx/connector.php'
        ,fields: ['id','name']
        ,displayField: 'name'
        ,valueField: 'id'
        {if $params.valueDelimiter}
            ,valueDelimiter: '{$params.valueDelimiter}'
        {/if}        
        ,clearOnRefresh: {if $params.clearOnRefresh == 1 || $params.clearOnRefresh == 'true'}true{else}false{/if} 
        {literal}
        ,baseParams: {
		    action: '{/literal}{$action}{literal}'
            ,resource_id: '{/literal}{$resource.id}{literal}' 
            ,object_id : '{/literal}{$object_id}{literal}'
            ,tvname : '{/literal}{{$tv->name}}{literal}' 	 	            
        }
        ,store: new Ext.data.JsonStore({
                        id:'id',
                        autoLoad: true,
                        root:'results',
                        fields: ['name', 'id'],
                        remoteSort: true,
                        url: MODx.config.assets_url+'components/dddx/connector.php',
                        baseParams:{
                            action: '{/literal}{$action}{literal}'
                            ,resource_id: '{/literal}{$resource.id}{literal}' 
                            ,object_id : '{/literal}{$object_id}{literal}'
                            ,tvname : '{/literal}{{$tv->name}}{literal}' 	                            
                        },
                        listeners: {
                            'load': {fn:function(store, records, options ) {
                                //this.hiddenName = config.paramHiddenName;
                                //this.setWidth('350');
                                }
                            },scope : this                        
                        }   
                    }) 
        
        ,listeners: { 
		    'select': {fn:this.selectOption,scope:this}
            ,'render': {fn:this.initSelect,scope:this}
		}
    });
    MODx.combo.{/literal}{$dddx_id}{literal}.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.{/literal}{$dddx_id}{literal},Ext.ux.form.SuperBoxSelect,{
	selectOption: function() {
        this.refreshChildren(true);
        MODx.fireResourceFormChange();	         
        
	}
    ,reload: function() {
        console.log(this);
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
        var dddx_select = null;
        for(i = 0; i <  this.children.length; i++) {
 		    child = this.children[i];
            dddx_select = Ext.getCmp('select_'+child);
            if(typeof(dddx_select) != "undefined"){
                dddx_select.baseParams.{/literal}{$dddx_id}{literal} = this.getValue();
                if (reload){
                    dddx_select.reload();
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
        this.store.load({
            callback: function() {
                this.setValue(Ext.get('{/literal}original{$dddx_id}{literal}').dom.value);
            },scope:this
       });
	}

});
Ext.reg('modx-combo-{/literal}{$dddx_id}{literal}',MODx.combo.{/literal}{$dddx_id}{literal});
Ext.onReady(function() {
    var fld = MODx.load({
        xtype: 'modx-combo-{/literal}{$dddx_id}{literal}'

    });
    
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
    {/literal}
});
// ]]>
</script>