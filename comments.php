<?php
/**
 * The template for displaying comments
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="iddi-comments-area">

    <div class="iddi-comments-header d-flex flex-jc-between flex-ai-center">
        <h3 class="iddi-comments-title">
             Bình luận (<?php echo get_comments_number(); ?>)
        </h3>
        <div class="iddi-comments-sort d-flex flex-ai-center">
            <span>Sắp xếp:</span>
            <select name="comment_sort" id="comment_sort" class="reset-select">
                <option value="newest">Mới nhất</option>
                <option value="oldest">Cũ nhất</option>
            </select>
        </div>
    </div>

    <?php
    comment_form( array(
        'title_reply' => '',
        'comment_field' => '<div class="iddi-comment-form-inner d-flex">
                                <div class="iddi-comment-avatar">' . get_avatar(get_current_user_id(), 48) . '</div>
                                <div class="iddi-comment-textarea-wrap flex-1">
                                    <textarea id="comment" name="comment" placeholder="Thêm bình luận..." required class="full-width"></textarea>
                                </div>
                            </div>',
        'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="iddi-comment-submit-btn">%4$s</button>',
        'submit_field' => '<div class="iddi-comment-form-footer d-flex flex-jc-end">%1$s %2$s</div>',
        'label_submit' => 'Gửi bình luận',
    ) );
    ?>

    <?php if ( have_comments() ) : ?>
        <ul class="iddi-comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ul',
                'short_ping' => true,
                'avatar_size' => 48,
                'callback' => 'iddi_comment_callback'
            ) );
            ?>
        </ul>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            <nav class="iddi-comments-navigation pagination d-flex flex-jc-center">
                <?php paginate_comments_links(); ?>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

</div><!-- #comments -->
