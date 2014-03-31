<?php
/// FullScreen Image Viewer. 
/// Just a single file keep it in the image folder. and it will work
/// 
/// 


// No change needed

// Folder location
$folder = '.';
// webroot location
$webroot = '';

// supported img files 
//type of image
$supported_img_files =array('jpg','JPG', 'png', 'jpeg', 'bmp', 'gif');



$sub_dir = array();
$list_images = array();
$fileextention = array();


///@Functions 

//Debug function
function pre($e){
	echo '<pre>';
	print_r($e);
	echo '</pre>';
}

// 
function get_image_files($dir_handdle){
	global $folder, $sub_dir, $list_images, $supported_img_files, $webroot;
	$sub_dir = array();
	$count = 0; 
	while(($entry = readdir($dir_handdle))){

		if(is_dir($folder.$entry) === TRUE) {
			array_push($sub_dir, $entry);
		}else{
			$ext = pathinfo($entry, PATHINFO_EXTENSION);
			// load only supported images 
			if(in_array($ext, $supported_img_files)) {
				array_push($list_images, $webroot.$entry);
			}
			$count += 1;
		}		
	}
	sort($list_images);
	
}


function html_body(){
	global $list_images;
?>
<html>
<body>
	<style>
	*{margin: 0px;padding: 0px;}
	#fs{background: -moz-radial-gradient(center, ellipse cover, rgba(239,247,255,1) 7%, rgba(166,176,188,1) 35%, rgba(8,23,43,0.7) 95%, rgba(8,23,43,0.67) 100%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(7%,rgba(239,247,255,1)), color-stop(35%,rgba(166,176,188,1)), color-stop(95%,rgba(8,23,43,0.7)), color-stop(100%,rgba(8,23,43,0.67))); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover, rgba(239,247,255,1) 7%,rgba(166,176,188,1) 35%,rgba(8,23,43,0.7) 95%,rgba(8,23,43,0.67) 100%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover, rgba(239,247,255,1) 7%,rgba(166,176,188,1) 35%,rgba(8,23,43,0.7) 95%,rgba(8,23,43,0.67) 100%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover, rgba(239,247,255,1) 7%,rgba(166,176,188,1) 35%,rgba(8,23,43,0.7) 95%,rgba(8,23,43,0.67) 100%); /* IE10+ */
background: radial-gradient(ellipse at center, rgba(239,247,255,1) 7%,rgba(166,176,188,1) 35%,rgba(8,23,43,0.7) 95%,rgba(8,23,43,0.67) 100%); /* W3C */}
	#boo{margin:0px auto;}
	.hd{display:none;}
	.control{position: absolute;z-index:9;top:0;height:100%;width:100%;}
	.btn{font-size: 100px; height:100%;background-color: rgba(256,256,256,0);color:rgba(220,220,200,.6); border:none;/**/ }
	.btn:hover,.btn:focus{border: none;outline: none;}
	#last{width:20%;text-align:left;}
	#next{width:75%;text-align:right; }
	</style>
<div id="fs">
	<img id="img" />
	<div class="control">
		<button class="btn" id="last"><</button>
		<button class="btn" id="next">></button>
	</div>
</div>
</style>
<script>
(function(w, d){
	"use strict";
	var el = function(id) {
		return d.getElementById(id);
	},
	image_array = <?php echo json_encode($list_images);?>,
	current_image = -1,
	img = el('img'),
	win_width,
	win_height,
	init = function () {
	
		win_width  = d.documentElement.offsetWidth;
		win_height = d.documentElement.offsetHeight;
		
		load_cb();
	},
	load_image = function (url){
		img.src = url;
	},
	// calculate the width and height of the Image
	// without changing the ratio
	load_cb =function (e){
		var iwidth = img.clientWidth,
			iheight =img.clientHeight,
			ir = iwidth / iheight, 
			set_ih = win_height,
			set_iw = win_width,
			cal_ih, // win_width / ir,
			cal_iw; // win_height * ir;

			
		if(iwidth > iheight) {
			// Landscape 
			cal_ih =  win_width / ir;
			if(cal_ih > win_height ){
				// find width
				set_iw = win_height * ir; 
				set_ih = set_iw / ir;
				// set screen hight and find width
			}
			
			img.style.height = '';
			img.style.width = set_iw;
		
		}else{
			//protrait 
			cal_iw = win_height * ir;
			if(cal_iw > win_width){
				set_ih = win_width / ir;
				set_iw = set_ih * ir; 
			}
			img.style.width = '';
			img.style.height = set_ih;
		
		}
		// margin
		img.style.marginTop  = (win_height - img.clientHeight) /2;
		img.style.marginLeft = (win_width - img.clientWidth) /2;

	},
	// Get next Image
	next = function(e){

		current_image += 1 ;
		if(current_image === image_array.length || current_image < 0){
			current_image = 0;
		}
		load_image(image_array[current_image]);
		
	},
	// Get Last Image
	last = function(e){
		current_image -= 1;
		if(current_image < 0){
			current_image = image_array.length-1;
		}
		load_image(image_array[current_image]);
	},
	// handdle image.. 
	last_btn = el('last'),
	next_btn = el('next');

	next_btn.addEventListener('click',next, false);
	last_btn.addEventListener('click',last, false);
	img.addEventListener('load', load_cb, false);
	//w.addEventListener('onresize',init, false);
	//alert(img.src);
	w.onresize = init;
	d.addEventListener('keyup', function(e){
		if (e.keyCode === 39){ // right
			next();
		}else if (e.keyCode === 37){ // left
			last();
		}else if(e.keyCode === 38){ // up
			el('fs').setAttribute("class","hd");
		}else if(e.keyCode === 40){ 	// down
			el('fs').removeAttribute("class");
		}
		
	
	}, false);
	init();
	next();
}(window, window.document));
</script>
</body>
</html>
<?php 
}



$dir_handdle = opendir($folder);
// no image found ... error handdle
get_image_files($dir_handdle);

if(sizeof($list_images) === 0){
	die('No image found in '. realpath($folder));
}

html_body();




