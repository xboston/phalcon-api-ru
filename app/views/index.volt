<!DOCTYPE html>
<html lang="ru">
{% include 'partials/head.volt' %}
<body>

<div class="wrapper">

    <div class="size-wrap">

        <div class="header">
            <a class="header-logo" href="/"><span class="logo-text">Phalcon</span></a>

            <div class="header-right">
                <iframe src="http://ghbtns.com/github-btn.html?user=phalcon&amp;repo=cphalcon&amp;type=watch&amp;count=true&amp;size=large"
                        allowtransparency="true" frameborder="0" scrolling="0" width="130px" height="30px"></iframe>
            </div>

            {% include 'partials/topmenu.volt' %}

        </div>

    </div>

    {{ content() }}

    {% include 'partials/footer.volt' %}

</div>

</body>
</html>
