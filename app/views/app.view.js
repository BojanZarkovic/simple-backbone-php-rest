// View class for rendering the entire collection
var AppView = Backbone.View.extend({
	//root element for the view
	el: '#articles-app',
	//functions to run whenever view instance is initiated
	initialize: function() {
		this.listenTo(this.collection, 'sync', this.render);
		this.listenTo(this.collection, 'remove', this.render);
		this.$('#article-name').focus();
	},
	//render function maps model data to template and renders the html
	render: function() {
		//clears the list
		var $list = this.$('#articles-list').empty();
		//loop rendering each item
		this.collection.each(function(model) {
			var item = new ArticlesListItemView({model: model});
			$list.append(item.render().$el);
		}, this);
		//return this to allow method chaining
		return this;
	},
	//event and handler pairs
	events: {
		'click #save-article'	: 'saveItem',
		'keypress #article-name': 'addNewOnEnter',
		'keypress #search'		: 'searchUpdateOnEnter',
		'keydown #search'		: 'revertOnEscape',
	},
	// saves new model with name from #article-name input to collection
	saveItem: function() {
		var $name = this.$('#article-name');
		this.collection.create({name: $name.val()});
		$name.val('').focus();
	},
	// calls the saveItem() function on enter key press
	addNewOnEnter: function (e) {
		if (e.keyCode === 13) {
			this.saveItem();
		}
	},
	// searches the models for a match from #search input on enter key press
	searchUpdateOnEnter: function (e) {
		if (e.keyCode === 13) {
			this.$('#notice').empty();
			var searchedName = this.$('#search').val();
			if (searchedName !=='') {
				var	filteredModels = this.collection.filter(function(model) {
					return model.get('name').indexOf(searchedName) > -1;
				});
				this.collection.set(filteredModels);
				/*
				var searchResults = articlesList.where({name: searchedName});
				var searchResults = this.searchNames(searchedName);
				this.collection.set(searchResults);
				*/
				this.$('#search').val('');
				//var resultsNum = searchResults.length;
				//this.$('#notice').append('<h2>' + resultsNum + ' found</h2>');
				this.$('#notice').append('<h2>' + this.collection.size() + ' found</h2>');
			} else {
				this.collection.fetch();
			}
		}
	},
	//discards the changes in #search input and blures it on esc key press
	revertOnEscape: function (e) {
		if (e.which === 27) {
			this.collection.fetch();
			this.$("#search").val('').blur();
			this.$('#notice').empty();
		}
	},
	searchNames: function(searchedName) {
		var searchResults = [];
		var searchResultNames = [];
		var namesArray = articlesList.pluck('name');
		var filteredNamesArray;

		for (let name of namesArray) {
			let position = name.indexOf(searchedName);
			if (position > -1) {
				searchResultNames.push(name);
			}
		}

		for (let resultName of searchResultNames) {
			let match = articlesList.where({name: resultName });
			searchResults = searchResults.concat(match);
		}

		return searchResults;
	}

});
