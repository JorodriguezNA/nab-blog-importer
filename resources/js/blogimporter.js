/*
*
*	Blog Importer JS
*	Author: Jordan Rodriguez
*
*
*/
$(document).ready(function(){

	$('.import-author').click(function(){
		var importerID = $(this).parents('tr').data('importerid');
		var newauthor = createAuthor($(this).parents('tr').find('td.author-cell').data('authoremail'), importerID);

	});
	$('.import-this').click(function(){
		if($(this).hasClass('disabled'))
		{

		}else
		{
			var ajaxCall = importPost(this, function(){console.log('success')});
		}
		
	});
	$('.import-all').click(function(){
		window.int = 0;
		$(this).attr('disabled', "disabled").find('.fa').removeClass('fa-cloud-download').addClass('fa-spinner').addClass('fa-spin');
		var loopArray = function(arrayLength){
			importLoop('Import Attempted', function(){
				var elem = $('.import-this')[int];
				//console.log(elem);
				if(!$(elem).hasClass('disabled'))
				{
					var ajaxCall = importPost(elem, function(){
						int++;
						if(int < arrayLength)
						{
							loopArray(arrayLength);
						}else
						{
							$(this).find('.fa').removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-check');
						}
					});
				}else
				{
					int++;
					if(int < arrayLength)
					{
						loopArray(arrayLength);
					}
				}
				
				
			});
			
		};

		loopArray($('.import-this').length);
		
	});
	$('.truncate-all').click(truncatePosts);

	
});

function importLoop(msg, callback)
{
	console.log(msg);

	callback();
}

function createAuthor(authoremail, importerID)
	{
		window.authorEmail = authoremail;
		var returnThis = false;
		$.ajax({
			url: '/admin/blogimporter/imports/importauthor/'+importerID+'/'+authoremail,
			dataType: 'JSON',
			async: true,
			success: function(data)
			{
				returnThis = true;
				$('td.author-cell').each(function(){
					if($(this).data('authoremail') == authorEmail)
					{
						$(this).removeClass('text-danger');
						$(this).parents('tr').find('.import-author').unbind().removeClass('import-author').addClass('import-this').html('<i class="fa fa-cloud-download" aria-hidden="true"></i> Import').click(importPost);
						
					}
				});
			}
		});
		returnThis = true;
		return returnThis;
	}

function importPost(elem, callback){
	$(elem).attr('disabled', "disabled").find('.fa').removeClass('fa-cloud-download').addClass('fa-spinner').addClass('fa-spin');
	var importerID = $(elem).parents('tr').data('importerid');
	var postID = $(elem).parents('tr').data('postid');
	var isImported = $(elem).parents('tr').data('isimported');
	var url = '/admin/blogimporter/imports/importpost/'+importerID+'/'+postID;
	var thisElem = elem;
	if($(elem).parents('tr').find('td.author-cell').hasClass('text-danger'))
	{
		var author = false;
		var newauthor = createAuthor($(elem).parents('tr').find('td.author-cell').data('authoremail'), importerID);

	}else
	{
		var author = true;
	}
	if(author == true)
	{
		$.ajax({
			url: url,
			dataType: 'JSON',
			success: function(data)
			{
				isSuccess = true;
				if(data.success == true)
				{
					$(thisElem).parents('tr').find('td.imported-cell').html('<i class="fa fa-check-circle-o fa-2 text-success" aria-hidden="true" title="Imported"></i>');
					$(thisElem).addClass('disabled').html('<i class="fa fa-check" aria-hidden="true"></i> Imported');
					callback();
				}
			}
		});
	}else{
		console.log(author);
	}
	return true;
}

function truncatePosts()
{
	var url = '/admin/blogimporter/imports/truncateposts/';
	$.ajax({
		url: url,
		dataType: 'JSON',
		success: function(data)
		{
			if(data.success == true)
			{
				location.reload();
			}
		}
	});
	
}



