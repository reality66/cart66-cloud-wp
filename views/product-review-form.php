<form id="cc-product-review-form" class="cart66-form" action="#">

  <input type="hidden" name="action" value="cc_save_product_review" />
  <input type="hidden" name="review[sku]" value="<?php echo $sku; ?>" />

  <div>
    <label class="desc" id="review-rating-label" for="review_rating">
        Rating
    </label>
    <div>
    <select id="review-rating" name="review[rating]" class="field select medium" tabindex="1"> 
      <option value="5">5 Stars</option>
      <option value="4">4 Stars</option>
      <option value="3">3 Stars</option>
      <option value="2">2 Stars</option>
      <option value="1">1 Star</option>
    </select>
    </div>
  </div>

  <div>
    <label class="desc" id="review-name-label" for="review-name">
        Name
    </label>
    <div>
      <input id="review-name" name="review[name]" type="text" class="field text fn" value="" size="8" tabindex="2">
    </div>
  </div>
    
  <div>
    <label class="desc" id="review-email-label" for="review-email">
      Email
    </label>
    <div>
      <input id="review-email" name="review[email]" type="email" spellcheck="false" value="" maxlength="255" tabindex="3"> 
   </div>
  </div>

  <div>
    <label class="desc" id="review-title-label" for="review-title">
        Title
    </label>
    <div>
      <input id="review-title" name="review[title]" type="text" class="field text fn" value="" size="8" tabindex="4">
    </div>
  </div>
    
  <div>
    <label class="desc" id="review-content-label" for="review-content">
        Review
    </label>
  
    <div>
      <textarea id="review-content" name="review[content]" spellcheck="true" rows="5" cols="30" tabindex="5"></textarea>
    </div>
  </div>
  
  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>

  <div>
    <div>
        <input id="cart66-product-reivew-save-button" name="save_button" type="submit" value="Submit Review">
        <img id="cc-review-processing" src="<?php echo cc_url() . 'resources/css/select2-spinner.gif' ?>" alt="processing" />
    </div>
  </div>
  
</form>

<div id="cc-product-review-received">
    <?php echo $thank_you_message; ?> 
</div>

<div id="cc-product-review-failed">
    We did not receive your review. Please be sure to check the "I'm not a robot" checkbox. <br>
    If you need further assistance, please contact us.
</div>
