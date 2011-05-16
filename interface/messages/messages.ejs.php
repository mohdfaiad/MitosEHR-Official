<?php

//--------------------------------------------------------------------------------------------------------------------------
// messages.ejs.php 
// v0.0.5
// Under GPLv3 License
// 
// Integrated by: GI Technologies & MitosEHR.org in 2011
// 
//--------------------------------------------------------------------------------------------------------------------------

session_name ( "MitosEHR" );
session_start();
session_cache_limiter('private');

include_once("../../library/I18n/I18n.inc.php");

//******************************************************************************
// Reset session count 10 secs = 1 Flop
//******************************************************************************
$_SESSION['site']['flops'] = 0;
?>

<script type="text/javascript">
Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext.ux', '<?php echo $_SESSION['dir']['ux']; ?>');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.state.*',
    'Ext.toolbar.Paging',
    'Ext.ux.SlidingPager'
]);

// *************************************************************************************
// Start Sencha Framework
// *************************************************************************************
Ext.onReady(function() {

//******************************************************************************
// Sanitizing Objects
// Destroy them, if already exists in the browser memory.
// This destructions must be called for all the objects that
// are rendered on the document.body 
//******************************************************************************
if ( Ext.getCmp('winPatients') ){ Ext.getCmp('winPatients').destroy(); }
if ( Ext.getCmp('winMessage') ){ Ext.getCmp('winMessage').destroy(); }

// *************************************************************************************
// Global variables
// *************************************************************************************
var rowContent;
var body_content;

body_content = '<?php i18n('Nothing posted yet...'); ?>';

// *************************************************************************************
// Structure of the message record
// creates a subclass of Ext.data.Record
//
// This should be the structure of the database table
// 
// *************************************************************************************
Ext.define("Messages", {
	extend: "Ext.data.Model", 
	fields: [
	{name: 'noteid',	type: 'int'},
	{name: 'user',		type: 'string'},
	{name: 'subject',   type: 'string'},
	{name: 'body',		type: 'string'},
	{name: 'from',	  	type: 'string'},
	{name: 'patient',  	type: 'string'},
	{name: 'type',	  	type: 'string'},
	{name: 'date',	  	type: 'string'},
	{name: 'status',   	type: 'string'},
	{name: 'reply_id',  type: 'int'},
	{name: 'reply_to',	type: 'int'}
]});

// *************************************************************************************
// Structure and load the data for Messages
// AJAX -> data_*.ejs.php
// *************************************************************************************
var storeMsgs = new Ext.data.Store({
	model: 'Messages',
  	noCache		: true,
   	autoSync	: false,
   	proxy		: {
   		type	: 'ajax',
		api		: {
			read	: 'interface/messages/data_read.ejs.php',
			create	: 'interface/messages/data_create.ejs.php',
			update	: 'interface/messages/data_update.ejs.php',
			destroy : 'interface/messages/data_destroy.ejs.php'
		},
       	reader: {
            type			: 'json',
   	        idProperty		: 'idusers',
       	    totalProperty	: 'totals',
           	root			: 'row'
   		},
   		writer: {
   			type			: 'json',
   			writeAllFields	: true,
   			allowSingle		: false,
   			encode			: true,
   			root			: 'row'
   		}
   	},
   	autoLoad: true
});

// *************************************************************************************
// Structure and load the data for cmb_toUsers
// AJAX -> component_data.ejs.php
// *************************************************************************************
Ext.define("Patients", {
	extend: "Ext.data.Model", 
	fields: [
	{name: 'id',    type: 'int'},
	{name: 'name',  type: 'string'},
	{name: 'phone', type: 'string'},
	{name: 'ss',    type: 'string'},
	{name: 'dob',   type: 'string'},
	{name: 'pid',   type: 'string'}
]});
var storePat = new Ext.data.Store({
   	model		: 'Patients',
   	proxy		: {
   		type	: 'ajax',
		api		: {
			read	: 'interface/messages/component_data.ejs.php?task=patients'
		},
  	   	reader: {
      	    type			: 'json',
        	idProperty		: 'id',
       	    totalProperty	: 'totals',
  	       	root			: 'row'
		}
	},
  	autoLoad: true
});// End storePat

// *************************************************************************************
// Structure and load the data for cmb_toUsers
// AJAX -> component_data.ejs.php
// *************************************************************************************
Ext.define("User", {
	extend: "Ext.data.Model", 
	fields: [
	{name: 'user',      type: 'string' },
	{name: 'full_name', type: 'string' }
]});
var toData = new Ext.data.Store({
   	model		: 'User',
   	proxy		: {
   		type	: 'ajax',
		api		: {
			read	: 'interface/messages/component_data.ejs.php?task=users'
		},
  	   	reader: {
      	    type			: 'json',
        	idProperty		: 'id',
       	    totalProperty	: 'totals',
  	       	root			: 'row'
		}
	},
  	autoLoad: true
}); // End toData

// *************************************************************************************
// Structure, data for cmb_Type
// AJAX -> component_data.ejs.php
// *************************************************************************************
Ext.define("Types", {
	extend: "Ext.data.Model", 
	fields: [
	{name: 'option_id', type: 'string' },
	{name: 'title',     type: 'string' }
]});
var typeData = new Ext.data.Store({
   	model		: 'Types',
   	proxy		: {
   		type	: 'ajax',
		api		: {
			read	: 'interface/messages/component_data.ejs.php?task=types'
		},
  	   	reader: {
      	    type			: 'json',
        	idProperty		: 'id',
       	    totalProperty	: 'totals',
  	       	root			: 'row'
		}
	},
  	autoLoad: true
}); // End typeData

// *************************************************************************************
// Structure, data for cmb_Status
// AJAX -> component_data.ejs.php
// *************************************************************************************
Ext.define("Status", {extend: "Ext.data.Model", fields: [
	{name: 'option_id', type: 'string' },
	{name: 'title',     type: 'string' }
]});
var statusData = new Ext.data.Store({
   	model		: 'Status',
   	proxy		: {
   		type	: 'ajax',
		api		: {
			read	: 'interface/messages/component_data.ejs.php?task=status'
		},
  	   	reader: {
      	    type			: 'json',
        	idProperty		: 'id',
       	    totalProperty	: 'totals',
  	       	root			: 'row'
		}
	},
  	autoLoad: true
}); // End statusData

// *************************************************************************************
// Patient Select Dialog
// *************************************************************************************
var winPatients = new Ext.create('Ext.window.Window', {
	width			: 900,
	height			: 400,
	modal			: true,
	resizable		: true,
	autoScroll		: true,
	title		    :	'<?php i18n('Patients'); ?>',
	closeAction		: 'hide',
	renderTo		: document.body,
	items: [{
			xtype		    : 'grid',
			name		    : 'gridPatients',
			autoHeight	: true,
			store		    : storePat,
			//stripeRows	: true,
			frame		    : false,
			viewConfig	: {forceFit: true, stripeRows: true,}, // force the grid to the width of the containing panel
			//sm			    : new Ext.grid.RowSelectionModel({singleSelect:true}),
			listeners: {
				
				// Single click to select the record, and copy the variables
				rowclick: function(grid, rowIndex, e) {
					
					// Get the content from the data grid
					rowContent = grid.getStore().getAt(rowIndex);
					
					// Enable the select button
					winPatients.patSelect.enable();
				}

			},
			columns: [
				{header: 'id', sortable: false, dataIndex: 'id', hidden: true},
				{ header: '<?php i18n('Name'); ?>', sortable: true, dataIndex: 'name' },
				{ header: '<?php i18n('Phone'); ?>', sortable: true, dataIndex: 'phone'},
				{ header: '<?php i18n('SS'); ?>', sortable: true, dataIndex: 'ss' },
				{ header: '<?php i18n('DOB'); ?>', sortable: true, dataIndex: 'dob' },
				{ header: '<?php i18n('PID'); ?>', sortable: true, dataIndex: 'pid' }
			],
			tbar:[],
	}],

	// Window Bottom Bar
	bbar:[{
		text		:'<?php i18n('Select'); ?>',
		iconCls		: 'select',
		ref			  : '../patSelect',
		formBind	: true,
		disabled	: true,
		handler: function() {
			winMessage.reply_to.setValue( rowContent.get('id') );
			winMessage.patient_name.setText( rowContent.get('name') );
			winMessage.send.enable();
			winPatients.hide();
		}
	},{
		text		  : '<?php i18n('Close'); ?>',
		iconCls		: 'delete',
		ref			  : '../patClose',
		formBind	: true,
		handler		: function(){ winPatients.hide(); }
	}]

}); // END WINDOW

// *************************************************************************************
// Previuos Messages Panel
// *************************************************************************************
var prvMsg = new Ext.create('Ext.panel.Panel', {
	title			: '<?php i18n('Reply History'); ?>',
	labelWidth		: 100,
	minSize			: 300,
	height			: 200,
	region			: 'north',
	bodyStyle		: 'padding: 5px;',
	autoScroll		: true,
	border			: false,
	animCollapse	: false,
	collapsible		: true,
	titleCollapse	: true,
	split			: true,
	html			: '<div id=\'previousMsg\' class="prvMsg">' + body_content + '</div>',
	listeners: {
		collapse: function() { winMessage.syncShadow(); },
		expand: function(){ winMessage.syncShadow(); }
	}
});

// *************************************************************************************
// Message Form
// *************************************************************************************
var formMessage = new Ext.create('Ext.form.Panel', {
	id			: 'formMessage',
	frame		: false,
	bodyStyle	: 'padding: 5px',
	defaults	: {labelWidth: 75, anchor: '100%'},
	items: [{ 
		xtype: 'button', 
		id: 'pid',
		text: '<?php i18n('Click to select patient...'); ?>',
		fieldLabel: '<?php i18n('Patient'); ?>',
		name: 'pid',
		editable: false,
		height: 30,
		margin: '5px',
		handler: function(){ winPatients.show(); }
	},{ 
		xtype: 'combo', 
		id: 'assigned_to',
		name: 'assigned_to',
		fieldLabel: '<?php i18n('To'); ?>',
		editable: false,
		triggerAction: 'all',
		mode: 'local',
		valueField: 'user',
		hiddenName: 'assigned_to',
		displayField: 'full_name',
		store: toData
	},{ 
		xtype: 'combo', 
		value: 'Unassigned',
		id: 'note_type',
		name: 'note_type',
		fieldLabel: '<?php i18n('Type'); ?>',
		editable: false,
		triggerAction: 'all',
		mode: 'local',
		valueField: 'option_id',
		hiddenName: 'option_id',
		displayField: 'title',
		store: typeData
	},{ 
		xtype: 'combo', 
		value: 'New',
		id: 'message_status',
		name: 'message_status',
		fieldLabel: '<?php i18n('Status'); ?>',
		editable: false,
		triggerAction: 'all',
		mode: 'local',
		valueField: 'option_id',
		hiddenName: 'option_id',
		displayField: 'title',
		store: statusData
	},{ 
		xtype: 'textfield', 
		fieldLabel: '<?php i18n('Subject'); ?>',
        id: 'subject',
        name: 'subject'
	},{ 
		xtype: 'htmleditor', 
		id: 'body',
		name: 'body',
		labelWidth  : 0,
		height: 130
	},{ 
		xtype: 'textfield', 
		id: 'id',
		hidden: true,
		name: 'id',
		value: ''
	},{ 
		xtype: 'textfield',
        id: 'reply_id',
        hidden: true,
        name: 'reply_id'
	},
//		prvMsg // prvMsg Object 
	]
});


// *************************************************************************************
// Message Window Dialog
// *************************************************************************************
var winMessage = new Ext.create('Ext.window.Window', {
	width		: 640,
	autoHeight	: true,
	modal		: true,
	resizable	: false,
	autoScroll	: true,
	id			: 'winMessage',
	title		: '<?php i18n('Compose Message'); ?>',
	closeAction	: 'hide',
	renderTo	: document.body,
	items: [formMessage],
	// Window Bottom Bar
	bbar:[{
		text		:'<?php i18n('Send'); ?>',
		ref			: '../send',
		iconCls		: 'save',
		disabled	: true,
		handler: function() { 
			// The datastore object will save the data
			// as soon changes is detected on the datastore
			// It's a AJAX thing
			if(Ext.getCmp("id").getValue()){ // Update message
				var record = storeMsgs.getAt(rowPos);
				var fieldValues = formMessage.getForm().getValues();
				for ( k=0; k <= record.fields.getCount()-1; k++) {
					i = record.fields.get(k).name;
					record.set( i, fieldValues[i] );
				}
			} else {							// New message
				//----------------------------------------------------------------
				// 1. Convert the form data into a JSON data Object
				// 2. Re-format the Object to be a valid record (UserRecord)
				// 3. Add the new record to the datastore
				//----------------------------------------------------------------
				var obj = eval( '(' + Ext.JSON.encode(formMessage.getForm().getValues()) + ')' );
				var rec = new usersRecord(obj);
				storeMsgs.add( rec );
			}
			winMessage.hide();	// Finally hide the dialog window
			storeMsgs.sync();	// Save the record to the dataStore
			storeMsgs.load();	// Reload the dataSore from the database
		}
	},{
		text:'<?php i18n('Close'); ?>',
		iconCls: 'delete',
		handler: function(){ winMessage.hide(); }
	}]
}); // END WINDOW

// *************************************************************************************
// Create the GridPanel
// *************************************************************************************
var msgGrid = new Ext.create('Ext.grid.Panel', {
	id			: 'msgGrid',
	store		: storeMsgs,
	autoHeight 	: true,
	border     	: false,
	frame		: false,
	loadMask    : true,
	viewConfig 	: {forceFit: true, stripeRows : true},
	listeners: {
	
		// Single click to select the record, and copy the variables
		rowclick: function(msgGrid, rowIndex, e) {
		
			//Copy the selected message ID into the variable
			rowContent = Ext.getCmp('msgGrid').getStore().getAt(rowIndex);
				
			// Enable buttons
			msgGrid.editMsg.enable();
			msgGrid.delMsg.enable();
		},

		// Double click to select the record, and edit the record
		rowdblclick:  function(msgGrid, rowIndex, e) {
				
			//Copy the selected message ID into the variable
			rowContent = Ext.getCmp('msgGrid').getStore().getAt(rowIndex);
				
			// Set the buttons state
			winMessage.cmb_assigned_to.readOnly = true;
			winMessage.patient_name.disable();
			winMessage.send.enable();
				
			winMessage.show();
		}
	},
	columns: [
		{ header: 'noteid', sortable: false, dataIndex: 'noteid', hidden: true},
		{ header: 'reply_id', sortable: false, dataIndex: 'reply_id', hidden: true},
		{ header: 'user', sortable: false, dataIndex: 'user', hidden: true},
		{ header: 'body', sortable: true, dataIndex: 'body', hidden: true },
		{ header: '<?php i18n('Subject'); ?>', sortable: true, dataIndex: 'subject', id: 'subject' },
		{ width: 200, header: '<?php i18n('From'); ?>', sortable: true, dataIndex: 'from' },
		{ header: '<?php i18n('Patient'); ?>', sortable: true, dataIndex: 'patient' },
		{ header: '<?php i18n('Type'); ?>', sortable: true, dataIndex: 'type' },
		{ header: '<?php i18n('Date'); ?>', sortable: true, dataIndex: 'date' }, 
		{ header: '<?php i18n('Status'); ?>', sortable: true, dataIndex: 'status' },
	],

	// *************************************************************************************
	// Grid Menu
	// *************************************************************************************
	tbar: [{
		xtype	:'button',
		id		: 'sendMsg',
		text	: '<?php i18n("Send message", 'e'); ?>',
		iconCls	: 'newMessage',
		handler: function(){
			
			// Clear the rowContent variable
			rowContent = null;
			winMessage.show();
		}
	},'-',{
		xtype	   :'button',
		id		   : 'editMsg',
		ref		   : '../editMsg',
		text	   : '<?php i18n('Reply message'); ?>',
		iconCls	 : 'edit',
		disabled : true,
		handler  : function(){ 
		
			// Set the buttons state
			winMessage.cmb_assigned_to.readOnly = true;
			winMessage.patient_name.disable();
			winMessage.send.enable();
			
			winMessage.show();
		}
	},'-',{
		xtype		  :'button',
		id			  : 'delMsg',
		ref			  : '../delMsg',
		text		  : '<?php i18n('Delete message'); ?>',
		iconCls		: 'delete',
		disabled	: true,
		handler: function(msgGrid){
			Ext.Msg.show({
				title: '<?php i18n("Please confirm...", 'e'); ?>', 
				icon: Ext.MessageBox.QUESTION,
				msg:'<?php i18n('Are you sure to delete this message?<br>From: '); ?>' + rowContent.get('from'),
				buttons: Ext.Msg.YESNO,
				fn:function(btn,msgGrid){
			        if(btn=='yes'){
						// The datastore object will save the data
						// as soon changes is detected on the datastore
						// It's a AJAX thing
						var rows = Ext.getCmp('msgGrid').selModel.getSelections();
						storeMsgs.remove(rows);
						storeMsgs.sync();
						storeMsgs.load();
	    	    	}
				}
			});
		}
	}], // END GRID TOP MENU

}); // END GRID

//******************************************************************************
// Render panel
//******************************************************************************
var topRenderPanel = Ext.create('Ext.panel.Panel', {
	title		: '<?php i18n("Messages"); ?>',
	renderTo	: Ext.getCmp('MainApp').body,
	layout		: 'fit',
	height		: Ext.getCmp('MainApp').getHeight(),
  	frame		: false,
  	border		: false,
	bodyPadding	: 0,
	id			: 'topRenderPanel',
	items		: [msgGrid]
});

}); // END EXTJS

</script>
