<?
namespace App;

use PDO;

class Model extends PDO {
	
	private $db;
	
	function __construct(){
		$db = require SITE_PATH.'core/db.config.php';
		$this->db = $db;
		parent::__construct("mysql:host=".$db['host'].";dbname=".$db['database'].";charset=".$db['charset'], $db['login'], $db['password']);
	}
	
	function getList(array $data):array {
		
		if(empty($data['table']) || $data['table']=='') throw new \App\Exception\BaseException('Имя таблицы обязательно');
		
		if(!empty($data['select'])){
			if(is_array($data['select'])){
				$select = implode(', ', $data['select']);
			} else {
				$select = $data['select'];
			}
		} else {
			$select = '*';
		}
		
		if(!empty($data['order'])){
			foreach($data['order'] as $name=>$value){
				$s[] = $name.' '.$value;
			}
			$sort = ' ORDER BY '.implode(', ', $s);
		} else {
			$sort = '';
		}
		
		if(!empty($data['filter'])){
			foreach($data['filter'] as $name=>$value){
				$f[] = $name.'= :'.$name;
			}
			$filter = ' WHERE '.implode(' AND ', $f);
		} else {
			$filter = '';
		}
		
		$query = $this->prepare('SELECT '.$select.' FROM '.$data['table'].$sort.$filter);
		
		if(!empty($data['filter'])){
			$query->execute($data['filter']);
		} else {
			$query->execute();
		}
		
		if((is_array($data['select']) && count($data['select'])==1) || is_string($data['select'])){
			$mode = PDO::FETCH_COLUMN;
		} else {
			$mode = PDO::FETCH_ASSOC;
		}
		
		return $query->fetchAll($mode);
	}
	
	function setList(array $data) {
		if(empty($data['table']) || $data['table']=='') throw new \App\Exception\BaseException('Имя таблицы обязательно');
		if(empty($data['columns']) || $data['columns']=='') throw new \App\Exception\BaseException('Названия столбцов обязательны');
		if(empty($data['values']) || $data['table']=='') throw new \App\Exception\BaseException('Добавляемые значения обязательны');
		
		$query = $this->prepare('INSERT INTO '.$data['table'].' ('.implode(', ', $data['columns']).') VALUES ('.implode(',', array_fill(0, count($data['columns']), '?')).')');
		$query->execute($data['values']);
	}
	
	function getTables(string $table = ''):array {
		if($table !== ''){
			$filter = ' WHERE Tables_in_'.$this->db['database'].' LIKE \'%'.$table.'%\'';
		} else {
			$filter = '';
		}
		
		$query = $this->prepare('SHOW TABLES FROM '.$this->db['database'].$filter);
		$query->execute();
		
		return $query->fetchAll(PDO::FETCH_COLUMN);
	}
	
	function doExternal($path){
		$command = sprintf('mysql -u%s -p%s -h %s -D %s --default-character-set=utf8 < %s 2>&1 1> /dev/null', $this->db['login'], $this->db['password'], $this->db['host'], $this->db['database'], $path);
		$result = shell_exec($command);
		//не выводить предупреждения
		if($result!==null && strpos($result, '[Warning]')===false) throw new \App\Exception\BaseException('Исполнение внешнего sql-файла провалено: '.$result);
	}
}
?>