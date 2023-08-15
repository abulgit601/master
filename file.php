<?php
class Mycontroller extends MY_Controller{
	
	function Mycontroller(){
		parent::MY_Controller();
		$this->load->model ( "ajio/testdropshipmodel" );
	}
    public function index(){
        echo "test";die;

    }
    public function push(){
        echo "hello";die;

    }
    public function download(){
        echo "download";die;
    }

?>