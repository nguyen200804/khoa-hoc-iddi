<?php
/**
 * Template for displaying course curriculum in popup
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.2
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="popup-sidebar">
	<?php
	// Gọi template tùy chỉnh cho phần danh sách bài học (Curriculum)
	learn_press_get_template( 'single-course/curriculum-sidebar.php' );
	?>
</div>
