<div class="cc_product_review">

    <h3 class="cc_product_reivew_title">
        <?php echo $review->title; ?>
    </h3>

    <div class="cc_product_review_rating">
        <p>Rating: <?php echo $review->rating; ?></p>
    </div>

    <div class="cc_product_review_content">
        <?php echo wpautop( $review->content ); ?>
    </div>

    <div class="cc_product_review_name">
        <p><?php echo $review->name; ?></p>
    </div>

</div>
