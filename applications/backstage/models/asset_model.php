<?php
class Asset_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
		
		$this->load->model("tags_model");
    //$this->load->model("publishers_model");
		$this->load->model("songwriters_model");
		$this->load->model("instruments_model");
		$this->load->model("album_model");
		$this->load->model("artist_model");
	}
	
	public function get($asset_id = false)
	{
		if ( !$asset_id ) { return false; }
		
		$asset = $this->db->get_where('assets', array("id"=>$asset_id))->row();
		
		if ( $asset ) {
			
			$asset->tags = $this->tags_model->get_type($asset->id, 'asset');
			$asset->songwriters = $this->songwriters_model->get_asset($asset->id);
			$asset->instruments = $this->instruments_model->get_asset($asset->id);
			$asset->artist = $this->artist_model->get($asset->artist_id);
			
			return $asset;
		}
		
		return false;
	}
	
	public function get_user($user_id = false)
	{
		if ( !$user_id ) { return false; }
		$assets = $this->db->get_where("assets", array("user_id"=>$user_id))->result();
		if ( !empty($assets) ) {
			
			foreach ( $assets as &$asset )
			{
				$asset->tags = $this->tags_model->get_type($asset->id, 'asset');
				$asset->songwriters = $this->songwriters_model->get_asset($asset->id);
				$asset->instruments = $this->instruments_model->get_asset($asset->id);
				$asset->album = $this->album_model->get($asset->album_id);
				$asset->artist = $this->artist_model->get($asset->artist_id);
			}
			return $assets;
		}
		return false;
	}
	
	public function search($search = false, $current_url = false)
	{
		if ( !$search ) return false;
		$page = $search['page'];
			unset($search['page']);
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/search", "GET", $this->clean_search($search), $this->data->token);
		$api->execute();
		$search = json_decode($api->getResponseBody());
		
		// On Error dont bother generating pages.
		if ( isset($search->error) ) return $search;
		
		if ( $search->total_media > 25 ) {
			$search->total_pages = ceil($search->total_media / 25);
			
			$pages = array();
			$pagesToShow = 14;
			
			if ( $page >= $pagesToShow ) {
				$start = $page - 3;
			} else {
				$start = 1;
			}

			for($i=$start; $i <= $start + $pagesToShow ;$i++) {
				if ( $i <= $search->total_pages ) {
					$thispage = new stdClass;	

					$thispage->url = $current_url.'/'.$i;
					$thispage->display = $i;
					$thispage->page = $i;
					$thispage->current = ($i == $page)? true : false;

					$pages[] = $thispage;
				}
			}
			
			if ( $search->total_pages > $pagesToShow ) {
				$first = new stdClass();
					$first->url = $current_url.'/1';
					$first->display = "<<";
					$first->page = 1;
				array_unshift($pages, $first);
						
				$last = new stdClass();
					$last->url = $current_url."/".$search->total_pages;
					$last->display = ">>";
					$last->page = $search->total_pages;
				array_push($pages, $last);
			}
			
			$search->pages = $pages;
		}
		
		return $search;
	}
	
	public function create($asset = false)
	{
		if ( !$asset ) { return false; }
		$data = array();
		
		// Set the user_id if not present
		$data['user_id'] = $this->data->user->id;
		if ( isset($asset['user_id']) && trim($asset['user_id']) != "" ) {
			$data['user_id'] = $asset['user_id'];
		}
		
		// Check that we dont exsist. 
		$assetTest = $this->db->get_where('assets', array("user_id"=>$data['user_id'], "album_id"=>$asset['album'], "title"=>$asset['title']));
		if ( $assetTest->num_rows > 0 ) { return false; }
		
		/*
		 * Pop off things that are "associative"
		 */
		if ( is_array($asset['tags']) && !empty($asset['tags']) ) {
			$tags = $asset['tags'];
		}
		if ( is_array($asset['songwriters']) && !empty($asset['songwriters']) ) {
			$songwriters = $asset['songwriters'];
		}
		if ( is_array($asset['instruments']) && !empty($asset['instruments']) ) {
			$instruments = $asset['instruments'];
		}
		if ( is_array($asset['likes']) && !empty($asset['likes']) ) {
			$likes = $asset['likes'];
		}
		
		$data["artist_id"] = $asset['artist_id'];
		$data["album_id"] = $asset['album'];
		$data["filename"] =  $asset['filename'];
		$data["file_path"] = $asset['file_path'];
		$data["title"] = $asset['title'];
		$data["isrc"] = $asset['isrc'];
		$data["type"] = $asset['type'];
		$data["track_order"] = $asset['track_order'];
		$data["lyrics"] = nl2br($asset['lyrics']);
		$data["vocals"] = $asset['vocals'];
		$data["explicit"] = $asset['explicit'];
		$data["bpm"] = $asset['bpm'];
		$data["youtube_id"] = $asset['youtube_id'];
		$data['instrumental'] = (isset($asset['instrumental']))? 1 : 0;
		$data["explicit"] = (isset($asset['explicit']))? 1 : 0;
		$data["created_by"] = $this->data->user->id;
		$data['created'] = date("c");
		
		if ( $this->db->insert('assets', $data) ) {
			// Asset Creation worked!
			$asset = $this->db->get_where("assets", array("id"=>$this->db->insert_id()))->row();
			
			// Associate Tags
			if ( !empty($tags) ) {
				$tagsResult = $this->tags_model->associate_to_asset($asset->id, $tags, 'asset');
				if ( $tagsResult->error == true ) {
					return $tagsResult->error_message;
				}
			}
			
			// Associate Songwriters
			if ( !empty($songwriters) ) {
				$songwritersResult = $this->songwriters_model->associate_to_asset($asset->id, $songwriters);
				if ( $songwritersResult->error == true ) {
					return $songwritersResult->error_message;
				}
			}
			
			// Associate Instruments
			if ( !empty($instruments) ) {
				$instrumentResult = $this->songwriters_model->associate_to_asset($asset->id, $instruments);
				if ( $instrumentResult->error == true ) {
					return $instrumentResult->error_message;
				}
			}
			
			// Asset Get gets all the connected info.
			return $this->get($asset->id);
		}
	}
	
	public function update($track_id = false, $asset = false)
	{
		if ( !$asset || !$track_id ) { return false; }
		$data = array();
		
		/*
		 * Pop off things that are "associative"
		 */
		if ( is_array($asset['tags']) && !empty($asset['tags']) ) {
			$tags = $asset['tags'];
		}
		if ( is_array($asset['songwriters']) && !empty($asset['songwriters']) ) {
			$songwriters = $asset['songwriters'];
		}
		if ( is_array($asset['instruments']) && !empty($asset['instruments']) ) {
			$instruments = $asset['instruments'];
		}
		if ( is_array($asset['likes']) && !empty($asset['likes']) ) {
			$likes = $asset['likes'];
		}
		
		$data["artist_id"] = $asset['artist_id'];
		$data["album_id"] = $asset['album'];
		$data["filename"] =  $asset['filename'];
		$data["file_path"] = $asset['file_path'];
		$data["title"] = $asset['title'];
		$data["isrc"] = $asset['isrc'];
		$data["type"] = $asset['type'];
		$data["track_order"] = $asset['track_order'];
		$data["lyrics"] = nl2br($asset['lyrics']);
		$data["vocals"] = $asset['vocals'];
		$data["explicit"] = $asset['explicit'];
		$data["bpm"] = $asset['bpm'];
		$data["youtube_id"] = $asset['youtube_id'];
		$data['instrumental'] = (isset($asset['instrumental']))? 1 : 0;
		$data["explicit"] = (isset($asset['explicit']))? 1 : 0;
		
		
		$this->db->where('id', $track_id);
		if ( $this->db->update('assets', $data) ) {
			// Asset Creation worked!
			$asset = $this->db->get_where("assets", array("id"=>$track_id))->row();
			
			// Associate Tags
			if ( !empty($tags) ) {
				$tagsResult = $this->tags_model->associate_to_asset($asset->id, $tags, 'asset');
				if ( $tagsResult->error == true ) {
					return $tagsResult->error_message;
				}
			}
			
			// Associate Songwriters
			if ( !empty($songwriters) ) {
				$songwritersResult = $this->songwriters_model->associate_to_asset($asset->id, $songwriters);
				if ( $songwritersResult->error == true ) {
					return $songwritersResult->error_message;
				}
			}
			
			// Associate Instruments
			if ( !empty($instruments) ) {
				$instrumentResult = $this->songwriters_model->associate_to_asset($asset->id, $instruments);
				if ( $instrumentResult->error == true ) {
					return $instrumentResult->error_message;
				}
			}
			
			// Asset Get gets all the connected info.
			return $this->get($asset->id);
		}
	}
	
	private function clean_search($search = array())
	{
		if ( !is_array($search) ) { return false; }
		$new = array();
		
		foreach ( $search as $key => $value) {
			if ( is_array($value) && count($value) > 1 ) {
				$new[$key] = $value;
			}
			elseif ( !empty($value) && count($value) == 1  ) {
				if ( trim($value) != "" ) {
					$new[$key] = $value;
				}
			}
			elseif (!is_array($value) && trim($value) != "" ) {
				$new[$key] = $value;
			}
		}
		
		return $new;
	}
}
