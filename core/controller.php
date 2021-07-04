<?
namespace App;

class Controller {
	public $model;
	public $view;
	
	function __construct(){
		//подключаем model
		$Mname = $this->className($this);
		$Mpath = SITE_PATH.'models/'.strtolower($Mname).'.php';
		if(file_exists($Mpath)){
			include_once $Mpath;
			$model = "\App\Model\\".$Mname;
			$this->model = new $model;
		} else {
			$this->model = new \App\Model();
		}
		
		$this->view = new \App\View;
	}
	
	function className($obj):string {
		$class = explode('\\', get_class($obj));
		return end($class);
	}
	
	function index(){
		
	}

}
?>