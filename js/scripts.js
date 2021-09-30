$(document).ready( function(){

	$('ul').each( function(){
		$(this).children(":first").addClass('first');
		$(this).children(":last").addClass('last');
	});

	$('.actions .addTag,#actions .addTag').click( function(e){
		e.preventDefault();
		$(this).modal( $(this).attr('data-imgID') );
	});

	$(document).on('click', 'button#addTag', function(e){
		e.preventDefault();

		var id = $('body .modal').attr('data-imgID');
		var tag = $('body .modal select[name="tags"]').val();

		$.get( "/assets/php/addTag.php", { imgID: id, tagID: tag } )
		.done(function( data ) {
			$(this).updateImageTags(id);
			$('body .modal').remove();
		});

	});

	$(document).on('click', 'button#createTag', function(e){
		e.preventDefault();

		var id = $('body .modal').attr('data-imgID');
		var tag = $('body .modal input[name="newTag"]').val();

		$.get( "/assets/php/createTag.php", { imgID: id, tag: tag } )
		.done(function( data ) {
			$(this).updateTagList();
			$(this).updateImageTags(id);
			$('body .modal').remove();
		});

	});

	$(document).on('click', 'a#closeModal', function(e){
		e.preventDefault();
		$('body .modal').remove();
	});

	$(document).on('click', '#tagList .item', function(e){
		if (e.shiftKey) {
			e.preventDefault();
			var url = window.location.search;
			var urlParts = url.split('=');
			var add = $(this).attr('href');
			var addParts = add.split('=');
			parts = urlParts[1]+","+addParts[1];
			window.location.href = "?tag="+parts;
	    }
	});

	$(document).on('contextmenu', '#image #tagList a[data-tag]', function(e){
		e.preventDefault();
		var tagID	= $(this).attr('data-tag');
		var tagName	= $(this).html();
		var imgID	= $('.remove').attr('data-imgid');
		if (confirm('Would you like to remove the "'+tagName+'" tag?')){
			$.get( "/assets/php/removeTag.php", { imgID: imgID, tagID: tagID } ).done(function( data ) {
				$(this).updateImageTags(imgID);
			});
		}
	});

	$(document).on('click', '#image #remove .remove', function(e){
		e.preventDefault();
		var imgID	= $(this).attr('data-imgid');
		if (confirm('Are you sure you want to remove this item?')){
			$.get( "/assets/php/removeItem.php", { imgID: imgID } ).done(function( data ) {
				location.reload();
			});
		}
	});

	$(document).on('click', '#navigation .upload', function(e){
		$('#uploadBox').toggle();
	});

	$.fn.modal = function(imgID) {

		$('body .modal').remove();

		var modal = "<div class='modal' data-imgID='"+imgID+"'>";
			modal += "<a id='closeModal'>✖</a>";
			modal += "<h3>Add Tag</h3>";
			modal += "<select name='tags'>";
			$.each(tags, function(index, element){
				var name = element.substr(0, element.indexOf('||'));
				var tagID = element.substr(element.indexOf('||')+2);
				modal += "<option value='"+tagID+"'>"+name+"</option>";
			});
			modal += "</select>";
			modal += "<button id='addTag'>Add Tag</button>";
			modal += "<br/>";
			modal += "<h3>Create Tag</h3>";
			modal += "<input type='text' name='newTag' placeholder='Create new tag...'>";
			modal += "<button id='createTag'>Create and Add Tag</button>";
			modal += "</div>";

		$('body').append(modal);

		$('body .modal select[name="tags"]').focus();

		return this;
	};

	$.fn.updateTagList = function() {
		if( $('#images').length ){
			$.get( "/assets/php/tagList.php" )
			.done(function( data ) {
				$('#tagList').html(data);
			});
		}
		return this;
	};

	$.fn.updateImageTags = function(imgID) {
		if( $('#image').length ){
			$.get( "/assets/php/tagList.php?imgID="+imgID )
			.done(function( data ) {
				$('#tagList').html(data);
			});
		}
		return this;
	};

});
