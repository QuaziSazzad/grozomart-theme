<?php

namespace GrozomartTheme\Classes;

use Walker_Comment;

defined('ABSPATH') || exit;

if (! class_exists('Grozomart_Comment_Walker')) {
    class Grozomart_Comment_Walker extends Walker_Comment
    {

        /**
         * Starts the element output.
         */
        public function start_el(&$output, $data_object, $depth = 0, $args = [], $current_object_id = 0)
        {
            $depth++;
            $GLOBALS['comment_depth'] = $depth;
            $GLOBALS['comment']       = $data_object;

            ob_start();

            if (! empty($args['callback'])) {
                call_user_func($args['callback'], $data_object, $args, $depth);
                $output .= ob_get_clean();

                return;
            }

            if (('pingback' == $data_object->comment_type || 'trackback' == $data_object->comment_type) && $args['short_ping']) {
                $this->ping($data_object, $depth, $args);
            } else {
                $this->comment($data_object, $depth, $args);
            }

            $output .= ob_get_clean();
        }

        /**
         * Outputs a Ping-back comment.
         */
        protected function ping($comment, $depth, $args)
        { ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
                <div class="comment-body" id="div-comment-<?php comment_ID() ?>">
                    <div class="comment-content">
                        <div class="author-info">
                            <h5 class="name"><?php printf('%s', get_comment_author_link()) ?></h5>
                            <span class="date"><?php printf('%1$s', get_comment_date()) ?></span>
                        </div>
                        <div class="comment-text">
                            <?php comment_text() ?>
                        </div>
                    </div>
                </div>
            <?php }

        /**
         * Outputs a single comment.
         */
        protected function comment($comment, $depth, $args)
        {
            $max_depth_comment = min($args['max_depth'], 4);
            $GLOBALS['comment'] = $comment;

            // Nested replies get the offset variant in this design.
            $comment_class = 'blog-single-comment';
            if ($depth > 1) {
                $comment_class .= ' style-2';
            }
            ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
                <div class="<?php echo esc_attr($comment_class); ?>" id="comment-<?php comment_ID(); ?>">
                    <div class="image">
                        <?php echo get_avatar($comment->comment_author_email, 80); ?>
                    </div>
                    <div class="content">
                        <h4>
                            <span><?php printf('%s', get_comment_author_link()); ?></span> - <?php printf('%1$s', get_comment_date()); ?>
                        </h4>
                        <div class="single-box">
                            <?php if ($comment->comment_approved == '0'): ?>
                                <p><?php esc_html_e('Your comment is awaiting moderation.', 'grozomart'); ?></p>
                            <?php endif; ?>
                            <?php comment_text(); ?>
                        </div>
                        <?php
                        // Core hardcodes class="comment-reply-link"; the design needs .reply.
                        $reply_link = get_comment_reply_link(array_merge($args, [
                            'depth'      => $depth,
                            'reply_text' => esc_html__('Reply', 'grozomart') . '<i class="fa-solid fa-arrow-right-long"></i>',
                            'max_depth'  => $max_depth_comment,
                        ]));

                        echo str_replace("class='comment-reply-link", "class='reply comment-reply-link", $reply_link);
                        ?>
                    </div>
                </div>
    <?php
        }
    }
}
