
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-142375785-1"></script>

<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-142375785-1');
</script>

<!-- hinted app para ayudas contextuales -->
<script src="https://hinted.me/script.js" organizationId="03a5f99b-59f8-4c55-9bf9-8a62a611e9d5" host="https://hinted.me/api"></script>


@if( env('CLARITY'))
<script type="text/javascript">

    (function(c,l,a,r,i,t,y){

        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};

        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;

        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);

    })(window, document, "clarity", "script", "j93pxwcuvf");

</script>
@endif