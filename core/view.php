<?
namespace App;

class View {
	function render($data = []){
		$data = $this->setDefault($data);
		include_once SITE_PATH.'views'.DS.'header.php';
		include_once SITE_PATH.'views'.DS.$data['template'].'.php';
		include_once SITE_PATH.'views'.DS.'footer.php';
	}
	
	function setDefault($data):array {
		$full = $data + [
			'result' => [
				'name' => 'Страница',
				'text' => '',
				'items' => null,
			],
			'seo' => [
				'title' => 'Страница',
				'description' => '',
			],
		];
		//шаблон может быть указан, но не существовать
		$full['template'] = (isset($full['template']) && file_exists(SITE_PATH.'views'.DS.$full['template'].'.php')) ? $full['template'] : 'default';
		
		return $full;
	}
}
?>