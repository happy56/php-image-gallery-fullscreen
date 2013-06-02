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
$supported_img_files =array('jpg', 'png', 'jpeg', 'bmp', 'gif');
//type of image





$sub_dir = array();
$list_images = array();
$fileextention = array();


///@Functions 


function pre($e){
	echo '<pre>';
	print_r($e);
	echo '</pre>';
}


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
}


function html_body(){
	global $list_images;
?>
<html>
<body>
	<style>
	*{margin: 0px;padding: 0px;}
	body{background-color: #cccccc;}
	#boo{margin:0px auto;}
	.control{position: absolute;z-index:9;top:0;height:100%;width:100%;}
	.btn{font-size: 100px; height:100%;background-color: rgba(256,256,256,0);color:rgba(220,220,200,.6); border:none;/**/ }
	#last{width:20%;text-align:left;}
	#next{width:75%;text-align:right; }
	</style>
	<img id="boo" />
	<div class="control">
		<button class="btn" id="last"><</button>
		<button class="btn" id="next">></button>
	</div>
<script>
(function(w, d){
	"use strict";
	var el = function(id) {
		return d.getElementById(id);
	},
	image_array = <?php echo json_encode($list_images);?>,
	current_image = -1,
	img = el('boo'),
	win_width,
	win_height,
	init = function () {
		console.log('init called!');
		win_width  = d.documentElement.offsetWidth;
		win_height = d.documentElement.offsetHeight;
		
		load_cb();
	},
	load_image = function (url){
		// check callback
		img.src = url;
		console.log('load: ',img.clientWidth, img.clientHeight);
	},
	load_cb =function (e){
		if(img.clientWidth >= img.clientHeight) {
			// Landscape 
			img.style.width = win_width;
			img.style.height = '';
			img.style.marginTop = (win_height - img.clientHeight) /2;
			img.style.marginLeft='';	
		}else{
			//protrait 
			img.style.width = '';
			img.style.marginTop='';
			img.style.height = win_height;
			img.style.marginLeft=(win_width - img.clientWidth) /2;;
		}

	},
	next = function(e){

		current_image += 1 ;
		if(current_image === image_array.length || current_image < 0){
			current_image = 0;
		}
		load_image(image_array[current_image]);
		
	},
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
	init();
	next();
}(window, window.document));
</script>
</body>
</html>
<?php 
}


//pre(readdir($dir_handdle));
$dir_handdle = opendir($folder);
// no image found ... error handdle
get_image_files($dir_handdle);

if(sizeof($list_images) === 0){
	die('No image found in '. realpath($folder));
}

html_body();



//echo json_encode($list_images);
//echo 'sub-dir';
//pre($sub_dir);
//echo 'files';
//pre($list_images);

