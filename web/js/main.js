$(function(){

 	// Typeahead code
	var substringMatcher = function(strs) {
		return function findMatches(q, cb) {
			var matches, substrRegex;
		 
			// an array that will be populated with substring matches
			matches = [];
		 
			// regex used to determine if a string contains the substring `q`
			substrRegex = new RegExp(q, 'i');
		 
			// iterate through the pool of strings and for any string that
			// contains the substring `q`, add it to the `matches` array
			$.each(strs, function(i, str) {
				if (substrRegex.test(str)) {
					// the typeahead jQuery plugin expects suggestions to a
					// JavaScript object, refer to typeahead docs for more info
					matches.push({ value: str });
				}
			});
		 
			cb(matches);
		};
	};
	 
	var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
		'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
		'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
		'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
		'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
		'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
		'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
		'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
		'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
	];
	 
	$('input.typeahead').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	},
	{
		name: 'states',
		displayKey: 'value',
		source: substringMatcher(states)
	}); 




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

	
	$('.link-del').click(function(e){
		e.preventDefault();
		var resp = confirm("¿Estas seguro que quieres eliminar?");
		if (resp) {
			location.href = $(this).attr('href');
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
	
 	$('.taskedit').editable("item-update", {
 		indicator: '<img src="../../img/loading.gif">',
 		tooltip: "Haga clic para editar...",
 		submitdata: { 
 			_method: "post", 
 			id: $(this).attr('id'), 
 			value: $(this).html()
 		},
 		callback: function() {
 			location.href = '';
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
