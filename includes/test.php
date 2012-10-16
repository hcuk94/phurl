<?php

//$image = file_get_contents("http://img.martynip.co.uk/4.jpg");
//$stuff = "<img src='data:image/png;base64,".base64_encode($image)."'>";
//echo $stuff."\n\n\n";
//echo base64_encode($stuff);

echo base64_encode("/cat|meow/i")."\n";
echo base64_encode(base64_encode("/cat|meow/i"))."\n";

?>
