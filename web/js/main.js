var resposta;
$(function(){
	$('.ajax-task-complete').on({
		click: function(e) {
			e.preventDefault();
			var $href = $(this).attr('href');
			
			//console.log("$href: " + $href); // /1/edit-complete

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
						resposta = obj;
						console.log('obj: ' + obj.toString());
						
						var $tic = $('#task-complete-' + obj.id + ' .ajax-task-complete');

						// obj.id undefined

						if (obj.complete) {
							$tic.text('X');
						} else {
							$tic.text('O')
						}						
					},
					error: function(err) {
						console.log(err);
					}
				});
			});
		}
	});
	
});