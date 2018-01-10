<div class="cc-product-review-average">
    <?php 
        $rounded_average = round( $average, 0 );

        for( $i = 0; $i < $rounded_average; $i++ ) {
            echo '<span class="cc-product-review-star">&#9733</span>';
        }
    ?>
    <span class="cc-product-total-reviews cc-product-review-small-text">
        <?php echo  $total; ?> customer <?php echo _n('review', 'reviews', $total); ?>
    </span>
    <br>

    <span class="cc-product-average-rating cc-product-review-small-text">
        <?php echo $average; ?> out of 5 stars
    </span>
</div>