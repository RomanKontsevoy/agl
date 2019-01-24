<!--Footer-->
<footer>
    <div class="wrapper">
        <div class="copyright-wrap">
            <p class="footer-parish">Православная община в честь преподобного Агапита Печерского <span class="nw">и святителя</span> Луки Крымского</p>
            <p class="copyright">Использование любых материалов сайта — только по благословению настоятеля</p>
        </div>
        <div class="contacts-wrap">
            <div class="address-wrap">
                <p class="address">
                    Парк им. Пушкина<br>
                    Проспект Победы, 40Б<br>
                    Киев 03057<br>
                    Украина
                </p>
                <a href="#ymap" data-fancybox="map" class="howfind">Как найти</a>
            </div>
            <div class="connection-wrap">
                <p class="phone">Тел.: <a href="tel:+380 44 332-34-63">+380 44 332-34-63</a> <span class="nw">(церковная лавка)</span>
                </p>
                <p class="email">Эл. почта: <a href="mailto:aglupub@gmail.com">aglupub@gmail.com</a></p>
            </div>
        </div>
    </div>
</footer>

<div id="ymap" style="display: none; width: 96%;">
<!--    <iframe src="https://www.google.com/maps/@50.4543757,30.456493,18.25z" width="100%" height="400" frameborder="0"></iframe>-->
    <iframe id="ifr-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2540.291315975218!2d30.456893142837018!3d50.454299726726966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4cc292fd927f9%3A0xc88fe3615b1a1bba!2z0KbQtdGA0LrQvtCy0Ywg0JDQs9Cw0L_QuNGC0LAg0J_QtdGH0LXRgNGB0LrQvtCz0L4g0Lgg0JvRg9C60Lgg0JrRgNGL0LzRgdC60L7Qs9C-!5e0!3m2!1sru!2sua!4v1546001871742" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>

<script>
    var ifr = document.querySelector("#ifr-map");
    ifr.height = window.innerHeight - 100;
    window.addEventListener('resize', function (e) {
        ifr.height = window.innerHeight - 100;
    })
</script>

<?php wp_footer(); ?>

</body>
</html>