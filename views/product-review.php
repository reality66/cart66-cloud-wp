<div class="cc_product_review">

    <h3 class="cc_product_reivew_title">
        <?php echo $review->title; ?>
    </h3>

    <div class="cc_product_review_rating">
        <?php 
            for( $i = 0; $i < $review->rating; $i++ ) {
                echo '<span class="cc_product_review_star">&#9733</span>';
            }
        ?>
        
        <span class="cc_product_review_date">
            <?php echo date('F d, Y', strtotime($review->date) ); ?>
        </span>
    </div>

    <div class="cc_product_review_content">
        <?php echo wpautop( $review->content ); ?>
    </div>

    <div class="cc_product_review_name">
        <p><?php echo $review->name; ?></p>
    </div>

</div>
