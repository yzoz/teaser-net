<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel='stylesheet' type='text/css' media='all' href='/style/all.css' />
	<link rel='stylesheet' type='text/css' media='all' href='/style/form.css' />
	<script src="/js/jquery.js" type="text/javascript"></script>  
	<script src="/js/jquery.NobleCount.js" type="text/javascript"></script>
	<title>YZoZ.com Network: Добавление записи</title>
	<script type="text/javascript">
		function addTag(tagName){
			tags = document.getElementById('post_tags').value;
			if (!tags){
				tags = tagName;
			}
			else{
				tags = tags + ', ' + tagName;
			}
			document.getElementById('post_tags').value=tags;
		}
	</script>
	<script type="text/javascript">  
		$(document).ready(function(){
			$('#post_title').NobleCount('#post_title_count',{
				on_negative: 'go-red',
				on_positive: 'go-green',
				max_chars: 100
			});
			$('#post_text').NobleCount('#post_text_count',{
				on_negative: 'go-red',
				on_positive: 'go-green',
				max_chars: 330
			});
		});	
	</script>
	<style type="text/css">
		.go-green{color:#177711}
		.go-red{color:#B7070D}
	</style>
</head>
<body>
	<div id="header">
		<h1><a href="<?=site_url()?>">YZoZ.com Network</a></h1>
		<h2>Добавление записи:</h2>
	</div>
	<div id="content">
	<div id="form-container">
<?php
// echo validation_errors(); 
?>

<?

echo form_open('manage42/add');
//form_hidden('blog_id', $blog_id); - потом
$query = $this->db->get('blogs');
foreach ($query->result_array() as $row)
{
	$blog_id = $row['blog_id'];
	$blog_name = $row['blog_name'];
        echo '<span class="check-blog">'.form_radio('post_blog', $blog_id). $blog_name.'</span>';
}
echo '<div class="text-input">';
echo '<p>Title (<span id="post_title_count"></span>): '.form_error('post_title').'</p>'.form_input('post_title', set_value('post_title'), 'id="post_title"');
echo '<p>Url: '.form_error('post_url').'</p>'.form_input('post_url', set_value('post_url'));
echo '<p>Text (<span id="post_text_count"></span>): '.form_error('post_text').'</p>'.form_textarea('post_text', set_value('post_text'), 'id="post_text"');
echo '<p>Image URL: '.form_error('post_image').'</p>'.form_input('post_image', set_value('post_image'));
echo '<p>Tags: '.form_error('post_tags').'</p>'.form_input('post_tags', set_value('post_tags'), 'id="post_tags"');
echo form_submit('post_submit', 'Submit Post!');
echo form_close();
echo '</div>';
?>
	</div>
	<div id="tags">
		{tags}
		<span><a href="/view/tag/{tag_name}/" onClick="addTag('{tag_name}'); return false">{tag_name}</a>&nbsp;({tag_count})</span>
		{/tags}
	</div>
	</div>
	<div class="clear">&nbsp;</div>
	<div id="footer">Page rendered in {elapsed_time} seconds</div>
</body>
</html>