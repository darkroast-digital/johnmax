<?php 

class userslib extends db
{
	var $tablename = 'tbl_users';

	public function loginCheck($data)
	{
		$q = "SELECT * from ".$this->tablename." where username = '".$data['username']."' AND password = '".md5($data['password'])."'";
		$result = $this->query($q);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			return $row;
		}
		else{
			return false;
		}
	}

	public function updateLastlogin($userid)
	{
		$q = "UPDATE ".$this->tablename." SET lastlogin=NOW() where userid = '".$userid."'";
		$result = $this->query($q);
		if ($result){
			return $this->insert_id;
		}
		else{
			return false;
		}
	}

	public function fetchAllusers()
	{
		$q = "SELECT * from ".$this->tablename;
		$result = $this->query($q);
		return $result;
	}

	public function fetchuserByid($userid)
	{
		$q = "SELECT * from ".$this->tablename." where userid = '".$userid."'";
		$result = $this->query($q);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			return $row;
		}
		else{
			return false;
		}
	}

	public function addUser($data)
	{
		$q = "INSERT INTO ".$this->tablename." (fullname, emailid, username, password, createdon) VALUES('".$data['fullname']."', '".$data['emailid']."', '".$data['username']."', '".md5($data['password'])."', NOW())";
		$result = $this->query($q);

		if ($result){
			$data['userid'] = $this->insert_id;
			$sendemailObj = new sendemail();
			$sendemailObj->newadmin($data);
			return $this->insert_id;
		}
		else{
			return false;
		}
	}

	public function updateUserdata($data)
	{
		$q = "UPDATE ".$this->tablename." SET fullname='".$data['fullname']."', emailid='".$data['emailid']."', username='".$data['username']."' where userid = '".$data['userid']."'";
		$result = $this->query($q);
		if ($result){
			return $this->insert_id;
		}
		else{
			return false;
		}
	}
	
	public function deleteUser($userid)
	{
		$q = "DELETE FROM ".$this->tablename." WHERE userid = '".$userid."'";
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