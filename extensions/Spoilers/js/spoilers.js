(function($) {
	'use strict';

	function init() {
		$('.spoilers-button').show();
		$('.spoilers-button').click(function() {
			$('.spoilers-body').toggle();
			$(this).children().toggle();
		});
	}

	$(init);
}(this.jQuery));
