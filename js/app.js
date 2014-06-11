/* 
* Phonebook in Backbone.js
*/

var PB = {
	run: function() {
		this.addcontactview = new this.addContactView();
		this.contactscollection = new this.contactsCollection();
		this.contactview = new this.contactView();
		this.navroutes = new this.NavRoutes();
		Backbone.history.start();
	}
};

PB.NavRoutes = Backbone.Router.extend({
	
	routes: {
		'edit_contact/:id' : 'renderEditContactModel'
	},

	renderEditContactModel: function(id) {
		setTimeout(function(){
			PB.contactview.editContactModel(id);
		}, 300);
	}

}); 

PB.contactModel = Backbone.Model.extend({
	sync: function (method, model, options) {
		var _this = this;
		if( method == "create" ) {
			if(this.get("name")=="" || this.get("phone")=="") {
				$("#NewContactModal .validate_error").fadeIn();
			} else {
				$.ajax({
					type: 'POST',				
	                url: './load.php', 
	                data: {
	                	name: ( this.get("name") || '' ),
	                	email: ( this.get("email") || '' ),
	                	address: ( this.get("address") || '' ),
	                	phone: ( this.get("phone") || '' ),
	                	action: method
	                },
	                dataType: 'json',
	                success: function(res) {
	                	if(res.response=="saved") {
	                		$('#NewContactModal').modal('hide');
	                		PB.contactview.listContacts();
	                		$("#NewContactModal .validate_error").fadeOut();
	                	}
	                }
				});
			}
		}
		else if( method == "update" ) {
			if(this.get("name")=="" || this.get("phone")=="") {
				$("#EditContactModal .validate_error").fadeIn();
			} else {
				$.ajax({
					type: 'POST',				
	                url: './load.php', 
	                data: {
	                	id: this.get("id"),
	                	name: ( this.get("name") || '' ),
	                	email: ( this.get("email") || '' ),
	                	address: ( this.get("address") || '' ),
	                	phone: ( this.get("phone") || '' ),
	                	action: method
	                },
	                dataType: 'json',
	                success: function(res) {
	                	if(res.response=="updated") {
	                		PB.contactview.listContacts();
	                		$('#EditContactModal').modal('hide');
	                		PB.navroutes.navigate('', {trigger: true});
	                		$("#EditContactModal .validate_error").fadeOut();
	                	}
	                }
				});
			}
		}
		else if( method == "delete" ) {
			id = this.get('id');
			return $.getJSON('./load.php', { id: id, action: "delete" }, function (data) {
				if(data.response=="deleted") {
					$("#contacts_list tr td a[contact_id='"+id+"']").parent().parent().fadeOut();
				}
			});	
		}
	},

	validate: function(attrs, options) {
		console.log(options);
	    if (attrs.end < attrs.start) {
	      return "can't end before it starts";
	    }
  	}

});

PB.contactsCollection = Backbone.Collection.extend({
	model: PB.contactModel,
	url: './load.php/',
});

PB.addContactView = Backbone.View.extend({
	
	el:"#add_new_contact_model",

	template: _.template($('#pb_add_contact_template').html()), 

	initialize: function() {
		_.bindAll(this, 'render');
		this.render();
	},

	events: {
		"click #save_contact" : "saveContact"
	},

	render: function() {
		this.$el.html( this.template ); 
	},

	saveContact: function() {
		var contact_id = "";
		var name = $("#pb_name").val();
		var email = $("#pb_email").val();
		var address = $("#pb_address").val();
		var phone = $("#pb_phone").val();
		var contactmodel = new PB.contactModel({
			name: name,
			email: email,
			address: address,
			phone: phone
		});
		contactmodel.save();
	}

});

PB.contactView = Backbone.View.extend({

	el: $("body"),

	template: _.template( $("#listContactsTemplate").html() ),
	edit_template: _.template( $("#pb_edit_contact_template").html() ),

	initialize: function() {
		_.bindAll(this, 'listContacts', 'render');
		this.listContacts();
	},

	events: {
		"click .edit_contact a" : "editContact",
		"click .delete_contact a" : "deleteContact",
		"click #update_contact" : "updateContact",
		"click button[data-dismiss='modal']" : "closeModal"
	},

	render: function(response) {
		var element = $("#contacts_list");
		element.html( this.template({ contacts:response }) );
		element.fadeIn();
	},

	listContacts: function() {
		var _self = this;
		PB.contactscollection.fetch({
			data: "action=get",
			success: function(collection, response) {
				_self.render(response);
			}
		});
	},

	deleteContact: function(event) {
		var _self = this;
		var target = $( event.target );
		var contact_id = target.attr("contact_id");
		PB.contactscollection.get(contact_id).destroy();
		return false;
	},

	editContact: function(event) {
		var _self = this;
		var target = $( event.target );
		var contact_id = target.attr("contact_id");
		PB.navroutes.navigate('#/edit_contact/' + contact_id, {trigger: true});
		return false;
	},

	editContactModel: function(contact_id) {
		var _this = this;
		if(contact_id!="") {
			model_obj = PB.contactscollection.get(contact_id);
			contact_details = model_obj.toJSON();
			$("#edit_contact_model").html(_this.edit_template({ contactDetails: contact_details }));
			$('#EditContactModal').modal('show');
		}
	},

	updateContact: function(event) {
		var _self = this;
		var target = $( event.target );
		var parent = target.parent().parent().find(".modal-body");
		var contact_id = parent.find("#contact_id").val();
		var name = parent.find("#pb_name").val();
		var email = parent.find("#pb_email").val();
		var address = parent.find("#pb_address").val();
		var phone = parent.find("#pb_phone").val();
		var contactmodel = new PB.contactModel({
			id: contact_id,
			name: name,
			email: email,
			address: address,
			phone: phone
		});
		contactmodel.save();
	},

	closeModal: function() {
		PB.navroutes.navigate('', {trigger: true});
	}

});


$(function() {
	PB.run();
});
