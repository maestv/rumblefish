<?php 
class List_Helper {
	
	var $list = array();
	var $html = "";
	
	function __construct($list, $html) {
		$this->list = $list;
		$this->html = $html;
	}
	
	public function getOptions($default = 0)
	{
		if ( !empty($this->list) ) {
			$this->html = $this->generateOption($this->list, 0, $default);
		}
		
		return $this->html;
	}
	
	public function loopKids()
	{
		
	}
	
	public function generateOption($row, $c, $default) 
	{	
		$i = $c;
		while ($i != 0) {
			$space .= "&nbsp;&nbsp;";
			$i--;
		}
		
		foreach ( $row as $r ) {
			$extra = "";
			if ( $r->id == $default ) {
				$extra = ' selected="selected"';
			}
			
		 	$out .= '<option value="'.$r->id.'"'.$extra.'>'.$space.$r->title.'</option>'."\n";
			
			if ( isset($r->children) ) {
				$out .= $this->generateOption($r->children , ($c+1), $default);
			}
		}
		
		return $out;
	}
}