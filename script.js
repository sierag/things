$(document).ready(function(){

	function loadCat(cat_id,status) {
		// Load default todo items
		$.get("ajax.php",{'action':'todoitems','status':status,'cat_id':cat_id},function(msg){ 
				console.log($('#cat' + cat_id + " .todoList"));
				$('#cat .todoList').html(msg);
				// Update all amounts 
				update(cat_id, status);
				$('#cat').fadeIn(); //Fade in the active ID content
		});	
	}
	
	// load page
	// Get default category
	$.get("ajax.php",{'action':'getcat'},function(msg) { 
		var defaultCategoryId = msg.id;
		// Load categories
		$.get("ajax.php",{'action':'getcats'},function(msg) {
			$(".tabs").html(msg);
			$(".tab_content").hide(); //Hide all content
			$("ul.tabs li:first").addClass("active").show(); //Activate first tab
			$(".tab_content:first").show(); //Show first tab content
			loadCat(defaultCategoryId,'active');

			//On Click Event
			$("ul.tabs li").click(function() {
				$("ul.tabs li").removeClass("active"); //Remove any "active" class
				$(this).addClass("active"); //Add "active" class to selected tab
				$(".tab_content").hide(); //Hide all tab content
				var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content			
				var cat_id = activeTab.replace("#cat","")
				$(activeTab + ".todolink").live('click',function(e){ loadpage(cat_id,"active"); });
				$(activeTab + ".donelink").live('click',function(e){ loadpage(cat_id,"done"); });
				$(activeTab + ".trash").live('click',function(e){ loadpage(cat_id,"pendingdelete"); });
				loadCat(cat_id,'active');
				return false;
			});
		
		});
	},"json");
	
	$(".todoList").sortable({
		/*axis		: 'y',				// Only vertical movements allowed
		containment	: 'window',*/			// Constrained by the window
		update		: function(){		// The function is called after the todos are rearranged
		
			// The toArray method returns an array with the ids of the todos
			var arr = $(".todoList").sortable('toArray');
			
			// Striping the todo- prefix of the ids:
			arr = $.map(arr,function(val,key){
				return val.replace('todo-','');
			});
			// Saving with AJAX
			$.get('ajax.php',{action:'rearrange','cat_id':getCurrentCatId(),positions:arr});
		},
		
		/* Opera fix: */
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});
	// A global variable, holding a jQuery object 
	// containing the current todo item:
	var currentTODO;
	
	$("#trash").droppable({
	        tolerance: 'touch',
	        over: function() {
	               $(this).removeClass('out').addClass('over');
	        },
	        out: function() {
	                $(this).removeClass('over').addClass('out');
	        },
	        drop: function(event,ui) {
					
	                $.get("ajax.php",{"action":"delete","id":event.target.parentNode.id.replace("todo-","")},function(msg){
	                	$(event.target.parentNode.id.replace("todo-","")).fadeOut('fast');
					})
					update();
	                $(this).removeClass('over').addClass('out');
	        }
	});
	
	// When a double click occurs, just simulate a click on the edit button:
	$('.todo').live('dblclick',function() {
		$(this).find('a.edit').click();
	});

	// When a double click occurs, just simulate a click on the edit button:
	$('.todo').live('dblclick',function() {
		$(this).find('a.edit').click();
	});

	$('.todo .checkbox').live('click',function(e) {
		currentTODO = $("#" + $(this).closest('.todo').attr("id"));
		console.log(currentTODO);
		$.get("ajax.php",{"action":"done","id":currentTODO.attr('id').replace("todo-","")},function(msg){
	    	currentTODO.fadeOut('fast');
	    	update();
		})
	    $(this).removeClass('over').addClass('out');
	});	

	// If any link in the todo is clicked, assign
	// the todo item to the currentTODO variable for later use.
	$('.todo a').live('click',function(e){
		currentTODO = $(this).closest('.todo');
		currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));
		e.preventDefault();
	});

	// Listening for a click on a donelink button
	$('#donelink').live('click',function(){
		cat_id = getCurrentCatId();
		loadCat(cat_id,'done');
	});
	
	// Listening for a click on a todolink button
	$('#todolink').live('click',function(){
		cat_id = getCurrentCatId();
		loadCat(cat_id,'active');
	});

	// Listening for a click on a edit button
	$('.todo a.edit').live('click',function(){
		var container = currentTODO.find('.text');
		if(!currentTODO.data('origText')) {
			// Saving the current value of the ToDo so we can
			// restore it later if the user discards the changes:
			currentTODO.data('origText',container.text());
		} else {
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<input type="text">').val(container.text()).appendTo(container.empty()).blur(function(){
			save();	
		});
	});
	
	function update(cat_id, status) {
		$.get("ajax.php",{'action':'get_amount','cat_id':cat_id},function(msg){
			if(msg['pendingdelete']>0){ 
				$("#trash img").attr("src","/img/TrashIconNotEmpty.png");
			} else { 
				$("#trash img").attr("src","/img/TrashIconEmpty.png");
			}
			$("#todolink span").html('('+msg['active']+')');
			$("#donelink span").html('('+msg['done']+')');
			$("#trash span").html('('+msg['pendingdelete']+')');
		},"json");
	}
	
	function save() {
		var text = currentTODO.find("input[type=text]").val();
		$.get("ajax.php",{'action':'edit','id':currentTODO.data('id'),'text':text});
		currentTODO.removeData('origText').find(".text").text(text);
	}
	
	function getCurrentCatId() {
		return $("li.active a").attr("href").replace("#cat",""); //Find the href attribute value to identify the active tab + content			
	}
	
	// New Todo Item
	$('.addButton').click(function(e){
		cat_id = getCurrentCatId();
		$.get("ajax.php",{'action':'new','text':'New Todo Item. Doubleclick to Edit.','cat_id':cat_id,'rand':Math.random()},function(data){
			// Appending the new todo and fading it into view:
			$(data).hide().appendTo('#cat' + cat_id + ' .todoList').fadeIn();
		},"json");
		loadCat(cat_id,'active');
		e.preventDefault();
	});

	$('#addCat').click(function(e){
		$.get("ajax.php",{'action':'newcat','text':'New Cat Item. Doubleclick to Edit.','rand':Math.random()},function(msg){
			// Appending the new todo and fading it into view:
			$(msg).hide().appendTo('.catList').fadeIn();
		});
		e.preventDefault();
	});
	
}); // Closing $(document).ready()
