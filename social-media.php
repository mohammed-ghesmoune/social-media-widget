<?php

/*
Plugin Name: Social Media Menu
Description: Used to add a social media menu to your pages.
Version: 1.0.0
Author: ghm
Text Domain: socialmedia
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('SocialMedia_Widget')) {

  class SocialMedia_Widget extends WP_Widget
  {
    const ICONS_CSS_CLASSES = [
      "facebook" => "fab fa-facebook-f",
      "instagram" => "fab fa-instagram",
      "twitter" => "fab fa-twitter",
      "snapchat" => "fab fa-snapchat",
      "youtube" => "fab fa-youtube",
      "pinterest" => "fab fa-pinterest",
      "linkedin" => "fab fa-linkedin"
    ];

    public function __construct()
    {
      load_plugin_textdomain('socialmedia');

      parent::__construct(
        'social-media',
        __('Social Media', 'socialmedia'),
      );

      add_action('widgets_init', [$this, 'init']);
      add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
      add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    public function init()
    {
      register_widget(self::class);
    }

    public function enqueue_admin_scripts($hook)
    {
      if ($hook == 'widgets.php') {
        wp_register_script('font-awesome-icons', 'https://use.fontawesome.com/releases/v5.13.0/js/all.js', [], false, true);
        wp_enqueue_script('font-awesome-icons');
        wp_register_script('socialmedia-admin-script', plugins_url('js/social-media-admin.js', __FILE__), ['jquery'], false, true);
        wp_enqueue_script('socialmedia-admin-script');
        wp_register_style('socialmedia-admin-style', plugins_url('css/social-media-admin.css', __FILE__), [], false, 'all');
        wp_enqueue_style('socialmedia-admin-style');
      }
    }

    public function enqueue_scripts()
    {
      if (is_active_widget(false, false, $this->id_base)) {
        wp_register_script('font-awesome-icons', 'https://use.fontawesome.com/releases/v5.13.0/js/all.js', [], false, true);
        wp_enqueue_script('font-awesome-icons');
        wp_register_style('socialmedia-style', plugins_url('css/social-media.css', __FILE__), [], false, 'all');
        wp_enqueue_style('socialmedia-style');
      }
    }

    public $args = array(
      'before_title'  => '<h4 class="widgettitle">',
      'after_title'   => '</h4>',
      'before_widget' => '<div class="widget-wrap social-icons">',
      'after_widget'  => '</div></div>'
    );

    public function widget($args, $instance)
    {

      echo $args['before_widget'];
      foreach ($instance['social_media'] as $value) {

        $href = !empty($value['href']) ? esc_url($value['href']) : '#';
        $class = !empty($value['class']) ? esc_attr($value['class']) : '';

        echo '<a class="social-icon" href="' . $href . '" target="_blank"><i class="' . $class . '"></i></a>';
      }
      echo $args['after_widget'];
    }
    public function form($instance)
    {
?>

      <div id="widget-sm-<?= $this->number ?>" data-widget-number="<?= $this->number ?>">
      <div style="margin:10px 0px 5px 0px;"><small>* Click to add a social media</small></div>
        <div class="widget-social-icons" id="widget-social-icons" style="margin-bottom:20px;">
          <?php foreach (self::ICONS_CSS_CLASSES as $class) : ?>
            <div class="social-icon" data-class="<?= $class ?>" onclick="widget.addSocialMedia(this)"><i class="<?= $class ?>"></i></div>
          <?php endforeach ?>
        </div>

        <?php foreach (($instance['social_media'] ?: []) as $k => $v) : ?>
          <div style="display:flex; padding:3px 0px;" class="sm-input-wrapper">
            <div class="widget-social-icons">
              <div class="social-icon"><i class="<?= esc_attr($v['class']) ?>"></i></div>
            </div>
            <input class="widefat" name="<?= esc_attr($this->get_field_name('social_media[' . $k . '][class]')); ?>" type="hidden" value="<?= esc_attr($v['class']) ?: ''; ?>">
            <input class="widefat" name="<?= esc_attr($this->get_field_name('social_media[' . $k . '][href]')); ?>" type="text" value="<?= esc_attr($v['href']) ?: ''; ?>" placeholder="<?= __("Enter link url", "socialmedia") ?>">
            <div class="widget-social-icons">
              <div class="delete" onclick="widget.deleteSocialMedia(this)"><i class="fas fa-times"></i></div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
<?php

    }

    public function update($new_instance, $old_instance)
    {
      $instance = array();

      $instance['social_media'] = (!empty($new_instance['social_media'])) ? $new_instance['social_media'] : '';

      return $instance;
    }
  }
}

new SocialMedia_Widget();
?>