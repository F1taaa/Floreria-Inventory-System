<?php
class Release
{
	private $DB_SERVER = 'localhost';
	private $DB_USERNAME = 'root';
	private $DB_PASSWORD = '';
	private $DB_DATABASE = 'floreria_db';
	private $conn;
	public function __construct()
	{
		$this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
	}

	public function new_release($sname, $desc, $amount)
	{
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');
	
		$stmt = $this->conn->prepare("INSERT INTO tbl_release(rel_customer, rel_description, rel_amount, rel_date_added, rel_time_added, rel_status) VALUES (?, ?, ?, ?, ?, ?)");
		try {
			$this->conn->beginTransaction();
			$stmt->execute([$sname, $desc, $amount, $NOW, $NOW, '1']);
			$id = $this->conn->lastInsertId();
			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			throw $e;
		}
	
		return $id;
	}
	


	public function list_release()
	{
		$sql = "SELECT * FROM tbl_release";
		$q = $this->conn->query($sql) or die("failed!");
		while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $r;
		}
		if (empty($data)) {
			return false;
		} else {
			return $data;
		}
	}

	function get_release_customer($id){
		$sql="SELECT rel_customer FROM tbl_release WHERE rel_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$rel_customer = $q->fetchColumn();
		return $rel_customer;
	}
	function get_release_date($id){
		$sql="SELECT rel_date_added FROM tbl_release WHERE rel_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$rel_date_added = $q->fetchColumn();
		return $rel_date_added;
	}
	function get_release_amount($id){
		$sql="SELECT rel_amount FROM tbl_release WHERE rel_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$rel_amount = $q->fetchColumn();
		return $rel_amount;
	}
	function get_release_desc($id){
		$sql="SELECT rel_description FROM tbl_release WHERE rel_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$rel_amount = $q->fetchColumn();
		return $rel_amount;
	}
	function get_release_save($id)
	{
		$sql = "SELECT rel_saved FROM tbl_release WHERE rel_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$rel_saved = $q->fetchColumn();
		return $rel_saved;
	}
	public function list_release_items($id) {
		$sql = "SELECT * FROM tbl_release_items WHERE rel_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		if (empty($data)) {
			return false;
		} else {
			return $data;
		}
	}
	
	public function new_release_item($relid,$prodid,$qty){
		/* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');
		$data = [
			[$relid,$prodid,$qty],
		];
		$stmt = $this->conn->prepare("INSERT INTO tbl_release_items(rel_id, prod_id, rel_qty) VALUES (?,?,?)");
		try {
			$this->conn->beginTransaction();
			foreach ($data as $row)
			{
				$stmt->execute($row);
			}
			//$id= $this->conn->lastInsertId();
			$this->conn->commit();
		}catch (Exception $e){
			$this->conn->rollback();
			throw $e;
		}
		return true;
	}

	public function save_release_items($id)
{
    $NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $NOW = $NOW->format('Y-m-d H:i:s');
    $status = 1;

    $sql = "UPDATE tbl_release SET rel_saved=:rel_saved WHERE rel_id=:id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':rel_saved', $status, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return true;
}


	public function save_to_released($id) {
		$sql = "SELECT * FROM tbl_release_items WHERE rel_id=:id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		$insertStmt = $this->conn->prepare("INSERT INTO tbl_release_inv(rel_id, prod_id, prod_qty) VALUES (?,?,?)");
	
		try {
			$this->conn->beginTransaction();
			foreach ($data as $row) {
				extract($row);
				$insertStmt->execute([$rel_id, $prod_id, $rel_qty]);
			}
			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			throw $e;
		}
	
		return true;
	}
	
}
?>