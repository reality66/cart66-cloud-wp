<?php

class CS_MetaBox {

  public static function add_memberships_box() {
    $screens = array('post', 'page');
    $screens = apply_filters('csm_meta_box_pages', $screens);

    foreach($screens as $screen) {
      add_meta_box(
        'csm_membership_ids',
        __('Membership Requirements', 'cloudswipe_memberships'),
        array(__CLASS__, 'render_memberships_box'),
        $screen,
        'side'
      );
    }
  }

  public static function render_memberships_box($post) {
    $lib = new CS_Library();
    try {
      $memberships = $lib->get_expiring_products();
      CS_Log::write("Expiring products data: " . print_r($memberships, true));
    }
    catch(CS_Exception_API $e) {
      $memberships = array(
        array(
          'name' => 'Products unavailable',
          'sku' => ''
        )
      );
    }

    $requirements = get_post_meta($post->ID, 'csm_required_memberships', true);
    $days = get_post_meta($post->ID, 'csm_days_in', true);
    $when_logged_in = get_post_meta($post->ID, 'csm_when_logged_in', true);
    $when_logged_out = get_post_meta($post->ID, 'csm_when_logged_out', true);
    $post_type = get_post_type($post->ID);
    $data = array(
      'memberships' => $memberships, 
      'requirements' => $requirements, 
      'days' => $days,
      'when_logged_in' => $when_logged_in,
      'when_logged_out' => $when_logged_out,
      'post_type' => $post_type
    );
    echo CS_View::get(CS_PATH . 'views/admin/memberships_box.phtml', $data);
  }

  public function save_membership_requirements() {
    // Don't do anything during autosaves
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }

    // Don't do anythingn if the nonce cannot be verified
    if( isset($_POST['csm_membership_ids_nonce']) && 
      !wp_verify_nonce($_POST['csm_membership_ids_nonce'], 'csm_save_membership_ids')) { 
      return;
    }

    if(isset($_POST['post_ID'])) {
      $post_ID = $_POST['post_ID'];
      $membership_ids = (isset($_POST['csm_membership_ids'])) ? $_POST['csm_membership_ids'] : array();
      $days = (isset($_POST['csm_days_in'])) ? (int)$_POST['csm_days_in'] : 0;
      $when_logged_in = (isset($_POST['csm_when_logged_in'])) ? $_POST['csm_when_logged_in'] : '';
      $when_logged_out = (isset($_POST['csm_when_logged_out'])) ? $_POST['csm_when_logged_out'] : '';
      update_post_meta($post_ID, 'csm_required_memberships', $membership_ids);
      update_post_meta($post_ID, 'csm_days_in', $days);
      update_post_meta($post_ID, 'csm_when_logged_in', $when_logged_in);
      update_post_meta($post_ID, 'csm_when_logged_out', $when_logged_out);
    }
  }

}
