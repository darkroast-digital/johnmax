<?php 

class teamlib extends db
{
	var $tablename = 'tbl_teams';
	
	public function addteam($data)
	{
		$q = "INSERT INTO ".$this->tablename." (teamname, captname, emailid, evenight, payamount, teammates, teamcost, addedon) VALUES('".$data['teamname']."', '".$data['captname']."', '".$data['emailid']."', '".$data['evenight']."', '".$data['payamount']."', '".$data['teammates']."', '".$data['teamcost']."', CURDATE())";
		$result = $this->query($q);

		if ($result){
			$data['teamid'] = $this->insert_id;
			$sendemailObj = new sendemail();
			$sendemailObj->newteamregitration($data);
			return $this->insert_id;
		}
		else{
			return false;
		}
	}

	public function checkIsactive($teamid)
	{
		$q = "SELECT status from ".$this->tablename." where teamid = '".$teamid."' AND status = 'Active'";
		$result = $this->query($q);
		if($result->num_rows > 0){
			return $result;
		}
		else{
			return false;
		}
	}
	
	public function checkIsdeposited($teamid)
	{
		$q = "SELECT status from ".$this->tablename." where teamid = '".$teamid."' AND totalamount > 0 ";
		$result = $this->query($q);
		if($result->num_rows > 0){
			return $result;
		}
		else{
			return false;
		}
	}

	public function fetchteamByid($teamid)
	{
		$q = "SELECT * from ".$this->tablename." where teamid = '".$teamid."'";
		$result = $this->query($q);
		$row = $result->fetch_assoc();
		return $row;
	}

	public function getTeamcount()
	{
		$q = "SELECT count(teamid) from ".$this->tablename." where status ='Active'";
		$result = $this->query($q);
		$row = $result->fetch_assoc();
		return $row['count(teamid)'];
	}

	public function getAllteamdata()
	{
		$q = "SELECT * from ".$this->tablename;
		$result = $this->query($q);
		return $result;
	}

	public function updateTeam($data)
	{
		$q = "UPDATE ".$this->tablename." SET teamname='".$data['teamname']."', captname='".$data['captname']."', emailid='".$data['emailid']."', evenight='".$data['evenight']."' where teamid = '".$data['teamid']."'";
		$result = $this->query($q);
		if ($result){
			return $this->affected_rows;
		}
		else{
			return false;
		}
	}

	public function deleteTeam($teamid)
	{
		$q = "DELETE FROM ".$this->tablename." WHERE teamid = '".$teamid."'";
		$result = $this->query($q);
		if ($result){
			return $this->affected_rows;
		}
		else{
			return false;
		}
	}

	public function getCountbyevenights()
	{
		$q = "SELECT count(teamid) as count, evenight from ".$this->tablename." where status='Active' GROUP BY evenight";
		$result = $this->query($q);
		return $result;
	}

	public function updateStatus($teamid)
	{
		$q = "UPDATE ".$this->tablename." SET status='Active' where teamid='".$teamid."'";
		$result = $this->query($q);
		if ($result){
			return $this->affected_rows;
		}
		else{
			return false;
		}
	}

	public function updateTotalamount($amount, $teamid)
	{
		$q = "UPDATE ".$this->tablename." SET totalamount='".$amount."' where teamid='".$teamid."'";
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