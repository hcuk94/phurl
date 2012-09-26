<?php
if( !defined('PHURL' ) ) {
    header('HTTP/1.0 404 Not Found');
    exit();
}
?>
<div id="footer">
<p>&copy; <?php echo date('Y'); ?> <?php echo get_phurl_option('site_title'); ?> 


<?php
// From the developers:
// You may remove the below powered by link if you wish, we don't mind. However, we'd really really appreciate it if you donated to Phurl.
// Any donation of any size is much appreciated, and you'll be able to see how it helps out in our public accounts on our website.
// To donate, simply head over to http://phurlproject.org/ and hit the donate button. Thank you!
?>
- Proudly powered by 
<a href="http://www.phurlproject.org/">Phurl</a>.</p>
</div>
</div>
</body>
</html>
