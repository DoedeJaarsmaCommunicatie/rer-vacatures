<?php
namespace PropertyPeople\Includes\Models;


use PropertyPeople\PropertyDatabase;

class Vacature {
	public $id;
	public $created_at;
	public $firstname;
	public $lastname;
	public $email;
	public $phone;
	public $file;
	public $motivation;
	public $status;
	
	/**
	 * @var int $origin
	 */
	public $origin;
	
	/**
	 * @var int $vacancy
	 */
	public $vacancy;
	
	/**
	 * @var \WP_Post
	 */
	public $post;
	
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
		
		if(is_int($data)) {
			$this->getData($data);
		}
	}
	
	private function spreadArray(array $data): self
	{
		$this->firstname = $data['voornaam'];
		$this->lastname = $data['naam'];
		$this->email = $data['email'];
		$this->phone = $data['mobiel'];
		$this->file = $data['cv'] ?? '';
		$this->motivation = $data['motivatie'] ?? '';
		$this->origin = (int) $data['origin'];
		$this->vacancy = (int) $data['vacancy'];
		$this->id = $data['id'] ?? '';
		$this->created_at = $data['created_at'] ?? '';
		$this->status = $data['status'] ?? 'nieuw';
		if ($this->vacancy) {
			$this->post = get_post($this->vacancy);
		}
		
		return $this;
	}
	
	private function getData(int $id): void
	{
		$vacancyTable = $this->_conn->prefix . PropertyDatabase::TABLE_NAME;
		
		$data = $this->_conn->get_row("SELECT * FROM $vacancyTable WHERE id = {$id}", ARRAY_A);
		
		if ($data) {
			$this->spreadArray($data);
		}
	}
	
	public function save(): self
	{
		$vacancyTable = $this->_conn->prefix . PropertyDatabase::TABLE_NAME;
		
		$vacature = $this->_conn->insert(
			$vacancyTable,
			[
				'voornaam'      => $this->firstname,
				'naam'          => $this->lastname,
				'email'         => $this->email,
				'mobiel'        => $this->phone,
				'cv'            => $this->file,
				'motivatie'     => $this->motivation,
				'origin'        => $this->origin,
				'vacancy'       => $this->vacancy,
                'status'        => $this->status
			]
		);
		
		if ($vacature) {
			$this->getData($this->_conn->insert_id);
			return $this;
		}
		
		throw new \Exception('Sollicitatie niet opgeslagen', 500);
	}
	
	
	/**
	 * Returns the full name.
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return "$this->firstname $this->lastname";
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
		$vacancyTable = $this->_conn->prefix . PropertyDatabase::TABLE_NAME;
		
		$this->_conn->delete($vacancyTable, ['id' => $this->id]);
		
		status_header(200);
		return true;
	}
	
	public function toggleStatus($status = 'opgepakt'): void
    {
	    $vacancyTable = $this->_conn->prefix . PropertyDatabase::TABLE_NAME;
	    
	    $this->_conn->update($vacancyTable, ['status' => $status ], [ 'id' => $this->id ]);
    }
}
