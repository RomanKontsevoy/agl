var author = url('?post_author');
$(document).ready(function () {
    if (document.querySelector('.author-selector')) {
        // Скрипт для вставки поисковых запросов в инпуты после перезагрузки. Требует url.js
        var author, // имя автора из url
            search, // ключевое слово из url
            select = document.querySelector('.author-selector'), // селект формы, где выбирается автор
            searchInput = document.querySelector('.search-input'), // инпут для ввода поискового слова
            filter = $('.filter'), // jquery-елемент формы фильтра/поиска
            action = filter.attr('action'), // урл запроса из формы
            method = filter.attr('method'); // метод запроса из формы

        function urlToForm() {
            var ops = select.options,
                author = url('?post_author');

            function titleSet() {
                return author && author!=="" && author!=="Все" ? author + " - проповеди" : "Все проповеди"
            };

            switch (location.pathname) {
                case "/video/":
                    document.title = titleSet();
                    break;
                case "/article/":
                    document.title = "Все публикации";
                    break;
                default:
                    document.title = document.querySelector(".article-title").innerHTML;
                    break;
            };

            if (author) { // ставит аттрибут "selected" необходимому пункту селекта исходя из url
                select.value = author;
                for (var i = 0; i < ops.length; i++) {
                    if (ops[i].value === author) {
                        ops[i].setAttribute('selected', '');
                    } else {
                        if (ops[i].hasAttribute('selected')) ops[i].removeAttribute('selected');
                    }
                }
            } else {
                select.value = "";
                ops[0].setAttribute('selected', '');
                for (var i = 1; i < ops.length; i++) {
                    if (ops[i].hasAttribute('selected')) ops[i].removeAttribute('selected');
                }
            }
            if (url('?keyword')) { // добавляет в инпут ключевое слово, если оно содержится в url
                search = url('?keyword');
                searchInput.value = search;
            }
        }

        urlToForm();

        function urlToObject(str) { // из url берет поиск и превращает в объект
            var arr = str.split("&"); //разбиваем строку по знаку &
            var resultArr = [];
            arr.forEach(function (item, i, arr) { // сщставляем массив из отдельных частей поиска в url
                item.split('=')
                resultArr.push(item.split('='));
            });
            var resultObj = {};
            resultArr.forEach(function (item) { // формируем объект с парами ключ-значение
                if (item[1]) {
                    resultObj[item[0]] = decodeURIComponent(item[1]);
                }
            });
            return resultObj;
        }

        function objectToUrl(obj) { // создает строчку url из объекта
            var url = '';
            var i = 0
            for (key in obj) {
                if (obj[key]) {
                    if (i > 0) url += "&";
                    url += key + "=" + obj[key];
                    i++
                }
            }
            return url;
        }

        function simplifyUrl(str) { // убирает из урл переменные, которым не присвоено значения
            return objectToUrl(urlToObject(str));
        }

        function ajaxPost(response) { // Обработчик ajax запроса
            $.ajax({
                url: action,
                data: response, // данные запроса в виде урла
                type: method, // тип запроса
                beforeSend: function (xhr) {
                    $('.preach-list__wrap').animate({opacity: 0.7}, 300);
                },
                success: function (data) {
                    $('.preach-list__wrap').animate({opacity: 1}, 100);
                    $('.preach-list__wrap').html(data);
                    urlToForm();
                }
            });
        }

        function paginationCurrent(el) { // находит в строке номер пагинации и возвращает плючевое слово со значением для передачи на бэкенд
            var url = el.attr('href') || '';
            var test = "page/";
            var position = url.indexOf(test);
            var number = parseInt(url.substring(position + test.length))
            return number ? '&next_page=' + number : '&next_page=1';
        }

        $('.filter').submit(function (e) { // событие отправки формы
            e.preventDefault();
            var filter = $(this);
            var currData = simplifyUrl(filter.serialize()); // данные в url-подобном виде с фильтра
            var url_to_obj = urlToObject(currData);
            history.pushState(url_to_obj, 'New Title', '?' + currData);
            ajaxPost(currData);
            return false;
        });

        $('.preach-list__wrap').on('click', '.page-numbers:not(".dots, .current")', function (e) { // событие нажатия на кнопку пагинации
            e.preventDefault();
            var filter = $('.filter');
            var page = paginationCurrent($(this)); // часть урла с номером пагинации
            var currData = simplifyUrl(filter.serialize() + page); // данные в url-подобном виде с фильтра и пагинации
            var url_to_obj = urlToObject(currData);
            history.pushState(url_to_obj, 'New Title', '?' + currData);
            ajaxPost(currData);
            return false;
        });

        /*select.addEventListener('change', function (e) { // запускает срабатывание события submit формы после выбора в селекте нового автора
            $('.filter').trigger('submit');
            return false;
        });*/

        /*selectize [*/

        var selOptions = {
            create: true,
            sortField: 'text',
            hideSelected: true,
            items: [url('?post_author') || "Все"],
            onChange: function (value) {
                $('.filter').trigger('submit');
            }
        };

        var $select = $('.author-selector').selectize(selOptions);
        var selectize = $select[0].selectize;

        /*] selectize*/

        window.addEventListener("popstate", function (event) { // Отслеживание события нажатия кнопок браузера "Вперед/Назад"
            var search = event.state;
            if (!search) search = {"action": "myfilter"};
            if (!search.action) search["action"] = "myfilter";
            if (search || search.post_author) {
                selectize.addItem(search.post_author, true);
            }
            ajaxPost(search);
            urlToForm();

        }, false);
    }
});