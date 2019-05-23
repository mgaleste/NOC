<?php
class setup{
	var $setupconn;
	var $pageclass;
	
	function setup(){
	
		$this->setupconn = new connection();
	}
	//setup flow
		//get access
	
	function setup_front_menu(){
		
		//frontmenu array will have menus and their corresponding children
		$frontmenu_arr = array();
		
		$pageparents = $this->get_pageparents();
		$ctr=0;
		
		
		foreach($pageparents as $ppid=>$pp){
			$frontmenu_arr[$ctr]['caption']=$pp['title'];
			$frontmenu_arr[$ctr]['submenu']=$this->get_submenu($ppid);
			$frontmenu_arr[$ctr]['link']="index.php?mod=pages&perma=".createPermaLink($pp['title']);
			//$frontmenu_arr[$ctr]['order']=$pp['menuorder'];
			$ctr++;
		}
		//print_r($frontmenu_arr);
		/*
		$ctr=1;
		$fmenuarr=array();
			for($ctr=1;$ctr<=count($frontmenu_arr);$ctr++){
				foreach($frontmenu_arr as $fma => $fmi){
					if($ctr==$fmi['order']){
						$fmenuarr[$ctr] = $fmi;
					}
				}
			}
		*/
		
		//return $fmenuarr;
		return $frontmenu_arr;
	}
	
	function get_submenu($parentid){
	    $submenu 	 = new menu();
		$submenu_arr = array();
		$submenu_arr = $submenu->get_pagetree('pages',$parentid);
		//print_r($submenu_arr);
		return $submenu_arr;
	}
	
	function get_predefined_sections(){
		$predefineds_array = array();
		$setupquery = "SELECT * FROM reference WHERE ref_name = 'predefpage' AND remarks1 = 'enabled'";
		$this->setupconn->qselectDb($setupquery);
		$ctr=0;
		while($this->setupconn->fetchRs()){
			$predefineds_array[$ctr]['title'] = $this->setupconn->rs['name'];
			$predefineds_array[$ctr]['menuorder'] = $this->setupconn->rs['remarks2'];
			$ctr++;
		}
		$setupquery = "SELECT * FROM modules WHERE modulestat = 'front' OR modulestat = 'frontback'";
		$this->setupconn->qselectDb($setupquery);
		
		while($this->setupconn->fetchRs()){
			$predefineds_array[$ctr]['title'] = $this->setupconn->rs['modulename'];
			$predefineds_array[$ctr]['menuorder'] = $this->setupconn->rs['menuorder'];
			$ctr++;
		}
		return $predefineds_array;
	}
	
	function get_pageparents(){
	
		$pageparent_array = array();
		$setupquery = "SELECT * FROM pages WHERE parentid = '0' AND status = 'published' AND pageremarks <> 'Secondary Menu / Root Page' ORDER BY page_order ASC";
		$this->setupconn->qselectDb($setupquery);
		$ctr=0;
		while($this->setupconn->fetchRs()){
			$pageparent_array[$this->setupconn->rs['id']]['title'] = $this->setupconn->rs['title'];
			//$pageparent_array[$this->setupconn->rs['id']]['menuorder'] = $this->setupconn->rs['page_order'];
		}
		return $pageparent_array;
	}
	
	function set_up_meta(){
		$meta_array = array();
		$setupquery = "SELECT * FROM settings WHERE id='1'";
		$this->setupconn->qselectDb($setupquery);
		if($this->setupconn->fetchRs()){
			$meta_array['keywords']=$this->setupconn->rs['keywords'];
			$meta_array['description']=$this->setupconn->rs['description'];
			$meta_array['site_title']=$this->setupconn->rs['site_title'];
		}
		return $meta_array;
	}
	
	function edit_home(){
	
	}
	
	function set_up_home(){
		$home_msg = "";
		$setupquery = "SELECT * FROM reference WHERE ref_name='homepage' AND name='welcome'";
		$this->setupconn->qselectDb($setupquery);
		if($this->setupconn->fetchRs()){
			
		}
	}
	
	



}




?>