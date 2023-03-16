<?php
/* @global array $atts */
/* @global string $content */
/* @global array $articles */
?>

<?php if ( 'latest' === $atts['type'] ) : ?>
<section class="last-news">
    <?= $content ?>
    <div class="news-slider">
<?php else: ?>
<div class="more-article">
    <?= $content ?>
<?php endif; ?>

<?php foreach ( $articles as $article ) : ?>
    <div class="more-article-item">
        <a href="<?= esc_url( $article['permalink'] ) ?>"></a>
        <img src="<?= esc_attr( $article['image_src'] ) ?>" alt="<?= esc_attr( $article['title'] ) ?>"/>
        <p class="date"><?= esc_html( $article['date'] ) ?></p>
        <h3 class="title"><?= esc_html( $article['title'] ) ?></h3>
    </div>
<?php endforeach; ?>

<?php if ( 'latest' === $atts['type'] ) : ?>
    </div>
    <a class="more-news" href="/blog">все новости <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0)">
                <path d="M1.90387 9.70265L1.87888 9.69762L12.5853 9.65803L9.23127 12.8328C9.06703 12.9878 8.97739 13.1972 8.9782 13.4168C8.97902 13.6363 9.07019 13.8436 9.23557 13.9978L9.76109 14.487C9.92635 14.6408 10.1461 14.7254 10.3801 14.7245C10.6143 14.7236 10.8335 14.6381 10.9976 14.4831L16.7471 9.05337C16.9119 8.89776 17.0015 8.69097 17 8.47134C16.9999 8.25048 16.9087 8.04423 16.7428 7.89009L10.9533 2.50255C10.7881 2.3489 10.5683 2.26483 10.3341 2.2657C10.1 2.26656 9.88091 2.35237 9.7168 2.50713L9.19491 3.00028C9.03067 3.15503 8.94103 3.36122 8.94184 3.58073C8.94265 3.80012 9.03378 3.99478 9.19916 4.14844L12.6146 7.31583L1.88323 7.35551C1.40102 7.35729 0.997257 7.74842 0.998928 8.20025L1.0015 8.895C1.00317 9.34683 1.42166 9.70443 1.90387 9.70265Z" fill="black"/>
            </g>
            <defs>
                <clipPath id="clip0">
                    <rect x="17.0277" y="15.9705" width="16" height="15" transform="rotate(179.788 17.0277 15.9705)" fill="white"/>
                </clipPath>
            </defs>
        </svg>
    </a>
</section>
<?php else: ?>
</div>
<?php endif; ?>
