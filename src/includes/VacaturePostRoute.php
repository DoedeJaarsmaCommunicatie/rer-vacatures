<?php
namespace PropertyPeople\Includes;


use PropertyPeople\Includes\Models\OpenVacature;
use PropertyPeople\Includes\Models\Vacature;

class VacaturePostRoute
{
	private $request;
	
	public function __construct()
	{
		add_action('admin_post_post_vacature', [$this, 'handleRequest']);
		add_action('admin_post_nopriv_post_vacature', [$this, 'handleRequest']);
		
		add_action('admin_post_post_open_soll', [$this, 'handleOpenRequest']);
		add_action('admin_post_nopriv_post_open_soll', [$this, 'handleOpenRequest']);
	}
	
	public function handleRequest()
	{
		$this->request = $_REQUEST;
		
		$vacature = new Vacature($_REQUEST);
		
		if (isset($_FILES['cv'])) {
			$cv = $_FILES['cv'];
			$uploadedCV = wp_handle_upload($cv, ['test_form' => false]);
		}
		
		if ($uploadedCV && !isset($uploadedCV['error'])) {
			$vacature->file = $uploadedCV['url'];
		}
		
		try {
			$vacature->save();
		} catch (\Exception $exception) {
			die($exception->getMessage());
		}

		wp_mail(
			'solliciteren@realestaterecruiters.nl',
			'Nieuwe sollicitatie',
			'Er is een nieuwe sollicitatie. Bekijk deze online op: https://realestaterecruiters.nl/wp-admin/admin.php?page=vacancy-overview',
			[
				'From: Real Estate Recruiters <info@realestaterecruiters.nl>'
			]
		);

		$text = sprintf("Beste %s, \r\n \r\n Bedankt voor je sollicitatie. Wij nemen spoedig contact met je op. \r\n \r\n Met vriendelijke groet, \r\n Real Estate Recruiters", $vacature->firstname);
		
		wp_mail(
			$vacature->email,
			'Bedankt voor je sollicitatie',
			$text,
			[
				'From: Real Estate Recruiters <info@realestaterecruiters.nl>'
			]
			);
		
		if (isset($_REQUEST['type']) && $_REQUEST['type'] === 'json') {
			wp_send_json($vacature, 201);
		}
		
		status_header(201);
		wp_safe_redirect(
			add_query_arg(
				[
					'status'	=> 'success',
					'action'	=> 'sollicitatie'
				],
			 	'/bedankt-voor-je-sollicitatie/'
			)
		);
	}
	
	public function handleOpenRequest()
	{
		$this->request = $_REQUEST;
		
		$openVacature = new OpenVacature($this->request);
		
		if (isset($_FILES['cv'])) {
			$cv = $_FILES['cv'];
			$uploadedCV = wp_handle_upload($cv, ['test_form' => false]);
		}
		
		if ($uploadedCV && !isset($uploadedCV['error'])) {
			$openVacature->file = $uploadedCV['url'];
		}
		
		try {
			$openVacature->save();
		} catch (\Exception $exception) {
			die($exception->getMessage());
		}

		wp_mail(
			'solliciteren@realestaterecruiters.nl', 
			'Nieuwe open sollicitatie',
			'Er is een nieuwe open sollicitatie. Bekijk deze online op: https://realestaterecruiters.nl/wp-admin/admin.php?page=vacancy-overview',
			[
				'From: Real Estate Recruiters <info@realestaterecruiters.nl>'
			]
		);

		$text = sprintf("Beste %s, \r\n \r\n Bedankt voor je sollicitatie. Wij nemen spoedig contact met je op. \r\n \r\n Met vriendelijke groet, \r\n Real Estate Recruiters", $openVacature->firstname);
		
		wp_mail(
			$openVacature->email,
			'Bedankt voor je sollicitatie',
			$text,
			[
				'From: Real Estate Recruiters <info@realestaterecruiters.nl>'
			]
			);
		
		if (isset($_REQUEST['type']) && $_REQUEST['type'] === 'json') {
			wp_send_json($openVacature, 201);
		}
		
		status_header(201);
		wp_safe_redirect(
			add_query_arg(
				[
					'status'	=> 'success',
					'action'	=> 'open-sollicitatie'
				],
				'/bedankt-voor-je-open-sollicitatie/'
			)
		);
	}
	
}
