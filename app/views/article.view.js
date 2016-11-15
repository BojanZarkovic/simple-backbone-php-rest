// View class for displaying individual models from collection
var ArticlesListItemView = Backbone.View.extend({
	//root element of the view
	tagName: 'div',
	className: 'article',
	//template wich view will use for rendering
	template: _.template($('#article-item-tmpl').html()),
	//functions to run whenever view instance is initiated
	initialize: function() {
		this.listenTo(this.model, 'destroy', this.remove);
	},
	//render function maps model data to template and renders the html
	render: function() {
		let isDone = '',
			markDone = '';
		
		if (this.model.get("done") == true) {
			isDone = 'checked';
			markDone = 'markedDone';
		}
		let toTpl = _.extend(this.model.toJSON(), {
			isDone: isDone,
			markDone: markDone,
		});
		let html = this.template(toTpl);
		this.$el.html(html);
		//return this to allow method chaining
		return this;
	},
	//event and handler pairs
	events: {
		'click .remove'		: 'onRemove',
		'click .edit-name'	: 'onEdit',
		'keypress .edit'	: 'updateOnEnter',
		'keydown .edit'		: 'revertOnEscape',
		'blur .edit'		: 'close',
		'click .name'		: 'onEdit',
		'click #toggle'		: 'checked'
	},

	//event handler functions:
	
	//destrys a model
	onRemove: function() {
		this.model.destroy();
	},
	//shows the #hidden-input and hides the #listed-name
	onEdit: function() {
		this.$("#hidden-input").attr('class', 'edit').focus().select();
		this.$("#listed-name").addClass('hidden');
	},
	//saves the article's new name obtained from .edit input field
	close: function () {
		var $newName = this.$('.edit').val();
		this.model.set({name: $newName});
		this.model.save();
	},
	//calls the close() function on enter key press
	updateOnEnter: function (e) {
		if (e.keyCode === 13) {
			this.close();
		} 
	},
	//shows the #listed-name and hides the #hidden-input, discards the changes in input
	revertOnEscape: function (e) {
		if (e.which === 27) {
			this.$("#hidden-input").attr('class', 'hidden');
			this.$("#hidden-input").val(this.model.get('name'));
			this.$("#listed-name").removeClass('hidden');
		}
	},
	//toggles the item completed or uncompleted
	checked: function() {
		let $checkbox = this.$("#toggle");
		if ($checkbox.is(':checked') == true) {
			this.model.set("done", true);
		} else {
			this.model.set("done", false);
		}
		this.$( "#listed-name" ).toggleClass('markedDone');
		this.model.save();
	}
});
