<?
namespace App\Model;

use App\Model;

class Test extends Model {
	
	function planets(){
		$result = $this->getList([
			'table' => 'planets',
			'order' => ['population' => 'desc'],
			'select' => ['name', 'rotation_period', 'orbital_period', 'diameter', 'population'],
		]);
			
		return array_merge(['columns' => array_flip($result[0])], $result);
	}
	
	function by3(){
		
		$query = $this->prepare('
		SELECT planets.name, planets.rotation_period, planets.orbital_period, planets.diameter, planets.population, COUNT(peoples_to_planets.planet_id) AS real_peoples 
		FROM planets 
		RIGHT JOIN peoples_to_planets ON planets.id=peoples_to_planets.planet_id 
		GROUP BY peoples_to_planets.planet_id 
		HAVING COUNT(peoples_to_planets.planet_id)=12 
		ORDER BY planets.name
		');
		$query->execute();
		$result = $query->fetchAll(\PDO::FETCH_ASSOC);
		
		return array_merge(['columns' => array_flip($result[0])], $result);
	}
	
	function peoples(){
		$result = $this->getList([
			'table' => 'peoples',
			'order' => ['name' => 'asc'],
			'select' => ['name', 'height', 'mass', 'birth_year', 'gender'],
		]);
			
		return array_merge(['columns' => array_flip($result[0])], $result);
	}
}
?>