<?php
namespace PropertyPeople\Includes\Models;

use PropertyPeople\PropertyDatabase;

class OpenVacature {
	
	public $id;
	public $created_at;
	public $firstname;
	public $lastname;
	public $email;
	public $phone;
	public $file;
	public $motivation;
	public $function;
    public $status;
    
    /**
	 * @var int $origin blog_id
	 */
	public $origin;
	
	/**
	 * @var \wpdb
	 */
	private $_conn;
	
	public function __construct($data)
	{
		$this->_conn = $GLOBALS['wpdb'];
		
		if (is_array($data)) {
			$this->spreadArray($data);
		}
		
		if (is_int($data)) {
			$this->getData($data);
		}
	}
	
	private function spreadArray(array $data): self
	{
		$this->firstname = $data['voornaam'];
		$this->lastname = $data['naam'];
		$this->email = $data['email'];
		$this->phone = $data['mobiel'];
		$this->file = $data['cv']?? '';
		$this->function = $data['functie'];
		$this->motivation = $data['motivatie'];
		$this->origin = (int) $data['origin'];
		$this->id = $data['id'] ?? '';
		$this->created_at = $data['created_at']?? '';
        $this->status = $data['status'] ?? 'nieuw';
        
        return $this;
	}
	
	private function getData(int $id): self
	{
		$openSollTable = $this->_conn->prefix . PropertyDatabase::OPEN_TABLE_NAME;
		
		$data = $this->_conn->get_row("SELECT * FROM $openSollTable WHERE id = {$id}", ARRAY_A);
		
		if ($data) {
			return $this->spreadArray($data);
		}
		
		return $this;
	}
	
	public function save(): self
	{
		$openSollTable = $this->_conn->prefix . PropertyDatabase::OPEN_TABLE_NAME;
		
		$soll = $this->_conn->insert(
			$openSollTable,
			[
				'voornaam'  => $this->firstname,
				'naam'      => $this->lastname,
				'email'     => $this->email,
				'mobiel'    => $this->phone,
				'cv'        => $this->file,
				'motivatie' => $this->motivation,
				'origin'    => $this->origin,
				'functie'   => $this->function,
                'status'        => $this->status
            
            ]
		);
		
		if ($soll) {
			$this->getData($this->_conn->insert_id);
			return $this;
		}
		
		throw new \Exception($this->_conn->last_error, 500);
	}
	
	public function getName(): string
	{
		return $this->firstname . ' ' . $this->lastname;
	}
	
	/**
	 * Returns the original blog details the vacancy was posted on.
	 *
	 * @return false|\WP_Site
	 */
	public function getOrigin()
	{
		return get_blog_details($this->origin);
	}
	
	public function delete()
	{
		$openSollTable = $this->_conn->prefix . PropertyDatabase::OPEN_TABLE_NAME;
		
		$this->_conn->delete($openSollTable, ['id' => $this->id]);
		
		status_header(200);
		return true;
	}
    
    public function toggleStatus($status = 'opgepakt'): void
    {
        $vacancyTable = $this->_conn->prefix . PropertyDatabase::OPEN_TABLE_NAME;
        
        $this->_conn->update($vacancyTable, ['status' => $status ], [ 'id' => $this->id ]);
    }
}
