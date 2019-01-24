jQuery(document).ready(function () {
    var $ = jQuery;

    function Optimizator(options) {

        let wrappingWords = [],
            wrappingWordsUppercase = [],
            wrappingWordsCapitalize = [],
            abbr = ["апп.", "архиеп.", "архиепп.", "архим.", "архимм.", "бесср.", "блгв.", "блгвв.", "блж.", "блжж.", "вел.", "вмц.", "вмцц.", "вмч.", "вмчч.", "диак.", "епп.", "игум.", "иером.", "иеросхим.", "имп.", "исп.", "кнг.", "кнж.", "кнн.", "митр.", "митрр.", "мцц.", "мчч.", "новмч.", "новосвщмч.", "патр.", "патрр.", "прав.", "правв.", "пресвит.", "прмц.", "прмцц.", "прмч.", "прмчч.", "прор.", "пророчц.", "прорр.", "просвет.", "прот.", "протопресв.", "прп.", "прпп.", "равноап.", "равноапп.", "свв.", "свт.", "свтт.", "свящ.", "столпн.", "страст.", "схим.", "сщмч.", "сщмчч.", "чудотв.", "юрод."];

        wrappingWords = wrappingWords.concat(abbr);

        this.options = options;
        this.options.leftQuote = options.leftQuote || "«";
        this.options.rightQuote = options.rightQuote || "»";
        this.selectors = options.selectors;
        this.target = options.target;
        this.btn = options.button || 'auto';
        this.totalText = '';

        this.createButtons = function () {
            let btnContainer = document.createElement("div");
            btnContainer.style.display = "flex";
            btnContainer.style.justifyContent = "space-evenly";
            btnContainer.style.width = "100%";
            btnContainer.style.padding = "15px";
            btnContainer.style.position = "fixed";
            btnContainer.style.top = "0";
            btnContainer.style.zIndex = "9999999999999999";
            btnContainer.id = "buttonChangeContainer";
            document.body.insertBefore(btnContainer, document.body.firstChild);
            let btnWhiteSpaces = document.createElement("button");
            btnWhiteSpaces.id = "btnWhiteSpaces";
            btnWhiteSpaces.style.width = '150px';
            btnWhiteSpaces.style.cursor = 'pointer';
            btnWhiteSpaces.innerHTML = 'Убрать все неверные переносы'
            btnContainer.insertBefore(btnWhiteSpaces, btnContainer.firstChild);
        };

        this.autoUpperCase = function () { // Автоматическое включение в базу элементов в Uppercase
            for (let i = 0; i < wrappingWords.length; i++) {
                let upperCase = wrappingWords[i].toUpperCase();
                if (wrappingWords[i].length > 1) {
                    let firstUpperCase = wrappingWords[i].replace(/^[а-яё]/, function (a) {
                        return a.toUpperCase();
                    });
                    wrappingWordsCapitalize.push(firstUpperCase);
                }
                wrappingWordsUppercase.push(upperCase);
            }
            wrappingWords = wrappingWords.concat(wrappingWordsUppercase, wrappingWordsCapitalize);
            console.log('Проверяемые варианты "висячих предлогов":');
            console.log(wrappingWords);
        };

        /* ] From "typograph.js"
        *
        * https://github.com/Spearance/jQuery.Typograf.js
        *
        * */

        this.typograph = function (el) {
            var caretPosition = 0, // не знаю, нужно ли - Р. К.
                ctx = this,
                text = el
                // Minus
                    .replace(/\u0020-(\d)/g, "\u0020−$1")

                    // Dash
                    .replace(/(^|\n|\s|>)\-(\s)/g, "$1—$2")

                    // Double hyphen
                    .replace(/\-{2} /g, function () {
                        caretPosition--;
                        return "— ";
                    })

                    // Multiple nbsp
                    .replace(/\u00a0{2,}|\u00a0\u0020|\u0020\u00a0/g, function (str) {
                        caretPosition -= str.length - 1;
                        return "\u00a0";
                    })

                    // HTML-comment
                    .replace(/<!—/ig, function () {
                        caretPosition++;
                        return "<!--";
                    })

                    // Numerical interval
                    .replace(/(\d)(\u0020)?[-—](\u0020)?(\d)/g, function (str, $1, $2, $3, $4) {
                        if ($2 == "\u0020") {
                            caretPosition--
                        }
                        if ($3 == "\u0020") {
                            caretPosition--
                        }
                        return $1 + "–" + $4;
                    })

                    // Copyright
                    .replace(/\([cс]\)/ig, function () {
                        caretPosition -= 2;
                        return "©";
                    })

                    // Registered trademark
                    .replace(/\(r\)/ig, function () {
                        caretPosition -= 2;
                        return "®";
                    })

                    // Trademark
                    .replace(/\(tm\)/ig, function () {
                        caretPosition -= 2;
                        return "™";
                    })

                    // Rouble
                    .replace(/\([рp]\)/ig, function () {
                        caretPosition -= 2;
                        return "₽";
                    })

                    // Three dots
                    .replace(/\.{3}/g, function () {
                        caretPosition -= 2;
                        return "…";
                    })

                    // Sizes
                    .replace(/(\d)[xх](\d)/ig, "$1×$2")

                    // Open quote
                    .replace(/\"([a-z0-9\u0410-\u042f\u0401…])/ig,
                        ctx.options.leftQuote + "$1")

                    // Close quote
                    .replace(/([a-z0-9\u0410-\u042f\u0401…?!])\"/ig,
                        "$1" + ctx.options.rightQuote)

                    // Open quote
                    .replace(new RegExp("\"(" + options.leftQuote + "[a-z0-9\u0410-\u042f\u0401…])", "ig"),
                        ctx.options.leftQuote + "$1")

                    // Close quote
                    .replace(new RegExp("([a-z0-9\u0410-\u042f\u0401…?!]" + ctx.options.rightQuote + ")\"", "ig"),
                        "$1" + ctx.options.rightQuote)

                    // Fix HTML open quotes
                    .replace(new RegExp("([-a-z0-9]+=)" +
                        "[" + ctx.options.leftQuote + ctx.options.rightQuote + "]" +
                        "([^" + ctx.options.leftQuote + ctx.options.rightQuote + "]*?)", "ig"),
                        "$1\"$2")

                    // Fix HTML close quotes
                    .replace(new RegExp("([-a-z0-9]+=)[\"]" +
                        "([^>" + ctx.options.leftQuote + ctx.options.rightQuote + "]*?)" +
                        "[" + ctx.options.leftQuote + ctx.options.rightQuote + "]", "ig"),
                        "$1\"$2\"")

                    // Degree
                    .replace(new RegExp("([0-6]?[0-9])[\'\′]([0-6]?[0-9])?(\\d+)" +
                        "[" + options.rightQuote + "\"]", "g"),
                        "$1\′$2$3\″")

                    // Prepositions
                    .replace(new RegExp("((?:^|\n|\t|[\u00a0\u0020]|>)[A-Z\u0410-\u042f\u0401]{1,2}\.?)\u0020", "ig"),
                        "$1\u00a0")

                    .replace(/\-(то|ка)\u00a0/gi, "-$1\u0020")

                    .replace(new RegExp("(?:\s|\t|[\u00a0\u0020])(же?|л[иь]|бы?|ка)([.,!?:;])?\u00a0", "ig"),
                        "\u00a0$1$2\u0020");

            return text;
        };

        /* ] From "typograph.js"*/

        this.findElement = function (arrOfSels) { // У каждого отдельного элемента ищет текстовые узлы
            var ctx = this;
            for (let i = 0; i < arrOfSels.length; i++) {
                let el = arrOfSels[i];
                if (el.length) {
                    ctx.findElement(el);
                } else {
                    ctx.findText(el);
                }
            }
        };

        this.createFragment = function (sel, n) {
            let thisSel = sel.childNodes[n];
            var ctx = this;
            let inneredText = optimizeText(ctx.setNoWrap(thisSel));
            let tempDiv = document.createElement('div');
            let fr = document.createDocumentFragment();
            tempDiv.innerHTML = inneredText;
            for (let i = 0; i < tempDiv.childNodes.length; i++) {
                fr.appendChild(tempDiv.childNodes[i].cloneNode(true));
            }
            return fr;
        };

        function addWhiteSpaces(sel) {
            for (let i = 0; i < sel.childNodes.length; i++) {
                if (sel.childNodes[i].nodeType === 3) {
                    sel.childNodes[i].data = ' ' + sel.childNodes[i].data + ' ';
                } else {
                    let arr = sel.childNodes[i];
                    for (let k = 0; k < arr.childNodes.length; k++) {
                        if (arr.childNodes[k].nodeType === 3) {
                            arr.childNodes[k].data = arr.childNodes[k].data + ' ';
                        }
                    }
                }
            }
        }

        this.findText = function (elem) { // 1) ищем текстовые узлы в определенном элементе
            var inputs = (elem.nodeName === 'INPUT' || elem.nodeName === 'TEXTAREA') && elem.type !== 'submit';
            if (elem.nodeName != 'BUTTON' && elem.childNodes && !inputs) {
                for (let n = 0; n < elem.childNodes.length; n++) { //Проходим циклом всех детей каждого DOM-элемента
                    if (elem.childNodes[n].nodeType == 3) { // Если ребенок является текстовым узлом, работаем с ним
                        let fr = this.createFragment(elem, n);
                        elem.replaceChild(fr, elem.childNodes[n]);
                    }
                }
                addWhiteSpaces(elem);
            }
            if (inputs) {
                elem.value = this.setNoWrap(elem.value);
                if (elem.innerText) elem.innerText = this.setNoWrap(elem.value);
                if (this.target) {
                    this.totalText += elem.value + ' ';
                }
            }
        };

        function optimizeText(wrongText) { // оптимизация текста, удаление линих пробелов, переносов и т. д.
            let string = wrongText.textContent ? wrongText.textContent : wrongText;
            let newLine = string.match(/\n/ig);
            if (newLine) { // Удаление всех переносов на новую строку
                string = string.replace(/\n/g, " ");
            }
            let tab = string.match(/\t/ig);
            if (tab) { // Удаление всех табуляций
                string = string.replace(/\t/g, " ");
            }
            let doubleWhs = string.match(/  /ig);
            if (doubleWhs) {
                string = string.replace(/\s{2,}/g, " ");
            }
            string = string.trim(); // Обрезка пробелов в начале и конце
            return (string);
        }

        this.setNoWrap = function (text) { // Непосредственно расстанавливает неразрывные пробелы
            var string = optimizeText(text);
            for (let j = 0; j < wrappingWords.length; j++) {
                string = string.replace(wrappingWords[j] + "\u0020", wrappingWords[j]+"\u00a0")
            }

            let installedText = string;
            var ret = this.typograph(installedText);
            return ret;
        };

        this.optimize = function (el) {
            var ctx = this;
            if (el) {
                this.findElement(el);
            } else {
                this.findElement(ctx.selectors);
                alert("Подготовка текста для публикации произведена!");
            }
            if (ctx.totalText) {
                if (ctx.target.length) {
                    ctx.target.html(ctx.totalText.trim());
                } else {
                    ctx.target.innerHTML = ctx.totalText.trim();
                }
            }
        };

        this.btnHandle = function () {
            var ctx = this;
            if (ctx.btn === "auto") {
                ctx.createButtons();
                console.log("auto");
                document.getElementById("btnWhiteSpaces").addEventListener('submit', function (e) {
                    console.log("auto");
                    e.preventDefault();
                    ctx.optimize();
                }, false);
            } else if (typeof ctx.btn === "object") {
                if (ctx.btn.on) {
                    ctx.btn.on('click', function (e) {
                        console.log('on');
                        e.preventDefault();
                        ctx.optimize();
                    });
                } else {
                    ctx.btn.addEventListener('click', function (e) {
                        console.log('addEventListener');
                        e.preventDefault();
                        ctx.optimize();
                    }, false);
                }
            }
        };

        this.init = function () {
            var ctx = this;
            ctx.autoUpperCase();
            console.log(ctx.btn);
            console.log(typeof ctx.btn);
            ctx.btnHandle();
        }
    }

    var textarea = $('textarea');
    var input = $('input');
    var btn = $('#typogr') ? $('#typogr') : false;

    let options = {
        selectors: [textarea, input],
        button: btn
    };

    var optimize = new Optimizator(options);
    optimize.init();
});