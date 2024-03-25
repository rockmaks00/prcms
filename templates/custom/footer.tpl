	{if $oCurrentNode->getUrl()!="root"}
	</section>
	{/if}
	<nav><h2>Меню</h2></nav>
	<footer>
		<h2>Футер</h2>
		{hook hookgroup="footer-menu"}
		Создание сайта - <a href="http://vprioritete.ru/" target="_blank">ИК Приоритет</a>
	</footer>
</div>

{*<style>
	.human{
		width: 300px;
		height: 400px;
		position: relative;
		background: #ddd;
	}
	.human div{
		background: #555;
		border: 3px solid white;
		border-radius: 10px;
	}
		.human div.head{
			position: absolute;
			top: 23px;
			left: 100px;
			border-radius: 30px;
			width: 40px;
			height: 40px;
		}
		.human div.body{
			position: absolute;
			top: 70px;
			left: 90px;
			width: 60px;
			height: 60px;
		}
		.human div.leg{
			position: absolute;
			top:80px;
			left: 101px;
			height: 95px;
			width: 17px;
			border-radius: 0 0 30px 30px;
			z-index: 1;

		}
			.human div.leg:after{
				content: "";
				display: block;
				position: absolute;
				background: #555;
				height: 45px;
				left: -1px;
				top: -3px;
				width: 25px;
				border: 3px solid #555;
				-webkit-transform: matrix(1, 0, 0.1, 1, 0, -1);
				transform: matrix(1, 0, 0.1, 1, 0, -1);
				z-index: 10px;
			}
		.human div.leg.right{
			left: 122px;
		}
			.human div.leg.right:after{
				left: auto;
				right: -1px;
				-webkit-transform: matrix(1, 0, -0.1, 1, 0, -1);
				transform: matrix(1, 0, -0.1, 1, 0, -1);
			}
</style>
<div class="human">
	<div class="head"></div>
	<div class="body"></div>
	<div class="leg"></div>
	<div class="leg right"></div>
</div>*}


{literal}
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter21873286 = new Ya.Metrika({id:21873286,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/21873286" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
{/literal}
</body>
</html>