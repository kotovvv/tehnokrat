jQuery(document).ready(function($){

	$('.rating-content').rating();

	var wind_height = $(window).height();
	var wind_width = $(window).width();

	$('.feedback-poput').css('height', wind_height);
	$('.feedback-poput').css('width', wind_width);

});

jQuery(window).resize(function(){
	var $ = jQuery;
	var wind_height = $(window).height();
	var wind_width = $(window).width();

	$('.feedback-poput').css('height', wind_height);
	$('.feedback-poput').css('width', wind_width);

});

vm = new Vue( {
	el: '#vue-app',
	data: {
		comments: [],
		currentPage: 1,
		karma: 0,
		name: '',
		email: '',
		content: '',
		loadMore: true,
		commentsToShow: 'all',
		showKarmaWarning: false
	},
	methods: {
		openPopup: function( event ) {
			if ( 'reviews-feedback' == event.target.className ) {
				jQuery('body').addClass('popup');
				jQuery('.wrapper').addClass('popup');
				jQuery('.feedback-poput').addClass('active');
			}
		},
		closePopup: function() {
			jQuery('.feedback-poput').removeClass('active');
			jQuery('.wrapper').removeClass('popup');
			jQuery('body').removeClass('popup');
		},
		getKarma: function() {
			this.karma = jQuery('a.fullStar', '.feedback-poput').length;
			this.showKarmaWarning = ( 0 === this.karma );
		},
		getComments: function( page ) {
			var data = {
				action:         'tehnokrat_get_comments',
				nonce:          tehnokrat.nonce,
				page:           page || this.currentPage,
				commentsToShow: this.commentsToShow
			};
			jQuery.ajax({
				'context': this,
				'data':    data,
				'type':    'get',
				'url':     tehnokrat.ajax_url,
				success: function( responce ) {
					if ( responce.success && responce.data.length ) {
						this.comments = this.comments.concat(responce.data);
						this.currentPage++;
					}
					if ( responce.data.length < 10 ) {
						this.loadMore = false;
					}
				}
			});
		},
		postComment: function() {
			if ( 0 === this.karma ) {
				this.showKarmaWarning = true;
				return;
			}

			grecaptcha.ready(
				() => {
					grecaptcha.execute(tehnokrat.recaptchaSiteKey).then(token => {
						const data = {
							action: 'tehnokrat_post_comment',
							nonce: tehnokrat.nonce,
							karma: this.karma,
							name: this.name,
							email: this.email,
							content: this.content,
							token: token
						}

						jQuery.post(
							tehnokrat.ajax_url,
							data,
							response => {
								if (response.success) {
									location.reload()
								} else {
									alert(response.data)
								}
							}
						)
					})
				}
			)
		},
		filterComments: function( karmaType ) {
			this.commentsToShow = karmaType;

			this.comments = [];
			this.currentPage = 1;
			this.loadMore = true;
			this.getComments();
		},
		hideOrShow: function( karma ) {
			if ( 'all' === this.commentsToShow ) {
				return true;
			} else if ( 'positive' === this.commentsToShow && karma > 2 ) {
				return true;
			} else if ( 'negative' === this.commentsToShow && karma < 3 ) {
				return true;
			} else {
				return false;
			}
		}
	},
	created: function() {
		this.getComments();
	}
} );
