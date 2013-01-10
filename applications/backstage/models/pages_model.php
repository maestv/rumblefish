<?php
class Pages_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
		$this->load->model("pages_meta_model");
		
	}
	
	public function get($idOrAlias)
	{
		if ( is_numeric($idOrAlias) ) 
		{
			$where = array("id"=>$idOrAlias);
		} else {
			$where = array("alias"=>$idOrAlias);
		}
		
		$page = $this->db->get_where('pages', $where)->row();
		$page->meta = $this->pages_meta_model->getPage($page->id);
		$page->url = $this->_buildPageUrl($page);
		
		return $page;
	}
	
	public function get_where($array = array())
	{
		return $this->db->get_where("pages", $array)->result();
	}
	
	public function getChildren($parent_id)
	{ // Only the admin should be listing pages in this manner. 
		return $this->db->get_where('pages', array("parent_id"=>$parent_id, "deleted"=>0))->result();
	}
	
	public function getchildrenAsNav($parent_id)
	{ // Only the admin should be listing pages in this manner. 
		$children = $this->db->select("id, parent_id, title, alias, forward")->from('pages')->where(array("parent_id"=>$parent_id, "deleted"=>"0", 'display'=>1))->order_by('sort_order ASC')->get()->result();
		foreach ( $children as &$child ) {
			$child->url = $this->_buildPageUrl($child);
		}
		return $children;
	}
	
	public function get_uri_segment($parent_id, $alias)
	{
		$page = $this->db->select("id")->from("pages")->where(array("parent_id"=>$parent_id, "alias"=>$alias))->get()->row();
		if ( !empty($page) ) {
			return $this->get($page->id);
		}
		return false;
	}
	
	public function save($parent_id, $form)
	{
		// If we did not set a parent id, use the one from the URL
		// else use the one we set
		if ( !isset($form['parent_id']) || !is_numeric($form['parent_id']) ) {
			$form['parent_id'] = $parent_id;
		} else {
			$parent_id = $form['parent_id'];
		}
		
		if ( !isset($form['display']) ) {
			$form['display'] = 0;
		}
		
		$valid = $this->_validatePage($parent_id, $form);
		if ( $valid['error'] == true ) {
			return $valid;
		}
		
		// Check for and seperate out meta
		$meta = false;
		if ( isset($form['meta']) && !empty($form['meta']) ) {
			$meta = $form['meta'];
				unset($form['meta']);
		}
		
		unset($form['_wysihtml5_mode']);
		
		$form['modified_by'] = $this->data->user->id;
		$form['date_created'] = date("c", time());
		
		if ( $this->db->insert('pages', $form) )
		{
			$id = mysql_insert_id();
			
			if ( !empty($meta) ) {
				$this->pages_meta_model->update($id, $meta);
			}
			
			return $this->get($id);
		}
		
		return false;
	}
	
	public function update($id = false, $form = array())
	{
		if ( $id == false || empty($form) ) { return false; }
		
		// Check for and seperate out meta
		$meta = false;
		if ( isset($form['meta']) && !empty($form['meta']) ) {
			$meta = $form['meta'];
				unset($form['meta']);
		}
		unset($form['_wysihtml5_mode']);
		
		$form['modified_by'] = $this->data->user->id;
		$form['last_modified'] = date("c", time());
		
		if ( !isset($form['display']) ) {
			$form['display'] = 0;
		}
		
		$this->db->where('id', $id);
		if ( $this->db->update('pages', $form) )
		{
			if ( !empty($meta) ) {
				$this->pages_meta_model->update($id, $meta);
			}
			return $this->get($id);
		}
		return false;
	}
	
	public function build_navigation()
	{
		$pages = $this->db->order_by("sort_order ASC")->get_where("pages", array("parent_id"=>0, "status"=>'live', "display"=>1))->result();
		foreach ($pages as &$page) {
			$page = $this->get($page->id);
		}

		return $pages;
	}
	
	public function _buildPageUrl($page = false) 
	{
		if ( !$page ) { return false; }
		if ( trim($page->forward) != "" ) { return $page->forward; }
		
		$url = '/'.$page->alias;
		$c = true;
		while ( $c == true ) {
			
			$page = $this->db->select("parent_id, alias")->from("pages")->where(array("id"=>$page->parent_id))->get();
			if ( $page->num_rows == 1 ) {
				$page = $page->row();
				$url = '/'.$page->alias.$url;
			}
			
			// If we have no more parents, break
			if ( $page->parent_id == 0 ) {
				$c = false;
			}
		}
		
		return base_url().substr($url, 1, strlen($url));
	}
	
	private function _validatePage($parent_id, $page)
	{
		$error['error'] = false;
		
		// Check that the parent exsits and is not deleted.
		if ( $parent_id != 0 ) {
			$parent_exists = $this->db->get_where('pages', array("id"=>$parent_id, "deleted"=>0));
			if ( $parent_exists->num_rows == 0 ) {
				$error['error'] = true;
				$error['errors'][]['message'] = "Parent Page does not exist.";
				return $error;
			}
		} 
		
		// Do more Validation
		return true;
	}
	
	public function generate_site_map()
	{	// This should probably be cached.
		$pages = $this->db->select("id, parent_id, title, alias, forward")->from('pages')->where(array("status !="=>"archived", "deleted"=>"0", "parent_id"=>0))->order_by('sort_order ASC')->get()->result();
		$this->get_recursive_children($pages);
		return $pages;
	}
	
	private function get_recursive_children($pages)
	{
		foreach ( $pages as &$page ) {
			$page->url = $this->_buildPageUrl($page);
			
			$children = $this->db->select("id, parent_id, title, alias, forward")
								 ->from('pages')
								 ->where(array("status !="=>"archived", "deleted"=>"0", "parent_id"=>$page->id))
								 ->order_by('sort_order ASC')
								 ->get();
			
			if ( $children->num_rows > 0 ) {
				$page->children = $this->get_recursive_children($children->result());
			} 
		}
		
		return $pages;
	}
}