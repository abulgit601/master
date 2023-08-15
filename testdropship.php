<?php
class testdropship extends MY_Controller{
	
	function testdropship(){
		parent::MY_Controller();
		$this->load->model ( "ajio/testdropshipmodel" );
	}
	
	function download($ordernumber){
		$orderdata = $this->testdropshipmodel->chkOrdersExist($ordernumber);
	    //print_r($orderdata);
	    $CI =& get_instance();
	    
	    $vendorid = $CI->config->item("ajio_vendor_id");
		echo $vendorid;die;
	    
	    $ajiofolder = $this->config->item($vendorid."_log");
	    
	    if(count($orderdata)>0){
	    	$this->load->helper('download');
	        // fetch relevant data
	       	
    	    	$s3Path = $this->config->item('s3Path');
    	    	
    	    	$this->load->library('zip');
    	    	// PO PDF 
    	    	$po_number = $orderdata[0]['a_ponumber']; 
    	    	//echo 'uploads/'.$ajiofolder.'/popdf/'.$po_number.'.pdf'; die;
    	    	if(file_exists('uploads/'.$ajiofolder.'/popdf/'.$po_number.'.pdf'))
    	    		$pofilepath = 'uploads/'.$ajiofolder.'/popdf/'.$po_number.'.pdf';
    	    	else if(file_get_contents($s3Path.'uploads/'.$ajiofolder.'/popdf/'.$po_number.'.pdf'))
    	    	 	$pofilepath = $s3Path.'uploads/'.$ajiofolder.'/popdf/'.$po_number.'.pdf';
    	    
    	    	if($pofilepath)	
        			$this->zip->add_data('po_'.$po_number.'.pdf',file_get_contents($pofilepath));
        		
        		
        		// INVOICE PDF
        		if(file_exists('uploads/'.$ajiofolder.'/invoice/'.$ordernumber.'.pdf'))
        			$invoicefilepath = 'uploads/'.$ajiofolder.'/invoice/'.$ordernumber.'.pdf';
        		else if(file_get_contents($s3Path.'uploads/'.$ajiofolder.'/invoice/'.$ordernumber.'.pdf'))
        			$invoicefilepath = $s3Path.'uploads/'.$ajiofolder.'/invoice/'.$ordernumber.'.pdf';	
        		
        		if($invoicefilepath)
        			$this->zip->add_data('invoice_'.$ordernumber.'.pdf',file_get_contents($invoicefilepath));
        			
        		// Shipping Label
        		$shippinglabel = $ordernumber."_".$orderdata[0]['a_awbnumber'];
        		//echo 'uploads/'.$ajiofolder.'/ShippingLabels/'.$shippinglabel.'.pdf'; die;
        		
        		if(file_exists('uploads/'.$ajiofolder.'/ShippingLabels/'.$shippinglabel.'.pdf'))	
        			$shippinglabelpath = 'uploads/'.$ajiofolder.'/ShippingLabels/'.$shippinglabel.'.pdf';
        		else if(file_get_contents($s3Path.'uploads/'.$ajiofolder.'/ShippingLabels/'.$shippinglabel.'.pdf'))
        			$shippinglabelpath = $s3Path.'uploads/'.$ajiofolder.'/ShippingLabels/'.$shippinglabel.'.pdf';	
        		
        		if($shippinglabelpath)	
        			$this->zip->add_data('shippinglabel_'.$shippinglabel.'.pdf',file_get_contents($shippinglabelpath));	
				
        		// Invoice PDF
        		if(file_exists('uploads/'.$ajiofolder.'/invoicepdf/'.$ordernumber.'.pdf'))
        			$invoicefilepath2 = 'uploads/'.$ajiofolder.'/invoicepdf/'.$ordernumber.'.pdf';
        		else if(file_get_contents($s3Path.'uploads/'.$ajiofolder.'/invoicepdf/'.$ordernumber.'.pdf'))
        			$invoicefilepath2 = $s3Path.'uploads/'.$ajiofolder.'/invoicepdf/'.$ordernumber.'.pdf';	
        		
        		if($invoicefilepath2)
        			$this->zip->add_data('invoicepdf_'.$ordernumber.'.pdf',file_get_contents($invoicefilepath2));
        			
        		// Manifest
        		
        		$manifestdata = $this->testdropshipmodel->chkManifestExist($ordernumber);
        		//print_r($manifestdata); die;
        		if(count($manifestdata)>0){
        			$manifestfile = $manifestdata[0]['a_manifestfilepath'];
        			//echo 'uploads/'.$manifestfile; die;
	        		if(file_exists('uploads/'.$manifestfile))	
	        			$manifestpath = 'uploads/'.$manifestfile;
	        		else if(file_get_contents($s3Path.'uploads/'.$manifestfile))
	        			$manifestpath = $s3Path.'uploads/'.$manifestfile;	
	        		
	        		if($manifestpath)	
	        			$this->zip->add_data('manifest_'.$ordernumber.'.pdf',file_get_contents($manifestpath));	
				
        		}
        		ob_end_clean();
				
				// Download ZIP archive containing /new/path/some_photo.jpg
				$this->zip->download($ordernumber.'.zip');
    	   
    	   
	        
	    }else{
	        echo $response = "Order data doesn't exists. ";
	       
		
	    }
	    
	}
	

	
}