<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Todo's</title>
	<link href="http://fonts.googleapis.com/css?family=Terminal+Dosis" rel="stylesheet" type="text/css" />
	<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<ul class="tabs"></ul>
<div class="leftPanel">
	<ul>
		<li><a href="#todos" id="todolink">Todo's<span>0</span></a></li>
		<li><a href="#done" id="donelink">Done<span>0</span></a></li>
    </ul>
	<div class="leftPanelFooter">
		<ul>
	    	<li><a href="#done" id="trash"><img src="/img/TrashIconEmpty.png" width="24px" height="24px">Trash<span>0</span></a></li>
	    </ul>
	</div>
</div>
<div class="mainPanel">
	<div class="tab_container">
		<div id="cat" class="tab_content">
			<ul class="todoList"></ul>
			<div class="footerPanel">
				<ul>
					<li><a class="addButton" href="#">New</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="script.js"></script>
</body>
</html>
