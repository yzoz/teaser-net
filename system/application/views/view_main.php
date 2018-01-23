<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel='stylesheet' type='text/css' media='all' href='/style/all.css' />
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<title>madred.NETwork: Сеть блогов</title>
</head>
<body>
	<div id="header">
		<h1><a href="<?=site_url();?>">&nbsp;</a></h1>
		<div id="tags">
			{tags}
			<span><a href="/tag/{tag_name}">{tag_name}</a>&nbsp;({tag_count})</span>
			{/tags}
		</div>
		<h2>{page_title}:</h2>
	</div>
	<div id="content">
	{posts}
	
		<a class="post-container all-posts" href="/go/{post_blog}/{post_id}" target="_blank" style="background-image:url(/images/{post_blog}/{post_id}.jpg)">
			<span>{post_title}</span>
		</a>
	
	{/posts}
	</div>
	<div class="clear">&nbsp;</div>
	<div id="navigator">{paginator}</div>
	<div id="footer">Page rendered in {elapsed_time} seconds</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2260634-11']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>