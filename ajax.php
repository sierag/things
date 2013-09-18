<?php

require "connect.php";
require "todo.class.php";

if(isset($_GET['id'])){ 
	$id = (int)$_GET['id']; 
} else {
	$id = 0;
}
if(isset($_GET['cat_id'])) { 
	$cat_id = (int)$_GET['cat_id'];
} else {
	$cat_id = 0;
}

try{
	switch($_GET['action']) {
		case 'todoitems':
			$result = ToDo::todoitems($_GET['status'],$cat_id);
			break;
		case 'get_amount':
			$result = ToDo::get_amount($cat_id);
			break;
		case 'delete':
			$result = ToDo::delete($id);
			break;
		case 'done':
			$result = ToDo::done($id);
			break;
		case 'rearrange':
			$result = ToDo::rearrange($_GET['positions'],$cat_id);
			break;
		case 'edit':
			$result = ToDo::edit($id,$_GET['text']);
			break;
		case 'new':
			$result = ToDo::createNew($_GET['text'],$cat_id);
			break;
		case 'getcat':
			$result = ToDo::get_category($id);
			break;			
		case 'getprimaircat':
			$result = ToDo::get_primair_category();
			break;
		case 'getcats':
			$result = ToDo::get_categories();
			break;
		case 'newcat':
			$result = ToDo::createNewCat($_GET['text']);
			break;
		case 'editcat':
			$result = ToDo::editCat($id, $_GET['text']);
			break;		
	}
}
catch(Exception $e){
	echo $e->getMessage();
	die("0");
}
echo $result;
?>