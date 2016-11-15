"use strict";
$( document ).ready(function() {
	
});
// methods to deal with lack of server support for all REST verbs
	Backbone.emulateHTTP = true;
	// and with some other problem :)
	// Send serialized JSON data as 'model' POST parameter
	Backbone.emulateJSON = true;
	//router to be added here later

	// Create a new collection and view, and fetch collection data:
	var articlesList = new ArticleCollection();
	var appView = new AppView({collection: articlesList});
	articlesList.fetch();
