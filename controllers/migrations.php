<?
namespace App\Controller;

use App\Controller;

class Migrations extends Controller {
	
	function index(){
		//можно хранить в файле, можно в БД, по-хорошему надо брать из модели, но для тестового сойдет как заглушки
		$this->view->render([
			'result' => [
				'name' => 'Действия миграций',
				'text' => '<div class="row">
	<div class="col-md-12">
		<a href="migrate/">Импортировать миграции</a>
	</div>
</div>',
			],
			'seo' => [
				'title' => 'Действия миграций',
				'description' => 'Тестовое задание',
			],
		]);
	}
	
	//импортировать все миграции в БД
	function migrate(){
		$this->view->render([
			'result' => [
				'name' => 'Статус миграций',
				'text' => $this->model->migrate(),
			],
			'seo' => [
				'title' => 'Статус миграций',
				'description' => 'Файлы миграций обработаны',
			],
		]);
	}
	
	function clear(){
		#если вдруг захотятся откаты
	}
	
}
?>