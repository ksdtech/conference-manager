<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Easy templating for any view
class MY_Loader extends CI_Loader {
    public function template($template_name, $vars = array(), $return = FALSE, $layout_name='application')
    {
        if ($return) {
        	$content  = $this->view('layouts/'.$layout_name.'/header', $vars, $return);
        	$content .= $this->view($template_name, $vars, $return);
        	$content .= $this->view('layouts/'.$layout_name.'/footer', $vars, $return);

        	return $content;
        } else {
        	$this->view('layouts/'.$layout_name.'/header', $vars);
        	$this->view($template_name, $vars);
        	$this->view('layouts/'.$layout_name.'/footer', $vars);
        }
    }
}
