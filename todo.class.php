<?php

class ToDo{
	
	private $data;
	
	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
	
	public function __toString(){
		return '<li id="todo-'.$this->data['id'].'" class="todo">
				<input type="checkbox" class="checkbox"><div class="text">'.$this->data['text'].'</div>
				<div class="actions">
					<a href="#" class="edit">Edit</a>
					<a href="#" class="delete">Delete</a>
				</div>
			</li>';
	}
	
	public static function _query($query){
		if(mysql_query($query)) { 
			mysql_query("INSERT INTO logs SET descr='".$query."'");
			return mysql_affected_rows($GLOBALS['link']);
		} else { 
			mysql_query("INSERT INTO logs SET descr='ERROR: ".$query."'");
			return false;
		}
	}

	public static function _select($query, $obj = true) {
		if($res = mysql_query($query)) {
			if($obj) {
				return mysql_fetch_object($res);	
			} else {
				return mysql_fetch_array($res,MYSQL_BOTH);
			}
		} else {
			mysql_query("INSERT INTO logs SET descr='ERROR: ".$query."'");
			return false;
		}
	}
	
		
	public static function esc($str){
		if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
		return mysql_real_escape_string(strip_tags($str));
	}
	
	public static function todoitems($status = 'active',$cat_id = '') {
	
		if(empty($cat_id)) { 
			// todo: getDefaultCat.		
		} 
	
		// Select all the todos, ordered by position:
		$query = mysql_query("SELECT * FROM `todo` WHERE status = '".$status."' AND category_id = ".$cat_id." ORDER BY `position` ASC");
		$todos = array();
		// Filling the $todos array with new ToDo objects:
		while($row = mysql_fetch_assoc($query)){
			$todos[] = new ToDo($row);
		}
		// Looping and outputting the $todos array. The __toString() method
		// is used internally to convert the objects to strings:
		$str = '';
		foreach($todos as $item) {
			$str .= $item;
		}
		return $str;
	}
	
	public static function edit($id, $text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");
		
		if(!self::_query("UPDATE todo SET text='".$text."' WHERE id=".$id))
			throw new Exception("Couldn't update item!");
	}
	
	public static function editCat($id, $text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");

		if(self::_query("UPDATE todocat SET text='".$text."' WHERE id=".$id)!=1)
			throw new Exception("Couldn't update cat!");
	}
	
	public static function delete($id){
		if(self::_query("UPDATE todo SET status='pendingdelete' WHERE id=".$id)!=1) { 
			throw new Exception("Couldn't delete item!");
		} else {
			return true;
		}
	}

	public static function get_category($id = '') { 
		if($id) { 
			// get specific
			return json_encode(self::_select("SELECT * FROM todocat WHERE status='active' AND id = ".$id.""));
		} else {
			// get primairy
			return json_encode(self::_select("SELECT * FROM todocat WHERE status='active' ORDER BY position ASC LIMIT 1"));			
		}
	}

	public static function get_categories(){
		$query = mysql_query("SELECT * FROM todocat WHERE status='active' ORDER BY position ASC");
		$cats = array();
		// Filling the $todos array with new ToDo objects:
		$str = '';
		while($cat = mysql_fetch_assoc($query)) {
			$str .= "<li><a href=\"#cat".$cat["id"]."\">".$cat["text"]."</a></li>";
		}
		return $str;		
	}
	
	public static function get_amount($cat_id){
		$status = array();
		$pending = 	self::_select("SELECT count(*) as amount FROM todo WHERE status='pendingdelete' AND category_id=".$cat_id."");
		$status["pendingdelete"] = $pending->amount;
		$active = 	self::_select("SELECT count(*) as amount FROM todo WHERE status='active' AND category_id=".$cat_id." ");
		$status["active"] = $active->amount;
		$done = 	self::_select("SELECT count(*) as amount FROM todo WHERE status='done' AND category_id=".$cat_id." ");
		$status["done"] = $done->amount;
		return json_encode($status);
	}
	
	
	public static function done($id){
		var_dump(self::_query("UPDATE todo SET status='done' WHERE id=".$id));
		if(self::_query("UPDATE todo SET status='done' WHERE id=".$id)!=1) {
			throw new Exception("Couldn't update item!"); 
		} else {
			return "1";
		}
	}	
	
	public static function rearrange($key_value,$cat_id){
		$updateVals = array();
		foreach($key_value as $k=>$v) {
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		if(!$strVals) throw new Exception("No data!");
	
		// We are using the CASE SQL operator to update the ToDo positions en masse:

		if(!self::_query("UPDATE todo SET position = CASE id ".join($strVals)." ELSE position END WHERE category_id=".$cat_id.""))
			throw new Exception("Error updating positions!");
	}
	
	public static function createNew($text,$cat_id){
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong input data!");
		
		$posResult = self::_select("SELECT MAX(position)+1 as position FROM todo WHERE category_id=".$cat_id."");
		$position = $posResult->position;
		if(!$position) $position = 1;

		if(mysql_query("INSERT INTO todo SET text='".$text."', category_id=".$cat_id.", position = ".$position)!=1)
			throw new Exception("Error inserting TODO!");
		
		// Creating a new ToDo and outputting it directly:
		
		return new ToDo(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text
		));
	}
	
	public static function createNewCat($text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong input data!");
		
		$posResult = mysql_query("SELECT MAX(position)+1 FROM todocat");
		
		if(mysql_num_rows($posResult))
			list($position) = mysql_fetch_array($posResult);

		if(!$position) $position = 1;

		if(self::_query("INSERT INTO todocat SET text='".$text."', position = ".$position)!=1)
			throw new Exception("Error inserting TODO!");
		
		// Creating a new ToDo and outputting it directly:
		
		echo (new ToDo(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text
		)));
		
		exit;
	}

	
} // closing the class definition
?>
