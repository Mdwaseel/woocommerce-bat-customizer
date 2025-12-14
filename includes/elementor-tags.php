<?php
/**
 * Elementor Dynamic Tags for Bat Customizer
 */

if (!defined('ABSPATH')) exit;

// TEXT TAGS
class Bat_Edition_Heading_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-edition-heading'; }
    public function get_title() { return 'Edition Heading'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            echo esc_html(get_post_meta($product->get_id(), '_edition_heading', true));
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_Short_Description_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-short-description'; }
    public function get_title() { return 'Short Edition Description'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            echo esc_html(get_post_meta($product->get_id(), '_short_edition_description', true));
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_Grains_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-grains'; }
    public function get_title() { return 'Grains'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            echo esc_html(get_post_meta($product->get_id(), '_grains', true));
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_Grade_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-grade'; }
    public function get_title() { return 'Grade'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            echo esc_html(get_post_meta($product->get_id(), '_grade', true));
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_Grain_Description_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-grain-description'; }
    public function get_title() { return 'Grain Description'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            echo wp_kses_post(get_post_meta($product->get_id(), '_grain_description', true));
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_Section_Below_Hero_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-section-below-hero'; }
    public function get_title() { return 'Section Below Hero'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            echo wp_kses_post(get_post_meta($product->get_id(), '_section_below_hero', true));
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_Matters_Section_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-matters-section'; }
    public function get_title() { return 'Bat That Matters Section'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            echo wp_kses_post(get_post_meta($product->get_id(), '_bat_that_matters_section', true));
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_Grid_Section_Text_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-grid-section-text'; }
    public function get_title() { return 'Grid Section Text'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            $grid = get_post_meta($product->get_id(), '_grid_section', true);
            if (is_array($grid) && !empty($grid)) {
                foreach ($grid as $item) {
                    if (!empty($item['text'])) {
                        echo wp_kses_post($item['text']);
                        return;
                    }
                }
            }
        } catch (Exception $e) {
            return;
        }
    }
}

// Edition Image Tag - FIXED
class Bat_Edition_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    public function get_name() { return 'bat-edition-image'; }
    public function get_title() { return 'Edition Image'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY]; }
    
    public function get_value(array $options = []) {
        try {
            $product = bat_get_current_product();
            if (!$product) return [];
            
            $image_id = get_post_meta($product->get_id(), '_edition_image', true);
            if (!$image_id || !is_numeric($image_id)) return [];
            
            $image_src = wp_get_attachment_image_src($image_id, 'full');
            if (!$image_src) return [];
            
            return [
                'id' => $image_id,
                'url' => $image_src[0],
            ];
        } catch (Exception $e) {
            return [];
        }
    }
}


// Bat Matters Image Tag - FIXED
class Bat_Matters_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    public function get_name() { return 'bat-matters-image'; }
    public function get_title() { return 'Bat That Matters Image'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY]; }
    
    public function get_value(array $options = []) {
        try {
            $product = bat_get_current_product();
            if (!$product) return [];
            
            $image_id = get_post_meta($product->get_id(), '_bat_that_matters_image', true);
            if (!$image_id || !is_numeric($image_id)) return [];
            
            $image_src = wp_get_attachment_image_src($image_id, 'full');
            if (!$image_src) return [];
            
            return [
                'id' => $image_id,
                'url' => $image_src[0],
            ];
        } catch (Exception $e) {
            return [];
        }
    }
}
// Grid Image 1 Tag - FIXED
class Bat_Grid_Image_1_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    public function get_name() { return 'bat-grid-image-1'; }
    public function get_title() { return 'Grid Image 1'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY]; }
    
    public function get_value(array $options = []) {
        try {
            $product = bat_get_current_product();
            if (!$product) return [];
            
            $grid = get_post_meta($product->get_id(), '_grid_section', true);
            if (!is_array($grid) || empty($grid)) return [];
            
            foreach ($grid as $item) {
                if (!empty($item['image1']) && is_numeric($item['image1'])) {
                    $image_src = wp_get_attachment_image_src($item['image1'], 'full');
                    if ($image_src) {
                        return [
                            'id' => $item['image1'],
                            'url' => $image_src[0],
                        ];
                    }
                }
            }
            return [];
        } catch (Exception $e) {
            return [];
        }
    }
}

