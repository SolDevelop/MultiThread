<?php
#namespace MultiThread;

class MultiThread{
	public $loglimit;
	private $logint;
	private $waitingcall;
	private $dbf;
	private $re;
	public $c;
	private $k;
	public $VH;
	//public $redb;
	public function __construct(string $dbfile, string $requeststring, bool $k = null, bool $start = null, int $limit = null) {
        $this->dbf = $dbfile;
		$this->re = $requeststring;
		if ($start == true){
			if ($limit !== null){
				$this->start($limit);
			}else{
				throw new Exception("Start Is Enabled But No Limit Was Set");
			}
		}else{
			$db = new SQLite3($dbfile);
			$db->enableExceptions(true);
			if ($k == null){
			try{
			$ro = $db->query("SELECT * FROM WaitingLine");
			while ($re = $ro->fetchArray()){
			$this->loglimit = $re['MAXU'];
			$this->logint = (int)$re['LineNumber'];
			}
			if (isset($this->loglimit)){
			$k = true;
			}
			}catch(Exception $e){
			$this->start(3);
			}
			}
			if ($k == true){
			$ro = $db->query("SELECT * FROM WaitingLine");
			while ($re = $ro->fetchArray()){
			$this->loglimit = $re['MAXU'];
			$this->logint = (int)$re['LineNumber'];
			}
			if ($this->loglimit == $this->logint){
			$this->fresh($dbfile);
			}
			}
		}
    }
	private function start($limit){
		$this->loglimit = $limit;
		$db = new SQLite3($this->dbf);
		$checker = $db->query("SELECT COUNT(*) as count FROM sqlite_master WHERE type='table' AND name='WaitingLine'");
		$F = 0;
		while ($C = $checker->fetchArray()){
			$F = $C['count'];
		}
		if ($F == 1){
			$db->query("DROP TABLE WaitingLine");
		}
		$db->query("CREATE TABLE WaitingLine(LineNumber TEXT PRIMARY KEY, Request TEXT, MAXU TEXT DEFAULT '{$this->loglimit}' )");
		
	}
	public function call(string $dbe, string $request){
		if ($this->loglimit == $this->logint){
			return $this->fresh($dbe);
		}else{
			$this->redb = $dbe;
			$db = new SQLite3($dbe);
			$checker = $db->query("SELECT COUNT(*) as count FROM sqlite_master WHERE type='table' AND name='WaitingLine'");
			$F = 0;
			while ($C = $checker->fetchArray()){
			$F = $C['count'];
			}
			if ($F !== 1){
			throw new Exception("This Database didnt have MultiThread Enabled");
			}else{
			//$number;
			$collect = $db->query("SELECT COUNT(*) as count FROM WaitingLine");
			while ($result = $collect->fetchArray()){
			$number = $result['count'];
			}
			$number = $number + 1;
			$db->query("INSERT INTO WaitingLine(LineNumber, Request) VALUES($number, '{$request}')");
			$this->logint = $number;
			$this->number = $number;
			if ($number == $this->loglimit){
			$this->fresh($dbe);
			}
			return $number;
			//return $this->number;
			}
		}
	}
	private function fresh(string $dbe){
		$db = new SQLite3($dbe);
		$startTable = $db->query("SELECT * FROM WaitingLine");
		$v = ['INIT'];
		$number = $this->loglimit;
		$e = $number - $this->logint;
		$e++;
		while ($startRow = $startTable->fetchArray()){
			$request = $startRow['Request'];
			$result = $db->query($request);
			$uniei = $e;
    		$id = $uniei;
			array_push($v,$id);
			$clo = var_export($result->fetchArray(), true);
			$file = "./ids/shared-" . $id . ".json";
			file_put_contents($file, $clo);
			$e++;
		}
	}
	
}
?>