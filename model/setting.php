<?php
class setting{
	var $setting_con;
	
	
	
	function setting(){
		$this->setting_con = new connection();
	}
	
	function load_settings(){
		//load basic
		$basic_cfg_arr=array();
		$modsql="SELECT * FROM settings";
		$this->setting_con->qselectDb($modsql);
		$this->setting_con->fetchRs();
		$basic_cfg_arr['MAIN'] = $this->setting_con->rs['main_add'];
		$basic_cfg_arr['ADMIN'] = $this->setting_con->rs['apanel_add'];
		$basic_cfg_arr['UPLOADS'] = $this->setting_con->rs['uploads_add'];
		$basic_cfg_arr['AP_MODEL'] = 'model/';
		$basic_cfg_arr['AP_CONTROLLER'] = 'controller/';
		$basic_cfg_arr['AP_VIEW'] = 'view/';
		$basic_cfg_arr['AP_IMAGES'] = 'view/images/';
		$basic_cfg_arr['SITE_TITLE'] = $this->setting_con->rs['site_title'];
		
		}
	

	function init_modules(){




	}

	
	function get_menu($module){
		
		$tempmod_array=array(); 
		$modsql="SELECT type, moduleName, moduleCaption, moduleStat, menuOrder, moduleGroup FROM modules WHERE  moduleStat IN ('back','system') AND stat='active' AND moduleGroup!='' GROUP BY moduleGroup ORDER BY menuOrder ASC";
		$this->setting_con->qselectDb($modsql);
		$ctr = 0;
		while($this->setting_con->fetchRs())
		{
			$tempmodname = $this->setting_con->rs['moduleName'];
			$tempmod_array[$ctr]['modname'] 	= $this->setting_con->rs['moduleName'];
			$tempmod_array[$ctr]['modcap'] 		= $this->setting_con->rs['moduleCaption'];			
			$tempmod_array[$ctr]['modgroup'] 	= $this->setting_con->rs['moduleGroup'];
			$tempmod_array[$ctr]['type'] 		= $this->setting_con->rs['type'];
				
			if($tempmod_array[$ctr]['modname']=="home"){	
					$mod 	= '';
					$type 	= '';	
					$cond = $mod.$type;				
			}else{
					//$mod 	= '?mod='.str_replace(' ','_',strtolower($this->setting_con->rs['modulegroup']));
					$mod 	= '?mod='.str_replace(' ','_',strtolower($this->setting_con->rs['moduleName']));
					$type	= '&type='.$this->setting_con->rs['type'];			
					$types 	= $this->setting_con->rs['type'];
					$cond 	= (!empty($types)) ? $mod.$type : $mod ;					
					 
			}
						
			$tempmod_array[$ctr]['linkout'] 	= 'index.php'.$cond; 
			$tempmod_array[$ctr]['dashlinkout'] = 'index.php'.$cond;
 
			$ctr++;
		}
		return $tempmod_array;
	}
	
	
	function get_submenu($module,$groupid){		
		$tempmod_array=array(); 
	 	$modsql="SELECT m.remarks2 as remarks2, m.type as type, m.moduleName as modulename, m.moduleCaption as modulecaption, m.moduleStat as modulestat, m.menuOrder as menuorder, m.moduleGroup as modulegroup
		FROM modules m LEFT JOIN group_modules ga ON m.moduleCode=ga.moduleCode		
		WHERE  m.moduleGroup='$module' AND ga.groupCode='$groupid'  AND m.moduleStat IN ('back','system') 
		AND m.stat='active' AND m.moduleGroup!='' GROUP BY m.remarks ORDER BY m.menuOrder ASC";
		
		$this->setting_con->qselectDb($modsql);
		$ctr = 0;
		while($this->setting_con->fetchRs())
		{
			$tempmodname = $this->setting_con->rs['modulename'];
			$tempmod_array[$ctr]['modname'] 	= $this->setting_con->rs['modulename'];
			$tempmod_array[$ctr]['modcap'] 		= $this->setting_con->rs['modulecaption'];			
			$tempmod_array[$ctr]['modgroup'] 	= $this->setting_con->rs['modulegroup'];
			$tempmod_array[$ctr]['type'] 		= $this->setting_con->rs['type'];
			$tempmod_array[$ctr]['substat']		= $this->setting_con->rs['remarks2'];
			
			$recon = new recordUpdate();
			$core = new coreFunctions();
			
			//Retrieve
			$arrayValues 	= array('id','remarks2');
			$intro = $tempmod_array[$ctr]['type']."_introduction";			
			$retArray 		= $recon->retrieveEntry("reference",$arrayValues,"","ref_name='$intro'");
			foreach($retArray as $retIndex=>$retValue){	
					$$retIndex 				= $retValue;	
					$mainArr 				= explode('|',$$retIndex);				 				 	 
					$mid					= $mainArr[0];
					$main_content			= $mainArr[1];
			}		
			
			
			
			
		 	$ismultiple = $core->isPageMultiple($tempmod_array[$ctr]['type']);	
		if(empty($main_content)){
			$tasky = ($ismultiple=='no') ? "&task=create" : "";
		}else{
			$tasky = ($ismultiple=='no') ? "&task=edit&sid=$mid" : "";
		}	
			
			
			if($tempmod_array[$ctr]['modname']=="home"){	
					$mod 	= '';
					$type 	= '';	
					$cond = $mod.$type;			
									
			}else{					 
					$mod 	= '?mod='.str_replace(' ','_',strtolower($this->setting_con->rs['modulename']));
					$type	= '&type='.$this->setting_con->rs['type'];			
					
					$cond 	= (!empty($type)) ? $mod.$type : $mod ;					
			}
						
			$tempmod_array[$ctr]['linkout'] 	= 'index.php'.$cond.$tasky; 
			$tempmod_array[$ctr]['dashlinkout'] = 'index.php'.$cond.$tasky;
 
			$ctr++;
		}
		return $tempmod_array;
	}
	
