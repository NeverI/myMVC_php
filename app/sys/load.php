<?php 

class Load{
	

	public function view($fileName, $data=null, $template='default') {
		if (!headers_sent()) {
            if (is_array($data)){
                extract($data);
            };

            $pageContent = "app/views/{$template}/{$fileName}.php";

            header("Content-Type: text/html; charset=UTF-8");
            include("app/views/{$template}/base.php");
        }
	}
	
	public function ajax($fileName, $data=null, $template='default') {
        if (!headers_sent()) {
            $content = array();
            if (is_array($data)){
                extract($data);
                if (isset($title))
                $content['title'] = $title; 
            };

            header("Content-Type: text/html; charset=UTF-8");
            ob_start();
            include "app/views/{$template}/{$fileName}.php";
            $content['content'] = ob_get_clean();

            echo json_encode($content);
        }
   }

   public function getJson($data) {
      if (!headers_sent()) {
        header("Content-Type: text/html; charset=UTF-8");
        echo json_encode($data);
      }
   }

}

?>
