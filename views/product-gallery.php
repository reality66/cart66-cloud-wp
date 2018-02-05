<?php if ( count( $images ) ): ?>
    <div class="cc-gallery">

        <?php if ( count( $thumbs ) > 1 ): ?>
            <div class="cc-gallery-gutter">
                <?php $image_index = 0; ?>
                <?php foreach( $thumbs as $key => $thumb_src ): ?>
                    <a href="#" class="cc-gallery-thumb-link" id="cc-gallery-thumb-<?php echo $key; ?>" data-ref="cc-full-<?php echo $key; ?>" data-index="<?php echo $image_index; ?>"><img class="cc-gallery-thumb" src="<?php echo $thumb_src; ?>" /></a>
                    <?php $image_index += 1; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="cc-gallery-product-image">
            <?php $image_index = 0; ?>
            <?php foreach( $images as $key => $image_src ): ?>
                <?php if ( 'image1' == $key ): ?>
                    <a href="<?php echo $image_src[1] ?>" data-featherlight="<?php echo $image_src[1] ?>"><img class="cc-gallery-full-image cc-image-index-<?php echo $image_index; ?>" id="cc-full-<?php echo $key; ?>" src="<?php echo $image_src[0]; ?>" /></a>
                <?php else: ?>
                    <a href="<?php echo $image_src[1] ?>" data-featherlight="<?php echo $image_src[1] ?>"><img class="cc-gallery-full-image cc-image-index-<?php echo $image_index; ?>" id="cc-full-<?php echo $key; ?>" style="display:none;" src="<?php echo $image_src[0]; ?>" /></a>
                <?php endif; ?>

                <?php $image_index += 1; ?>
            <?php endforeach; ?>
            <p class="cc-gallery-note"><?php _e( 'click image to zoom', 'cart66' ); ?></p>
        </div>

    </div>
<?php endif; ?>
