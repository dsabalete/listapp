$(function(){

	$('.ajax-task-complete').on({
		click: function(e) {
			e.preventDefault();
			var $href = $(this).attr('href');
			
			// console.log("$href: " + $href); // /1/edit-complete

			$('<div></div>').load($href+' form', function(){

				// set form
				var $form = $(this).children('form');			

				// set checkbox
				var $cb = $form.find('input[type="checkbox"]');

				// toggle 
				$cb.prop('checked', !$cb.prop('checked'));

				// form action url
				var $url = $form.attr('action');

				//console.log("url: " + $url); // /1/update-complete

				// set data
				var $data = $form.serialize();

				console.log("data: " + $data); // _method=PUT&dev_taskbundle_taskcomplete%5Bcomplete%5D=1&dev_taskbundle_taskcomplete%

				$.ajax({
					url: $url,
					data: $data,
					method: 'post',
					dataType: 'json',
					cache: false,
					success: function(obj) {
						console.log('success');

						var $tic = $('#task-complete-' + obj.id + ' .ajax-task-complete');

						if (obj.complete) {
							$tic.text('X');
						} else {
							$tic.text('O')
						}
					},
					complete: function(obj) {
						console.log('complete!');
					},
					error: function(err) {
						console.log(err);
					}
				});
			});
		}
	});
	

	// http://www.appelsiini.net/projects/jeditable
 	$('.listcreate').editable("inline-create", {
 		submitdata: { 
 			_method: "post",
 			value: $(this).html()
 		},
 		indicator: '<img src="../../img/loading.gif">',
 		tooltip: 'Haz click aquí para editar',
 		callback: function() {
 			location.href = '';
 		}
 	});

 	$('.itemcreate').editable("item-create", {
 		submitdata: { 
 			_method: "post",
 			value: $(this).html()
 		},
 		indicator: '<img src="../../img/loading.gif">',
 		tooltip: 'Haz click aquí para editar',
 		callback: function() {
 			location.href = '';
 		}
 	}); 
	
 	$('.taskedit').editable("inline-edit", {
 		indicator: '<img src="../../img/loading.gif">',
 		tooltip: "Haga clic para editar...",
 		submitdata: { 
 			_method: "post", 
 			id: $(this).attr('id'), 
 			value: $(this).html()
 		}
 	}); 





 	
 	$('.listedit').editable("inline-edit", {
 		submitdata: { 
 			_method: "post", 
 			id: $(this).attr('id'), 
 			value: $(this).html()
 		},
 		indicator: '<img src="../../img/loading.gif">',
 		tooltip: 'Haz click aquí para editar',
 	}); 
 	
	
 	
});