document.body.appendChild( document.createElement('script') ).src='https://images.kittenangie.com/assets/bookmarklet/jquery.js';

var cssNode = document.createElement('link');
cssNode.type = 'text/css';
cssNode.rel = 'stylesheet';
cssNode.href = 'https://images.kittenangie.com/assets/bookmarklet/main.css';
cssNode.media = 'screen';
document.body.appendChild(cssNode);

var url = location.href;
setTimeout("imageDownloader( url )", 1000);

function file_box_create(url){

	var params = "?url="+url

	var file_box = 	'<div id="file_box">'+
						'<div id="fw_header">'+
							'Add an Image'+
						'</div>'+
						'<a id="closeThis" href="#" onclick="closeThis(\'file_box\');"></a>'+
						'<div id="fw_content">'+
							'<iframe src=\'https://images.kittenangie.com/assets/bookmarklet/downloader.php'+params+'\' style="border:none;" />'+
						'</div>'+
					'</div>'+
					'<div id="mask"></div>';
	return file_box;
}

function imageDownloader( url ){

	var user = jQuery('script#imageDownloader:first').attr('data-user');

	var shrt = "";
	if( url.indexOf("https")=='-1' ){
		shrt = url.substring(7);
	} else {
		shrt = url.substring(8);
	}

	shrt = shrt.split("?")[0].split("#")[0];

	var extension = shrt.split('.').pop();
	extension = extension.replace('#','');

	if(
		extension != "jpg" &&
		extension != "jpeg" &&
		extension != "png" &&
		extension != "gif" &&
		extension != "webm" &&
		extension != "mp4" &&
		extension != "bmp" ){

		alert('Wrong file type.');

	} else {

		var file_box = file_box_create(url);
		$("body").append(file_box);

	}
}

function closeThis(item){
	$('#'+item).remove();
	$('#mask').remove();
}
