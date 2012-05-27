/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/15/12
 * Time: 4:30 PM
 *
 * @namespace Immunization.getImmunizationsList
 * @namespace Immunization.getPatientImmunizations
 * @namespace Immunization.addPatientImmunization
 */
Ext.define('App.view.patientfile.NewDocumentsWindow', {
	extend     : 'Ext.window.Window',
	title      : 'Document Window',
	layout     : 'fit',
	closeAction: 'hide',
	height     : 550,
	width      : 700,
	bodyStyle  : 'background-color:#fff',
	modal      : true,
	defaults   : {
		margin: 5
	},
	mixins     : ['App.classes.RenderPanel'],

	initComponent: function() {
		var me = this;
		me.patientPrescriptionStore = Ext.create('App.store.patientfile.PatientsPrescription');

		me.items = [
			me.tabPanel = Ext.create('Ext.tab.Panel', {

				items: [
					{
						title: 'New Lab Order',
						items: [

							{

								xtype  : 'grid',
								margin : 10,
								store  : me.patientPrescriptionStore,
								height : 350,
								columns: [

									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon   : 'ui_icons/delete.png',
												tooltip: 'Remove',
												scope  : me,
												handler: me.onRemove
											}
										]
									},
									{
										header   : 'Medication',
										width    : 100,
										dataIndex: 'medication'
									},
									{
										header   : 'Dispense',
										width    : 100,
										dataIndex: 'dispense'
									},
									{
										header   : 'Refill',
										flex     : 1,
										dataIndex: 'refill'
									}

								],

								bbar:{
									xtype:'textfield',
									margin:5,
									fieldLabel:'Add',
									hideLabel:false,
									listeners:{
										scope:me,
										select:me.addMedications
									}
								}
							}
						],


						bbar : [
							'->', {
								text   : 'Create',
								scope  : me,
								handler: me.Create
							}, {
								text   : 'Cancel',
								scope  : me,
								handler: me.Create
							}
						]
					},
					{
						title: 'New X-Ray Order',
						items: [

							{

								xtype  : 'grid',
								margin : 10,
								store  : me.patientPrescriptionStore,
								height : 350,
								columns: [

									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon   : 'ui_icons/delete.png',
												tooltip: 'Remove',
												scope  : me,
												handler: me.onRemove
											}
										]
									},
									{
										header   : 'Medication',
										width    : 100,
										dataIndex: 'medication'
									},
									{
										header   : 'Dispense',
										width    : 100,
										dataIndex: 'dispense'
									},
									{
										header   : 'Refill',
										flex     : 1,
										dataIndex: 'refill'
									}

								],

								bbar:{
								xtype:'textfield',
								margin:5,
								fieldLabel:'Add',
								hideLabel:false,
								listeners:{
									scope:me,
									select:me.addMedications
								}
							}

							}
						],
						bbar : [
							'->', {
								text   : 'Create',
								scope  : me,
								handler: me.Create
							}, {
								text   : 'Cancel',
								scope  : me,
								handler: me.Create
							}
						]
					},
					{
						title: 'New Prescription',
						items: [
							{
								xtype     : 'mitos.pharmaciescombo',
								fieldLabel: 'Pharmacies',
								width     : 250,
								labelWidth: 75,
								margin    : '10 0 0 10'

							},
							{

								xtype  : 'grid',
								margin : 10,
								store  : me.patientPrescriptionStore,
								height : 350,
								columns: [

									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon   : 'ui_icons/delete.png',
												tooltip: 'Remove',
												scope  : me,
												handler: me.onRemove
											}
										]
									},
									{
										header   : 'Medication',
										width    : 100,
										dataIndex: 'medication'
									},
									{
										header   : 'Dispense',
										width    : 100,
										dataIndex: 'dispense'
									},
									{
										header   : 'Refill',
										flex     : 1,
										dataIndex: 'refill'
									}

								],

								plugins: Ext.create('App.classes.grid.RowFormEditing', {
									autoCancel  : false,
									errorSummary: false,
									clicksToEdit: 1,
									listeners   :{
										scope   : me,
										edit    : me.onEditPrescription

									},
									formItems   : [

										{
											title : 'general',
											xtype : 'container',
											layout: 'vbox',
											items : [
												{
													/**
													 * Line one
													 */
													xtype   : 'fieldcontainer',
													layout  : 'hbox',
													defaults: { margin: '5 0 5 5' },
													items   : [
														{
															xtype     : 'medicationlivetsearch',
															fieldLabel: 'Medication',
															hideLabel : false,
															name      : 'medication',
															width     : 350,
															labelWidth: 80,
															listeners : {
																scope : me,
																select: me.addPrescription
															}
														},
														{
															xtype     : 'numberfield',
															fieldLabel: 'Dose',
															labelWidth: 40,
															action    :'dose',
															name      : 'dose',
															width     : 100,
															value     : 0,
															minValue  : 0
														},
														{
															xtype     : 'textfield',
															fieldLabel: 'Dose mg',
															action    :'dose_mg',
															name      : 'dose_mg',
															hideLabel : true,
															width     : 150
														}
													]

												},
												{
													/**
													 * Line two
													 */
													xtype   : 'fieldcontainer',
													layout  : 'hbox',
													defaults: { margin: '5 0 5 3'},

													items: [
														{
															xtype     : 'numberfield',
															fieldLabel: 'Take',
															margin    : '5 0 5 5',
															name      : 'take_pills',
															width     : 130,
															labelWidth: 80,
															value     : 0,
															minValue  : 0
														},
														{
															xtype     : 'mitos.prescriptiontypes',
															fieldLabel: 'Type',
															hideLabel : true,
															name      : 'type',
															width     : 120
														},
														{
															xtype     : 'mitos.prescriptionhowto',
															fieldLabel: 'By',
															name      : 'by',
															hideLabel : true,
															width     : 100
														},
														{
															xtype: 'mitos.prescriptionoften',
															name : 'prescription_often',
															width: 120
														},
														{
															xtype: 'mitos.prescriptionwhen',
															name : 'prescription_when',
															width: 100
														}
													]

												},
												{
													/**
													 * Line three
													 */
													xtype   : 'fieldcontainer',
													layout  : 'hbox',
													defaults: { margin: '5 0 5 5'},
													items   : [
														{

															fieldLabel: 'Dispense',
															xtype     : 'numberfield',
															name      : 'dispense',
															width     : 130,
															labelWidth: 80,
															value     : 0,
															minValue  : 0
														},
														{
															fieldLabel: 'Refill',
															xtype     : 'numberfield',
															name      : 'refill',
															labelWidth: 35,
															width     : 140,
															value     : 0,
															minValue  : 0
														}
													]

												}

											]

										}


									]
								}),
								tbar   : [
									'->',
									{
										text   : 'New Medication',
										scope  : me,
										handler: me.onAddNewPrescription

									}
								]

							}

						],
						bbar : [
							'->', {
								text   : 'Create',
								scope  : me,
								handler: me.onCreate
							}, {
								text   : 'Cancel',
								scope  : me,
								handler: me.Create
							}
						]

					},
					{
						title: 'New Doctors Note',
						items: [
							{

								xtype  : 'grid',
								margin : 10,
								store  : me.patientPrescriptionStore,
								height : 350,
								columns: [

									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon   : 'ui_icons/delete.png',
												tooltip: 'Remove',
												scope  : me,
												handler: me.onRemovePrescription
											}
										]
									},
									{
										header   : 'Medication',
										width    : 100,
										dataIndex: 'medication'
									},
									{
										header   : 'Dispense',
										width    : 100,
										dataIndex: 'dispense'
									},
									{
										header   : 'Refill',
										flex     : 1,
										dataIndex: 'refill'
									}

								],
								bbar:{
									xtype:'textfield',
									margin:5,
									fieldLabel:'Add',
									hideLabel:false,
									listeners:{
										scope:me,
										select:me.addMedications
									}
								}

							}
						],
						bbar : [
							'->', {
								text   : 'Create',
								scope  : me,
								handler: me.Create
							}, {
								text   : 'Cancel',
								scope  : me,
								handler: me.Create
							}
						]
					}
				]

			})
		];
		me.listeners = {
			scope: me,
			show : me.onDocumentsWinShow
		};
		me.callParent(arguments);
	},

	cardSwitch          : function(action) {

		var layout = this.tabPanel.getLayout();

		if(action == 'lab') {
			layout.setActiveItem(0);
		} else if(action == 'xRay') {
			layout.setActiveItem(1);
		} else if(action == 'prescription') {
			layout.setActiveItem(2);
		} else if(action == 'notes') {
			layout.setActiveItem(3);
		}
	},
	onAddNewPrescription: function(btn) {
		var grid = btn.up('grid');
		grid.editingPlugin.cancelEdit();

		this.patientPrescriptionStore.insert(0,{});
		grid.editingPlugin.startEdit(0, 0);
	},
	onRemove: function(grid, rowIndex){
		var me = this,
			store = grid.getStore(),
			record = store.getAt(rowIndex);
			grid.editingPlugin.cancelEdit();

			store.remove(record);
	},
	addPrescription     : function(combo, model) {
		var me = this,
			field,field2, dose, dose_mg;
		dose = model[0].data.ACTIVE_NUMERATOR_STRENGTH;
		dose_mg = model[0].data.ACTIVE_INGRED_UNIT;

		field =  combo.up('fieldcontainer').query('[action="dose"]')[0];
		field2 =  combo.up('fieldcontainer').query('[action="dose_mg"]')[0];
		field.setValue(dose);
		field2.setValue(dose_mg);

	},
	onEditPrescription: function(editor,e){
		say(editor);
		say(e.record.commit());

	},
	onCreate: function (){
		var records =this.patientPrescriptionStore.data.items,
			params = [];
		Ext.each(records, function(record){
			params.push(record.data);

		});
		params['pid']=app.currPatient.pid;
		params['documentId']=5;
		say(params);

		Documents.findAndReplaceTokens(params, function(provider, response){});

	},
	addMedications: function(){

	},
	onDocumentsWinShow  : function() {

	}
});