<?php
//Makes sure that the bookmarklet is displayed as php, even if the mime type of the current page differs (eg image)
header("Content-type: text/html");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

.loptwtclass{
width: 220px;
color: white;
padding: 5px;
/*background-color: #F3F3F3;*/
/*border: 1px solid black;*/
margin-bottom: 1em;
filter:progid:DXImageTransform.Microsoft.alpha(opacity=80); /*Specify fade effect in IE. Remove if desired.*/
-moz-opacity: 0.8; /*Specify fade effect in Firefox. Remove if desired.*/
}

.loptwtclass a{
text-decoration: none;
}

.rsstitle{ /*shared class for all title elements in an RSS feed*/
font-weight: bold;
color: white;
}

.rssdate{ /*shared class for all date elements in an RSS feed*/
color: gray;
font-size: 85%;
}

.rssdescription{ /*shared class for all description elements in an RSS feed*/
color: white;
}

</style>
<style>
body 
{
background: url('../images/bkmbg.png');
font-family:"Verdana",Verdana,sans-Serif;
color: white;
}
</style>
	<style type="text/css">
		body { font-family:arial,sans-serif; font-size:9pt; }
		
		.my_clip_button { width:40px; text-align:center; border:1px solid black; background-color:#333; font-size:9pt; }
		.my_clip_button.hover { background-color:#aaa; }
		.my_clip_button.active { background-color:#bbb; }
		.statsbtn { width:40px; text-align:center; border:1px solid black; background-color:#333; font-size:9pt; }
		.statsbtn.hover { background-color:#aaa; }
		.statsbtn.active { background-color:#bbb; }
	</style>
	<script type="text/javascript" src="ZeroClipboard.js"></script>
	<script language="JavaScript">
		var clip = null;
		
		function $(id) { return document.getElementById(id); }
		
		function init() {
			clip = new ZeroClipboard.Client();
			clip.setHandCursor( true );
			
			clip.addEventListener('load', my_load);
			clip.addEventListener('mouseOver', my_mouse_over);
			clip.addEventListener('complete', my_complete);
			
			clip.glue( 'd_clip_button' );
			clip.glue( 'statsbtn' );
		}
		
		function my_load(client) {
			debugstr("Flash movie loaded and ready.");
		}
		
		function my_mouse_over(client) {
			// we can cheat a little here -- update the text on mouse over
			clip.setText( $('fe_text').value );
		}
		
		function my_complete(client, text) {
			debugstr("Copied text to clipboard: " + text );
		}
		
		function debugstr(msg) {
			var p = document.createElement('p');
			p.innerHTML = msg;
			$('d_debug').appendChild(p);
		}
	</script>
</head>
<body link="#DBAF00" onLoad="init()">
<?php 
require_once("../config.php");
require_once("../functions.php");
print_errors();
echo "<h3>".SITE_TITLE."</h3>";
?>
<p>URL Shortened to:
<script type="text/javascript">
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>
<input type="text" onChange="clip.setText(this.value)" onClick="SelectAll('shorturl');" id="fe_text" size="20" value="
<?php
require_once("../config.php");
require_once("../functions.php");

db_connect();

if (count($_GET) > 0) {
    $url   = mysql_real_escape_string(trim($_GET['url']));
    $alias = mysql_real_escape_string(trim($_GET['alias']));

    if (!preg_match("/^(".URL_PROTOCOLS.")\:\/\//i", $url)) {
        $url = "http://".$url;
    }

    $last = $url[strlen($url) - 1];

    if ($last == "/") {
        $url = substr($url, 0, -1);
    }

    $data = @parse_url($url);

    if (strlen($url) == 0) {
        $_ERROR[] = "Please enter an URL to shorten.";
    }
    else if (empty($data['scheme']) || empty($data['host'])) {
        $_ERROR[] = "Please enter a valid URL to shorten.";
    }
    else {
        $hostname = get_hostname();
        $domain   = get_domain();

        if (preg_match("/($hostname|$domain)/i", $data['host'])) {
            $_ERROR[] = "The URL you have entered is not allowed.";
        }
    }

    if (strlen($alias) > 0) {
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $alias)) {
            $_ERROR[] = "Custom alias can only contain letters, numbers, underscores and dashes.";
        }
        else if (code_exists($alias) || alias_exists($alias)) {
            $_ERROR[] = "The custom alias you entered is already exists.";
        }
    }

    if (count($_ERROR) == 0) {
        $create = true;

        if (($url_data = url_exists($url))) {
            $create    = false;
            $id        = $url_data[0];
            $code      = $url_data[1];
            $old_alias = $url_data[2];

            if (strlen($alias) > 0) {
                if ($old_alias != $alias) {
                    $create = true;
                }
            }
        }

        if ($create) {
            do {
                $code = generate_code(get_last_number());

                if (!increase_last_number()) {
                    die("System error!");
                }

                if (code_exists($code) || alias_exists($code)) {
                    continue;
                }

                break;
            } while (1);

            $id = insert_url($url, $code, $alias);
        }

        if (strlen($alias) > 0) {
            $code = $alias;
        }

        $short_url = SITE_URL."/".$code;

        $_GET['url']   = "";
        $_GET['alias'] = "";
	echo "$short_url";
	echo '" >';
        //exit();
    }
}
?><br/>
<a id="d_clip_button" href="#" >Copy</a>&nbsp;&nbsp;<a href="<?php echo $short_url; ?>+" target="_new" >Stats</a>&nbsp;&nbsp;(<?php echo strlen($short_url); ?> chars)
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<label for="alias">Add custom alias:</label><br />
<input class="lopbox" id="url" type="hidden" name="url" value="<?php echo $url; ?>" />
<input id="alias" class="lopbox" size="20" type="text" name="alias" value="<?php echo htmlentities(@$_GET['alias']) ?>" />
<input class="button" type="submit" value="Go" style="width:35px;" />
</form><br/>
<script type="text/javascript">
  window.___gcfg = {lang: 'en-GB'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=283084098371511";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php 
$twimg = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAkRJREFUeNqklb9u01AUxj9fXyepK2gbUiEgDFVFNxjYQEIyFQsSQxYkBhZegMdAQn0BQLwBDJGYqTJ0KGyRKgakFhQqQWkgtKHOv/uHc+w41LVKSnqVY9m5vj+f75zv2s7ympkBUKUIcLpRo6hIY1GdzyNYIqwUk5GUAT7uIdjtoSqNscEiwUKaCNVkwAIlwoydbwikthbTHtDsTq71QAOlAqAtqbQE5JS1Gb9wygXKeQfbPYuOzsq2DCTJoF9E51H0HPwc2CyMZD2+LFEuOKi3DZ5vp4nMMAQVhrAMVTaOxSlgZUnibklgRmL0f1AUuEQwftS1MwIPL7ijOTVkMEswWdlYMsdaK9Z+b97Fkyse7p93MUfg5WLaAjdmBW7PidE6ZphIsk1L5hGSGt+Nz++cc3GTFvuukykDz3EpGl2LVt9EHMFN4U5r8zdefBnEFR6Gz8kduk6i5DFURFKZwSyhhxeH67FFKX74baJ6/Su+9y2eNQZ490sj4YiomCadYZu6vLLVH2uj118HeM+wIYNDJOneKrpRJE9iz30OjzfnJ5pb/aFG92sbd1km/nnbVHhU9vDquj82s422xtPNXqqRzIi6bCMPxq1/2ehjvaXw4GIOV8+6GRDX9s2Owmozu+mZYYkh9RHb1Pc1RQfTZJMFX6QkHmh7bNYJQ8a2ye7lfbqjvmdO/ILQNtnLdAx7QI6SObrhTzrytJYZ8U4xqG22ECzQ+yyXJ6c7/w/sksOIwY2pSfJ3ZbeDKsWpPwGOg8ofAQYAEP6Yv2fsJR4AAAAASUVORK5CYII=";
?>
<div class="fb-like" data-href="<?php echo $short_url; ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-colorscheme="dark"></div>
<a href="http://twitter.com/home?status=<?php echo $short_url; ?>" target="_blank"><img src="<?php echo $twimg; ?>" alt="share on twitter" /></a>
<div class="g-plusone" data-size="small" data-annotation="none" data-href="<?php echo $short_url; ?>"></div>

</div>
</body>
</html>
