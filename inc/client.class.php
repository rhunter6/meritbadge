<?php
class clients
{
	// Database connection.
	var $connection;

	function __construct($connection)
	{
		$this->connection = $connection;
	}

// start main list functions	
	function addClient($data)
	{	
			$check_q = sprintf("SELECT email from main_list WHERE email = '%s'",
			mysqli_real_escape_string($this->connection, $data['email']));
			$check_result = mysqli_query($this->connection, $check_q) or die(mysql_error());
			$num_rows = mysqli_num_rows($check_result);
			
			if($num_rows==0) {
			
			$q = sprintf("INSERT INTO main_list set email = '%s', first_name = '%s', last_name = '%s', phone = '%s',district_id = '%s',troop_id = '%s',council = '%s', ytp_exp = '%s', created = NOW()",
			mysqli_real_escape_string($this->connection, $data['email']),
			mysqli_real_escape_string($this->connection, $data['first_name']),
			mysqli_real_escape_string($this->connection, $data['last_name']),
			mysqli_real_escape_string($this->connection, $data['phone']),
			mysqli_real_escape_string($this->connection, $data['district_id']),
			mysqli_real_escape_string($this->connection, $data['troop_id']),
			mysqli_real_escape_string($this->connection, $data['council']),
			mysqli_real_escape_string($this->connection, $data['ytp_exp']));		
			$insert_result = mysqli_query($this->connection, $q) or die(mysql_error());
			$result = mysqli_insert_id();		
			
			} else { $result = "EXIST";	}
			
			return $result;			
		
	}
	
function editClient($data)
	{	
				
			
			$q = sprintf("UPDATE main_list set email = '%s', first_name = '%s', last_name = '%s', phone = '%s',district_id = '%s',troop_id = '%s',council = '%s',ytp_exp = '%s' WHERE list_id = '%s'",
			mysqli_real_escape_string($this->connection, $data['email']),
			mysqli_real_escape_string($this->connection, $data['first_name']),
			mysqli_real_escape_string($this->connection, $data['last_name']),
			mysqli_real_escape_string($this->connection, $data['phone']),
			mysqli_real_escape_string($this->connection, $data['district_id']),
			mysqli_real_escape_string($this->connection, $data['troop_id']),
			mysqli_real_escape_string($this->connection, $data['council']),
			mysqli_real_escape_string($this->connection, $data['ytp_exp']),
			mysqli_real_escape_string($this->connection, $data['list_id']));		
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			
			
			return $q;
		
	}	
	
	function viewClient($data)
	{	
		
		q = "SELECT * FROM main_list where list_id = '$data'";
		$result = mysqli_query($this->connection, $q) or die(mysql_error());		
		$row = mysqli_fetch_assoc($result);

		return $row;
	}	

	/*
	 * Looks up the ClientId for the data pased in.  Uses email in the data.
	 */
	function findClient($data)
	{
		$q = sprintf("SELECT list_id from main_list WHERE email = '%s'",
		mysqli_real_escape_string($this->connection, $data['email']));
		$query_result = mysqli_query($this->connection, $q) or die(mysql_error());
		$num_rows = mysqli_num_rows($query_result);
		if ($num_rows == 1) {
			$row = mysqli_fetch_assoc($query_result);
			$result = $row;		
		}
		else 
		{
			$result = NULL;
		}

		return $result;				
	}
	
	function listClients($data)
	{	
		    if($data['badge_id']!=0) {$badge_search = "AND b.t_badge_id = '$data[badge_id]'"; }
			 else { $badge_search = ""; }
			 if($data['troop_id']!="") {$troop_search = "m.troop_id = '$data[troop_id]' AND "; }
			 else { $troop_search = ""; }
			 if($data['council_id']=="YES") { $council_search = "OR m.council = 'YES'"; }
			 else { $council_search = ""; }
			 if($data['district_id']!="") {$district_search = "AND ( ( $troop_search m.district_id = '$data[district_id]'  )  $council_search ) "; }
			 else { $district_search = ""; }
			if ( ($data['limit1']>=0) && ($data['limit2']!=0)  ) $limit = " LIMIT " . $data['limit1'] . ", " . $data['limit2']; else $limit = "";
			$q = "SELECT * FROM  badge_links b
			      join main_list m on m.list_id = b.t_main_id
			      where m.active = 'YES'
				  $badge_search 
				  $district_search
				  GROUP BY m.list_id
				  ORDER BY m.last_name
				  $limit  ";		
			
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			while ($row = mysqli_fetch_assoc($result)) {			
				$r[]=$row;
			}
			return $r;			
		
		
	}
	
