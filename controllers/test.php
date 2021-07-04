<?
namespace App\Controller;

use App\Controller;

class Test extends Controller {
	
	function index(){
		//можно хранить в файле, можно в БД, по-хорошему надо брать из модели, но для тестового сойдет как заглушки
		$this->view->render([
			'result' => [
				'name' => 'Тестовое задание',
				'text' => '<div class="row">
	<div class="col-md-6">
		<a href="loadPeoples/">Загрузить жителей</a>
	</div>
	<div class="col-md-6">
		<a href="loadPlanets/">Загрузить планеты</a>
	</div>
	<div class="col-md-4">
		<a href="planets/">Отчет по планетам</a>
	</div>
	<div class="col-md-4">
		<a href="by3/">Отчет по планетам с тремя жителями</a>
	</div>
	<div class="col-md-4">
		<a href="peoples/">Отчет по жителям</a>
	</div>
</div>',
			],
		]);
	}
	
	//загрузить список жителей
	function loadPeoples(){
		$url = 'https://swapi.dev/api/people/?format=json';
		$i = 0;
		$p = 0;
		do {
			$peoples = $this->request($url);
			$fPeople = fopen(SITE_PATH.'core'.DS.'migrations'.DS.'Peoples_'.(str_pad($i, 4, '0', STR_PAD_LEFT)).'.sql', 'w+');
			//fwrite($fPeople, "ALTER TABLE `peoples` CHANGE `id` `id` INT UNSIGNED NOT NULL;\n");
			fwrite($fPeople, "INSERT IGNORE INTO `peoples` (`id`, `name`, `height`, `mass`, `birth_year`, `gender`) VALUES \n");
			
			//записываем жителей
			$peoplesRows = [];
			foreach($peoples['results'] as $people){
				$peoplesRows[] = "('".$this->getIdFromUrl($people['url'])."', '".$people['name']."', '".$people['height']."', '".$people['mass']."', '".$people['birth_year']."', '".$people['gender']."')";
				
				$p++;
			}
			fwrite($fPeople, implode(",\n", $peoplesRows));
			//fwrite($fPeople, ";\nALTER TABLE `peoples` CHANGE `id` `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;");
			fclose($fPeople);
			
			$i++;
		} while ($url = $peoples['next']);
		
		$this->view->render([
			'result' => [
				'name' => 'Сохранение информации жителей',
				'text' => 'Загружено '.$p.' жителей на '.$i.' страницах'."\n".'<br>Не забудьте <a href="/migrations/">импортировать</a> миграции',
			],
		]);
	}
	
	//загрузить список планет
	function loadPlanets(){
		$url = 'https://swapi.dev/api/planets/?format=json';
		$i = 0;
		$p = 0;
		do {
			$planets = $this->request($url);
			
			foreach($planets['results'] as $planet){
				
				$planet_id = $this->getIdFromUrl($planet['url']);
				
				//записываем планету
				$fPlanet = fopen(SITE_PATH.'core'.DS.'migrations'.DS.'Planets_'.str_replace(' ', '_', $planet['name']).'.sql', 'w+');
				//fwrite($fPlanet, "ALTER TABLE `planets` CHANGE `id` `id` INT UNSIGNED NOT NULL;\n");
				fwrite($fPlanet, "INSERT IGNORE INTO `planets` (`id`, `name`, `rotation_period`, `orbital_period`, `diameter`, `population`) VALUES \n");
				fwrite($fPlanet, "('".$planet_id."', '".$planet['name']."', '".$planet['rotation_period']."', '".$planet['orbital_period']."', '".$planet['diameter']."', '".$planet['population']."')\n");
				//fwrite($fPlanet, ";\nALTER TABLE `planets` CHANGE `id` `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;");
				fclose($fPlanet);
				
				//доп. таблица люди->планеты
				if(!empty($planet['residents'])){
					$fPeopleToPlanets = fopen(SITE_PATH.'core'.DS.'migrations'.DS.'Planets_'.str_replace(' ', '_', $planet['name']).'_Peoples.sql', 'w+');
					fwrite($fPeopleToPlanets, "INSERT IGNORE INTO `peoples_to_planets` (`planet_id`, `people_id`) VALUES \n");
					
					$planetsRows = [];
					foreach($planet['residents'] as $resident){
						$planetsRows[] = "('".$planet_id."', '".$this->getIdFromUrl($resident)."')";
					}
					fwrite($fPeopleToPlanets, implode(",\n", $planetsRows));
					fclose($fPeopleToPlanets);
				}
				
				$p++;
			}
			
			$i++;
		} while ($url = $planets['next']);
		
		$this->view->render([
			'result' => [
				'name' => 'Сохранение планет и их жителей',
				'text' => 'Загружено '.$p.' планет на '.$i.' страницах'."\n".'<br>Не забудьте <a href="/migrations/">импортировать</a> миграции',
			],
		]);
	}
	
	function request($url):array {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response, true);
	}
	
	function getIdFromUrl($url):int {
		$ar = array_diff(explode('/', $url), ['']);
		return end($ar);
	}
	
	//отчет по планетам
	function planets(){
		$content = $this->model->planets();
		
		$this->view->render([
			'result' => [
				'name' => 'По планетам',
				'text' => 'Список планет, сортировка по убыванию популяции',
				'items' => $content,
			],
			'seo' => [
				'title' => 'Отчет по планетам',
				'description' => '',
			],
		]);
	}
	
	//отчет по планетам с тремя жителями
	function by3(){
		$content = $this->model->by3();
		
		$this->view->render([
			'result' => [
				'name' => 'Планеты с 12 жителями',
				'text' => 'В задании было с 3 жителями, но таких планет нет в принципе',
				'items' => $content,
			],
			'seo' => [
				'title' => 'Отчет по планетам, на которых живут тремя жителя',
				'description' => '',
			],
		]);
	}
	
	//отчет по жителям
	function peoples(){
		$content = $this->model->peoples();
		
		$this->view->render([
			'result' => [
				'name' => 'Жители',
				'text' => 'Жители по алфавиту',
				'items' => $content,
			],
			'seo' => [
				'title' => 'Отчет по жителям',
				'description' => '',
			],
		]);
	}

}
?>