<?php
/**
 * Template Name: Check Meta Key
 */

if (!current_user_can('manage_options')) {
    wp_die('Bạn không có quyền truy cập trang này.');
}

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'check-meta-key-style',
        get_template_directory_uri() . '/assets/css/page-templates/check-meta-key.css',
        array(),
        '1.0.0'
    );
});

get_header();

$object_id = isset($_GET['object_id']) ? intval($_GET['object_id']) : 0;
$object_type = isset($_GET['object_type']) ? sanitize_text_field($_GET['object_type']) : 'post';
$results = null;
$error = null;

if ($object_id > 0) {
    if ($object_type === 'post') {
        $post = get_post($object_id);
        if ($post) {
            $results = get_metadata('post', $object_id);
        } else {
            $error = "Không tìm thấy Post với ID: $object_id";
        }
    } elseif ($object_type === 'term') {
        $term = get_term($object_id);
        if ($term && !is_wp_error($term)) {
            $results = get_metadata('term', $object_id);
        } else {
            $error = "Không tìm thấy Term với ID: $object_id";
        }
    }
}
?>

<main class="check-meta-key bg-img-fixed">
    <div class="container">
        <h1 class="check-meta-key__title">Check Meta Key</h1>

        <div class="check-meta-key__form">
            <form method="get">
                <div class="check-meta-key__field">
                    <label class="check-meta-key__label" for="object_id">Nhập ID (Post hoặc Term):</label>
                    <input type="number" name="object_id" id="object_id" class="check-meta-key__input" value="<?php echo $object_id > 0 ? $object_id : ''; ?>" required>
                </div>

                <div class="check-meta-key__field">
                    <label class="check-meta-key__label">Loại đối tượng:</label>
                    <div class="check-meta-key__radio-group">
                        <label class="check-meta-key__radio-label">
                            <input type="radio" name="object_type" value="post" <?php checked($object_type, 'post'); ?>> Post / Page / Product
                        </label>
                        <label class="check-meta-key__radio-label">
                            <input type="radio" name="object_type" value="term" <?php checked($object_type, 'term'); ?>> Term / Category / Tag
                        </label>
                    </div>
                </div>

                <button type="submit" class="check-meta-key__submit">Kiểm tra ngay</button>
            </form>
        </div>

        <?php if ($error): ?>
            <div class="check-meta-key__error">
                <?php echo esc_html($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($results !== null): ?>
            <div class="check-meta-key__results">
                <?php if (empty($results)): ?>
                    <p class="center-text">Đối tượng này không có metadata nào.</p>
                <?php else: ?>
                    <table class="check-meta-key__table">
                        <thead>
                            <tr>
                                <th>Meta Key</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            ksort($results);
                            foreach ($results as $key => $values): 
                                foreach ($values as $value):
                                    // Special handling for LearnPress Materials
                                    if ($key === '_lp_materials') {
                                        $materials = maybe_unserialize($value);
                                        $display_value = '';
                                        if (is_array($materials) && !empty($materials)) {
                                            $display_value .= "--- LEARNPRESS MATERIALS DETECTED ---\n";
                                            foreach ($materials as $index => $material) {
                                                $name = isset($material['file_name']) ? $material['file_name'] : 'Unnamed File';
                                                $url  = isset($material['file_url']) ? $material['file_url'] : 'No URL';
                                                $display_value .= "[" . ($index + 1) . "] Name: $name\n    URL: $url\n\n";
                                            }
                                            $display_value .= "--------------------------------------";
                                        } else {
                                            $display_value = print_r($materials, true);
                                        }
                                    } else {
                                        // Handle other serialized data
                                        $display_value = $value;
                                        if (is_serialized($value)) {
                                            $display_value = print_r(maybe_unserialize($value), true);
                                        }
                                    }
                            ?>
                                <tr>
                                    <td class="check-meta-key__key"><?php echo esc_html($key); ?></td>
                                    <td class="check-meta-key__value"><pre><?php echo esc_html($display_value); ?></pre></td>
                                </tr>
                            <?php 
                                endforeach;
                            endforeach; 
                            ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