// Grid Image 2 Tag - FIXED
class Bat_Grid_Image_2_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    public function get_name() { return 'bat-grid-image-2'; }
    public function get_title() { return 'Grid Image 2'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY]; }
    
    public function get_value(array $options = []) {
        try {
            $product = bat_get_current_product();
            if (!$product) return [];
            
            $grid = get_post_meta($product->get_id(), '_grid_section', true);
            if (!is_array($grid) || empty($grid)) return [];
            
            foreach ($grid as $item) {
                if (!empty($item['image2']) && is_numeric($item['image2'])) {
                    $image_src = wp_get_attachment_image_src($item['image2'], 'full');
                    if ($image_src) {
                        return [
                            'id' => $item['image2'],
                            'url' => $image_src[0],
                        ];
                    }
                }
            }
            return [];
        } catch (Exception $e) {
            return [];
        }
    }
}

// Grid Image 3 Tag - FIXED
class Bat_Grid_Image_3_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    public function get_name() { return 'bat-grid-image-3'; }
    public function get_title() { return 'Grid Image 3'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY]; }
    
    public function get_value(array $options = []) {
        try {
            $product = bat_get_current_product();
            if (!$product) return [];
            
            $grid = get_post_meta($product->get_id(), '_grid_section', true);
            if (!is_array($grid) || empty($grid)) return [];
            
            foreach ($grid as $item) {
                if (!empty($item['image3']) && is_numeric($item['image3'])) {
                    $image_src = wp_get_attachment_image_src($item['image3'], 'full');
                    if ($image_src) {
                        return [
                            'id' => $item['image3'],
                            'url' => $image_src[0],
                        ];
                    }
                }
            }
            return [];
        } catch (Exception $e) {
            return [];
        }
    }
}

// HTML TAGS
class Bat_Grid_Section_HTML_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-grid-section-html'; }
    public function get_title() { return 'Grid Section (Full HTML)'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            $grid = get_post_meta($product->get_id(), '_grid_section', true);
            
            if (!is_array($grid) || empty($grid)) return;

            echo '<div class="bat-grid-section" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">';
            
            foreach ($grid as $item) {
                if (empty($item['image1']) && empty($item['image2']) && empty($item['image3']) && empty($item['text'])) continue;

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
        } catch (Exception $e) {
            return;
        }
    }
}

class Bat_FAQs_HTML_Tag extends \Elementor\Core\DynamicTags\Tag {
    public function get_name() { return 'bat-faqs-html'; }
    public function get_title() { return 'FAQs (Accordion HTML)'; }
    public function get_group() { return 'bat-customizer'; }
    public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
    public function render() {
        try {
            $product = bat_get_current_product();
            if (!$product) return;
            $faqs = get_post_meta($product->get_id(), '_faqs', true);
            
            if (empty($faqs) || !is_array($faqs)) return;

            echo '<div class="bat-faqs-dynamic">';
            
            foreach ($faqs as $faq) {
                if (empty($faq['question']) && empty($faq['answer'])) continue;
                
                echo '<details style="margin-bottom:15px; border-bottom:1px solid #eee; padding-bottom:10px;">';
                echo '<summary style="font-weight:600; cursor:pointer; color:#2271b1; padding:10px 0;">' . esc_html($faq['question']) . '</summary>';
                echo '<div style="margin-top:10px; padding-left:10px; color:#666;">' . wp_kses_post(wpautop($faq['answer'])) . '</div>';
                echo '</details>';
            }
            
            echo '</div>';
        } catch (Exception $e) {
            return;
        }
    }
}