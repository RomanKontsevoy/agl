<?php

new My_Best_Shedule_Metaboxes;


class My_Best_Shedule_Metaboxes
{

    public $post_type = 'shedule';

    static $meta_key = 'days';

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_metabox'));
        add_action('save_post_' . $this->post_type, array($this, 'save_metabox'));
        add_action('admin_print_footer_scripts', array($this, 'show_assets'), 10, 999);
    }

    ## Добавляет матабоксы
    public function add_metabox()
    {
        add_meta_box('box_info_company', 'Расписание', array($this, 'render_metabox'), $this->post_type, 'advanced', 'high');
    }

    ## Отображает метабокс на странице редактирования поста
    public function render_metabox($post)
    {
        ?>
        <table class="form-table shedule-info">
            <tr>
                <th>
                    День: <span class="dashicons dashicons-plus-alt add-weekday"></span>
                </th>
                <td class="company-address-list">
                    <?php

                    $one_day = '
					<div class="item-day">
					<div class="item-day__wrap">
                        <div class="item-day__meta">
                            <input type="date" name="' . self::$meta_key . '[%d][date]" value="%s">
                            <input type="text" readonly class="weekday" name="' . self::$meta_key . '[%d][day]" value="%s">
                        </div>
                        <div class="item-day__events">
                            <div class="event-row">
                                <div class="event-left">
                                    Событие: <span class="dashicons dashicons-plus-alt add-event"></span>
                                </div>
                                <div class="event-right">
                                     %s                               
                                </div>
                            </div>
                        </div>
                    </div>
						<span class="dashicons dashicons-trash remove-weekday"></span>
					</div>
					';

                    $one_event = '
                        <div class="event-item">
                            <div class="event-input__block">
                                <input type="time" class="event-time" name="' . self::$meta_key . '[%d][events][%d][time]" value="%s">
                                <textarea type="text" class="event-text typograf" name="' . self::$meta_key . '[%d][events][%d][text]" value="%s">%s</textarea>
                            </div>
                            <span class="dashicons dashicons-trash remove-event"></span>
                        </div>
                    ';

                    $days = get_post_meta($post->ID, self::$meta_key, true);
                    if (is_array($days)) {
                        for ($i = 0; $i < count($days); $i++) {
                            $this_day = $days[$i];

                            $events = '';

                            foreach ($this_day["events"] as $j => $event) {
                                $curr_event = sprintf($one_event, $i, $j, esc_attr($event[time]), $i, $j, esc_attr($event[text]), esc_attr($event[text]));
                                $events .= $curr_event;
                            }

                            printf(
                                $one_day,
                                $i,
                                esc_attr($this_day[date]),
                                $i,
                                esc_attr($this_day[day]),
                                $events
                            );
                        }
                    } else {
                        printf($one_day, 0, '', 0, '',
                            sprintf($one_event, 0, 0, "", 0, 0, "", "")
                        );
                    }
                    ?>
                </td>
            </tr>
        </table>
        <div class="btn-like" id="typogr">Подготовить текст для публикации</div>
        <style>
            #typograf {
                background-color: #cccccc;
                display: inline-block;
                border-radius: 2px;
                border: 1px solid #aaa;
                cursor: pointer;
                padding: 2px 5px;
            }

            #typograf:hover {
                background-color: #ddddcc;
            }

        </style>
        <?php
    }

    ## Очищает и сохраняет значения полей
    public function save_metabox($post_id)
    {

        // Check if it's not an autosave.
        if (wp_is_post_autosave($post_id))
            return;

        if (isset($_POST[self::$meta_key]) && is_array($_POST[self::$meta_key])) {
            $days = $_POST[self::$meta_key];
            foreach ($days as $day) {
                foreach ($day as $key => $value) {
                    if (!is_array($value)) {
                        $day[$key] = array_map('sanitize_text_field', array($value)); // очистка
                        $day[$key] = array_filter(array($value)); // уберем пустые адреса
                    } else {
                        foreach ($value as $event) {
                            foreach ($event as $key => $value) {
                                $event[$key] = array_map('sanitize_text_field', array($value)); // очистка
                                $event[$key] = array_filter(array($value)); // уберем пустые адреса
                            }
                        }
                    }

                }
            }

            if ($days)
                update_post_meta($post_id, self::$meta_key, $days);
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
            .add-weekday,
            .add-event {
                color: #00a0d2;
                cursor: pointer;
            }

            .company-address-list .item-day {
                display: flex;
                align-items: center;
            }

            .company-address-list .item-day input {
                width: 100%;
                max-width: 400px;
            }

            .shedule-info th {
                width: 65px;
                vertical-align: bottom;
            }

            .remove-weekday,
            .remove-event {
                color: brown;
                cursor: pointer;
            }

            .item-day__wrap {
                width: 100%;
                padding: 2px 2px 0 2px;
                border: 2px inset #ccc;
                margin-bottom: 4px;
            }

            .item-day__meta {
                display: flex;
                margin: 0 0 4px;

            }

            .item-day__events {
                background: #d6d6d6;
                margin: 0 0 4px;
            }

            .event-item {
                display: flex;
                align-items: flex-end;
            }

            .event-input__block {
                width: 100%;
            }

            .event-text {
                width: 100%;
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
                var sheduleInfo = $('.shedule-info');
                // Добавляет бокс с новым днем недели
                $('.add-weekday', sheduleInfo).click(function () {
                    var $list = $('.company-address-list');
                    var itemDays = $list.find('.item-day').length;
                    var item = $list.find('.item-day').first().clone();
                    var event = item.find('.event-item').first().clone();

                    item.find('.event-right').html(event); // вставляем только одно поле с событием
                    item.find('input').val(''); // чистим знанчение
                    item.find('.event-text').val(''); // чистим знанчение

                    item.find('input').attr("name", function () {
                        var el = this;
                        var currName = $(el).attr("name");
                        currName = currName.replace("0", itemDays);
                        return currName;
                    }); // устанавливаем правильный индекс массива days
                    item.find('.event-text').attr("name", function () {
                        var el = this;
                        var currName = $(el).attr("name");
                        currName = currName.replace("0", itemDays);
                        return currName;
                    });
                    $list.append(item);
                });

                // Добавляет внрутри дня недели новое событие

                $(sheduleInfo).on('click', '.add-event', function () {
                    var el = $(this);
                    var list = el.parent().next('.event-right');
                    var itemDays = list.find('.event-item').length;
                    var item = list.find('.event-item').first().clone();
                    item.find('input').val(''); // чистим знанчение
                    item.find('.event-text').val(''); // чистим знанчение

                    item.find('input').attr("name", function () {
                        var el = this;
                        var currName = $(el).attr("name");
                        currName = currName.replace("[events][0", "[events][" + itemDays);
                        return currName;
                    }); // устанавливаем правильный индекс массива days
                    item.find('.event-text').attr("name", function () {
                        var el = this;
                        var currName = $(el).attr("name");
                        currName = currName.replace("[events][0", "[events][" + itemDays);
                        return currName;
                    });


                    list.append(item);
                });


                // Удаляет день недели

                sheduleInfo.on('click', '.remove-weekday', function () {
                    if ($('.item-day').length > 1) {
                        $(this).closest('.item-day').remove();
                    }
                    else {
                        $(this).closest('.item-day').find('input').val('');
                        $(this).closest('.item-day').find('.event-text').val('');
                    }
                });

                // Удаляет одно событие

                sheduleInfo.on('click', '.remove-event', function () {
                    var el = $(this);

                    if (el.closest('.event-right').find('.event-item').length > 1) {
                        el.closest('.event-item').remove();
                    }
                    else {
                        el.closest('.event-item').find('input').val('');
                        el.closest('.event-item').find('.event-text').val('');
                    }
                });

                // Трансформирует дату в человеческий вид

                function dateTransform(date, type) {
                    var thisDate = new Date(date);
                    var options = {};
                    if (type === "weekday") options.weekday = 'long';
                    if (type === "date") {
                        options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                        }
                    }
                    thisDate = thisDate.toLocaleString("ru", options);
                    return thisDate;
                }

                // Автоматически выводит день недели после ввода даты

                $('.company-address-list').on('input', 'input[type="date"]', function () {
                    var thisEl = $(this);
                    var el = $(thisEl).siblings('.weekday');
                    if (thisEl.val()) {
                        var thisDate = dateTransform(thisEl.val(), "weekday");
                        el.attr("value", thisDate);
                        if (thisDate === "воскресенье") {
                            $('input[name="extra[_date]"]').val(thisEl.val());
                        } else {
                            $('input[name="extra[_date]"]').val("");
                        }
                    }
                    var days = $('.item-day');
                    var post_title = $('input[name="post_title"]');
                    var start_date = $(days[0]).find('input[type="date"]').val();
                    var end_date = $(days[days.length - 1]).find('input[type="date"]').val();
                    start_date = dateTransform(start_date, "date");
                    end_date = dateTransform(end_date, "date");
                    var output = start_date + " - " + end_date;
                    post_title.val(output);
                    $('#title-prompt-text').html("");
                });
            });
        </script>
        <?php
    }

}