<?php

global $post, $wp_locale;

$month_genitive = array_values( $wp_locale->month_genitive );

$posts_pagination = paginate_links(
	[
		'type'      => 'list',
		'mid_size'  => 1,
		'prev_text' => '<svg width="16" height="16" viewBox="0 0 13 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.2693 4.1057L12.2895 4.10986H3.59054L6.32519 1.54048C6.4591 1.41504 6.53255 1.24511 6.53255 1.06675C6.53255 0.888399 6.4591 0.719657 6.32519 0.593918L5.89968 0.194802C5.76587 0.0693597 5.58757 0 5.39743 0C5.20719 0 5.02878 0.0688644 4.89498 0.194306L0.207263 4.58864C0.0729296 4.71458 -0.000525624 4.88233 2.83161e-06 5.06078C-0.000525624 5.24023 0.0729296 5.40808 0.207263 5.53382L4.89498 9.92855C5.02878 10.0539 5.20708 10.1229 5.39743 10.1229C5.58757 10.1229 5.76587 10.0538 5.89968 9.92855L6.32519 9.52943C6.4591 9.40419 6.53255 9.23693 6.53255 9.05858C6.53255 8.88032 6.4591 8.72189 6.32519 8.59654L3.55968 6.0128H12.279C12.6708 6.0128 13 5.69622 13 5.32911V4.76462C13 4.39751 12.661 4.1057 12.2693 4.1057Z" fill="#9C9C9E"/></svg>',
		'next_text' => '<svg width="16" height="16" viewBox="0 0 13 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.730748 6.01735L0.710455 6.01319L9.40946 6.01319L6.67481 8.58257C6.5409 8.70801 6.46745 8.87794 6.46745 9.05629C6.46745 9.23465 6.5409 9.40339 6.67481 9.52913L7.10032 9.92825C7.23413 10.0537 7.41243 10.123 7.60257 10.123C7.79281 10.123 7.97122 10.0542 8.10502 9.92874L12.7927 5.5344C12.9271 5.40847 13.0005 5.24072 13 5.06226C13.0005 4.88282 12.9271 4.71497 12.7927 4.58923L8.10502 0.194498C7.97122 0.0691553 7.79292 0.000192003 7.60257 0.000192003C7.41243 0.000192003 7.23413 0.0692543 7.10032 0.194498L6.67481 0.593614C6.5409 0.718858 6.46745 0.886114 6.46745 1.06447C6.46745 1.24272 6.5409 1.40116 6.67481 1.5265L9.44032 4.11025L0.721024 4.11025C0.329227 4.11025 8.1177e-09 4.42683 8.1177e-09 4.79394V5.35843C8.1177e-09 5.72554 0.338951 6.01735 0.730748 6.01735Z" fill="#9C9C9E"/></svg>',
	]
);

get_header( 'home' ); ?>

<section class="blog-page">
<section class="page-title">
        <div class="container">
            <div class="page-title-cont">
                <h1><?php _e('News', 'tehnokrat') ?></h1>
            </div>
        </div>
    </section>
    <div class="item-bg"></div>
    <div class="blog-items">
        <a href="<?= site_url( '/' ) ?>" class="back-button">
            <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0)">
                    <path d="M15.1006 6.32368L15.1256 6.3288H4.41913L7.78485 3.16648C7.94966 3.01209 8.04007 2.80295 8.04007 2.58343C8.04007 2.36392 7.94966 2.15624 7.78485 2.00148L7.26114 1.51026C7.09646 1.35587 6.87701 1.27051 6.643 1.27051C6.40885 1.27051 6.18927 1.35526 6.02459 1.50965L0.255093 6.91807C0.0897596 7.07307 -0.000646921 7.27953 3.48506e-06 7.49917C-0.000646921 7.72002 0.0897596 7.92661 0.255093 8.08136L6.02459 13.4903C6.18927 13.6445 6.40872 13.7294 6.643 13.7294C6.87701 13.7294 7.09646 13.6444 7.26114 13.4903L7.78485 12.999C7.94966 12.8449 8.04007 12.639 8.04007 12.4195C8.04007 12.2001 7.94966 12.0051 7.78485 11.8509L4.38114 8.67087H15.1126C15.5948 8.67087 16 8.28124 16 7.82941V7.13465C16 6.68282 15.5828 6.32368 15.1006 6.32368Z"
                          fill="#222222"/>
                </g>
                <defs>
                    <clipPath id="clip0">
                        <rect width="16" height="15" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
            на главную</a>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php $date = getdate( strtotime( $post->post_date ) ); ?>

            <div class="item">
                <a href="<?php the_permalink() ?>"></a>
                <div class="date">
                    <p><?= $date['mday'] ?></p>
                    <span><?= $month_genitive[ $date['mon'] - 1 ] ?></span>
                </div>
                <h2 class="title"><?php the_title() ?></h2>
                <h3 class="desc"><?php the_excerpt() ?></h3>
                <div class="img">
					<?php the_post_thumbnail() ?>
                </div>
            </div>

		<?php endwhile; // end of the loop. ?>

    </div>
</section>

<?= $posts_pagination ?>

<?php get_footer(); ?>
