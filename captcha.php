<?php
/*
Plugin Name: fancy-captcha
Plugin URI: http://leo108.com/pid-1220.asp
Description: Ajax Fancy Captcha is a jQuery plugin that helps you protect your web pages from bots and spammers. We are introducing you to a new, intuitive way of completing “verify humanity” tasks. In order to do that you are asked to drag and drop specified item into a circle.通过拖动解锁来实现评论验证。能够有效防止垃圾评论、机器人评论。
Version: 1.5
Author: leo108
Author URI: http://leo108.com/
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
function captcha_footer() {
    $options = get_option( 'fancycaptcha_options' );
	if ( (is_single()||is_page()) && !is_user_logged_in())
	{
		$current_path = get_option('siteurl') .'/wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
        $notice = $options['Notice']?$options['Notice']:'verify that you are a human,<br />drag %item% into the circle.';
        $text = str_ireplace('%item%','<span></span>',$notice);
        $pencil = $options['Pencil']?$options['Pencil']:'Pencil';
        $scissors = $options['Scissors']?$options['Scissors']:'Scissors';
        $clock = $options['Clock']?$options['Clock']:'Clock';
        $heart = $options['Heart']?$options['Heart']:'Heart';
        $note = $options['Note']?$options['Note']:'Note';
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
				text: "<?php echo $text; ?>",
				lang:Array("<?php echo $pencil; ?>","<?php echo $scissors; ?>","<?php echo $clock; ?>","<?php echo $heart; ?>","<?php echo $note; ?>")
			});
		});
        </script>
	<?php
	}
}
function captcha_head() {
	if ( (is_single()||is_page()) && !is_user_logged_in())
	{
		$current_path = get_option('siteurl') .'/wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
		?>
		<link type="text/css" rel="stylesheet" href="<?php echo $current_path; ?>captcha/captcha.css" />
		<?php
	}
}
function captcha_filter($comment) {
	session_start();
    $options = get_option( 'fancycaptcha_options' );
	if(is_user_logged_in() || ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha']))
	{
		return $comment;
	}else{
		wp_die( ($options['Error']?$options['Error']:'You put the wrong thing in the circle , Please try again').'<input onclick="javascript:history.go(-1)" type="button" value="'.($options['Back']?$options['Back']:'Back').'" />');
	}
}
function captcha_form()
{
    $options = get_option( 'fancycaptcha_options' );
	if ( (is_single()||is_page()) && !is_user_logged_in())
	{
?>
        <div class="ajax-fc-container"><?php echo $options['Javascript']?$options['Javascript']:'You must enable javascript to see captcha here!'; ?></div>
<?php
    }
}

add_action('admin_init', 'fancycaptcha_admin_init');
function fancycaptcha_admin_init(){
    register_setting( 'fancycaptcha_options', 'fancycaptcha_options');
    add_settings_section('fancycaptcha_main', 'Settings', 'fancycaptcha_section', 'fancycaptcha');
    add_settings_field('Notice', 'Verify that you are a human,Please drag %item% into the circle.', 'fancycaptcha_Notice', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Pencil', 'Pencil', 'fancycaptcha_Pencil', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Scissors', 'Scissors', 'fancycaptcha_Scissors', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Clock', 'Clock', 'fancycaptcha_Clock', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Heart', 'Heart', 'fancycaptcha_Heart', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Note', 'Note', 'fancycaptcha_Note', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Javascript', 'You must enable javascript to see captcha here!', 'fancycaptcha_Javascript', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Error', 'You put the wrong thing in the circle , Please try again', 'fancycaptcha_Error', 'fancycaptcha', 'fancycaptcha_main');
    add_settings_field('Back', 'Back', 'fancycaptcha_Back', 'fancycaptcha', 'fancycaptcha_main');
}
function fancycaptcha_section() {
    echo '<p>Please translate these words into your language.</p>';
}
function fancycaptcha_Notice()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Notice]" id="Notice" value="<?php echo $options['Notice']?$options['Notice']:'verify that you are a human,<br />drag %item% into the circle.'; ?>" /><br />
<div>DO NOT translate the %item%!!</div>
<?php
}
function fancycaptcha_Pencil()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Pencil]" id="Pencil" value="<?php echo $options['Pencil']?$options['Pencil']:'Pencil'; ?>" /><br />
<?php
}
function fancycaptcha_Scissors()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Scissors]" id="Scissors" value="<?php echo $options['Scissors']?$options['Scissors']:'Scissors'; ?>" /><br />
<?php
}
function fancycaptcha_Clock()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Clock]" id="Clock" value="<?php echo $options['Clock']?$options['Clock']:'Clock'; ?>" /><br />
<?php
}
function fancycaptcha_Heart()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Heart]" id="Heart" value="<?php echo $options['Heart']?$options['Heart']:'Heart'; ?>" /><br />
<?php
}
function fancycaptcha_Note()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Note]" id="Note" value="<?php echo $options['Note']?$options['Note']:'Note'; ?>" /><br />
<?php
}
function fancycaptcha_Javascript()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Javascript]" id="Javascript" value="<?php echo $options['Javascript']?$options['Javascript']:'You must enable javascript to see captcha here!'; ?>" /><br />
<?php
}
function fancycaptcha_Error()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Error]" id="Error" value="<?php echo $options['Error']?$options['Error']:'You put the wrong thing in the circle , Please try again'; ?>" /><br />
<?php
}
function fancycaptcha_Back()
{
$options = get_option( 'fancycaptcha_options' );
?>
<input type="text" name="fancycaptcha_options[Back]" id="Back" value="<?php echo $options['Back']?$options['Back']:'Back'; ?>" /><br />
<?php
}
function fancycaptcha_menu() {
    add_options_page('Fancy-Captcha Settings', 'Fancy-Captcha Settings', 'manage_options', 'captcha', 'fancycaptcha_options_page');
}
function fancycaptcha_options_page() {
        $options = get_option( 'fancycaptcha_options' );
?>
    <div class="wrap">
        <h2>Fancy-Captcha Settings</h2>
        <div class="narrow">
            <form action="options.php" method="post">
                <p>Ajax Fancy Captcha is a jQuery plugin that helps you protect your web pages from bots and spammers. We are introducing you to a new, intuitive way of completing “verify humanity” tasks. In order to do that you are asked to drag and drop specified item into a circle.</p>
                <?php settings_fields('fancycaptcha_options'); ?>
                <?php do_settings_sections('fancycaptcha'); ?>
                <p class="submit">
                    <input name="submit" type="submit" class="button-primary" value="Save Change" />
                </p>
            </form>
        </div>
    </div>
<?php
}
add_action('admin_menu','fancycaptcha_menu');

add_action( 'wp_footer' , 'captcha_footer' );
add_action( 'wp_head' , 'captcha_head' );
add_action( 'comment_form', 'captcha_form', 1 );
add_action( 'init' , 'captcha_init');
add_filter( 'preprocess_comment', 'captcha_filter' , 1 );	
?>