<?php

	if(!defined("__XE__")) exit();
    if($called_position!='before_module_proc') return;
	if(($this->module == 'board') && $act) return;
	  
	$extra_val_name = $addon_info->extra_val_name;
	$module_srl = $this->module_srl;
	$document_srl = $_REQUEST['document_srl'];
	
	if($extra_val_name == '') return;
	 
	$args->module_srl = $module_srl;
	$output = executeQuery('addons.cc_ad_limited_posts.doc_list', $args);
	$oDocumentController = &getController('document');

	$oDocumentModel = &getModel('document');
	
	 
	
	foreach($output->data as $key=>$val)
	{
		if($key == "document_srl")
		{
			$getExtraVars = $oDocumentModel->getExtraVars($module_srl,$val);	
			$date_val = "";
			foreach($getExtraVars AS $key_=>$val_)
			{
				if ($val_->eid == $extra_val_name) {
				$date_val =  $val_->value;
				}
			}
			
			if(strtotime($date_val) < strtotime(date('Ymd')))
			{// 날짜가 과거인 경우 
				$temp[] =$val;
			}
		}
		
	}

  	foreach($temp as $key=>$val)
	{
		$oDocumentController->deleteDocument($val, true); 
	}
		
   
?>
