(function($) {
	$.fn.extend({
		limiter: function(limit, type) {
			var $this = $(this);

			if ($this.length) {
				var $text = $('<div class="limitee"></div>');

				$text.insertAfter($this)

				$this.on('keyup focus', function() {
					setCount(this);
				});

				function setCount() {
					var chars = $this.val().length;

					if (chars > limit) {
						if (type == 2) {
							$this.val($this.val().substr(0, limit));
							chars = limit;
						} else {
							$text.addClass('limitee-over');
						}
					} else {
						$text.removeClass('limitee-over');
					}

					var remaining = (limit - chars),
						output = remaining + '/' + limit + ' characters remaining';

					$text.html(output);
				}

				setCount();
			}
		}
	});
})(jQuery);