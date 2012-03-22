<?php
/*
Plugin Name: fancy-captcha
Plugin URI: http://leo108.com/pid-1220.asp
Description: Ajax Fancy Captcha is a jQuery plugin that helps you protect your web pages from bots and spammers. We are introducing you to a new, intuitive way of completing “verify humanity” tasks. In order to do that you are asked to drag and drop specified item into a circle.通过拖动解锁来实现评论验证。能够有效防止垃圾评论、机器人评论。
Version: 1.2.1
Author: leo108
Author URI: http://leo108.com/
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
function captcha_footer() {
	if ( is_single()||is_page() )
	{
		$current_path = get_option('siteurl') .'/wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
		?>
		<script type="text/javascript" src="<?php echo $current_path; ?>captcha/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="<?php echo $current_path; ?>captcha/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo $current_path; ?>captcha/jquery.captcha.js"></script>
		<script type="text/javascript" charset="utf-8">
		$(function() {
			$(".ajax-fc-container").captcha({
				borderColor: "silver",
				captchaDir: "<?php echo $current_path; ?>captcha",
				url: "<?php echo $current_path; ?>captcha/captcha.php",
				formId: "commentform",
				text: "<?php echo __("verify that you are a human,<br />drag <span>scissors</span> into the circle.",'fancy-captcha'); ?>",
				lang:Array("<?php echo __("pencil",'fancy-captcha'); ?>","<?php echo __("scissors",'fancy-captcha'); ?>","<?php echo __("clock",'fancy-captcha'); ?>","<?php echo __("heart",'fancy-captcha'); ?>","<?php echo __("note",'fancy-captcha'); ?>")
			});
		});
	</script>
	<?php
	}
}
function captcha_head() {
	if ( is_single()||is_page() )
	{
		$current_path = get_option('siteurl') .'/wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
		?>
		<link type="text/css" rel="stylesheet" href="<?php echo $current_path; ?>captcha/captcha.css" />
		<?php
	}
}
function captcha_filter($comment) {
	session_start();
	if(is_user_logged_in() || ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha']))
	{
		return $comment;
	}else{
		wp_die( __('you put the wrong thing in the circle , please try again . <input onclick="javascript:history.go(-1)" type="button" value="back" />','fancy-captcha'));
	}
}
function captcha_form()
{
?>
	<div class="ajax-fc-container"><?php echo __("you must enable javascript to see captcha here!",'fancy-captcha'); ?></div>
<?php
}
function captcha_init() {
	$plugin_dir = dirname(plugin_basename(__FILE__));
	load_plugin_textdomain( 'fancy-captcha', false , $plugin_dir.'/language' );
}
add_action( 'wp_footer' , 'captcha_footer' );
add_action( 'wp_head' , 'captcha_head' );
add_action( 'comment_form', 'captcha_form', 1 );
add_action( 'init' , 'captcha_init');
add_filter( 'preprocess_comment', 'captcha_filter' , 1 );	
?>