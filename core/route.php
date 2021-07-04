<?
class Route {
	
	static function go(){
		$routes = explode('/', $_SERVER['REQUEST_URI']);
		
		$Cname = !empty($routes[1]) ? strtolower($routes[1]) : 'home'; //имя контроллера
		$Aname = !empty($routes[2]) ? strtolower($routes[2]) : 'index'; //имя действия
		
		//подключаем controller
		$Cpath = SITE_PATH.'controllers'.DS.$Cname.'.php';
		try {
			if(file_exists($Cpath)){
				include_once $Cpath;
				
				$controllerName = "\\App\\Controller\\$Cname";
				$controller = new $controllerName;
				if(method_exists($controller, $Aname)){
					$controller->$Aname();
				} else {
					throw new \App\Exception\BaseException('Действие не найдено', 404);
				}
			} else {
				throw new \App\Exception\BaseException('Страница не найдена', 404);
			}
		} catch (\App\Exception\BaseException $e){
			$e->ErrorPage();
		}

	}
}
?>