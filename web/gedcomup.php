<?php



//Inclusion fonction gedcom2timeline

require 'ged2db2.php';

//Inclusion fonction gedcom2timeline

//
// Provient de la documentation PHP de la fonction ini_get()
//
function return_bytes($val) {
   $val = trim($val);
   $last = strtolower($val{strlen($val)-1});
   switch($last) {
       // The 'G' modifier is available since PHP 5.1.0
       case 'g':
           $val *= 1024;
       case 'm':
           $val *= 1024;
       case 'k':
           $val *= 1024;
   }

   return $val;
}

define('MAX_FILE_SIZE', return_bytes(ini_get('post_max_size')));

if(!empty($_FILES))
{
    //
    // Debug
    //
    //echo '<pre>';
    //print_r($_FILES);
    //echo '</pre>';
	
	$fich = $_FILES['file']['tmp_name'];
	
	//echo "Conversion<br/>...".$fich;
	
	                                        

	$res = gedcom2timeline($fich);
	
	
	
	//echo "Lancement timeline";
	
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <!-- See http://developer.yahoo.com/yui/grids/ for info on the grid layout -->
   <title>MY HISTORY</title>
   <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />

   <!-- See http://developer.yahoo.com/yui/ for info on the reset, font and base css -->
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/base/base-min.css"> 

   <!-- Load the Timeline library after reseting the fonts, etc -->
   <script src="http://api.simile-widgets.org/timeline/2.3.1/timeline-api.js" type="text/javascript"></script> 
 
   <link rel="stylesheet" href="local_example.css" type="text/css">

   <!-- Since we don't have our own server, we do something tricky and load our data here as if it were a library file -->
   <script src="local_data.js" type="text/javascript"></script>
   <script src=<?php echo "\"$res\"" ?> type="text/javascript"></script> 

   <script>        
        var tl;
		 var t2;
		 
        function onLoad() {
            var tl_el = document.getElementById("tl");
            var eventSource1 = new Timeline.DefaultEventSource();
			
			var t2_el = document.getElementById("t2");
            var eventSource2 = new Timeline.DefaultEventSource();
            
            var theme1 = Timeline.ClassicTheme.create();
            theme1.autoWidth = true; // Set the Timeline's "width" automatically.
                                     // Set autoWidth on the Timeline's first band's theme,
                                     // will affect all bands.
            theme1.timeline_start = new Date(Date.UTC(1800, 0, 1));
            theme1.timeline_stop  = new Date(Date.UTC(2110, 0, 1));
        			
			
			    		
            var d = Timeline.DateTime.parseGregorianDateTime("2000")
						
            var bandInfos = [
                Timeline.createBandInfo({
                    width:          45, // set to a minimum, autoWidth will then adjust
                    intervalUnit:   Timeline.DateTime.DECADE, 
                    intervalPixels: 200,
                    eventSource:    eventSource1,
                    date:           d,
                    theme:          theme1,
                    layout:         'original'  // original, overview, detailed
                })
            ];
            
		 var bandInfos2 = [
                Timeline.createBandInfo({
                    width:          45, // set to a minimum, autoWidth will then adjust
                    intervalUnit:   Timeline.DateTime.DECADE, 
                    intervalPixels: 200,
                    eventSource:    eventSource2,
                    date:           d,
                    theme:          theme1,
                    layout:         'original'  // original, overview, detailed
                })
            ];
            // create the Timeline
            tl = Timeline.create(tl_el, bandInfos, Timeline.HORIZONTAL);
			t2 = Timeline.create(t2_el, bandInfos2, Timeline.HORIZONTAL);
            
            var url = '.'; // The base url for image, icon and background image
                           // references in the data
            eventSource1.loadJSON(timeline_data1, url); // The data was stored into the 
                                                       // timeline_data variable.
            tl.layout(); // display the Timeline
			
			eventSource2.loadJSON(timeline_data2, url); // The data was stored into the 
                                                       // timeline_data variable.
            t2.layout(); // display the Timeline
					
			
			
        }
        
        var resizeTimerID = null;
        function onResize() {
            if (resizeTimerID == null) {
                resizeTimerID = window.setTimeout(function() {
                    resizeTimerID = null;
                    tl.layout();
                }, 500);
            }
        }
   </script>

</head>
<body onload="onLoad();" onresize="onResize();">
<div id="doc3" class="yui-t7">
   <div id="hd" role="banner">
     <h1>Local Timeline Example</h1>
     <p>This example reads a local data file from the disk and displays it using the Simile Timeline library. The software library is retrieved from servers via the Internet.</p>
     <p>The Timeline will grow automatically to fit the events. Scroll towards 2030 to see it grow. Click on an event to see its details.</p>
   </div>
   <div id="bd" role="main">
	   <div class="yui-g">
	     <div id='tl'></div>
	     <p>To move the Timeline: use the mouse scroll wheel, the arrow keys or grab and drag the Timeline.</p>
	   </div>
	   <br><br>
	   <div class="yui-g">
	     <div id='t2'></div>
	     <p>To move the Timeline: use the mouse scroll wheel, the arrow keys or grab and drag the Timeline.</p>
	   </div> 
	   
	   
	 </div>
   <div id="ft" role="contentinfo">
     <p>Thanks to the <a href=''>Simile Timeline project</a> Timeline version <span id='tl_ver'></span></p>
     <script>Timeline.writeVersion('tl_ver')</script> 
   </div>
</div>

</body>
</html>	
	
		
	
<?php	
}else{
?>


		<form method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data">
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
			<label>Fichier joint : <input type="file" name="file" /></label><br /><br />

			<input type="submit" value="Envoyer" />
			<input type="reset" value="Rétablir" />
		</form>

<?php
	}
?>
