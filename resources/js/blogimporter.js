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
	$('.import-this').click(importPost);

	

	
});

function createAuthor(authoremail, importerID)
	{
		window.authorEmail = authoremail;
		var returnThis = false;
		$.ajax({
			url: 'http://rcfdev.nabamarketingteam.com/admin/blogimporter/imports/importauthor/'+importerID+'/'+authoremail,
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

function importPost(){
	var importerID = $(this).parents('tr').data('importerid');
	var postID = $(this).parents('tr').data('postid');
	var isImported = $(this).parents('tr').data('isimported');
	var url = '/admin/blogimporter/imports/importpost/'+importerID+'/'+postID;
	var thisElem = this;
	if($(this).parents('tr').find('td.author-cell').hasClass('text-danger'))
	{
		var author = false;
		var newauthor = createAuthor($(this).parents('tr').find('td.author-cell').data('authoremail'), importerID);

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
				isSuccess = true
				if(data.success == true)
				{
					$(thisElem).parents('tr').find('td.imported-cell').html('<i class="fa fa-check-circle-o fa-2 text-success" aria-hidden="true" title="Imported"></i>');
				}
			}
		});
	}else{
		console.log(author);
	}
	

}