	function listCount($data)
	{	
		
			if($data['badge_id']!=0) {$badge_search = "AND b.t_badge_id = '$data[badge_id]'"; }
			 else { $badge_search = ""; }
			  if($data['troop_id']!="") {$troop_search = "m.troop_id = '$data[troop_id]' AND "; }
			 else { $troop_search = ""; }
			  if($data['council_id']=="YES") { $council_search = "OR m.council = 'YES'"; }
			 else { $council_search = ""; }
			 
			 if($data['district_id']!="") {$district_search = "AND ( ( $troop_search m.district_id = '$data[district_id]'  )  $council_search  ) "; }
			 else { $district_search = ""; }
			if ( ($data['limit1']>=0) && ($data['limit2']!=0)  ) $limit = " LIMIT " . $data['limit1'] . ", " . $data['limit2']; else $limit = "";
			$q = "SELECT * FROM  badge_links b
			      join main_list m on m.list_id = b.t_main_id
			      where m.active = 'YES'
				  $badge_search 
				  $district_search
				  GROUP BY m.list_id
				    ";		
			
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			$ct = 0;
			while ($row = mysqli_fetch_assoc($result)) {			
				$ct++;
			}
			
			return $ct;				
					
		
		
	}	
	function archiveClient($data)
	{	
				
			
			$q = sprintf("UPDATE main_list set active = 'NO' where list_id = '%s'",
			mysqli_real_escape_string($this->connection, $data['list_id']));		
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			
			
			
			return $result;			
		
	}
	
	
// end main list functions
// start admin functions
//add admin
function addAdmin($data)
	{	
			$check_q = sprintf("SELECT email from control WHERE email = '%s'",
			mysqli_real_escape_string($this->connection, $data['email']));
			$check_result = mysqli_query($this->connection, $check_q) or die(mysql_error());
			$num_rows = mysqli_num_rows($check_result);
			
			if($num_rows==0) {
			
			$q = sprintf("INSERT INTO control set email = '%s', first_name = '%s', last_name = '%s', password = '%s'",
			mysqli_real_escape_string($this->connection, $data['email']),
			mysqli_real_escape_string($this->connection, $data['first_name']),
			mysqli_real_escape_string($this->connection, $data['last_name']),
			md5($data['password']));		
			$insert_result = mysqli_query($this->connection, $q) or die(mysql_error());
			$result = mysqli_insert_id($this->connection);		
			
			} else { $result = "EXIST";	}
			
			return $result;			
		
	}
//delete admin		
function archiveAdmin($data)
	{	
				
			
			$q = sprintf("DELETE FROM control WHERE client_id = '%s'",
			mysqli_real_escape_string($this->connection, $data['client_id']));		
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			
			
			
			return $result;			
		
	}
// list admin	
	function listAdmin($data)
	{	
				
			
			$q = "SELECT * FROM control";		
			
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			while ($row = mysqli_fetch_assoc($result)) {			
				$r[]=$row;
			}
			return $r;						
		
	}	
//login check	
function getAdmin_login($data)
	{	
				
			
			$q = sprintf("SELECT * FROM control where email = '%s' and password = '%s'",
			mysqli_real_escape_string($this->connection, $data['email']),
			mysqli_real_escape_string($this->connection, $data['password']));		
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			
			while ($row = mysqli_fetch_assoc($result)) {			
				$r = $row;
			}
			
			return $r;			
		
	}
//end admin functions


// start troop functions

function viewBadges($data)
	{	
		
			$q = "SELECT * FROM badge_list
			ORDER BY badge_name ASC ";		
			
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			while ($row = mysqli_fetch_assoc($result)) {			
				$r[] = $row;
			}
			
			
			return $r;			
		
		
	}	

function viewBadges_id($data)
	{	
		
			$q = "SELECT * FROM badge_list where badge_name LIKE '$data%' ";		
			$result = mysqli_query($this->connection, $q) or die(mysql_error());
			$row = mysqli_fetch_assoc($result);
			return $row;			
		
		
	}	

function addBadges($data)
	{	
				
			$q = sprintf("INSERT INTO badge_links set t_main_id = '%s',t_badge_id = '%s' ",		
			mysqli_real_escape_string($this->connection, $data['t_main_id']),
			mysqli_real_escape_string($this->connection, $data['t_badge_id']));
			$insert_result = mysqli_query($this->connection, $q) or die(mysql_error());
			$result = mysqli_insert_id($this->connection);	
			
			return $result;	
						
		
		
	}	
function viewLinkBadges($data)
	{	
		
			$q = sprintf("SELECT * FROM badge_links a
			      left join badge_list b on b.badge_id = a.t_badge_id			       
			      where a.t_main_id = '%s'",		
			mysqli_real_escape_string($this->connection, $data['list_id']));
			$result = mysqli_query($this->connection, $q) or die(mysql_error());		
			while ($row = mysqli_fetch_assoc($result)) {			
				$r[] = $row;
			}
			
			if (mysqli_num_rows($result)!=0) {
			return $r;			
			} else return "NULL";
		
	}		
	

}//end class
?>