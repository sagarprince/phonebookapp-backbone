<?php

class LoadApp {
	
	public $con;

	public function load_db() {
		// Create connection
		$this->con=mysqli_connect("localhost","root","","phonebook");
		// Check connection
		if (mysqli_connect_errno()) {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}

	public function insert_into_db( $name, $email, $phone, $address ) {
		$result = mysqli_query($this->con, "INSERT INTO  contacts (full_name, email, phone, address) VALUES ('".$name."', '".$email."', '".$phone."', '".$address."')");
		if($result) {
			return mysqli_insert_id($this->con);
		}
		return false;	
	}

	public function get_data_from_db() {
		$result = mysqli_query($this->con, "SELECT * FROM contacts");
		$contacts = array();
		if($result) {
			while($row = mysqli_fetch_array($result)) {
				$contacts[] = array( 
					'id' => $row['id'],
					'NAME' => $row['full_name'],
					'EMAIL' => $row['email'],
					'ADDRESS' => $row['address'],
					'PHONE' => $row['phone']
				);	
			}
		}
		return $contacts;	
	}

	public function update_contact_in_db($object) {
		$id = $object->id;
		$name = $object->name;
		$email = $object->email;
		$address = $object->address;
		$phone = $object->phone;
		$result = mysqli_query($this->con, "UPDATE contacts SET full_name='$name', email='$email', phone='$phone', address='$address'  where id='$id'");
		if($result) {
			return "updated";
		}
		return false;
	}
	
	public function delete_contact_from_db($id) {
		$result = mysqli_query($this->con, "DELETE FROM contacts where id='$id'");
		if($result) {
			return "deleted";
		}
		return false;
	}



}


$app_obj = new LoadApp();
$app_obj->load_db();

if(!empty($_POST)) {
	$object = json_encode($_POST);
	$object = json_decode($object);
} else if(!empty($_GET)) {
	$object = json_encode($_GET);
	$object = json_decode($object);
}
if(!empty($object)) {
	$action = $object->action;
	if( isset($action) && $action == "create" ) {
		$name = $object->name;
		$email = $object->email;
		$address = $object->address;
		$phone = $object->phone;
		$id = $app_obj->insert_into_db( $name, $email, $phone, $address );
		if($id!=false) {
			$saved = "saved";
		} else {
			$saved = "";
		}
		echo json_encode(array( 'response' => $saved, "ID" => $id ));
	}	

	if( isset($action) && $action == "update" ) {
		$result = $app_obj->update_contact_in_db( $object );
		if($result!=false) {
			$updated = $result;
		} else {
			$updated = "";
		}
		echo json_encode(array( 'response' => $updated ));
	}	

	if( isset($action) && $action == "get" ) {
		//sleep(1);
		$contacts = $app_obj->get_data_from_db();
		echo json_encode($contacts);
	}

	if( isset($action) && $action == "delete" ) {
		$contact_id = $object->id;
		$deleted = $app_obj->delete_contact_from_db($contact_id);
		echo json_encode(array( 'response' => $deleted ));
	}	
}
