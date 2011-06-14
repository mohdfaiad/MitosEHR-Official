//******************************************************************************
// Render panel
//******************************************************************************
Ext.define('Ext.mitos.SaveCancelWindow', {
    extend      : 'Ext.window.Window',
    alias       : 'widget.savecancelwindow',
	width       : 520,
    title       : 'New Save/Cancel Window',
    renderTo    : document.body,
	form 	    : '', //this is the form the save btn will be getting the data from
	store	    : '', //this is the store we are using to store the data
	scope		: '', //the scope of data store
	idField     : '', //form id field, use to ck if the form is new an update
    rowPos      : '', //rowPos value, use to update store
    initComponent: function() {
    	var win = this;
		Ext.apply(this, {
			autoHeight  : true,
		    modal       : true,
		    border	  	: true,
		    autoScroll	: true,
		    resizable   : false,
		    closeAction : 'hide',
		    items		: win.form,
		    buttons: [{
                text: 'save',
                handler: function(){
                    if (win.form.getForm().findField(win.idField).getValue()){ // Update
                        var record = win.store.getAt(win.scope.rowPos);
                        var fieldValues = win.form.getForm().getValues();
                        for ( var k=0; k <= record.fields.getCount()-1; k++) {
                            var i = record.fields.get(k).name;
                            record.set( i, fieldValues[i] );
                        }
                    } else { // Add
                        var obj = eval( '(' + Ext.JSON.encode(win.form.getForm().getValues()) + ')' );
                        win.store.add( obj );
                    }
                    win.hide();	// Finally hide the dialog window
                    win.store.sync();	// Save the record to the dataStore
                    win.store.load();	// Reload the dataSore from the database
                }
            },{
                text: 'cancel',
                handler: function(){
                    win.hide();
                }
            }]
	    });
        win.callParent(arguments);
    }
});