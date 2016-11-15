// Model class for each Article item
var ArticleModel = Backbone.Model.extend({
	// Default atribute values for article models
	defaults: {
		id: null,
		name: null,
		done: false
	},
	// base url for sending requests to server
	urlRoot: 'app/backend/api.php',
	// function to change the url to server supported format
	url: function() {
		var base = this.urlRoot;
		if (this.isNew()) return base;
		return base + "?id=" + encodeURIComponent(this.id);
	},
	parse: function(response) {
		// Cast integer value for 'done' (1/0) to boolean true/false
		response.done = response.done == 1 ? true : false;
		return response;
	},
});
