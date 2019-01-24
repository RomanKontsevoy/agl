<?php

new My_Best_Metaboxes;

class My_Best_Metaboxes
{

    public $post_type = 'album';

    static $meta_key = 'album_youtube';

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_metabox'));
        add_action('save_post_' . $this->post_type, array($this, 'save_metabox'));
        add_action('admin_print_footer_scripts', array($this, 'show_assets'), 10, 999);
    }

    ## Добавляет матабоксы
    public function add_metabox()
    {
        add_meta_box('box_info_company', 'Видео альбома', array($this, 'render_metabox'), $this->post_type, 'advanced', 'high');
    }

    ## Отображает метабокс на странице редактирования поста
    public function render_metabox($post)
    {
        ?>
        <table class="form-table company-info">
            <tr>
                <th>
                    Коды видео с youtube <span class="dashicons dashicons-plus-alt add-company-address"></span>
                    <br>
                    <i style="font-size: 10px; font-weight: normal;">(11-значный набор символов, в адресе страницы YouTube после "?v=", например Jj8XVO30pU4)</i>
                </th>
                <td class="company-address-list">
                    <?php
                    $value = get_post_meta($post->ID, self::$meta_key, 1);
                    $input = '
					<span class="item-address">
						<input type="text" name="' . self::$meta_key . '[]" value="%s">
						<span class="dashicons dashicons-trash remove-company-address"></span>
					</span>
					';

                    $addresses = get_post_meta($post->ID, self::$meta_key, true);

                    if (is_array($addresses)) {
                        for ($i = 0; $i < count($addresses); $i++) {
                            $addr = $addresses[$i];
                            printf($input, esc_attr($addr));
                        }
                    } else {
                        printf($input, '');
                    }
                    ?>
                </td>
            </tr>

        </table>

        <?php
    }

    ## Очищает и сохраняет значения полей
    public function save_metabox($post_id)
    {

        // Check if it's not an autosave.
        if (wp_is_post_autosave($post_id))
            return;

        if (isset($_POST[self::$meta_key]) && is_array($_POST[self::$meta_key])) {
            $addresses = $_POST[self::$meta_key];

            $addresses = array_map('sanitize_text_field', $addresses); // очистка

            $addresses = array_filter($addresses); // уберем пустые адреса

            if ($addresses)
                update_post_meta($post_id, self::$meta_key, $addresses);
            else
                delete_post_meta($post_id, self::$meta_key);

        }
    }

    ## Подключает скрипты и стили
    public function show_assets()
    {
        if (is_admin() && get_current_screen()->id == $this->post_type) {
            $this->show_styles();
            $this->show_scripts();
        }
    }

    ## Выводит на экран стили
    public function show_styles()
    {
        ?>
        <style>
            .add-company-address {
                color: #00a0d2;
                cursor: pointer;
            }

            .company-address-list .item-address {
                display: flex;
                align-items: center;
            }

            .company-address-list .item-address input {
                width: 100%;
                max-width: 400px;
            }

            .remove-company-address {
                color: brown;
                cursor: pointer;
            }
        </style>
        <?php
    }

    ## Выводит на экран JS
    public function show_scripts()
    {
        ?>
        <script>
            jQuery(document).ready(function ($) {

                var $companyInfo = $('.company-info');

                // Добавляет бокс с вводом адреса фирмы
                $('.add-company-address', $companyInfo).click(function () {
                    var $list = $('.company-address-list');
                    $item = $list.find('.item-address').first().clone();

                    $item.find('input').val(''); // чистим знанчение

                    $list.append($item);
                });

                // Удаляет бокс с вводом адреса фирмы
                $companyInfo.on('click', '.remove-company-address', function () {
                    if ($('.item-address').length > 1) {
                        $(this).closest('.item-address').remove();
                    }
                    else {
                        $(this).closest('.item-address').find('input').val('');
                    }
                });

            });
        </script>
        <?php
    }

}