	function get_subsubmenu($module,$mod){		
		$tempmod_array=array(); 
		$modsql="SELECT remarks2, type, moduleName, moduleCaption, moduleStat, menuOrder, moduleGroup FROM modules 
		WHERE  moduleGroup='$module' AND moduleName='$mod' AND moduleStat IN ('back','system') 
		AND stat='active' AND moduleGroup!='' AND remarks2='yes' GROUP BY type  ORDER BY menuOrder ASC";
		
		$this->setting_con->qselectDb($modsql);
		$ctr = 0;
		while($this->setting_con->fetchRs())
		{
			$tempmodname 						= $this->setting_con->rs['moduleName'];
			$tempmod_array[$ctr]['modname'] 	= $this->setting_con->rs['moduleName'];
			$tempmod_array[$ctr]['modcap'] 		= $this->setting_con->rs['moduleCaption'];			
			$tempmod_array[$ctr]['modgroup'] 	= $this->setting_con->rs['moduleGroup'];
			$tempmod_array[$ctr]['type'] 		= $this->setting_con->rs['type'];
			$tempmod_array[$ctr]['substat']		= $this->setting_con->rs['remarks2'];
				
			if($tempmod_array[$ctr]['modname']=="home"){	
					$mod 	= '';
					$type 	= '';	
					$cond = $mod.$type;			
									
			}else{					 
					$mod 	= '?mod='.str_replace(' ','_',strtolower($this->setting_con->rs['moduleName']));
					$type	= '&type='.$this->setting_con->rs['type'];			
					
					$cond 	= (!empty($type)) ? $mod.$type : $mod ;					
			}
						
			$tempmod_array[$ctr]['linkout'] 	= 'index.php'.$cond; 
			$tempmod_array[$ctr]['dashlinkout'] = 'index.php'.$cond;
 
			$ctr++;
		}
		return $tempmod_array;
	}
	
	
	function get_modules(){
		$tempmod_array=array(); 
		$modsql="SELECT * FROM modules WHERE modulestat ='front' OR modulestat ='back' OR modulestat ='frontback' ORDER BY modulename ASC";
		$this->setting_con->qselectDb($modsql);
		$ctr = 0;
		while($this->setting_con->fetchRs())
		{
			$tempmodname = $this->setting_con->rs['modulename'];
			$tempmod_array[$ctr]['modname'] = $this->setting_con->rs['modulename'];
			$tempmod_array[$ctr]['modcap'] = $this->setting_con->rs['modulecaption'];
			$remarks = $this->setting_con->rs['remarks'];
			if($remarks=='predefined'){
				if(check_custom_mod($tempmodname)==1){
					$tempmod_array[$ctr]['linkout'] = 'href = "index.php?mod='.$this->setting_con->rs['modulename'].'"';
					$tempmod_array[$ctr]['dashlinkout'] = 'href = "index.php?mod='.$this->setting_con->rs['modulename'];
				}else{
					$tempmod_array[$ctr]['linkout'] = 'href = "index.php?mod=sections&type='.$this->setting_con->rs['modulename'].'"';
					$tempmod_array[$ctr]['dashlinkout'] = 'href = "index.php?mod=sections&type='.$this->setting_con->rs['modulename'];
				}
			}else{
				$tempmod_array[$ctr]['linkout'] = 'href = "index.php?mod='.$this->setting_con->rs['modulename'].'"';
				$tempmod_array[$ctr]['dashlinkout'] = 'href = "index.php?mod='.$this->setting_con->rs['modulename'];
			}
			$ctr++;
		}
		return $tempmod_array;
	}
	
	
	
		
	
