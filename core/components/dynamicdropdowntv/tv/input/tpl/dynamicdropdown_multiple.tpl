<input type="hidden" id="original{$ddId}" name="original{$tv->id}" value="{$tv->get('value')|escape}" />


<div id="div_{$ddId}">
</div>

<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var selectElement = Ext.get('tv{/literal}{$tv->id}{literal}');

    // Create SuperBoxSelect with remote store
    var fld = new Ext.ux.form.SuperBoxSelect({{/literal}
        id: 'select_{$ddId}'
        ,name: '{$ddId}'
        ,hiddenName: 'tv{$tv->id}[]'
        ,renderTo: 'div_{$ddId}'
        ,triggerAction: 'all'
        ,mode: 'remote'
        ,extraItemCls: 'x-tag'
        ,expandBtnCls: 'x-form-trigger'
        ,clearBtnCls: 'x-form-trigger'
        ,width: 400
        ,displayField: 'name'
        ,valueField: 'id'
        ,resizable: true
        {if $params.allowBlank == 1 || $params.allowBlank == 'true'}
        ,allowBlank: true
        {else}
        ,allowBlank: false
        {/if}
        {if $params.title}
        ,title: '{$params.title}'
        {/if}
        {if $params.listWidth}
        ,listWidth: {$params.listWidth}
        {/if}
        ,maxHeight: {if $params.maxHeight}{$params.maxHeight}{else}300{/if}
        {if $params.typeAhead}
        ,typeAhead: true
        ,typeAheadDelay: {if $params.typeAheadDelay && $params.typeAheadDelay != ''}{$params.typeAheadDelay}{else}250{/if}
        ,editable: true
        {else}
        ,typeAhead: false
        {/if}
        {if $params.listEmptyText}
        ,listEmptyText: '{$params.listEmptyText}'
        {/if}
        ,allowAddNewData: {if $params.forceSelection && $params.forceSelection != 'false'}false{else}true{/if}
        ,addNewDataOnBlur: true
        {if $params.valueDelimiter}
        ,valueDelimiter: '{$params.valueDelimiter}'
        {else}
        ,valueDelimiter: ','
        {/if}
        ,clearOnRefresh: {if $params.clearOnRefresh == 1 || $params.clearOnRefresh == 'true'}true{else}false{/if}
        ,stackItems: true
        ,msgTarget: 'under'
        ,forceFormValue: false
        ,children: Ext.util.JSON.decode('{$children}')
        ,parents: Ext.util.JSON.decode('{$parents}')
        {literal}
        ,baseParams: {
            action: '{/literal}{$action}{literal}',
            resource_id: '{/literal}{$resource.id}{literal}',
            object_id: '{/literal}{$object_id}{literal}',
            tvname: '{/literal}{$tv->name}{literal}'
        }
        ,store: new Ext.data.JsonStore({
            id: 'id',
            autoLoad: false,
            root: 'results',
            fields: ['name', 'id'],
            remoteSort: true,
            url: {/literal}{$connector_path}{literal},
            baseParams: {
                action: '{/literal}{$action}{literal}'
                ,resource_id: '{/literal}{$resource.id}{literal}'
                ,object_id: '{/literal}{$object_id}{literal}'
                ,tvname: '{/literal}{$tv->name}{literal}'
            },
            listeners: {
                'load': {
                    fn: function(store, records, options) {
                        // Force refresh dropdown after data load
                        if (this.rendered) {
                            this.doLayout();
                        }
                    },
                    scope: this
                }
            }
        })
        ,listeners: {
            'select': {
                fn: function(comp) {
                    comp.refreshChildren(true);
                    MODx.fireResourceFormChange();
                }
            }
            ,'render': {
                fn: function(comp) {
                    // Copy exact initSelect logic from working template
                    var parent_field = null;
                    var parents = comp.parents || [];
                    for(var i = 0; i < parents.length; i++) {
                        var parent = parents[i];
                        parent_field = Ext.get('original' + parent);
                        if (parent_field) {
                            // Update both baseParams like in working template
                            comp.baseParams[parent] = parent_field.dom.value;
                            comp.store.baseParams[parent] = parent_field.dom.value;
                        }
                    }
                    comp.store.load({
                        callback: function() {
                            var originalValue = Ext.get('{/literal}original{$ddId}{literal}').dom.value;
                            if (originalValue) {
                                var values = originalValue.split(comp.valueDelimiter || ',');
                                comp.setValue(values);
                            }
                        },
                        scope: comp
                    });
                }
            }
            ,'beforeadditem': {fn: MODx.fireResourceFormChange, scope: this}
            ,'newitem': {fn: function(bs,v,f) {
                var item = {};
                item[bs.valueField] = v;
                item[bs.displayField] = v;
                bs.addNewItem(item);
                MODx.fireResourceFormChange();
                return true;
            }, scope: this}
            ,'beforeremoveitem': {fn: MODx.fireResourceFormChange, scope: this}
            ,'clear': {fn: MODx.fireResourceFormChange, scope: this}
        }
        ,reload: function() {
            this.store.load({
                callback: function() {
                    if (this.clearOnRefresh) {
                        this.setValue('');
                    }
                    this.refreshChildren(true);
                },
                scope: this
            });
        }
        ,refreshChildren: function(reload) {
            var ddSelect = null;
            for(var i = 0; i < this.children.length; i++) {
                var child = this.children[i];
                ddSelect = Ext.getCmp('select_' + child);
                if(typeof(ddSelect) != "undefined" && ddSelect !== null) {
                    if (ddSelect.store && ddSelect.store.baseParams) {
                        ddSelect.store.baseParams.{/literal}{$ddId}{literal} = this.getValue();
                    }
                    if (reload && typeof ddSelect.reload === 'function') {
                        ddSelect.reload();
                    }
                }
            }
        }
    });

    // Register component and add to form
    Ext.reg('modx-combo-{/literal}{$ddId}{literal}', Ext.ux.form.SuperBoxSelect);
    Ext.getCmp('modx-panel-resource').getForm().add(fld);

    // Create a refresh function that updates all parent parameters (like initSelect in working template)
    fld.refreshFromParents = function() {
        var parent_field = null;
        var parentComponent = null;
        var params = {};
        for(var j = 0; j < this.parents.length; j++) {
            var parent = this.parents[j];

            // Try to get value from parent component first
            parentComponent = Ext.getCmp('select_' + parent);
            if (parentComponent && typeof parentComponent.getValue === 'function') {
                var value = parentComponent.getValue();
                this.baseParams[parent] = value;
                this.store.baseParams[parent] = value;
                params[parent] = value;
            } else {
                // Fallback to original field
                parent_field = Ext.get('original' + parent);
                if (parent_field) {
                    var value = parent_field.dom.value;
                    this.baseParams[parent] = value;
                    this.store.baseParams[parent] = value;
                    params[parent] = value;
                }
            }
        }
        this.setValue(''); // Clear current selection
        this.store.load();
    };

    // Setup parent field listeners for cascading updates
    var parents = fld.parents || [];
    for(var i = 0; i < parents.length; i++) {
        var parentName = parents[i];
        var parentCmp = Ext.getCmp('select_' + parentName);
        if (parentCmp) {
            parentCmp.on('select', function() {
                fld.refreshFromParents();
            });
        }
    }

    // Override refreshChildren to work exactly like in working template
    fld.refreshChildren = function(reload) {
        var ddSelect = null;
        for(var i = 0; i < this.children.length; i++) {
            var child = this.children[i];
            ddSelect = Ext.getCmp('select_' + child);
            if(typeof(ddSelect) != "undefined") {
                // Copy exact logic from working template
                ddSelect.baseParams.{/literal}{$ddId}{literal} = this.getValue();
                ddSelect.store.baseParams.{/literal}{$ddId}{literal} = this.getValue();
                if (reload) {
                    ddSelect.reload();
                }
            }
        }
    };

    // Ensure getValue() returns correct format for legacy components
    var originalGetValue = fld.getValue;
    fld.getValue = function() {
        var value = originalGetValue.call(this);
        if (Ext.isArray(value)) {
            return value.join(this.valueDelimiter || ',');
        }
        return value;
    };

    // Global listener for any field changes that might affect us
    Ext.getCmp('modx-panel-resource').on('actioncomplete', function(form, action) {
        if (action.type === 'load') {
            // Form loaded, refresh our field
            setTimeout(function() {
                fld.refreshFromParents();
            }, 100);
        }
    });

    // Listen for changes in original fields (hidden fields that store values)
    var lastParentValues = {};
    var checkParentChanges = function() {
        var hasChanges = false;
        for(var j = 0; j < fld.parents.length; j++) {
            var parent = fld.parents[j];
            var parent_field = Ext.get('original' + parent);
            if (parent_field) {
                var currentValue = parent_field.dom.value;
                var lastValue = lastParentValues[parent];
                if (currentValue !== lastValue) {
                    lastParentValues[parent] = currentValue;
                    hasChanges = true;
                }
            }
        }
        if (hasChanges) {
            fld.refreshFromParents();
        }
    };

    // Initialize last values
    for(var j = 0; j < fld.parents.length; j++) {
        var parent = fld.parents[j];
        var parent_field = Ext.get('original' + parent);
        if (parent_field) {
            lastParentValues[parent] = parent_field.dom.value;
        }
    }

    // Check for parent changes periodically (fallback for legacy components)
    var intervalId = setInterval(checkParentChanges, 500);

    // Initialize parent values at startup (like initSelect in working template)
    var parent_field = null;
    for(var j = 0; j < fld.parents.length; j++) {
        var parent = fld.parents[j];
        parent_field = Ext.get('original' + parent);
        if (parent_field) {
            fld.baseParams[parent] = parent_field.dom.value;
        }
    }

    fld.on('destroy', function() {
        fld.baseParams = null;
        if (intervalId) {
            clearInterval(intervalId);
        }
    });
});
{/literal}
// ]]>
</script>
