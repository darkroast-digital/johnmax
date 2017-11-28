<?php 

class playerslib extends db
{

	var $tablename = 'tbl_players';

	public function addplayer($data)
	{
		$q = "INSERT INTO ".$this->tablename." (teamid,playername,playeremail,pplramount,addedon,ispaid) VALUES('".substr($data['teamid'], 3)."','".$data['playername']."','".$data['playeremail']."','".$data['payfee']."',CURDATE(),'".$data['ispaid']."')";
		$result = $this->query($q);

		if ($result){
			return $this->insert_id;
		}
		else{
			return false;
		}
	}
	
	public function fetchteamByid($teamid)
	{
		$q = "SELECT * from ".$this->tablename." where teamid = '".$teamid."'";
		$result = $this->query($q);
		return $result;
	}

	public function updateIspaid($playerid)
	{
		$q = "UPDATE ".$this->tablename." SET ispaid='Y' where playerid='".$playerid."'";
		$result = $this->query($q);
		if ($result){
			return $this->affected_rows;
		}
		else{
			return false;
		}
	}

	public function updateisPaidbyteamid($teamid)
	{
		$q = "UPDATE ".$this->tablename." SET ispaid='Y' where teamid='".$teamid."' AND ispaid='N'";
		$result = $this->query($q);
		if ($result){
			return $this->affected_rows;
		}
		else{
			return false;
		}
	}

}

?>