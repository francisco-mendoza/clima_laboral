<?php
include_once './include/dbconection.php';
include_once './include/class.db.php';
/**
 * Controles del Gráfico
 */
class Html_Utils {
		
	/** arma un Select **/
	function html_select($name, $options, $selected_value) {
		
		$data = "<select name='".$name."'>";													
		foreach ($options as $list)
		{		
			$data .= "<option ";
			if ($selected_value == $list['value'])
				$data .= " selected ";
		   $data .= " value='".$list['value']."'>".$list['text']."</option>";		
		}
		$data .= "</select>";
		return $data;	
	}

	/** arma un checkbox **/
	
	function html_checkbox($name, $value, $title, $checked_values) {		
		 	$data = "<input type='checkbox' id='chk' name='".$name."' ". 
					 " value='".$value . "'";									
			if (in_array($value, $checked_values))
				$data .= " checked ";
			$data .= " />" .$title;
		return $data;					
	}	
}