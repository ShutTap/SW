<?
namespace App\Exception;

class BaseException extends \Exception {
	
	public $view;
	
	function __construct(string $message = "", int $code = 0, \Exception $previous = null){
		parent::__construct($message, $code, $previous);
		
		$this->view = new \App\View;
	}
	
	//вывод страницы ошибки
	public function ErrorPage(){
		
		http_response_code($this->getCode());
		
		$code = $this->getCode();
		$code = $code!==0 ? ' '.$code : '';
		
		$this->view->render([
			'title' => 'Ошибка'.$code,
			'content' => $this->getMessage(),
			'seo' => [
				'title' => 'Ошибка'.$code,
				'description' => $this->getMessage(),
			],
		]);
		
		die();
	}
	
	
	
	
}
?>