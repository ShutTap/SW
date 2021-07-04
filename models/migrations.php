<?
namespace App\Model;

use App\Model;

class Migrations extends Model {
	
	//импортировать все миграции в БД
	function migrate(){
		$migrationsFiles = glob(SITE_PATH.'core'.DS.'migrations'.DS.'*.sql');
		
		//если таблицы миграций ещё нет - значит, не запускали. если есть - отсекаем файлы миграций по ней
		$migrationsRecords = [];
		if(!empty($this->getTables('migrations'))){
			$migrationsRecords = $this->getList([
				'table' => 'migrations',
				'select' => 'path',
			]);
		}
		
		$migrationsFiles = array_diff($migrationsFiles, $migrationsRecords);
		asort($migrationsFiles);
		
		if(empty($migrationsFiles)){
			$content = 'Новых миграций нет';
		} else {
			$content = 'Следующие файлы миграций импортированы:';
			foreach ($migrationsFiles as $path) {
				$this->doExternal($path);
					
				$this->setList([
					'table' => 'migrations',
					'columns' => ['path'],
					'values' => [$path],
				]);
				
				$content .= "<br>\n".$path;
			}
		}
		
		return $content;
	}
	
	function clear(){
		#если вдруг захотятся откаты
	}
}
?>