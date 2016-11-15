// Collection class for ArticleModel models
var ArticleCollection = Backbone.Collection.extend({
	model: ArticleModel,
	url: 'app/backend/api.php'
});
