 /**
 * summary.ejs.php
 * Description: Patient Summary
 * v0.0.1
 *
 * Author: Ernesto J Rodriguez
 * Modified: n/a
 *
 * MitosEHR (Electronic Health Records) 2011
 *
 * @namespace Encounter.getVitals
 */
Ext.define('App.view.patientfile.Summary', {
    extend       : 'App.classes.RenderPanel',
    id           : 'panelSummary',
    pageTitle    : 'Patient Summary',
    pageLayout   : {
        type : 'hbox',
        align: 'stretch'
    },
    initComponent: function() {
        var me = this;

	    me.demographicsData = null;

        me.vitalsStore = Ext.create('App.store.patientfile.Vitals');


        me.immuCheckListStore = Ext.create('App.store.patientfile.ImmunizationCheck');
        me.patientAllergiesListStore = Ext.create('App.store.patientfile.Allergies');
        me.patientMedicalIssuesStore = Ext.create('App.store.patientfile.MedicalIssues');
        me.patientSurgeryStore = Ext.create('App.store.patientfile.Surgery');
        me.patientDentalStore = Ext.create('App.store.patientfile.Dental');
        me.patientMedicationsStore = Ext.create('App.store.patientfile.Medications');
        me.patientDocumentsStore = Ext.create('App.store.patientfile.PatientDocuments');


        me.patientNotesStore = Ext.create('App.store.patientfile.Notes');
        me.patientRemindersStore = Ext.create('App.store.patientfile.Reminders');

        me.pageBody = [
            {
                xtype      : 'tabpanel',
                flex       : 1,
                margin     : '3 0 0 0',
                bodyPadding: 0,
                frame      : false,
                border     : false,
                plain      : true,
                itemId     : 'centerPanel',
                items      : [
                    {
                        xtype:'panel',
                        title:'Patient General',
                        autoScroll:true,
                        defaults   : { margin: 5, bodyPadding: 5, collapsible: true, titleCollapse: true },
                        items:[
                            {
                                xtype:'panel',
                                action:'balance',
                                title: 'Billing',
                                html:'Account Balance: '

                            },
                            {
                                xtype : 'form',
                                title : 'Demographics',
                                action: 'demoFormPanel',
                                itemId: 'demoFormPanel',
	                            dockedItems: [
		                            {
		                                xtype: 'toolbar',
		                                dock: 'bottom',
		                                items: [
			                                '->',
			                                {
				                                xtype: 'button',
		                                        text:'Save',
				                                minWidth: 75,
		                                        scope:me,
		                                        handler:me.formSave
		                                    },
		                                    '-',
		                                    {
		                                        xtype: 'button',
		                                        text:'Cancel',
		                                        minWidth: 75,
		                                        scope:me,
		                                        handler:me.formCancel
		                                    }
		                                ]
		                            },
		                            {
		                                xtype: 'toolbar',
		                                dock: 'top',
		                                items: [
			                                '->',
			                                {
				                                xtype: 'button',
		                                        text:'Save',
				                                minWidth: 75,
		                                        scope:me,
		                                        handler:me.formSave
		                                    },
		                                    '-',
		                                    {
		                                        xtype: 'button',
		                                        text:'Cancel',
		                                        minWidth: 75,
		                                        scope:me,
		                                        handler:me.formCancel
		                                    }
		                                ]
		                            }
	                            ]
                            },
                            {
                                title      : 'Notes',
                                itemId     : 'notesPanel',
                                xtype      : 'grid',
                                bodyPadding: 0,
                                store      : me.patientNotesStore,
                                columns    : [
                                    {
                                        text     : 'Date',
                                        dataIndex: 'date'
                                    },
                                    {
                                        header   : 'Type',
                                        dataIndex: 'type'
                                    },
                                    {
                                        text     : 'Note',
                                        dataIndex: 'body',
                                        flex     : 1
                                    },
                                    {
                                        text     : 'User',
                                        dataIndex: 'user_name'
                                    }
                                ]

                            },
                            {
                                title      : 'Reminders',
                                itemId     : 'remindersPanel',
                                xtype      : 'grid',
                                bodyPadding: 0,
                                store      : me.patientRemindersStore,
                                columns    : [
                                    {
                                        text     : 'Date',
                                        dataIndex: 'date'
                                    },
                                    {

                                        header   : 'Type',
                                        dataIndex: 'type'
                                    },
                                    {
                                        text     : 'Note',
                                        dataIndex: 'body',
                                        flex     : 1
                                    },
                                    {
                                        text     : 'User',
                                        dataIndex: 'user_name'
                                    }
                                ]

                            },
                            {
                                title: 'Disclosure',
                                html : 'Panel content!'
                            }
                        ]
                    },
                    {
                        title     : 'Vitals',
                        autoScroll: true,
                        bodyPadding: 0,
                        items     : {
                            xtype: 'vitalsdataview',
                            store: me.vitalsStore
                        }
                    },
                    {
                        title     : 'History',
                        xtype     :'grid',
                        columns:[
                            {
                                header:'Date',
                                dataIndex:'date'
                            },
                            {
                                header:'Event',
                                dataIndex:'title',
                                flex:true
                            },
                            {
                                header:'User',
                                dataIndex:'user'
                            }
                        ]
                    },
                    {
                        title     : 'Documents',
                        xtype     :'grid',
                        store: me.patientDocumentsStore,
                        columns:[
                            {
                                xtype: 'actioncolumn',
                                width:26,
                                items: [
                                    {
                                        icon: 'ui_icons/preview.png',
                                        tooltip: 'View Document',
	                                    handler: me.onDocumentView,
                                        getClass:function(){
                                            return 'x-grid-icon-padding';
                                        }
                                    }
                                ]
                            },
                            {
                                header:'Type',
                                dataIndex:'docType'
                            },
                            {
                                header:'Date',
                                dataIndex:'date'
                            },
                            {
                                header:'Title',
                                dataIndex:'title',
                                flex:true
                            },
                            {
                                header:'User',
                                dataIndex:'user_name'
                            }
                        ]
//	                    tbar:[
//		                    {
//			                    text:'New Lab Order',
//			                    action:'lab',
//			                    scope:me,
//			                    handler:me.newDoc
//		                    },
//		                    '-',
//		                    {
//			                    text:'New X-Ray Order',
//			                    action:'xRay',
//			                    scope:me,
//			                    handler:me.newDoc
//		                    },
//		                    '-',
//		                    {
//			                    text:'New Prescription',
//			                    action:'prescription',
//			                    scope:me,
//			                    handler:me.newDoc
//		                    },
//		                    '-',
//		                    {
//			                    text:'New Doctors Note',
//			                    action:'notes',
//			                    scope:me,
//			                    handler:me.newDoc
//		                    }
//	                    ]
                    }
                ]
            },
            {
                xtype      : 'panel',
                width      : 250,
                bodyPadding: 0,
                frame      : false,
                border:false,
                bodyBorder     : true,
                margin     : '0 0 0 5',
                defaults   : {
                    layout: 'fit',
                    margin: '5 5 0 5'
                },
                listeners  : {
                    scope      : me,
                    afterrender: me.afterRightCol
                },
                items      : [
                    {
                        title      : 'Active Medications',
                        itemId     : 'MedicationsPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientMedicationsStore,
                        columns    : [
                            {
                                header   : 'Name',
                                dataIndex: 'medication',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }

                        ]
                    },
                    {
                        title      : 'Immunizations',
                        itemId     : 'ImmuPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.immuCheckListStore,
                        region     : 'center',
                        columns    : [
                            {

                                header   : 'Name',
                                dataIndex: 'immunization_name',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.alertRenderer
                            }

                        ]
                    },
                    {
                        title      : 'Allergies',
                        itemId     : 'AllergiesPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientAllergiesListStore,
                        region     : 'center',
                        columns    : [
                            {
                                header   : 'Name',
                                dataIndex: 'allergy',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }
                        ]
                    },
                    {
                        title      : 'Active Problems',
                        itemId     : 'IssuesPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientMedicalIssuesStore,
                        columns    : [
                            {

                                header   : 'Name',
                                dataIndex: 'code_text',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }

                        ]

                    },
                    {
                        title      : 'Dental',
                        itemId     : 'DentalPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientDentalStore,

                        columns: [
                            {

                                header   : 'Name',
                                dataIndex: 'title',
                                flex     : 1

                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }

                        ]

                    },
                    {
                        title      : 'Surgery',
                        itemId     : 'SurgeryPanel',
                        hideHeaders: true,
                        xtype      : 'grid',
                        store      : me.patientSurgeryStore,

                        columns: [
                            {
                                dataIndex: 'title',
                                flex     : 1
                            },
                            {
                                text     : 'Alert',
                                width    : 55,
                                dataIndex: 'alert',
                                renderer : me.boolRenderer
                            }
                        ]
                    },
                    {
                        title: 'Clinical Reminders',
                        html : 'Panel content!'

                    },
                    {
                        title: 'Appointments',
                        html : 'Panel content!'

                    },
                    {
                        title: 'Prescriptions',
                        margin: 5,
                        html : 'Panel content!'
                    }
                ],
                dockedItems:[
                    {
                        xtype:'toolbar',
                        style:'background:none',
                        items:[
                            '->',
                            {
                                text:'Patient Picture',
	                            handler: function() {
                                    me.getPhotoIdWindow();
                                }
                            }
                        ]
                    }
                ]
            }
        ];

        me.listeners = {
            scope       : me,
            beforerender: me.beforePanelRender

        };

        me.callParent(arguments);
    },

	onDocumentView:function(grid, rowIndex){
		var rec = grid.getStore().getAt(rowIndex),
			src = rec.data.url;
		app.onDocumentView(src);
	},

	formSave:function(btn){
		var me = this,
			form = btn.up('form').getForm(),
			record = form.getRecord(),
			values = form.getValues();
		record.set(values);
		record.store.save({
			scope:me,
			callback:function(){
				me.getPatientImgs();
			}
		});
	},

	formCancel:function(btn){
		var form = btn.up('form').getForm(),
			record = form.getRecord();
		form.loadRecord(record);
	},

	newDoc:function(btn){
		app.onNewDocumentsWin(btn.action)
	},

    disableFields: function(fields) {
        Ext.each(fields, function(field) {
	        if(field.name == 'SS' || field.name == 'DOB' || field.name == 'sex'){
		        field.setReadOnly(true);
	        }
        }, this);
    },

    getFormData: function(formpanel) {

        var me = this, rFn, uFn;

        if(formpanel.itemId == 'demoFormPanel') {
	        rFn = Patient.getPatientDemographicData;
	        uFn = Patient.updatePatientDemographicData;
        }

        var formFields = formpanel.getForm().getFields(), modelFields = [{name:'pid',type:'int'}];

        Ext.each(formFields.items, function(field) {
	        if(field.xtype == 'datefield'){
		        say(field);
		        modelFields.push({name: field.name, type: 'date', dateFormat:'Y-m-d H:i:s'});
	        }else{
		        modelFields.push({name: field.name});
	        }
        });

        var model = Ext.define(formpanel.itemId + 'Model', {
            extend: 'Ext.data.Model',
            fields: modelFields,
            proxy : {
                type: 'direct',
                api : {
                    read: rFn,
	                update: uFn
                }
            }
        });

        var store = Ext.create('Ext.data.Store', {
            model: model
        });

        store.load({
            scope   : me,
            callback: function(records, operation, success) {
                formpanel.getForm().loadRecord(records[0]);
            }
        });

        /**
         * load the vitals store to render the vitals data view
         */
        me.vitalsStore.load();

    },

    beforePanelRender: function() {
        var me = this, demoFormPanel = me.query('[action="demoFormPanel"]')[0], who, imgCt;

        this.getFormItems(demoFormPanel, 'Demographics', function(success) {
            if(success) {
                me.disableFields(demoFormPanel.getForm().getFields().items);
                who = demoFormPanel.query('fieldset[title="Who"]')[0];

                imgCt = Ext.create('Ext.container.Container',{
                    action :'patientImgs',
                    layout:'hbox',
                    style:'float:right',
                    height:100,
                    width:220,
                    items:[
                        me.patientImg = Ext.create('Ext.container.Container', {
                            html: '<img src="ui_icons/patientPhotoId.jpg" height="100" width="100" />',
                            margin:'0 5 0 0'
                        }),
                        me.patientQRcode = Ext.create('Ext.container.Container', {
                            html: '<img src="ui_icons/patientDataQrCode.png" height="100" width="100" />',
                            margin: 0
                        })
                    ]
                });
                who.insert(0,imgCt);
            }
        });
    },


    afterRightCol : function(panel) {
        var me = this;
        panel.getComponent('ImmuPanel').header.add({
            xtype  : 'button',
            text   : 'update',
            action : 'immunization',
            scope  : me,
            handler: me.medicalWin


        });
        panel.getComponent('MedicationsPanel').header.add({
            xtype  : 'button',
            text   : 'update',
            action : 'medications',
            scope  : me,
            handler: me.medicalWin


        });

        panel.getComponent('AllergiesPanel').header.add({
            xtype  : 'button',
            text   : 'update',
            action : 'allergies',
            scope  : me,
            handler: me.medicalWin


        });
        panel.getComponent('IssuesPanel').header.add({
            xtype  : 'button',
            text   : 'update',
            action : 'issues',
            scope  : me,
            handler: me.medicalWin


        });
        panel.getComponent('DentalPanel').header.add({
            xtype  : 'button',
            text   : 'update',
            action : 'dental',
            scope  : me,
            handler: me.medicalWin
        });
        panel.getComponent('SurgeryPanel').header.add({
            xtype  : 'button',
            text   : 'update',
            action : 'surgery',
            scope  : me,
            handler: me.medicalWin
        });
        this.doLayout();
    },
    medicalWin    : function(btn) {
        app.onMedicalWin(btn);
    },

    getPatientImgs: function() {
        var me = this,
	        number = Ext.Number.randomInt(1,1000);
        me.patientImg.update('<img src="' + settings.site_url + '/patients/' + app.currPatient.pid + '/patientPhotoId.jpg?' + number + '" height="100" width="100" />');
        me.patientQRcode.update('<a ondblclick="printQRCode(app.currPatient.pid)"><img src="' + settings.site_url + '/patients/' + app.currPatient.pid + '/patientDataQrCode.png?' + number + '" height="100" width="100" title="Print QRCode" /></a>');
    },

	getPhotoIdWindow: function() {
		var me = this;
		me.PhotoIdWindow = Ext.create('App.classes.PhotoIdWindow', {
			title      : 'Patient Photo Id',
			loadMask   : true,
			modal      : true
		}).show();
	},

	completePhotoId:function(){
		this.PhotoIdWindow.close();
		this.getPatientImgs();
	},

    /**
     * This function is called from MitosAPP.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback) {
        var me = this,
	        billingPanel = me.query('[action="balance"]')[0];

        Fees.getPatientBalance({pid:app.currPatient.pid},function(balance){
            billingPanel.body.update('Account Balance: $' + balance);
        });
	    me.patientNotesStore.load({params: {pid: app.currPatient.pid}});
	    me.patientRemindersStore.load({params: {pid: app.currPatient.pid}});
	    me.immuCheckListStore.load({params: {pid: app.currPatient.pid}});
	    me.patientAllergiesListStore.load({params: {pid: app.currPatient.pid}});
	    me.patientMedicalIssuesStore.load({params: {pid: app.currPatient.pid}});
	    me.patientSurgeryStore.load({params: {pid: app.currPatient.pid}});
	    me.patientDentalStore.load({params: {pid: app.currPatient.pid}});
	    me.patientMedicationsStore.load({params: {pid: app.currPatient.pid}});
	    me.patientDocumentsStore.load({params: {pid: app.currPatient.pid}});

        if(me.checkIfCurrPatient()) {
            var patient = me.getCurrPatient();
	        me.updateTitle(patient.name + ' - #' + patient.pid + ' (Patient Summary)');
            var demoFormPanel = me.query('[action="demoFormPanel"]')[0];
            me.getFormData(demoFormPanel);
            me.getPatientImgs();
        } else {
            callback(false);
            me.currPatientError();
        }
	    PreventiveCare.activePreventiveCareAlert({pid:app.currPatient.pid},function(provider,response){
	       if(response.result.success){
		       app.PreventiveCareWindow.show();
	       }
        });
    }

});