	function get_modulecap($module){
		$modsql="SELECT * FROM modules WHERE modulename='$module'";
		$this->setting_con->qselectDb($modsql);
		$this->setting_con->fetchRs();
		return $this->setting_con->rs['modulecaption'];
	}
	
	function get_module_path($module,$path_section){
		$modsql="SELECT modules,module_paths FROM settings";
		$this->setting_con->qselectDb($modsql);
		$this->setting_con->fetchRs();
		$module_path_array=unserialize($this->setting_con->rs['module_paths']);
		//print_r($module_array);
		
		return $module_array;
	}




	function init_includes(){



	}
	
	
	
	function menulist(){
		$hidden_mods = array('admin','login','profile','home','config');
		$all_mods = array('admin','profile','config','pages','articles','banners','products','about','contactus','users','usergroups','reports','gallery','products');
		
		$common_subs = array();
		$additional_subs = array();
		$corr_links = array();
	
		//set common modules
				
		//set modules submenu
	
	
	}
	
	function get_sections(){
		$sections_query="SELECT predefpages FROM settings";
		$this->setting_con->qselectDb($sections_query);
		$this->setting_con->fetchRs();
		$sections=unserialize($this->setting_con->rs['predefpages']);
		return $sections;
		
	}
	
	function get_frontendmenu(){
		$sections_query="SELECT menuitemsfront FROM settings";
		$this->setting_con->qselectDb($sections_query);
		$this->setting_con->fetchRs();
		$fmenu=unserialize($this->setting_con->rs['predefpages']);
		return $fmenu;
		
	}
	
	function get_backendmenu(){
		$sections_query="SELECT menuitemsback FROM settings";
		$this->setting_con->qselectDb($sections_query);
		$this->setting_con->fetchRs();
		$bmenu=unserialize($this->setting_con->rs['predefpages']);
		return $bmenu;
		
	}
	
	function add_section($section){
		
	}
	
	function add_frontendmenu($fmenuitem){
		
	}
	
	function add_backendmenu($bmenuitem){
		
	}
	
	function get_sitemeta(){
	}
	
	function get_site_switches(){
		$modsql="SELECT * FROM settings WHERE id='1'";
		$this->setting_con->qselectDb($modsql);
		$this->setting_con->fetchRs();
		$site_switches = array();


	}

}









?>
