<?php

$blog_url    = get_permalink(get_option('page_for_posts'));
$referer_url = empty($_SERVER["HTTP_REFERER"]) ? $blog_url : $_SERVER["HTTP_REFERER"];
$back_url    = 0 === strpos($referer_url, site_url()) ? $referer_url : $blog_url;

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="container">
  <section class="blog-article-page">
    <div class="one-article">
      <div class="img">
        <?php the_post_thumbnail() ?>
        <h1 class="title"><?php the_title() ?></h1>
        <a class="share">
          <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M10.8333 10.6024C10.2844 10.6024 9.79333 10.8283 9.41778 11.1822L4.26833 8.05723C4.30444 7.88404 4.33333 7.71084 4.33333 7.53012C4.33333 7.3494 4.30444 7.17621 4.26833 7.00301L9.36 3.90813C9.75 4.28464 10.2628 4.51807 10.8333 4.51807C12.0322 4.51807 13 3.50904 13 2.25904C13 1.00904 12.0322 0 10.8333 0C9.63444 0 8.66667 1.00904 8.66667 2.25904C8.66667 2.43976 8.69556 2.61295 8.73167 2.78614L3.64 5.88102C3.25 5.50452 2.73722 5.27108 2.16667 5.27108C0.967778 5.27108 0 6.28012 0 7.53012C0 8.78012 0.967778 9.78916 2.16667 9.78916C2.73722 9.78916 3.25 9.55572 3.64 9.17922L8.78222 12.3117C8.74611 12.4699 8.72444 12.6355 8.72444 12.8012C8.72444 14.0136 9.67056 15 10.8333 15C11.9961 15 12.9422 14.0136 12.9422 12.8012C12.9422 11.5889 11.9961 10.6024 10.8333 10.6024Z"
              fill="#222222"></path>
          </svg>
        </a>
        <p class="date"><?php the_date('d.m.Y') ?></p>
        <p class="subscribe"><?php _e('Subscribe', 'tehnokrat') ?>:
          <a target="_blank" href="https://www.threads.net/@tehnokrat.ua">
            <img src="/wp-content/uploads/2023/07/Threads_app.png">
          </a>
          <a target="_blank" href="https://www.instagram.com/tehnokrat.ua/">
            <img src="/wp-content/uploads/2022/12/inst-1.png">
          </a>
        </p>
      </div>
      <div class="description">
        <div class="desc-text">

          <?php the_content() ?>

        </div>
      </div>
    </div>
    <a class="button" href="<?php echo get_home_url() . '/blog/' ?>">
      <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0)">
          <path
            d="M15.1006 6.32368L15.1256 6.3288H4.41913L7.78485 3.16648C7.94966 3.01209 8.04007 2.80295 8.04007 2.58343C8.04007 2.36392 7.94966 2.15624 7.78485 2.00148L7.26114 1.51026C7.09646 1.35587 6.87701 1.27051 6.643 1.27051C6.40885 1.27051 6.18927 1.35526 6.02459 1.50965L0.255093 6.91807C0.0897596 7.07307 -0.000646921 7.27953 3.48506e-06 7.49917C-0.000646921 7.72002 0.0897596 7.92661 0.255093 8.08136L6.02459 13.4903C6.18927 13.6445 6.40872 13.7294 6.643 13.7294C6.87701 13.7294 7.09646 13.6444 7.26114 13.4903L7.78485 12.999C7.94966 12.8449 8.04007 12.639 8.04007 12.4195C8.04007 12.2001 7.94966 12.0051 7.78485 11.8509L4.38114 8.67087H15.1126C15.5948 8.67087 16 8.28124 16 7.82941V7.13465C16 6.68282 15.5828 6.32368 15.1006 6.32368Z"
            fill="#222222"></path>
        </g>
        <defs>
          <clipPath id="clip0">
            <rect width="16" height="15" fill="white"></rect>
          </clipPath>
        </defs>
      </svg>
      <?php _e('All publications', 'tehnokrat') ?></a>

    <?= do_shortcode('[tehnokrat_more_articles type="more" count="4"]<p>' . __("Other publication", "tehnokrat") . '</p>[/tehnokrat_more_articles]') ?>

  </section>
</section>

<?php endwhile; // end of the loop.
?>

<?php get_footer(); ?>