<div class="cc-product-review">

    <h3 class="cc-product-reivew-title">
        <?php echo $review->title; ?>
    </h3>

    <div class="cc-product-review-rating">
        <?php 
            for( $i = 0; $i < $review->rating; $i++ ) {
                echo '<span class="cc-product-review-star">&#9733</span>';
            }
        ?>
        
        <span class="cc-product-review-date">
            <?php echo date('F d, Y', strtotime($review->date) ); ?>
        </span>
    </div>

    <div class="cc-product-review-content">
        <?php echo wpautop( $review->content ); ?>
    </div>

    <div class="cc-product-review-name">
        <p><?php echo $review->name; ?></p>
    </div>

</div>
