(function($) {
	$.fn.extend({
		limitee: function(limit, type) {
			var $el = this;

			if ($el.length) {
				var $text = $('<div class="limitee"/>');

				$text.insertAfter($el);

				function setCount() {
					var val = $el.val(),
						chars = val.length;

					if (chars > limit) {
						if (type === 2) {
							$el.val(val.substr(0, limit));
							chars = limit;
						} else {
							$text.addClass('-is-over');
						}
					} else {
						$text.removeClass('-is-over');
					}

					$text.html((limit - chars) + '/' + limit + ' characters remaining');
				}

				$el.on('keyup focus', function() {
					setCount();
				});

				setCount();
			}
		}
	});
})(jQuery);