<div class="comments">
	<?php if (post_password_required()) : ?>
	<p><?php _e( 'Post is password protected. Enter the password to view any comments.', 'html5blank' ); ?></p>
</div>

	<?php return; endif; ?>

<?php if (have_comments()) : ?>

	<h2><?php comments_number(); ?></h2>

	<ul>
		<?php wp_list_comments('type=comment&callback=html5blankcomments'); // Custom callback in functions.php ?>
	</ul>

<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	
	<p><?php _e( 'Comments are closed here.', 'html5blank' ); ?></p>
	
<?php endif; ?>

<?php 
$args = array(
  'title_reply'       => __( 'Leave a Comment' ),
  'label_submit'      => __( 'Submit' ),
  'fields' => apply_filters( 'comment_form_default_fields', array(
      'author' =>
        '<p class="comment-form-author">' .
        '<label for="author">' . __( 'Name', 'domainreference' ) . '</label>' .
        ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30"' . $aria_req . ' /></p>',

      'email' =>
        '<p class="comment-form-email"><label for="email">' . __( 'Email', 'domainreference' ) . '</label>' .
        ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30"' . $aria_req . ' /></p>',
      )
    )
);

comment_form($args); 
?>

</div>