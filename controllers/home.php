<?
namespace App\Controller;

use App\Controller;

class Home extends Controller {
	
	function index(){
		//можно хранить в файле, можно в БД, по-хорошему надо брать из модели, но для тестового сойдет как заглушки
		$this->view->render([
			'result' => [
				'name' => 'Главная',
				'text' => '<p>Путь к файлу настроек БД /core/db.config.php</p>
				<div class="row">
	<div class="col-md-6">
		<a href="test/">Тестовое задание</a>
	</div>
	<div class="col-md-6">
		<a href="migrations/">Миграции</a>
	</div>
</div>',
			],
			'seo' => [
				'title' => 'Главная',
				'description' => 'Тестовое задание',
			],
		]);
	}
}
?>