
<div id="shorten">
	<img src="<?php echo get_phurl_option('theme_path'); ?>enter-url-here.png" />
<form id="surlform">	
<div id="url-box">
		<div id="input"><input type="text" id="url" autocomplete="off" /></div>
		<button id="button">Shorten</button>
		<button id="hbutton">View Stats</button>
		<div class="clear"></div>
	</div>
</form>
	
	<div id="show-options" onclick="$('#shorten-options').slideToggle('slow');">[+] Show Shortening Options</div>
	<div id="shorten-options">
		<table>
			<tr>
				<td>Consecutive (Short)</td>
				<td><input type="radio" name="alias-type" value="short" /></td>
			</tr>
			<tr>
				<td>Random (Longer)</td>
				<td><input type="radio" name="alias-type" value="long" /></td>
			</tr>
			<tr>
				<td>Custom</td>
				<td><input type="radio" name="alias-type" value="custom" onclick="showHideCustom();" /></td>
			</tr>
		</table>
	</div>
</div>
