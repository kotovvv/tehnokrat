<?php

global $wpdb;
$query = sprintf(
	'SELECT count(%1$s) count_%1$s, sum(%1$s) sum_%1$s FROM %2$s WHERE comment_post_ID = %3$d AND comment_parent = 0',
	'comment_karma',
	$wpdb->comments,
	url_to_postid( $_SERVER['REQUEST_URI'] )
);
$result = $wpdb->get_row( $query );
$count_comment_karma   = $result->count_comment_karma;
$sum_comment_karma     = $result->sum_comment_karma;
$average_comment_karma = $sum_comment_karma / $count_comment_karma;

$query = sprintf(
	'SELECT count(%1$s) FROM %2$s WHERE comment_post_ID = %3$d AND comment_parent = 0 AND %1$s < 3',
	'comment_karma',
	$wpdb->comments,
	url_to_postid( $_SERVER['REQUEST_URI'] )
);
$count_negative_comment_karma = $wpdb->get_var( $query );

get_header(); ?>

<div class="reviews">
	<div class="container">
		<div class="reviews-content">
			<h1>ОТЗЫВЫ</h1>

			<ul class="filter">
				<li @click="filterComments('positive')"><a href="javascript:void(0)">Позитивные <span>(<?php echo $count_comment_karma - $count_negative_comment_karma; ?>)</span></a></li>
				<li @click="filterComments('all')"><a href="javascript:void(0)">Все отзывы <span>(<?php echo $count_comment_karma; ?>)</span></a></li>
				<li @click="filterComments('negative')"><a href="javascript:void(0)">Негативные <span>(<?php echo $count_negative_comment_karma; ?>)</span></a></li>
			</ul>

			<div class="rating clearfix">
				<div class="containerdiv">
					<div>
                        <img bp-pso-lazyload-skip src="<?php echo get_template_directory_uri(); ?>/img/stars_blank.png" alt="img" />
                    </div>
					<div class="cornerimage" style="width:<?php printf( '%f', $average_comment_karma * 20 ); ?>%;">
                        <img bp-pso-lazyload-skip src="<?php echo get_template_directory_uri(); ?>/img/stars_full.png" alt="" />
                    </div>
				</div>
			</div>

			<div class="rating_sum">
				<span>Средняя оценка: <?php printf( '%01.2f',$average_comment_karma ); ?></span>
			</div>

			<a @click.stop.prevent="openPopup" href="javascript:void(0)" class="reviews-feedback" v-cloak>Оставить отзыв</a>

			<div class="reviews-items">

				<div v-for="comment in comments" :class="[ 'item-reviews', { 'answer': comment.is_answer } ]" :key="comment.id" v-cloak>
					<ul v-if=" ! comment.is_answer" v-once>
						<li v-for="n in 5">
							<img bp-pso-lazyload-skip v-if="n > comment.karma" src="<?php echo get_template_directory_uri(); ?>/img/rating-no.png"  alt="" />
							<img bp-pso-lazyload-skip v-else src="<?php echo get_template_directory_uri(); ?>/img/rating-yes.png" alt="" />
						</li>
					</ul>
					<p v-once>{{ comment.content }}</p>
					<span v-once>{{ comment.author }} <i>{{ comment.date }}</i></span>
				</div>

			</div>

			<a v-if="loadMore" @click.stop.prevent="getComments(currentPage)" href="javascript:void(0)" class="reviews-feedback" v-cloak>Еще</a>

		</div>
	</div>
</div>

<?php get_footer(); ?>
