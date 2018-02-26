<?php

class JsonTable {

	protected $jsonFile;
	protected $fileHandle;
	protected $fileData = array(), $fileSize;
	
	public function __construct($_jsonFile, $create = false) {
		if (!file_exists($_jsonFile)) {
			if($create === true)
			{
				$this->createTable($_jsonFile, true);
			}
			else
			{
				throw new Exception("JsonTable Error: Table not found: ".$_jsonFile);
			}
		}

		$this->jsonFile = $_jsonFile;
		$this->fileData = json_decode(file_get_contents($this->jsonFile), true);
		$this->fileSize = count($this->fileData);
		$this->lockFile();
	}
	
	public function __destruct() {
		$this->save();
		fclose($this->fileHandle);	
	}
	
	protected function lockFile() {
		$handle = fopen($this->jsonFile, "c");
		if (flock($handle, LOCK_EX)) $this->fileHandle = $handle;
		else throw new Exception("JsonTable Error: Can't set file-lock");
	}
	
	protected function save() {
		if (ftruncate($this->fileHandle, 0) && fwrite($this->fileHandle, json_encode($this->fileData, JSON_PRETTY_PRINT))) return true;
		else throw new Exception("JsonTable Error: Can't write data to: ".$this->jsonFile);
	}
	
	public function selectAll() {
		return $this->fileData;
	}

	public function search($key, $value, $fileData = null) 
	{
		$result = array_filter($this->fileData, function($results) {
			return $results[$key] == $value;
		});
		return $result;
	}
	
	public function select($key, $val = 0) {
		$result = array();
		if (is_array($key)) $result = $this->select($key[1], $key[2]);
		else {
			$data = $this->fileData;
			foreach($data as $_key => $_val) {
				if (isset($data[$_key][$key])) {
					if ($data[$_key][$key] == $val) {
						$result[] = $data[$_key];
					}
				}
			}
		}
		return $result;
	}
	
	public function updateAll($data = array()) {
		if (isset($data[0]) && substr_compare($data[0],$this->jsonFile,0)) $data = $data[1];
		return $this->fileData = empty($data) ? array() : $data;
	}
	
	public function update($key, $val = 0, $newData = array()) {
		$result = false;
		if (is_array($key)) $result = $this->update($key[1], $key[2], $key[3]);
		else {
			$data = $this->fileData;
			foreach($data as $_key => $_val) {
				if (isset($data[$_key][$key])) {
					if ($data[$_key][$key] == $val) {
						$data[$_key] = $newData;
						$result = true;
						break;
					}
				}
			}
			if ($result) $this->fileData = $data;
		}
		return $result;
	}
	
	public function insert($data = array(), $create = false) {
		if (isset($data[0]) && substr_compare($data[0],$this->jsonFile,0)) $data = $data[1];
		$this->fileData[] = $data;
		return true;
	}
	
	public function deleteAll() {
		$this->fileData = array();
		return true;
	}
	
	public function delete($key, $val = 0) {
		$result = 0;
		if (is_array($key)) $result = $this->delete($key[1], $key[2]);
		else {
			$data = $this->fileData;
			foreach($data as $_key => $_val) {
				if (isset($data[$_key][$key])) {
					if ($data[$_key][$key] == $val) {
						unset($data[$_key]);
						$result++;
					}
				}
			}
			if ($result) {
				sort($data);
				$this->fileData = $data;
			}
		}
		return $result;
	}

	public function createTable($tablePath) {
		if(is_array($tablePath)) $tablePath = $tablePath[0];
		if(file_exists($tablePath))
			throw new Exception("Table already exists: ".$tablePath);

		if(fclose(fopen($tablePath, 'a')))
		{
			return true;
		}
		else
		{
			throw new Exception("New table couldn't be created: ".$tablePath);
		}
	}	
	
}

class JsonDB {

	protected $path = "./";
	protected $fileExt = ".json";
	protected $tables = array();
	
	public function __construct($path) {
		if (is_dir($path)) $this->path = $path;
		else throw new Exception("JsonDB Error: Database not found");
	}
	
	protected function getTableInstance($table, $create) {
		if (isset($tables[$table])) return $tables[$table];
		else return $tables[$table] = new JsonTable($this->path.$table, $create);
	}
	
	public function __call($op, $args) {
		if ($args && method_exists("JsonTable", $op)) {
			$table = $args[0].$this->fileExt;
			$create = false;
			if($op == "createTable")
			{
				return $this->getTableInstance($table, true);
			}
			elseif($op == "insert" && isset($args[2]) && $args[2] === true)
			{
				$create = true;
			}
			return $this->getTableInstance($table, $create)->$op($args);
		} else throw new Exception("JsonDB Error: Unknown method or wrong arguments ");
	}
	
	public function setExtension($_fileExt) {
		$this->fileExt = $_fileExt;
		return $this;
	}
	
}

?>
