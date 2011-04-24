<?php 
//******************************************************************************
// facilities.ejs.php
// Description: Facilities Screen
// v0.0.3
// 
// Author: Ernesto J Rodriguez
// Modified: n/a
// 
// MitosEHR (Eletronic Health Records) 2011
//**********************************************************************************
session_name ( "MitosEHR" );
session_start();

include_once("../../../library/I18n/I18n.inc.php");

//**********************************************************************************
// Reset session count 10 secs = 1 Flop
//**********************************************************************************
$_SESSION['site']['flops'] = 0;

?>
<script type="text/javascript">

Ext.onReady(function(){
	//******************************************************************************
	// ExtJS Global variables 
	//******************************************************************************
	var rowPos; // Stores the current Grid Row Position (int)
	var currList; // Stores the current List Option (string)
	var currRec; // Store the current record (Object)

	//******************************************************************************
	// Sanitizing Objects!
	// Destroy them, if already exists in the browser memory.
	// This destructions must be called for all the objects that
	// are rendered on the document.body 
	//******************************************************************************
	if ( Ext.getCmp('winRoles') ){ Ext.getCmp('winRoles').destroy(); }
	if ( Ext.getCmp('winPerms') ){ Ext.getCmp('winPerms').destroy(); }
	//******************************************************************************
	// Roles model
	//******************************************************************************
	var permModel = Ext.regModel('PermissionList', { fields: [
		{name: 'roleID', type: 'int'},
		{name: 'role_name', type: 'string'},
	    {name: 'permID', type: 'string'},
	    {name: 'perm_key', type: 'int'},
	    {name: 'perm_name', type: 'string'},
		{name: 'rolePermID', type: 'int'},
	    {name: 'role_id', type: 'int'},
	    {name: 'perm_id', type: 'int'},
	    {name: 'value', type: 'string'}
	]});

	//******************************************************************************
	// Roles Store
	//******************************************************************************
	var permStore = new Ext.data.Store({
	    model		: 'PermissionList',
		noCache		: true,
    	autoSync	: false,
	    proxy		: {
	    	type	: 'ajax',
			api		: {
				read	: 'interface/administration/roles/data_read.ejs.php',
				create	: 'interface/administration/roles/data_create.ejs.php',
				update	: 'interface/administration/roles/data_update.ejs.php',
				destroy : 'interface/administration/roles/data_destroy.ejs.php'
			},
	        reader: {
	            type			: 'json',
	            idProperty		: 'id',
	            totalProperty	: 'totals',
	            root			: 'row'
	    	},
	    	writer: {
				type	 		: 'json',
				writeAllFields	: true,
				allowSingle	 	: true,
				encode	 		: true,
				root	 		: 'row'
			}
	    },
	    autoLoad: true
	});

	// ****************************************************************************
	// Structure, data for Roles
	// AJAX -> component_data.ejs.php
	// ****************************************************************************
	Ext.regModel('Roles', { fields: [
		{name: 'id', type: 'int'},
	    {name: 'role_name', type: 'string'}
	],
		idProperty: 'id'
	});
	var roleStore = new Ext.data.Store({
		model		: 'Roles',
		proxy		: {
			type	: 'ajax',
			url		: 'interface/administration/roles/component_data.ejs.php?task=roles',
			reader	: {
				type			: 'json',
				idProperty		: 'id',
				totalProperty	: 'totals',
				root			: 'row'
			}
		},
		autoLoad: true
	}); // End storeTitles
	//------------------------------------------------------------------------------
	// When the data is loaded
	// Select the first record
	//------------------------------------------------------------------------------
	roleStore.on('load',function(ds,records,o){
		Ext.getCmp('cmbList').setValue(records[0].data.id);
		currList = records[0].data.id; // Get first result for first grid data
		permStore.load({params:{role_id: currList}}); // Filter the data store from the currList value
	});
	// *************************************************************************************
	// Structure, combo permssions value
	// *************************************************************************************
	Ext.namespace('Ext.data');
	Ext.data.permValues = [
	    ['0', 'No Access'],
	    ['1', 'View / Read'],
	    ['2', 'View / read / Edit']
	];
	var cb_permVales = new Ext.data.ArrayStore({
	    fields: ['cb_id', 'cb_value'],
	    data : Ext.data.permValues
	});

	// ****************************************************************************
	// Create the Role Form
	// ****************************************************************************
    var rolesForm = Ext.create('Ext.form.Panel', {
    	frame		: false,
    	border		: false,
    	id			: 'rolesForm',
        bodyStyle	:'padding:2px',
        fieldDefaults: {
            msgTarget	: 'side',
            labelWidth	: 100
        },
        defaultType	: 'textfield',
        defaults	: { anchor: '100%' },
        items: [{
			xtype		: 'textfield',
			fieldLabel	: '<?php i18n("Role Name"); ?>',
			id			: 'role_name', 
			name		: 'role_name'
		}],

        buttons: [{
            text: 'Save',
            handler: function(){
				//----------------------------------------------------------------
				// Check if it has to add or update
				// Update: 
				// 1. Get the record from store, 
				// 2. get the values from the form, 
				// 3. copy all the 
				// values from the form and push it into the store record.
				// Add: The re-formated record to the dataStore
				//----------------------------------------------------------------
				if (rolesForm.getForm().findField('id').getValue()){ // Update
					var record = permModel.getAt(rowPos);
					var fieldValues = rolesForm.getForm().getValues();
					for ( k=0; k <= record.fields.getCount()-1; k++) {
						i = record.fields.get(k).name;
						record.set( i, fieldValues[i] );
					}
				} else { // Add
					//----------------------------------------------------------------
					// 1. Convert the form data into a JSON data Object
					// 2. Re-format the Object to be a valid record (UserRecord)
					// 3. Add the new record to the datastore
					//----------------------------------------------------------------
					var obj = eval( '(' + Ext.JSON.encode(rolesForm.getForm().getValues()) + ')' );
					var rec = new pernModel(obj);
					permStore.add( rec );
				}
				permStore.save();          // Save the record to the dataStore
				winRoles.hide();				// Finally hide the dialog window
				permStore.load();			// Reload the dataSore from the database
			}
        },{
            text: 'Cancel',
            handler: function(){
            	winRoles.hide();
            }
        }]
    });
	// ****************************************************************************
	// Create the Permisions Form
	// ****************************************************************************
    var permsForm = Ext.create('Ext.form.Panel', {
    	frame		: false,
    	border		: false,
    	id			: 'permsForm',
        bodyStyle	:'padding:2px',
        fieldDefaults: {
            msgTarget	: 'side',
            labelWidth	: 100
        },
        defaultType	: 'textfield',
        defaults	: { anchor: '100%' },
        items: [{
			xtype		: 'textfield',
			fieldLabel	: '<?php i18n("Permission Name"); ?>',
			id			: 'perm_name', 
			name		: 'perm_name'
		},{
			xtype		: 'textfield',
			fieldLabel	: '<?php i18n("Permission Unique Name"); ?>',
			id			: 'perm_key', 
			name		: 'perm_key'
		}],

        buttons: [{
            text: 'Save',
            handler: function(){
				//----------------------------------------------------------------
				// Check if it has to add or update
				// Update: 
				// 1. Get the record from store, 
				// 2. get the values from the form, 
				// 3. copy all the 
				// values from the form and push it into the store record.
				// Add: The re-formated record to the dataStore
				//----------------------------------------------------------------
				if (permsForm.getForm().findField('id').getValue()){ // Update
					var record = permModel.getAt(rowPos);
					var fieldValues = permsForm.getForm().getValues();
					for ( k=0; k <= record.fields.getCount()-1; k++) {
						i = record.fields.get(k).name;
						record.set( i, fieldValues[i] );
					}
				} else { // Add
					//----------------------------------------------------------------
					// 1. Convert the form data into a JSON data Object
					// 2. Re-format the Object to be a valid record (UserRecord)
					// 3. Add the new record to the datastore
					//----------------------------------------------------------------
					var obj = eval( '(' + Ext.JSON.encode(permsForm.getForm().getValues()) + ')' );
					var rec = new pernModel(obj);
					permStore.add( rec );
				}
				permStore.save();          // Save the record to the dataStore
				winPerms.hide();				// Finally hide the dialog window
				permStore.load();			// Reload the dataSore from the database
			}
        },{
            text: 'Cancel',
            handler: function(){
            	winPerms.hide();
            }
        }]
    });

	// ****************************************************************************
	// Create the Window
	// ****************************************************************************	
	var winRoles = Ext.create('widget.window', {
		id			: 'winRoles',
		closable	: true,
		closeAction	: 'hide',
		width		: 450,
		resizable	: false,
		modal		: true,
		bodyStyle	: 'background-color: #ffffff; padding: 5px;',
		items		: [ rolesForm ]
	});
	// ****************************************************************************
	// Create the Window
	// ****************************************************************************	
	var winPerms = Ext.create('widget.window', {
		id			: 'winPerms',
		closable	: true,
		closeAction	: 'hide',
		width		: 450,
		resizable	: false,
		modal		: true,
		bodyStyle	: 'background-color: #ffffff; padding: 5px;',
		items		: [ permsForm ]
	});

	// *************************************************************************************
	// RowEditor Class
	// *************************************************************************************
	var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
		saveText: 'Update',
		errorSummary: false,
		listeners:{
			afteredit: function(roweditor, changes, record, rowIndex){
				storeListsOption.save();
				storeListsOption.commitChanges();
				storeListsOption.reload();
			}
		}
	});
	// ****************************************************************************
	// Create the GridPanel
	// ****************************************************************************
	var rolesGrid = Ext.create('Ext.grid.Panel', {
		store		: permStore,
        columnLines	: true,
        frame		: false,
        frameHeader	: false,
        border		: false,
        layout		: 'fit',
		plugins: [rowEditing],
        columns: [
			{
				text     	: '<?php i18n("Secction Area"); ?>',
				flex     	: 1,
				sortable 	: true,
				dataIndex	: 'perm_name'
            },{
				header		: '<?php i18n("Access Control / Permision"); ?>',
	            dataIndex	: 'value',
				flex     	: 1,
	            field: {
	                xtype			: 'combo',
	                typeAhead		: true,
	                triggerAction	: 'all',
					store: [
						['0','No Access'],
	                    ['1','View / Read'],
	                    ['2','View / Read / Edit']
					],
				},
                lazyRender: true,
                listClass: 'x-combo-list-small'
            }
		],
		viewConfig: { stripeRows: true },
		listeners: {
			itemclick: {
            	fn: function(DataView, record, item, rowIndex, e){ 
            		Ext.getCmp('rolesForm').getForm().reset(); // Clear the form
            		Ext.getCmp('cmdEdit').enable();
            		Ext.getCmp('cmdDelete').enable();
					var rec = permModel.getAt(rowIndex);
					Ext.getCmp('rolesForm').getForm().loadRecord(rec);
            		currRec = rec;
            		rowPos = rowIndex;
            	}
			},
			itemdblclick: {
            	fn: function(DataView, record, item, rowIndex, e){ 
            		Ext.getCmp('rolesForm').getForm().reset(); // Clear the form
            		Ext.getCmp('cmdEdit').enable();
            		Ext.getCmp('cmdDelete').enable();
					var rec = permModel.getAt(rowIndex);
					Ext.getCmp('rolesForm').getForm().loadRecord(rec);
            		currRec = rec;
            		rowPos = rowIndex;
            		winFacility.setTitle('<?php i18n("Edit Role Permision"); ?>');
            		winFacility.show();
            	}
			}
		},
		listeners:{
			cellclick: function(DataView, record, item, rowIndex, columnIndex, e){
				currRec = permStore.getAt(rowIndex); // Copy the record to the global variable
			}
		},
		dockedItems: [{
			xtype	: 'toolbar',
			dock	: 'top',
			items: [{
				text	: '<?php i18n("Add a Role"); ?>',
				iconCls	:'icoAddRecord',
				handler	: function(){
					Ext.getCmp('rolesForm').getForm().reset(); // Clear the form
					winRoles.show();
					winRoles.setTitle('<?php i18n("Add a Role"); ?>'); 
				}
			},'-',{
				text	: '<?php i18n("Add a Permission"); ?>',
				iconCls	:'icoAddRecord',
				handler	: function(){
					Ext.getCmp('permsForm').getForm().reset(); // Clear the form
					winPerms.show();
					winPerms.setTitle('<?php i18n("Add a Permission"); ?>'); 
				}

		  	},'-','<?php i18n('Select Role'); ?>: ',{
				name			: 'cmbList', 
				width			: 250,
				xtype			: 'combo',
				displayField	: 'role_name',
				id				: 'cmbList',
				mode			: 'local',
				triggerAction	: 'all', 
				hiddenName		: 'id',
				valueField		: 'id',
				ref				: '../cmbList',
				iconCls			: 'icoListOptions',
				editable		: false,
				store			: roleStore,
				listeners: {
					select: function(combo, record){
						// Reload the data store to reflect the new selected list filter
						permStore.load({params:{role_id: record[0].data.id }});
					}
				}
						},'-',{
				text		: '<?php i18n("Edit a Role"); ?>',
				iconCls		: 'edit',
				id			: 'cmdEdit',
				disabled	: true,
				handler		: function(){
      				winRoles.setTitle('<?php i18n("Edit a Role"); ?>');
					winRoles.show(); 
				}
			},'-',{
				text		: '<?php i18n("Delete Role"); ?>',
				iconCls		: 'delete',
				disabled	: true,
				id			: 'cmdDelete',
				handler: function(){
					Ext.Msg.show({
						title	: '<?php i18n('Please confirm...'); ?>', 
						icon	: Ext.MessageBox.QUESTION,
						msg		:'<?php i18n('Are you sure to delete this Role?'); ?>',
						buttons	: Ext.Msg.YESNO,
						fn		:function(btn,msgGrid){
								if(btn=='yes'){
								rolesStore.remove( currRec );
								rolesStore.save();
								rolesStore.load();
			    		    }
						}
					});
				}
			}]
		}]
    }); // END Facility Grid

	//******************************************************************************
	// Render panel
	//******************************************************************************
	var topRenderPanel = Ext.create('Ext.panel.Panel', {
		title		: '<?php i18n('Roles and Permissions'); ?>',
		renderTo	: Ext.getCmp('MainApp').body,
		layout		: 'fit',
		height		: Ext.getCmp('MainApp').getHeight(),
	  	frame 		: false,
		border 		: false,
		id			: 'topRenderPanel',
		items		: [	rolesGrid ]
	});
}); // End ExtJS
</script>