<?php
/**
 * Plugin Name: WooCommerce Bat Customizer
 * Description: Custom WooCommerce product customizer for bats, replicating Anglar Reserve Edition.
 * Version: 1.0
 * Author: Waseel
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}


// 1. PRODUCT DATA TAB (ADMIN) — Build Custom Tab Inside WooCommerce Product Data

// Add custom tab to product data metabox
add_filter('woocommerce_product_data_tabs', 'add_bat_customizer_tab');
function add_bat_customizer_tab($tabs) {
    $tabs['bat_customizer'] = array(
        'label' => __('Bat Customizer', 'woocommerce'),
        'target' => 'bat_customizer_product_data',
    );
    return $tabs;
}

// Add fields to the custom tab
add_action('woocommerce_product_data_panels', 'bat_customizer_product_data_fields');
function bat_customizer_product_data_fields() {
    global $post;
    ?>
    <div id="bat_customizer_product_data" class="panel woocommerce_options_panel" style="padding:20px 15px;">
        
        <!-- Deep Customisation Toggle -->
        <div class="options_group" style="border-bottom:2px solid #f1f1f1; padding-bottom:15px; margin-bottom:25px;">
            <?php
            woocommerce_wp_checkbox(array(
                'id'          => '_deep_customisation',
                'label'       => __('Enable Deep Customisation', 'woocommerce'),
                'description' => __('Turn on full bat customizer on frontend', 'woocommerce'),
                'desc_tip'    => true,
            ));
            ?>
        </div>

        <?php
        $repeaters = array(
            'handle_shape' => array(
                'title' => 'Handle Shape',
                'icon'  => 'dashicons dashicons-editor-expand',
                'fields' => array('label' => 'text'),
            ),
            'handle_thickness' => array(
                'title' => 'Handle Thickness',
                'icon'  => 'dashicons dashicons-leftright',
                'fields' => array('label' => 'text'),
            ),
            'handle_type' => array(
                'title' => 'Handle Type',
                'icon'  => 'dashicons dashicons-admin-tools',
                'fields' => array('image' => 'image', 'label' => 'text', 'price' => 'number'),
            ),
            'sweet_spot' => array(
                'title' => 'Sweet Spot',
                'icon'  => 'dashicons dashicons-star-filled',
                'fields' => array('image' => 'image', 'label' => 'text', 'price' => 'number'),
            ),
            'toe_shape' => array(
                'title' => 'Toe Shape',
                'icon'  => 'dashicons dashicons-carrot',
                'fields' => array('image' => 'image', 'label' => 'text', 'price' => 'number'),
            ),
            'oiling_knocking' => array(
                'title' => 'Oiling & Knocking',
                'icon'  => 'dashicons dashicons-admin-customizer',
                'fields' => array('label' => 'text', 'price' => 'number', 'description' => 'textarea'),
            ),
            'anti_scuff_sheet' => array(
                'title' => 'Anti-Scuff Sheet',
                'icon'  => 'dashicons dashicons-shield',
                'fields' => array('label' => 'text', 'price' => 'number'),
            ),
            'toe_guard' => array(
                'title' => 'Toe Guard',
                'icon'  => 'dashicons dashicons-yes-alt',
                'fields' => array('label' => 'text', 'price' => 'number'),
            ),
            'extra_grips' => array(
                'title' => 'Extra Grips',
                'icon'  => 'dashicons dashicons-admin-links',
                'fields' => array('label' => 'text', 'price' => 'number'),
            ),
        );

        foreach ($repeaters as $key => $repeater) {
            $values = get_post_meta($post->ID, '_' . $key, true);
            $values = is_array($values) ? $values : array(array()); // Ensure at least one empty row
            ?>
            <div class="bat-repeater-block" style="margin-bottom:35px; border:1px solid #e1e1e1; border-radius:8px; overflow:hidden; background:#fff;">
                <div class="repeater-header" style="background:#2271b1; color:white; padding:12px 15px; font-weight:600; display:flex; align-items:center; gap:10px;">
                    <span class="<?php echo esc_attr($repeater['icon']); ?>" style="font-size:18px;"></span>
                    <span><?php echo esc_html($repeater['title']); ?></span>
                </div>
                
                <div class="repeater-items" style="padding:15px;">
                    <?php foreach ($values as $index => $value) { ?>
                        <div class="repeater-item" style="background:#f9f9f9; border:1px solid #ddd; border-radius:6px; padding:15px; margin-bottom:8px; position:relative;">
                            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
                                <?php foreach ($repeater['fields'] as $field_name => $field_type) { ?>
                                    <?php if ($field_type === 'image') { ?>
                                        <div class="image-field">
                                            <label style="display:block; margin-bottom:8px; font-weight:600;"><?php echo ucfirst($field_name); ?></label>
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <input type="hidden" name="<?php echo esc_attr($key); ?>[<?php echo $index; ?>][<?php echo $field_name; ?>]" value="<?php echo esc_attr($value[$field_name] ?? ''); ?>" class="image-id-input">
                                                <?php if (!empty($value[$field_name])): ?>
                                                    <img src="<?php echo esc_url(wp_get_attachment_url($value[$field_name])); ?>" style="width:80px; height:80px; object-fit:cover; border-radius:4px;" class="preview-image">
                                                <?php else: ?>
                                                    <img src="" style="width:80px; height:80px; object-fit:cover; border-radius:4px; display:none;" class="preview-image">
                                                <?php endif; ?>
                                                <div style="width:80px; height:80px; background:#eee; border:2px dashed #ccc; border-radius:4px; <?php echo !empty($value[$field_name]) ? 'display:none;' : ''; ?>" class="image-placeholder"></div>
                                                <button type="button" class="upload_image_button button button-secondary" style="height:40px;">Upload</button>
                                            </div>
                                        </div>
                                    <?php } elseif ($field_type === 'text') { ?>
                                        <div>
                                            <label style="display:block; margin-bottom:8px; font-weight:600;">Label</label>
                                            <input type="text" class="widefat" name="<?php echo esc_attr($key); ?>[<?php echo $index; ?>][<?php echo $field_name; ?>]" value="<?php echo esc_attr($value[$field_name] ?? ''); ?>" placeholder="e.g. Round, 6 Piece, Yes...">
                                        </div>
                                    <?php } elseif ($field_type === 'number') { ?>
                                        <div>
                                            <label style="display:block; margin-bottom:8px; font-weight:600;">Extra Price (<?php echo get_woocommerce_currency_symbol(); ?>)</label>
                                            <input type="number" class="widefat" name="<?php echo esc_attr($key); ?>[<?php echo $index; ?>][price]" value="<?php echo esc_attr($value['price'] ?? 0); ?>" min="0" step="0.01" placeholder="0">
                                        </div>
                                    <?php } elseif ($field_type === 'textarea') { ?>
                                        <div style="grid-column: span 2;">
                                            <label style="display:block; margin-bottom:8px; font-weight:600;">Description</label>
                                            <textarea class="widefat" rows="3" name="<?php echo esc_attr($key); ?>[<?php echo $index; ?>][description]"><?php echo esc_textarea($value['description'] ?? ''); ?></textarea>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <button type="button" class="remove-repeater-item button button-link-delete" style="position:absolute; top:10px; right:10px; color:#a00;">Remove</button>
                        </div>
                    <?php } ?>
                </div>
                
                <div style="padding:0 15px 15px;">
                    <button type="button" class="add-repeater-item button button-primary" style="background:#2271b1; border:none;">
                        Add Option
                    </button>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <style>
        .bat-repeater-block .repeater-header { font-size:15px; }
        .repeater-item { box-shadow:0 2px 5px rgba(0,0,0,0.05); }
        .upload_image_button:hover { background:#0073aa !important; color:white !important; }
        .remove-repeater-item:hover { color:#d63638 !important; }
        .add-repeater-item { width:100%; height:44px; font-size:14px; }
        @media (max-width: 782px) {
            .repeater-item > div > div { grid-column: span 2 !important; }
        }
    </style>
    <?php
}

// Admin scripts for repeater functionality
add_action('admin_footer', 'bat_customizer_admin_scripts');
function bat_customizer_admin_scripts() {
    global $post_type, $pagenow;
    if (!in_array($pagenow, array('post.php', 'post-new.php')) || $post_type !== 'product') {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // BAT CUSTOMIZER REPEATERS
        $('.bat-repeater-block').each(function() {
            var $block = $(this);
            var $itemsContainer = $block.find('.repeater-items');

            $block.on('click', '.add-repeater-item', function(e) {
                e.preventDefault();
                var $firstItem = $itemsContainer.find('.repeater-item').first().clone();
                $firstItem.find('input, textarea').val('');
                $firstItem.find('.image-id-input').val('');
                $firstItem.find('.preview-image').attr('src', '').hide();
                $firstItem.find('.image-placeholder').show();

                var newIndex = $itemsContainer.find('.repeater-item').length;
                $firstItem.find('input[name], textarea[name]').each(function() {
                    var name = $(this).attr('name').replace(/\[\d+\]/g, '[' + newIndex + ']');
                    $(this).attr('name', name);
                });
                $itemsContainer.append($firstItem);
            });

            $block.on('click', '.remove-repeater-item', function(e) {
                e.preventDefault();
                var $item = $(this).closest('.repeater-item');
                if ($itemsContainer.find('.repeater-item').length > 1) {
                    $item.remove();
                } else {
                    $item.find('input, textarea').val('');
                    $item.find('.image-id-input').val('');
                    $item.find('.preview-image').attr('src', '').hide();
                    $item.find('.image-placeholder').show();
                }
            });

            $block.on('click', '.upload_image_button', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $container = $button.closest('.image-field');
                var $field = $container.find('.image-id-input');
                var $img = $container.find('.preview-image');
                var $placeholder = $container.find('.image-placeholder');

                var frame = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use this image' },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $field.val(attachment.id);
                    $img.attr('src', attachment.url).show();
                    $placeholder.hide();
                });

                frame.open();
            });
        });

        // GRID & FAQ REPEATERS
        // GRID & FAQ REPEATERS
$('.repeater').each(function() {
    var $repeater = $(this);
    var $items = $repeater.find('.repeater-items');

    $repeater.on('click', '.add-repeater-item', function(e) {
        e.preventDefault();
        var $newItem = $items.find('.repeater-item').first().clone();
        $newItem.find('input, textarea').val('');
        $newItem.find('.grid-image-id').val('');
        $newItem.find('.grid-preview-image').attr('src', '').hide();
        $newItem.find('.grid-image-placeholder').show();
        var count = $items.find('.repeater-item').length;
        $newItem.find('input[name], textarea[name]').each(function() {
            var name = $(this).attr('name').replace(/\[\d+\]/g, '[' + count + ']');
            $(this).attr('name', name);
        });
        $items.append($newItem);
    });

    $repeater.on('click', '.remove-repeater-item', function(e) {
        e.preventDefault();
        if ($items.children().length > 1) {
            $(this).closest('.repeater-item').remove();
        }
    });

    $repeater.on('click', '.upload_image_button', function(e) {
        e.preventDefault();
        var $button = $(this);
        var $container = $button.closest('.image-upload-wrapper');
        var $field = $container.find('.grid-image-id');
        var $img = $container.find('.grid-preview-image');
        var $placeholder = $container.find('.grid-image-placeholder');

        var frame = wp.media({ 
            title: 'Select Image', 
            button: { text: 'Use' }, 
            multiple: false 
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $field.val(attachment.id);
            $img.attr('src', attachment.url).show();
            $placeholder.hide();
        });

        frame.open();
    });
});

    // Edition Image Uploader
        $('.upload-edition-image').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var frame = wp.media({
                title: 'Select Edition Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#_edition_image').val(attachment.id);
                $('.edition-preview-image').remove();
                $('.edition-image-placeholder').remove();
                $button.before('<img src="' + attachment.url + '" style="width:100px; height:100px; object-fit:cover; border-radius:4px;" class="edition-preview-image">');
            });

            frame.open();
        });

        // Bat Matters Image Uploader
        $('.upload-bat-matters-image').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var frame = wp.media({
                title: 'Select Bat Matters Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#_bat_that_matters_image').val(attachment.id);
                $('.bat-matters-preview-image').remove();
                $('.bat-matters-image-placeholder').remove();
                $button.before('<img src="' + attachment.url + '" style="width:100px; height:100px; object-fit:cover; border-radius:4px;" class="bat-matters-preview-image">');
            });

            frame.open();
             });
             // Laser Engraving Image Uploader
        $('.upload-laser-image').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var frame = wp.media({
                title: 'Select Laser Engraving Bat Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#_laser_engraving_image').val(attachment.id);
                $('.laser-preview-image').remove();
                $('.laser-image-placeholder').remove();
                $button.before('<img src="' + attachment.url + '" style="width:100px; height:150px; object-fit:cover; border-radius:4px;" class="laser-preview-image">');
            });

            frame.open();
        });

        // Cover Engraving Image Uploader
        $('.upload-cover-image').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var frame = wp.media({
                title: 'Select Bat Cover Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#_cover_engraving_image').val(attachment.id);
                $('.cover-preview-image').remove();
                $('.cover-image-placeholder').remove();
                $button.before('<img src="' + attachment.url + '" style="width:100px; height:150px; object-fit:cover; border-radius:4px;" class="cover-preview-image">');
            });

            frame.open();
        });
        });
    </script>
    <?php
}

// Sanitize and save repeaters
function bat_sanitize_and_save_repeaters($post_id) {
    $repeaters = array('handle_shape','handle_thickness','handle_type','sweet_spot','toe_shape','oiling_knocking','anti_scuff_sheet','toe_guard','extra_grips','grid_section','faqs');

    foreach ($repeaters as $key) {
        if (empty($_POST[$key]) || !is_array($_POST[$key])) {
            delete_post_meta($post_id, '_' . $key);
            continue;
        }

        $clean = array();
        foreach ($_POST[$key] as $row) {
            $has_data = false;
            foreach ($row as $val) {
                if ($val !== '' && $val !== '0') { $has_data = true; break; }
            }
            if (!$has_data) continue;

            $clean_row = array();
            foreach ($row as $k => $v) {
                if (strpos($k, 'image') !== false) $clean_row[$k] = absint($v);
                elseif ($k === 'price') $clean_row[$k] = floatval($v);
                elseif (in_array($k, ['description','answer','text'])) $clean_row[$k] = sanitize_textarea_field($v);
                else $clean_row[$k] = sanitize_text_field($v);
            }
            $clean[] = $clean_row;
        }

        if (!empty($clean)) {
            update_post_meta($post_id, '_' . $key, $clean);
        } else {
            delete_post_meta($post_id, '_' . $key);
        }
    }
}

// Save custom fields
add_action('woocommerce_process_product_meta', 'save_bat_customizer_product_data');
function save_bat_customizer_product_data($post_id) {
    if (!isset($_POST['woocommerce_meta_nonce']) || 
        !wp_verify_nonce($_POST['woocommerce_meta_nonce'], 'woocommerce_save_data')) {
        return;
    }
    
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // Verify user capabilities
    if (!current_user_can('edit_product', $post_id)) return;



    update_post_meta($post_id, '_deep_customisation', isset($_POST['_deep_customisation']) ? 'yes' : 'no');
    bat_sanitize_and_save_repeaters($post_id);
    save_display_sections($post_id);
}

// Add display section fields
add_action('woocommerce_product_options_general_product_data', 'add_display_sections_fields');
function add_display_sections_fields() {
    global $post;

    woocommerce_wp_text_input(array(
        'id' => '_edition_heading',
        'label' => __('Edition Heading', 'woocommerce'),
    ));

    woocommerce_wp_textarea_input(array(
        'id' => '_short_edition_description',
        'label' => __('Short Edition Description', 'woocommerce'),
    ));

    echo '<p class="form-field">';
    echo '<label>' . __('Edition Image', 'woocommerce') . '</label>';
    $edition_img_id = get_post_meta($post->ID, '_edition_image', true);
    echo '<div class="edition-image-uploader" style="display:flex; align-items:center; gap:10px;">';
    if ($edition_img_id) {
        echo '<img src="' . wp_get_attachment_url($edition_img_id) . '" style="width:100px; height:100px; object-fit:cover; border-radius:4px;" class="edition-preview-image">';
    } else {
        echo '<div style="width:100px; height:100px; background:#eee; border:2px dashed #ccc; border-radius:4px;" class="edition-image-placeholder"></div>';
    }
    echo '<input type="hidden" id="_edition_image" name="_edition_image" value="' . esc_attr($edition_img_id) . '">';
    echo '<button type="button" class="button upload-edition-image">Upload Image</button>';
    echo '</div>';
    echo '</p>';

    woocommerce_wp_text_input(array(
        'id' => '_grains',
        'label' => __('Grains', 'woocommerce'),
    ));

    woocommerce_wp_text_input(array(
        'id' => '_grade',
        'label' => __('Grade', 'woocommerce'),
    ));

    woocommerce_wp_textarea_input(array(
        'id' => '_grain_description',
        'label' => __('Grain Description', 'woocommerce'),
    ));

    woocommerce_wp_textarea_input(array(
        'id' => '_section_below_hero',
        'label' => __('Section Below Hero', 'woocommerce'),
    ));

    woocommerce_wp_textarea_input(array(
        'id' => '_bat_that_matters_section',
        'label' => __('Bat That Matters Section', 'woocommerce'),
    ));

    echo '<p class="form-field">';
    echo '<label>' . __('Bat That Matters Image', 'woocommerce') . '</label>';
    $bat_img_id = get_post_meta($post->ID, '_bat_that_matters_image', true);
    echo '<div class="bat-matters-image-uploader" style="display:flex; align-items:center; gap:10px;">';
    if ($bat_img_id) {
        echo '<img src="' . wp_get_attachment_url($bat_img_id) . '" style="width:100px; height:100px; object-fit:cover; border-radius:4px;" class="bat-matters-preview-image">';
    } else {
        echo '<div style="width:100px; height:100px; background:#eee; border:2px dashed #ccc; border-radius:4px;" class="bat-matters-image-placeholder"></div>';
    }
    echo '<input type="hidden" id="_bat_that_matters_image" name="_bat_that_matters_image" value="' . esc_attr($bat_img_id) . '">';
    echo '<button type="button" class="button upload-bat-matters-image">Upload Image</button>';
    echo '</div>';
    echo '</p>';
    // Grid Section Repeater
    $grid_values = get_post_meta($post->ID, '_grid_section', true);
    $grid_values = is_array($grid_values) ? $grid_values : array(array());
    ?>
    <div class="repeater grid-repeater">
        <h3>Grid Section (3 Images + Text)</h3>
        <div class="repeater-items">
            <?php foreach ($grid_values as $i => $item) : ?>
                <div class="repeater-item" style="border:1px solid #ddd; padding:15px; margin-bottom:8px; background:#f9f9f9; border-radius:6px;">
                    <?php for ($n = 1; $n <= 3; $n++) : ?>
                        <div style="margin-bottom:8px;">
                            <label><strong>Image <?php echo $n; ?></strong></label><br>
                            <div class="image-upload-wrapper" style="display:flex; align-items:center; gap:10px; margin-top:8px;">
                                <input type="hidden" name="grid_section[<?php echo $i; ?>][image<?php echo $n; ?>]" value="<?php echo esc_attr($item['image'.$n] ?? ''); ?>" class="grid-image-id">
                                <?php if (!empty($item['image'.$n])) : ?>
                                    <img src="<?php echo wp_get_attachment_url($item['image'.$n]); ?>" style="width:80px; height:80px; object-fit:cover; border-radius:4px;" class="grid-preview-image">
                                <?php else : ?>
                                    <img src="" style="width:80px; height:80px; object-fit:cover; border-radius:4px; display:none;" class="grid-preview-image">
                                <?php endif; ?>
                                <div style="width:80px; height:80px; background:#eee; border:2px dashed #ccc; border-radius:4px; <?php echo !empty($item['image'.$n]) ? 'display:none;' : ''; ?>" class="grid-image-placeholder"></div>
                                <button type="button" class="upload_image_button button">Upload Image <?php echo $n; ?></button>
                            </div>
                        </div>
                    <?php endfor; ?>
                    <div>
                        <label><strong>Text</strong></label>
                        <textarea name="grid_section[<?php echo $i; ?>][text]" rows="3" style="width:100%;"><?php echo esc_textarea($item['text'] ?? ''); ?></textarea>
                    </div>
                    <button type="button" class="remove-repeater-item button button-link-delete" style="margin-top:10px;">Remove Item</button>
                </div>
            <?php endforeach; ?>
        </div>
        <p><button type="button" class="add-repeater-item button button-primary">Add Grid Item</button></p>
    </div>

    <?php
    // FAQs Repeater
    $faqs = get_post_meta($post->ID, '_faqs', true);
    $faqs = is_array($faqs) ? $faqs : array(array());
    ?>
    <div class="repeater faq-repeater">
        <h3>FAQs</h3>
        <div class="repeater-items">
            <?php foreach ($faqs as $i => $faq) : ?>
                <div class="repeater-item" style="border:1px solid #ddd; padding:15px; margin-bottom:8px; background:#f9f9f9; border-radius:6px;">
                    <p>
                        <label><strong>Question</strong></label>
                        <input type="text" name="faqs[<?php echo $i; ?>][question]" value="<?php echo esc_attr($faq['question'] ?? ''); ?>" style="width:100%;">
                    </p>
                    <p>
                        <label><strong>Answer</strong></label>
                        <textarea name="faqs[<?php echo $i; ?>][answer]" rows="4" style="width:100%;"><?php echo esc_textarea($faq['answer'] ?? ''); ?></textarea>
                    </p>
                    <button type="button" class="remove-repeater-item button button-link-delete">Remove FAQ</button>
                </div>
            <?php endforeach; ?>
        </div>
        <p><button type="button" class="add-repeater-item button button-primary">Add FAQ</button></p>
    </div>
    <?php
    
    // ============================================
    // LASER ENGRAVING SECTION
    // ============================================
    echo '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e1e1e1;">';
    echo '<h2 style="margin-bottom: 20px;">Laser Engraving Options</h2>';
    
    woocommerce_wp_checkbox(array(
        'id' => '_enable_laser_engraving',
        'label' => __('Enable Laser Engraving', 'woocommerce'),
        'description' => __('Show laser engraving option on product page', 'woocommerce'),
    ));
    
    echo '<p class="form-field">';
    echo '<label>' . __('Laser Engraving Image', 'woocommerce') . '</label>';
    $laser_img_id = get_post_meta($post->ID, '_laser_engraving_image', true);
    echo '<div class="laser-engraving-image-uploader" style="display:flex; align-items:center; gap:10px;">';
    if ($laser_img_id) {
        echo '<img src="' . wp_get_attachment_url($laser_img_id) . '" style="width:100px; height:150px; object-fit:cover; border-radius:4px;" class="laser-preview-image">';
    } else {
        echo '<div style="width:100px; height:150px; background:#eee; border:2px dashed #ccc; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:12px; color:#999;" class="laser-image-placeholder">No Image</div>';
    }
    echo '<input type="hidden" id="_laser_engraving_image" name="_laser_engraving_image" value="' . esc_attr($laser_img_id) . '">';
    echo '<button type="button" class="button upload-laser-image">Upload Bat Image</button>';
    echo '</div>';
    echo '</p>';
    
    woocommerce_wp_text_input(array(
        'id' => '_laser_engraving_price',
        'label' => __('Laser Engraving Price (' . get_woocommerce_currency_symbol() . ')', 'woocommerce'),
        'type' => 'number',
        'custom_attributes' => array('step' => '0.01', 'min' => '0'),
        'value' => get_post_meta($post->ID, '_laser_engraving_price', true) ?: '5.49',
    ));
    
    woocommerce_wp_text_input(array(
        'id' => '_laser_engraving_max_chars',
        'label' => __('Max Characters', 'woocommerce'),
        'type' => 'number',
        'custom_attributes' => array('min' => '1', 'max' => '50'),
        'value' => get_post_meta($post->ID, '_laser_engraving_max_chars', true) ?: '8',
    ));
    
    // ============================================
    // BAT COVER ENGRAVING SECTION
    // ============================================
    echo '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e1e1e1;">';
    echo '<h2 style="margin-bottom: 20px;">Bat Cover Customization</h2>';
    
    woocommerce_wp_checkbox(array(
        'id' => '_enable_cover_engraving',
        'label' => __('Enable Bat Cover Customization', 'woocommerce'),
        'description' => __('Show bat cover option on product page', 'woocommerce'),
    ));
    
    echo '<p class="form-field">';
    echo '<label>' . __('Bat Cover Image', 'woocommerce') . '</label>';
    $cover_img_id = get_post_meta($post->ID, '_cover_engraving_image', true);
    echo '<div class="cover-engraving-image-uploader" style="display:flex; align-items:center; gap:10px;">';
    if ($cover_img_id) {
        echo '<img src="' . wp_get_attachment_url($cover_img_id) . '" style="width:100px; height:150px; object-fit:cover; border-radius:4px;" class="cover-preview-image">';
    } else {
        echo '<div style="width:100px; height:150px; background:#eee; border:2px dashed #ccc; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:12px; color:#999;" class="cover-image-placeholder">No Image</div>';
    }
    echo '<input type="hidden" id="_cover_engraving_image" name="_cover_engraving_image" value="' . esc_attr($cover_img_id) . '">';
    echo '<button type="button" class="button upload-cover-image">Upload Cover Image</button>';
    echo '</div>';
    echo '</p>';
    
    woocommerce_wp_text_input(array(
        'id' => '_cover_engraving_price',
        'label' => __('Bat Cover Price (' . get_woocommerce_currency_symbol() . ')', 'woocommerce'),
        'type' => 'number',
        'custom_attributes' => array('step' => '0.01', 'min' => '0'),
        'value' => get_post_meta($post->ID, '_cover_engraving_price', true) ?: '8.05',
    ));
    
    woocommerce_wp_text_input(array(
        'id' => '_cover_engraving_max_chars',
        'label' => __('Max Characters', 'woocommerce'),
        'type' => 'number',
        'custom_attributes' => array('min' => '1', 'max' => '50'),
        'value' => get_post_meta($post->ID, '_cover_engraving_max_chars', true) ?: '8',
    ));
}


// Save display sections
function save_display_sections($post_id) {
   $fields = array(
        '_edition_heading', 
        '_short_edition_description', 
        '_edition_image', 
        '_grains', 
        '_grade', 
        '_grain_description', 
        '_section_below_hero', 
        '_bat_that_matters_section', 
        '_bat_that_matters_image',
        // Laser Engraving
        '_enable_laser_engraving',
        '_laser_engraving_image',
        '_laser_engraving_price',
        '_laser_engraving_max_chars',
        // Cover Engraving
        '_enable_cover_engraving',
        '_cover_engraving_image',
        '_cover_engraving_price',
        '_cover_engraving_max_chars'
    );
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}

// Render display sections
add_action('woocommerce_single_product_summary', 'render_display_sections', 25);
function render_display_sections() {
    global $product;
    $product_id = $product->get_id();

    $edition_heading = get_post_meta($product_id, '_edition_heading', true);
    $short_desc = get_post_meta($product_id, '_short_edition_description', true);
    if ($edition_heading || $short_desc) {
        echo '<section class="edition-section">';
        if ($edition_heading) echo '<h2>' . esc_html($edition_heading) . '</h2>';
        if ($short_desc) echo '<p>' . esc_html($short_desc) . '</p>';
        echo '</section>';
    }

    $edition_image = get_post_meta($product_id, '_edition_image', true);
    if ($edition_image) {
        echo '<section class="edition-image"><img src="' . esc_url(wp_get_attachment_url($edition_image)) . '" alt="Edition Image"></section>';
    }

    $grains = get_post_meta($product_id, '_grains', true);
    $grade = get_post_meta($product_id, '_grade', true);
    $grain_desc = get_post_meta($product_id, '_grain_description', true);
    if ($grains || $grade || $grain_desc) {
        echo '<section class="grain-section">';
    if ($grains) echo '<p>Grains: ' . esc_html($grains) . '</p>';
    if ($grade) echo '<p>Grade: ' . esc_html($grade) . '</p>';
    if ($grain_desc) echo '<p>' . wp_kses_post($grain_desc) . '</p>';
        echo '</section>';
    }

    $grid = get_post_meta($product_id, '_grid_section', true);
    if (is_array($grid) && !empty($grid)) {
        $has_content = false;
        foreach ($grid as $item) {
            if (!empty($item['image1']) || !empty($item['image2']) || !empty($item['image3']) || !empty($item['text'])) {
                $has_content = true;
                break;
            }
        }

        if ($has_content) {
            echo '<section class="grid-section">';
            foreach ($grid as $item) {
                if (empty($item['image1']) && empty($item['image2']) && empty($item['image3']) && empty($item['text'])) {
                    continue;
                }

                echo '<div class="grid-item">';
                if (!empty($item['image1'])) echo wp_get_attachment_image($item['image1'], 'large', false, ['style' => 'margin-bottom:10px;']);
                if (!empty($item['image2'])) echo wp_get_attachment_image($item['image2'], 'large', false, ['style' => 'margin-bottom:10px;']);
                if (!empty($item['image3'])) echo wp_get_attachment_image($item['image3'], 'large', false, ['style' => 'margin-bottom:10px;']);
                if (!empty($item['text'])) echo '<p>' . wp_kses_post(nl2br($item['text'])) . '</p>';
                echo '</div>';
            }
            echo '</section>';
        }
    }

    $below_hero = get_post_meta($product_id, '_section_below_hero', true);
    if ($below_hero) {
        echo '<section class="below-hero">' . wp_kses_post($below_hero) . '</section>';
    }

    $bat_matters = get_post_meta($product_id, '_bat_that_matters_section', true);
    $bat_image = get_post_meta($product_id, '_bat_that_matters_image', true);
    if ($bat_matters || $bat_image) {
        echo '<section class="bat-matters">';
        if ($bat_matters) echo '<p>' . esc_html($bat_matters) . '</p>';
        if ($bat_image) echo '<img src="' . esc_url(wp_get_attachment_url($bat_image)) . '">';
        echo '</section>';
    }

    $faqs = get_post_meta($product_id, '_faqs', true);
    if (!empty($faqs)) {
        echo '<section class="faqs">';
        echo '<h2>FAQs</h2>';
        foreach ($faqs as $faq) {
            if (empty($faq['question']) && empty($faq['answer'])) continue;
            echo '<div class="faq-item">';
            echo '<h3>' . esc_html($faq['question']) . '</h3>';
            echo '<p>' . esc_html($faq['answer']) . '</p>';
            echo '</div>';
        }
        echo '</section>';
    }
}

// FRONTEND CUSTOMIZER
// FRONTEND CUSTOMIZER
add_action('woocommerce_single_product_summary', 'render_bat_customizer', 20);

function render_bat_customizer($force = false) {
    static $rendered = false;
    if (!$force && $rendered) return;
    $rendered = true;

    global $product;

    // Try to get product context
    if (!$product || !is_object($product)) {
        $product = wc_get_product(get_the_ID());
    }
    if (!$product) return;

    $product_id = $product->get_id();

    // Only show on products that enabled it (unless forced, e.g., Elementor preview)
    if (!$force && get_post_meta($product_id, '_deep_customisation', true) !== 'yes') return;

    $sections = array(
        'handle_shape' => 'Handle Shape',
        'handle_thickness' => 'Handle Thickness',
        'handle_type' => 'Handle Type',
        'sweet_spot' => 'Sweet Spot',
        'toe_shape' => 'Toe Shape',
        'oiling_knocking' => 'Oiling & Knocking',
        'anti_scuff_sheet' => 'Anti-Scuff Sheet',
        'toe_guard' => 'Toe Guard',
        'extra_grips' => 'Extra Grips',
    );
$deep_custom_enabled = get_post_meta($product_id, '_deep_customisation', true);
$is_enabled = ($deep_custom_enabled === 'yes');

echo '<div id="bat-customizer">';

echo '<div class="deep-customisation-toggle" style="margin-bottom:20px;">';
echo '<h3 style="font-size:16px; font-weight:600; margin-bottom:10px;">Want Deep Customisation?</h3>';
echo '<div class="toggle-buttons">';
echo '<button type="button" class="toggle-btn yes-btn ' . ($is_enabled ? 'active' : '') . '" data-value="yes" style="padding:8px 20px; background:' . ($is_enabled ? '#0066ff' : '#e0e0e0') . '; color:' . ($is_enabled ? 'white' : '#666') . '; border:none; border-radius:4px 0 0 4px; cursor:pointer; font-weight:600;">Yes</button>';
echo '<button type="button" class="toggle-btn no-btn ' . (!$is_enabled ? 'active' : '') . '" data-value="no" style="padding:8px 20px; background:' . (!$is_enabled ? '#0066ff' : '#e0e0e0') . '; color:' . (!$is_enabled ? 'white' : '#666') . '; border:none; border-radius:0 4px 4px 0; cursor:pointer; font-weight:600;">No</button>';
echo '</div>';
echo '<input type="hidden" id="deep-customisation" value="' . ($is_enabled ? 'yes' : 'no') . '">';
echo '</div>';


        foreach ($sections as $key => $title) {
            $options = get_post_meta($product_id, '_' . $key, true);
    if (empty($options) || !is_array($options)) continue;

    echo '<div class="customizer-section" data-group="' . esc_attr($key) . '" style="margin-bottom:6px; padding:20px; border-radius:8px; ' . ($is_enabled ? '' : 'display:none;') . '">';
    echo '<h3 style="font-size:18px; font-weight:600; margin-bottom:8px; color:#333;">' . esc_html($title) . '</h3>';
    echo '<div class="options" style="display:flex; flex-wrap:wrap; gap:15px;">';

    foreach ($options as $index => $option) {
        if (empty($option['label'])) continue;

        if (!empty($option['image'])) {
            echo '<div class="image-option" data-index="' . $index . '" data-price="' . floatval($option['price'] ?? 0) . '">';
            echo '<img src="' . esc_url(wp_get_attachment_url($option['image'])) . '" alt="' . esc_attr($option['label']) . '">';
            echo '<span class="label">' . esc_html($option['label']) . '</span>';
            if (!empty($option['price'])) echo '<span class="price">' . wc_price($option['price']) . '</span>';
            echo '</div>';
        } else {
            echo '<div class="text-option" data-index="' . $index . '" data-price="' . floatval($option['price'] ?? 0) . '">';
            echo esc_html($option['label']);
            if (!empty($option['price'])) echo '<span class="price">' . wc_price($option['price']) . '</span>';
            echo '</div>';
        }
    }

    echo '</div>';
    echo '<button type="button" class="clear-section-btn" data-group="' . esc_attr($key) . '" style="display:none; margin-top:10px; padding:6px 15px; background:#f44336; color:white; border:none; border-radius:4px; cursor:pointer; font-size:13px; transition:all 0.3s ease;">Clear Selection</button>';
    if ($key === 'toe_shape') {
        // ============================================
        // LASER ENGRAVING SECTION
        // ============================================
        $enable_laser = get_post_meta($product_id, '_enable_laser_engraving', true);
        $laser_image = get_post_meta($product_id, '_laser_engraving_image', true);
        $laser_price = get_post_meta($product_id, '_laser_engraving_price', true) ?: '5.49';
        $laser_max_chars = get_post_meta($product_id, '_laser_engraving_max_chars', true) ?: '8';

        if ($enable_laser === 'yes' && $laser_image) {
            echo '<div class="engraving-section laser-engraving-section" style="margin-top:20px; margin-bottom:0; padding:20px; border-radius:8px; background:#f9f9f9; ' . ($is_enabled ? '' : 'display:none;') . '">';
            echo '<h3 style="font-size:18px; font-weight:600; margin-bottom:20px; color:#333;">Laser Engraving</h3>';
            
            echo '<div class="laser-preview-wrapper" style="margin-bottom:20px;">';
            echo '<div style="font-size:13px; color:#666; margin-bottom:10px; font-weight:600;">Preview</div>';
            echo '<div style="position:relative; display:inline-block; text-align:left;">';
            echo '<img src="' . esc_url(wp_get_attachment_url($laser_image)) . '" style="width:150px; height:300px; object-fit:contain;" id="laser-bat-image">';
            echo '<div id="laser-text-overlay" style="position:absolute; top:45%; left:57%; transform:translate(-50%,-50%) rotate(-90deg); font-size:13px; font-weight:bold; font-style:italic; color:#99633d; text-transform:uppercase; white-space:nowrap; pointer-events:none; text-shadow:0 1px 2px rgba(0,0,0,0.1);"></div>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="laser-input-section">';
            echo '<label style="display:block; margin-bottom:8px; font-weight:600; color:#333;">Laser Engraving <span style="color:#0066ff; font-size:13px;">(+' . wc_price($laser_price) . ')</span></label>';
            echo '<input type="text" id="laser-engraving-input" name="laser_engraving_text" placeholder="Type here..." maxlength="' . esc_attr($laser_max_chars) . '" style="width:100%; padding:12px 15px; border:2px solid #ddd; border-radius:6px; font-size:15px; text-transform:uppercase; letter-spacing:1px;" data-price="' . esc_attr($laser_price) . '">';
            echo '<div style="display:flex; justify-content:space-between; margin-top:8px;">';
            echo '<div style="font-size:12px; color:#FF9800; font-style:italic;">Laser engraving will take 1 day extra</div>';
            echo '<div id="laser-char-counter" style="font-size:12px; color:#666;">0 / ' . $laser_max_chars . '</div>';
            echo '</div>';
            echo '</div>';
            
            echo '</div>';
        }

        // ============================================
        // BAT COVER ENGRAVING SECTION
        // ============================================
        $enable_cover = get_post_meta($product_id, '_enable_cover_engraving', true);
        $cover_image = get_post_meta($product_id, '_cover_engraving_image', true);
        $cover_price = get_post_meta($product_id, '_cover_engraving_price', true) ?: '8.05';
        $cover_max_chars = get_post_meta($product_id, '_cover_engraving_max_chars', true) ?: '8';

        if ($enable_cover === 'yes' && $cover_image) {
            echo '<div class="engraving-section cover-engraving-section" style="margin-top:20px; margin-bottom:0; padding:20px; border-radius:8px; background:#f9f9f9; ' . ($is_enabled ? '' : 'display:none;') . '">';
            echo '<h3 style="font-size:18px; font-weight:600; margin-bottom:20px; color:#333;"> Customised Premium Bat Cover</h3>';
            
            echo '<div class="cover-preview-wrapper" style="margin-bottom:20px;">';
            echo '<div style="font-size:13px; color:#666; margin-bottom:10px; font-weight:600;">Preview</div>';
            echo '<div style="position:relative; display:inline-block; background:#f9f9f9; padding:20px; border-radius:8px; text-align:left;">';
            echo '<img src="' . esc_url(wp_get_attachment_url($cover_image)) . '" style="width:200px; height:300px; object-fit:contain;" id="cover-bat-image">';
            echo '<div id="cover-text-overlay" style="position:absolute; top:45%; left:50%; transform:translateX(-50%); font-size:16px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:3px; white-space:nowrap; pointer-events:none; text-shadow:0 1px 3px rgba(0,0,0,0.3); opacity:0.9;"></div>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="cover-input-section">';
            echo '<label style="display:block; margin-bottom:8px; font-weight:600; color:#333;">Bat Cover Customization <span style="color:#0066ff; font-size:13px;">(+' . wc_price($cover_price) . ')</span></label>';
            echo '<input type="text" id="cover-engraving-input" name="cover_engraving_text" placeholder="Type here..." maxlength="' . esc_attr($cover_max_chars) . '" style="width:100%; padding:12px 15px; border:2px solid #ddd; border-radius:6px; font-size:15px; text-transform:uppercase; letter-spacing:2px;" data-price="' . esc_attr($cover_price) . '">';
            echo '<div style="display:flex; justify-content:space-between; margin-top:8px;">';
            echo '<div style="font-size:12px; color:#666; font-style:italic;">💡 Add your name, team, or custom text</div>';
            echo '<div id="cover-char-counter" style="font-size:12px; color:#666;">0 / ' . $cover_max_chars . '</div>';
            echo '</div>';
            echo '</div>';
            
            echo '</div>';
        }
    }

    echo '</div>'; // Close customizer-section
} // Close foreach sections


// Totals section continues here...

   $totals_display = $is_enabled ? '' : 'display:none;';
echo '<div class="totals" style="background:#f9f9f9; padding:20px; border-radius:8px; margin-top:30px; ' . $totals_display . '" id="customizer-totals">';
    echo '<div style="display:flex; justify-content:space-between; margin-bottom:10px;"><span style="font-weight:600;">Base Price:</span><span id="base-price">' . wc_price($product->get_price()) . '</span></div>';
    echo '<div style="display:flex; justify-content:space-between; margin-bottom:10px;"><span style="font-weight:600;">Add-ons:</span><span id="addons-total">' . wc_price(0) . '</span></div>';
    echo '<div style="display:flex; justify-content:space-between; padding-top:15px; border-top:2px solid #ddd; font-size:18px;"><span style="font-weight:700;">Grand Total:</span><span id="grand-total" style="font-weight:700; color:#0066ff;">' . wc_price($product->get_price()) . '</span></div>';
    echo '</div>';

    foreach ($sections as $key => $title) {
        echo '<input type="hidden" name="bat_' . esc_attr($key) . '" id="bat_' . esc_attr($key) . '">';
    }

    echo '</div>';
}


// Frontend JavaScript
add_action('wp_footer', 'bat_customizer_js');
function bat_customizer_js() {
    if (!is_product()) return;
    global $product;
    if (!$product || !is_object($product)) {
        $product = wc_get_product(get_the_ID());
    }
    if (!$product) return;
    ?>
    
    <script>
    jQuery(document).ready(function($) {
        var batCustomizerNonce = '<?php echo wp_create_nonce('bat_customizer_nonce'); ?>';
        var basePrice = parseFloat('<?php echo $product->get_price(); ?>') || 0;
         var addonsTotal = 0;
        var grandTotal = basePrice;

        function updateTotals() {
            
    addonsTotal = 0;
    
    // Add regular customizer options
    $('.customizer-section .selected').each(function() {
        addonsTotal += parseFloat($(this).data('price')) || 0;
    });
    
    // Add laser engraving price if text entered
    var laserText = $('#laser-engraving-input').val();
    if (laserText && laserText.trim() !== '') {
        var laserPrice = parseFloat($('#laser-engraving-input').data('price')) || 0;
        addonsTotal += laserPrice;
    }
    
    // Add cover engraving price if text entered
    var coverText = $('#cover-engraving-input').val();
    if (coverText && coverText.trim() !== '') {
        var coverPrice = parseFloat($('#cover-engraving-input').data('price')) || 0;
        addonsTotal += coverPrice;
    }
    grandTotal = basePrice + addonsTotal;

    // Use AJAX to format prices with WooCommerce
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'format_bat_price',
            security: batCustomizerNonce,  // ← ADD THIS LINE
            addons: addonsTotal,
            total: grandTotal
        },
        success: function(response) {
            if (response.success) {
                $('#addons-total').html(response.data.addons);
                $('#grand-total').html(response.data.total);
            }
        }
    });
}

        // Allow deselecting options
$('.customizer-section .options > div').on('click', function() {
    var $this = $(this);
    var group = $this.closest('.customizer-section').data('group');
    var $section = $this.closest('.customizer-section');
    var $clearBtn = $section.find('.clear-section-btn');
    
    if ($this.hasClass('selected')) {
        // Deselect
        $this.removeClass('selected');
        $('#bat_' + group).val('');
        // Hide clear button
        $clearBtn.fadeOut(200);
    } else {
        // Select
        $this.siblings().removeClass('selected');
        $this.addClass('selected');
        $('#bat_' + group).val($this.data('index'));
        // Show clear button
        $clearBtn.fadeIn(200);
    }
    
    updateTotals();
});

// Clear section button
$('.clear-section-btn').on('click', function() {
    var $btn = $(this);
    var group = $btn.data('group');
    $('.customizer-section[data-group="' + group + '"] .selected').removeClass('selected');
    $('#bat_' + group).val('');
    // Hide the button after clearing
    $btn.fadeOut(200);
    updateTotals();
});



// Intercept Add to Cart button to include deep customization value
$('form.cart').on('submit', function(e) {
    // Add deep customization value to form
    var deepCustomValue = $('#deep-customisation').val();
    
    // Check if deep customization input already exists in the form
    if ($(this).find('input[name="deep_customisation"]').length === 0) {
        $(this).append('<input type="hidden" name="deep_customisation" value="' + deepCustomValue + '">');
    } else {
        $(this).find('input[name="deep_customisation"]').val(deepCustomValue);
    }
});

        $('.toggle-btn').on('click', function() {
  $('.toggle-btn').removeClass('active');
  $(this).addClass('active');

  var isYes = ($(this).data('value') === 'yes');

  if (isYes) {
    $('.yes-btn').css({'background':'#0066ff','color':'#fff'});
    $('.no-btn').css({'background':'#e0e0e0','color':'#666'});
    $('#deep-customisation').val('yes');

    // SHOW UI
    $('.customizer-section').show();
     $('.engraving-section').show();
    $('#customizer-totals').show();

  } else {
    $('.no-btn').css({'background':'#0066ff','color':'#fff'});
    $('.yes-btn').css({'background':'#e0e0e0','color':'#666'});
    $('#deep-customisation').val('no');

    // HIDE UI + reset
    $('.customizer-section').hide();
    $('#customizer-totals').hide();
    $('.customizer-section .selected').removeClass('selected');
    $('.clear-section-btn').hide();
    $('.engraving-section').hide(); // Hide engraving sections
     $('#laser-engraving-input').val('');
    $('#cover-engraving-input').val('');
    $('#laser-text-overlay').text('');
    $('#cover-text-overlay').text('');
    $('#laser-char-counter').text('0 / ' + $('#laser-engraving-input').attr('maxlength'));
    $('#cover-char-counter').text('0 / ' + $('#cover-engraving-input').attr('maxlength'));
    addonsTotal = 0;
    grandTotal = basePrice;

    $.ajax({
      url: '<?php echo admin_url('admin-ajax.php'); ?>',
      type: 'POST',
      data: { 
    action: 'format_bat_price', 
    security: batCustomizerNonce,  // ← ADD THIS
    addons: 0, 
    total: basePrice 
},
      success: function(response) {
        if (response.success) {
          $('#addons-total').html(response.data.addons);
          $('#grand-total').html(response.data.total);
        }
      }
    });
  }
});

// Initial state (since your hidden input defaults to yes)
// Initial state - respect the saved setting
var initialState = $('#deep-customisation').val();
if (initialState === 'no') {
    $('.customizer-section').hide();
    $('#customizer-totals').hide();
} else {
    $('.customizer-section').show();
    $('#customizer-totals').show();
}
// Laser Engraving Live Preview
$('#laser-engraving-input').on('input', function() {
    var text = $(this).val().trim();
    var length = $(this).val().length;
    var maxChars = parseInt($(this).attr('maxlength'));
    
    $('#laser-text-overlay').text(text);
    $('#laser-char-counter').text(length + ' / ' + maxChars);
    
    if (length >= maxChars - 2) {
        $('#laser-char-counter').css('color', '#f44336');
    } else {
        $('#laser-char-counter').css('color', '#666');
    }
    
    updateTotals();
});

// Cover Engraving Live Preview
$('#cover-engraving-input').on('input', function() {
    var text = $(this).val().trim();
    var length = $(this).val().length;
    var maxChars = parseInt($(this).attr('maxlength'));
    
    $('#cover-text-overlay').text(text);
    $('#cover-char-counter').text(length + ' / ' + maxChars);
    
    if (length >= maxChars - 2) {
        $('#cover-char-counter').css('color', '#f44336');
    } else {
        $('#cover-char-counter').css('color', '#999');
    }
    
    // Adjust font size for cover
    var fontSize = 16;
    if (length > 6) fontSize = 14;
    if (length > 8) fontSize = 12;
    $('#cover-text-overlay').css('fontSize', fontSize + 'px');
    
    updateTotals();
});

        $('.faqs .faq-item h3').on('click', function() {
            $(this).next('p').slideToggle(300);
            $(this).parent().toggleClass('active');
        });
    });
    </script>
    <?php
}

// Add custom data to cart
add_filter('woocommerce_add_cart_item_data', 'add_bat_customizer_to_cart', 10, 3);
function add_bat_customizer_to_cart($cart_item_data, $product_id, $variation_id) {
    if (empty($_POST)) return $cart_item_data;
    
    // Check if deep customization is enabled
    $deep_custom = isset($_POST['deep_customisation']) ? sanitize_text_field($_POST['deep_customisation']) : 'no';
    
    $sections = array('handle_shape', 'handle_thickness', 'handle_type', 'sweet_spot', 'toe_shape', 'oiling_knocking', 'anti_scuff_sheet', 'toe_guard', 'extra_grips');
    $custom_data = array();
    $additional_price = 0;

    // Only process customizations if deep customization is enabled
    if ($deep_custom === 'yes') {
        foreach ($sections as $key) {
            if (isset($_POST['bat_' . $key]) && $_POST['bat_' . $key] !== '') {
                $options = get_post_meta($product_id, '_' . $key, true);
                $index = intval($_POST['bat_' . $key]);
                if (isset($options[$index])) {
                    $option = $options[$index];
                    $custom_data[$key] = $option['label'];
                    $additional_price += floatval($option['price'] ?? 0);
                }
            }
        }
    }

    if (isset($_POST['laser_engraving_text']) && !empty($_POST['laser_engraving_text'])) {
        $laser_text = sanitize_text_field($_POST['laser_engraving_text']);
        $laser_price = floatval(get_post_meta($product_id, '_laser_engraving_price', true)) ?: 0;
        
        $cart_item_data['laser_engraving'] = $laser_text;
        $additional_price += $laser_price;
    }
    
    if (isset($_POST['cover_engraving_text']) && !empty($_POST['cover_engraving_text'])) {
        $cover_text = sanitize_text_field($_POST['cover_engraving_text']);
        $cover_price = floatval(get_post_meta($product_id, '_cover_engraving_price', true)) ?: 0;
        
        $cart_item_data['cover_engraving'] = $cover_text;
        $additional_price += $cover_price;
    }

    // Always save deep customization status
    $cart_item_data['deep_customisation'] = $deep_custom;
    
    if (!empty($custom_data) || isset($cart_item_data['laser_engraving']) || isset($cart_item_data['cover_engraving'])) {
    if (!empty($custom_data)) {
        $cart_item_data['bat_customizer'] = $custom_data;
    }
    $cart_item_data['bat_additional_price'] = $additional_price;
    $prod = wc_get_product($product_id);
    $cart_item_data['bat_base_price'] = floatval($prod->get_price());
    $cart_item_data['unique_key'] = md5(microtime() . rand());
}

    return $cart_item_data;
}

add_filter('woocommerce_get_cart_item_from_session', 'get_bat_customizer_from_session', 10, 3);
function get_bat_customizer_from_session($cart_item, $values, $cart_item_key) {
    if (isset($values['deep_customisation'])) {
        $cart_item['deep_customisation'] = $values['deep_customisation'];
    }
    
    if (isset($values['bat_customizer'])) {
        $cart_item['bat_customizer'] = $values['bat_customizer'];
        $cart_item['bat_additional_price'] = isset($values['bat_additional_price']) ? $values['bat_additional_price'] : 0;
        $cart_item['bat_base_price'] = isset($values['bat_base_price']) ? $values['bat_base_price'] : 0;
    }

    if (isset($values['laser_engraving'])) {
        $cart_item['laser_engraving'] = $values['laser_engraving'];
    }
    
    if (isset($values['cover_engraving'])) {
        $cart_item['cover_engraving'] = $values['cover_engraving'];
    }
    return $cart_item;
}

add_action('woocommerce_before_calculate_totals', 'add_bat_customizer_price_to_cart', 20);
function add_bat_customizer_price_to_cart($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['bat_base_price'])) {
            $base = floatval($cart_item['bat_base_price']);
            $addons = floatval($cart_item['bat_additional_price'] ?? 0);
            $cart_item['data']->set_price($base + $addons);
        }
    }
}

add_filter('woocommerce_cart_item_name', 'display_bat_customizer_in_cart', 10, 3);
function display_bat_customizer_in_cart($item_name, $cart_item, $cart_item_key) {
    if (isset($cart_item['deep_customisation'])) {
        $item_name .= '<dl class="bat-customizer-data">';
        $item_name .= '<dt><strong>Deep Customisation:</strong></dt><dd>' . ucfirst($cart_item['deep_customisation']) . '</dd>';
        
        if (isset($cart_item['laser_engraving'])) {
            $item_name .= '<dt> Laser Engraving:</dt><dd>' . esc_html($cart_item['laser_engraving']) . '</dd>';
        }
        
        if (isset($cart_item['cover_engraving'])) {
            $item_name .= '<dt>Cover Customization:</dt><dd>' . esc_html($cart_item['cover_engraving']) . '</dd>';
        }


        if (isset($cart_item['bat_customizer']) && !empty($cart_item['bat_customizer'])) {
            foreach ($cart_item['bat_customizer'] as $key => $value) {
                $item_name .= '<dt>' . ucwords(str_replace('_', ' ', $key)) . ':</dt><dd>' . esc_html($value) . '</dd>';
            }
        }
        
        $item_name .= '</dl>';
    }
    return $item_name;
}

add_action('woocommerce_checkout_create_order_line_item', 'save_bat_customizer_to_order', 10, 4);
function save_bat_customizer_to_order($item, $cart_item_key, $values, $order) {
    if (isset($values['deep_customisation'])) {
        $item->add_meta_data('Deep Customisation', ucfirst($values['deep_customisation']), true);
    }
    
    if (isset($values['bat_customizer'])) {
        foreach ($values['bat_customizer'] as $key => $value) {
            $item->add_meta_data(ucwords(str_replace('_', ' ', $key)), $value, true);
        }
    }
    if (isset($values['laser_engraving'])) {
        $item->add_meta_data('Laser Engraving', $values['laser_engraving'], true);
    }
    
    if (isset($values['cover_engraving'])) {
        $item->add_meta_data('Cover Customization', $values['cover_engraving'], true);
    }
}

add_filter('woocommerce_order_item_name', 'display_bat_customizer_in_order', 10, 2);
function display_bat_customizer_in_order($item_name, $item) {
    $deep_custom = $item->get_meta('Deep Customisation');
    
    if ($deep_custom) {
        $item_name .= '<dl class="bat-customizer-data" style="margin-top:10px; font-size:0.9em;">';
        $item_name .= '<dt style="font-weight:600;">Deep Customisation:</dt><dd style="margin:0 0 5px 0;">' . esc_html($deep_custom) . '</dd>';
        
        // Get all metadata and display customizer options
        $metadata = $item->get_meta_data();
        foreach ($metadata as $meta) {
            if ($meta->key !== 'Deep Customisation' && strpos($meta->key, '_') !== 0) {
                $item_name .= '<dt style="font-weight:600;">' . esc_html($meta->key) . ':</dt><dd style="margin:0 0 5px 0;">' . esc_html($meta->value) . '</dd>';
            }
        }
        
        $item_name .= '</dl>';
    }
    
    return $item_name;
}

// CSS
add_action('wp_head', 'bat_customizer_css');
function bat_customizer_css() {
    if (!is_product()) return;
    ?>
    <style>
        #bat-customizer {
            margin-top: 20px;
            padding: 20px;
            background: #fff;
        }
        .deep-customisation-toggle {
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.deep-customisation-toggle label {
    user-select: none;
}
#deep-customisation {
    accent-color: #0066ff;
}
 .customizer-section {
    margin-bottom: 10px;
}

.customizer-section:has(.engraving-section) {
    margin-bottom: 0;
}

.engraving-section {
    margin-bottom: 10px !important;
}

        .customizer-section h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .text-option {
    padding: 10px 20px;
    border: 1px solid #ddd;
    cursor: pointer;
    text-align: center;
    border-radius: 4px;
    transition: all 0.3s;
    background: white;
    min-width: 120px;
    font-size: 14px;
}

.text-option:hover {
    border-color: #0066ff;
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,102,255,0.1);
}

.image-option:hover {
    border-color: #0066ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,102,255,0.15);
}

.image-option {
    padding: 15px;
    border: 2px solid #ddd;
    cursor: pointer;
    text-align: center;
    border-radius: 8px;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    background: white;
    flex-direction: column;
    display: flex;
    align-items: center;
    min-width: 140px;
}
        .text-option.selected {
    border: 2px solid #0066ff;
    background: #f0f7ff;
    color: #0066ff;
    font-weight: 600;
}
.image-option.selected {
    border: 3px solid #0066ff;
    box-shadow: 0 0 10px rgba(0,102,255,0.2);
}
        .image-option.selected::after {
            content: "✓";
            position: absolute;
            top: 8px;
            right: 8px;
            background: #0066ff;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            font-size: 16px;
            line-height: 24px;
        }
        .image-option img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
}
        .price {
    color: #666;
    font-size: 13px;
    margin-top: 5px;
    display: block;
}
.image-option .label {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 5px;
}
        .totals p {
            font-size: 16px;
        }
        .faqs .faq-item {
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .faqs h3 {
            cursor: pointer;
        }
        .faqs .faq-item p {
            display: none;
        }
        .faqs .faq-item.active p {
            display: block;
        }
        .grid-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .grid-item img {
            width: 100%;
        }
        .edition-section, .grain-section, .below-hero, .bat-matters {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
        }
        @media (max-width: 768px) {
            .grid-section {
                grid-template-columns: 1fr;
            }
            .options {
                flex-direction: column;
            }
        }
        .bat-customizer-data {
    margin: 10px 0;
    padding: 10px;
    background: #f9f9f9;
    border-left: 3px solid #0066ff;
}
.bat-customizer-data dt {
    font-weight: 600;
    margin-top: 5px;
    color: #333;
}
.bat-customizer-data dd {
    margin: 0 0 8px 15px;
    color: #666;
}
    </style>
    <?php
}

// Shortcode
add_shortcode('bat_customizer', 'bat_customizer_shortcode');
function bat_customizer_shortcode() {
    ob_start();
    render_bat_customizer();
    return ob_get_clean();
}

// Elementor Widget
// Elementor Widget (Fixed)
if (defined('ELEMENTOR_VERSION') && class_exists('\Elementor\Widget_Base')) {

    class Bat_Customizer_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'bat_customizer';
        }

        public function get_title() {
            return __('Bat Customizer', 'woocommerce');
        }

        public function get_icon() {
            return 'eicon-form-horizontal';
        }

        // IMPORTANT: Use general so it always appears in Elementor
        public function get_categories() {
            return ['general'];
        }

        protected function render() {
            // Force render inside Elementor too
            render_bat_customizer(true);
        }
    }

    add_action('elementor/widgets/register', function($widgets_manager) {
        $widgets_manager->register(new \Bat_Customizer_Widget());
    });
}


// Dynamic Tags for Page Builders
// ============================================
// DYNAMIC TAGS FOR PAGE BUILDERS (ELEMENTOR)
// ============================================

// Register all custom fields as product properties
add_action('init', 'register_bat_custom_fields_for_dynamic_tags');
function register_bat_custom_fields_for_dynamic_tags() {
    // This function registers the fields (already exists in your code)
    $fields = array(
        '_edition_heading' => 'Edition Heading',
        '_short_edition_description' => 'Short Edition Description',
        '_grains' => 'Grains',
        '_grade' => 'Grade',
        '_grain_description' => 'Grain Grade Description',
        '_section_below_hero' => 'Section Below Hero (Text)',
        '_bat_that_matters_section' => 'Bat That Matters – Description',
        '_edition_image' => 'Edition Image (URL)',
        '_bat_that_matters_image' => 'Bat That Matters – Image (URL)',
        '_grid_image_1' => 'Grid Image 1 (URL)',
        '_grid_image_2' => 'Grid Image 2 (URL)',
        '_grid_image_3' => 'Grid Image 3 (URL)',
        '_grid_section' => 'Grid Section (HTML)',
        '_faqs' => 'FAQs (Accordion HTML)',
    );
}

// ============================================
// 1. EDITION HEADING
// ============================================
add_filter('woocommerce_product_get__edition_heading', 'get_edition_heading_dynamic', 10, 2);
function get_edition_heading_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_edition_heading', true);
    }
    return $value ? $value : '';
}

// ============================================
// 2. SHORT EDITION DESCRIPTION
// ============================================
add_filter('woocommerce_product_get__short_edition_description', 'get_short_edition_description_dynamic', 10, 2);
function get_short_edition_description_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_short_edition_description', true);
    }
    return $value ? $value : '';
}

// ============================================
// 3. EDITION IMAGE (Returns URL)
// ============================================
add_filter('woocommerce_product_get__edition_image', 'get_edition_image_url_dynamic', 10, 2);
function get_edition_image_url_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_edition_image', true);
    }
    
    // Convert image ID to URL
    if ($value && is_numeric($value)) {
        $url = wp_get_attachment_url($value);
        return $url ? $url : '';
    }
    
    return $value ? $value : '';
}

// ============================================
// 4. GRAINS
// ============================================
add_filter('woocommerce_product_get__grains', 'get_grains_dynamic', 10, 2);
function get_grains_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_grains', true);
    }
    return $value ? $value : '';
}

// ============================================
// 5. GRADE
// ============================================
add_filter('woocommerce_product_get__grade', 'get_grade_dynamic', 10, 2);
function get_grade_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_grade', true);
    }
    return $value ? $value : '';
}

// ============================================
// 6. GRAIN DESCRIPTION
// ============================================
add_filter('woocommerce_product_get__grain_description', 'get_grain_description_dynamic', 10, 2);
function get_grain_description_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_grain_description', true);
    }
    return $value ? $value : '';
}

// ============================================
// 7. SECTION BELOW HERO
// ============================================
add_filter('woocommerce_product_get__section_below_hero', 'get_section_below_hero_dynamic', 10, 2);
function get_section_below_hero_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_section_below_hero', true);
    }
    return $value ? $value : '';
}

// ============================================
// 8. BAT THAT MATTERS SECTION (Description)
// ============================================
add_filter('woocommerce_product_get__bat_that_matters_section', 'get_bat_matters_section_dynamic', 10, 2);
function get_bat_matters_section_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_bat_that_matters_section', true);
    }
    return $value ? $value : '';
}

// ============================================
// 9. BAT THAT MATTERS IMAGE (Returns URL)
// ============================================
add_filter('woocommerce_product_get__bat_that_matters_image', 'get_bat_matters_image_url_dynamic', 10, 2);
function get_bat_matters_image_url_dynamic($value, $product) {
    if (!$value) {
        $value = get_post_meta($product->get_id(), '_bat_that_matters_image', true);
    }
    
    // Convert image ID to URL
    if ($value && is_numeric($value)) {
        $url = wp_get_attachment_url($value);
        return $url ? $url : '';
    }
    
    return $value ? $value : '';
}

// ============================================
// 10. GRID IMAGE 1 (Returns URL)
// ============================================
add_filter('woocommerce_product_get__grid_image_1', 'get_grid_image_1_dynamic', 10, 2);
function get_grid_image_1_dynamic($value, $product) {
    $image_id = get_first_grid_image_by_position($product->get_id(), 1);
    if ($image_id && is_numeric($image_id)) {
        $url = wp_get_attachment_url($image_id);
        return $url ? $url : '';
    }
    return '';
}

// ============================================
// 11. GRID IMAGE 2 (Returns URL)
// ============================================
add_filter('woocommerce_product_get__grid_image_2', 'get_grid_image_2_dynamic', 10, 2);
function get_grid_image_2_dynamic($value, $product) {
    $image_id = get_first_grid_image_by_position($product->get_id(), 2);
    if ($image_id && is_numeric($image_id)) {
        $url = wp_get_attachment_url($image_id);
        return $url ? $url : '';
    }
    return '';
}

// ============================================
// 12. GRID IMAGE 3 (Returns URL)
// ============================================
add_filter('woocommerce_product_get__grid_image_3', 'get_grid_image_3_dynamic', 10, 2);
function get_grid_image_3_dynamic($value, $product) {
    $image_id = get_first_grid_image_by_position($product->get_id(), 3);
    if ($image_id && is_numeric($image_id)) {
        $url = wp_get_attachment_url($image_id);
        return $url ? $url : '';
    }
    return '';
}

// Helper function to get grid images
function get_first_grid_image_by_position($product_id, $position) {
    $grid = get_post_meta($product_id, '_grid_section', true);
    if (!is_array($grid)) return '';

    foreach ($grid as $item) {
        if (!empty($item['image' . $position])) {
            return $item['image' . $position];
        }
    }
    return '';
}

// ============================================
// 13. GRID SECTION TEXT (First grid item text)
// ============================================
add_filter('woocommerce_product_get__grid_section_text', 'get_grid_section_text_dynamic', 10, 2);
function get_grid_section_text_dynamic($value, $product) {
    $grid = get_post_meta($product->get_id(), '_grid_section', true);
    if (is_array($grid) && !empty($grid)) {
        foreach ($grid as $item) {
            if (!empty($item['text'])) {
                return $item['text'];
            }
        }
    }
    return '';
}

// ============================================
// 14. GRID SECTION (Returns Full HTML)
// ============================================
add_filter('woocommerce_product_get__grid_section', 'render_grid_section_html_for_dynamic_tags', 10, 2);
function render_grid_section_html_for_dynamic_tags($value, $product) {
    $grid = get_post_meta($product->get_id(), '_grid_section', true);
    
    if (!is_array($grid) || empty($grid)) return '';

    ob_start();
    echo '<div class="bat-grid-section" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px; margin:40px 0;">';
    
    foreach ($grid as $item) {
        if (empty($item['image1']) && empty($item['image2']) && empty($item['image3']) && empty($item['text'])) {
            continue;
        }

        echo '<div class="bat-grid-item" style="background:#f9f9f9; padding:20px; border-radius:8px;">';
        
        if (!empty($item['image1'])) {
            echo wp_get_attachment_image($item['image1'], 'medium', false, ['style' => 'width:100%; height:auto; border-radius:4px; margin-bottom:10px;']);
        }
        
        if (!empty($item['image2'])) {
            echo wp_get_attachment_image($item['image2'], 'medium', false, ['style' => 'width:100%; height:auto; border-radius:4px; margin-bottom:10px;']);
        }
        
        if (!empty($item['image3'])) {
            echo wp_get_attachment_image($item['image3'], 'medium', false, ['style' => 'width:100%; height:auto; border-radius:4px; margin-bottom:10px;']);
        }
        
        if (!empty($item['text'])) {
            echo '<p style="margin:0; line-height:1.6;">' . wp_kses_post(nl2br($item['text'])) . '</p>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
    return ob_get_clean();
}

// ============================================
// 15. FAQS (Returns Accordion HTML)
// ============================================
add_filter('woocommerce_product_get__faqs', 'render_faqs_html_for_dynamic_tags', 10, 2);
function render_faqs_html_for_dynamic_tags($value, $product) {
    if (empty($value) || !is_array($value)) {
        $value = get_post_meta($product->get_id(), '_faqs', true);
    }
    
    if (empty($value) || !is_array($value)) return '';

    ob_start();
    echo '<div class="bat-faqs-dynamic" style="margin:40px 0;">';
    echo '<h3 style="font-size:24px; margin-bottom:20px; font-weight:600;">Frequently Asked Questions</h3>';
    
    foreach ($value as $faq) {
        if (empty($faq['question']) && empty($faq['answer'])) continue;
        
        echo '<details style="margin-bottom:8px; border-bottom:1px solid #eee; padding-bottom:10px;">';
        echo '<summary style="font-weight:600; cursor:pointer; color:#2271b1; padding:10px 0;">' . esc_html($faq['question']) . '</summary>';
        echo '<div style="margin-top:10px; padding-left:10px; color:#666;">' . wp_kses_post(wpautop($faq['answer'])) . '</div>';
        echo '</details>';
    }
    
    echo '</div>';
    return ob_get_clean();
}

// AJAX handler for formatting prices
add_action('wp_ajax_format_bat_price', 'format_bat_price_ajax');
add_action('wp_ajax_nopriv_format_bat_price', 'format_bat_price_ajax');
function format_bat_price_ajax() {
    
    check_ajax_referer('bat_customizer_nonce', 'security');

    $addons = isset($_POST['addons']) ? floatval($_POST['addons']) : 0;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    
    wp_send_json_success(array(
        'addons' => wc_price($addons),
        'total' => wc_price($total)
    ));
}