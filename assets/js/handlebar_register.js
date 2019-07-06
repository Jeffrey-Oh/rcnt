$(function() {
	// 당첨 유무
	Handlebars.registerHelper("x-winner", function(m, options) {
		if (m != null) {
			return options.fn(this);
		} else {
			return options.inverse(this);
		}
	});
});