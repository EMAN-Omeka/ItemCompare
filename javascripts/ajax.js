window.jQuery = window.$ = jQuery;

jQuery(document).on('focus', '#listitemleft, #listitemright', function(e) {
  webroot = $('#phpWebRoot').val();
  currentElement = $(this).attr('id');
	$(this).autocomplete({
		source: function(request, response) {
			$.getJSON(webroot + "/itemcompareajax/", {
				q: request.term,
    		username : "OmEkA",
    		password : "nm493ie698vg"				
			}, function(data) {
				// data is an array of objects and must be transformed for autocomplete to use
				var array = data.error ? [] : $.map(data, function(item) {
					return {
						label: item.text,
						id: item.id
					};
				});
				response(array);
			});
		},
	  minLength: 3,
		focus: function(event, ui) {
			// prevent autocomplete from updating the textbox
			event.preventDefault();
		},
		select: function(event, ui) {
			// prevent autocomplete from updating the textbox
			event.preventDefault();
			// navigate to the selected item's url
			$(this).val(ui.item.label);
        $.ajax({
           url : webroot + '/itemfill/' + ui.item.id, 
           type : 'GET', 
           datatype : 'html',
           success : function(html, status) {
             toFill = '#itemleft';
             other = '#itemright';
             if (currentElement == 'listitemright') {
               toFill = '#itemright';
               other = '#itemleft';
             }
             $(toFill).html(html);
             // Ajustement des hauteurs des conteneurs files
             $(toFill).find('.element').each(function(index){
               id = '#' + $(this).attr('id');
               toFillHeight = $(this).height();
               otherHeight = $(other).find(id).height();
               if (toFillHeight > otherHeight) {
                 $(other).find(id).height(toFillHeight);
               } else {
                 $(this).height(otherHeight);
               }             
             });
           }
        });
		}	  
	});  
});



