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


		if (strpos($_REQUEST['voornaam'], 'gutenmorgen') !== false) {
			wp_safe_redirect(wp_get_referer());
			return;
		}

		if (isset($_REQUEST['tussenvoegsel']) && $_REQUEST['tussenvoegsel'] !== '') {
			wp_safe_redirect(wp_get_referer());
			return;
		}


		$vacature = new Vacature($_REQUEST);

		if (isset($_FILES['cv'])) {
			$cv = $_FILES['cv'];
            add_filter('upload_dir', [ $this, 'custom_upload_dir']);
            $uploadedCV = wp_handle_upload($cv, ['test_form' => false]);

            if ($uploadedCV && !isset($uploadedCV['error'])) {
                $month = date('m');
                $year = date('Y');
                $vacature->file = str_replace("/$year/$month", '', $uploadedCV['url']);
            }

            remove_filter('upload_dir', [ $this, 'custom_upload_dir']);
		}

		try {
			$vacature->save();
		} catch (\Exception $exception) {
		    wp_mail(
		        'support@doedejaarsma.nl',
                'Problemen sollicitatie',
                $exception->getMessage(),
                [
                    'From: Real Estate Recruiters <info@realestaterecruiters.nl>'
                ]
            );
		    wp_safe_redirect(wp_get_referer());
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
			add_filter('upload_dir', [ $this, 'custom_upload_dir']);
			$uploadedCV = wp_handle_upload($cv, ['test_form' => false]);

            if ($uploadedCV && !isset($uploadedCV['error'])) {
                $month = date('m');
                $year = date('Y');
                $openVacature->file = str_replace("/$year/$month", '', $uploadedCV['url']);
            }

			remove_filter('upload_dir', [ $this, 'custom_upload_dir']);
		}


		try {
			$openVacature->save();
		} catch (\Exception $exception) {
            wp_mail(
                'support@doedejaarsma.nl',
                'Problemen sollicitatie',
                $exception->getMessage(),
                [
                    'From: Real Estate Recruiters <info@realestaterecruiters.nl>'
                ]
            );
            wp_safe_redirect(wp_get_referer());
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


	public function custom_upload_dir($dir_data) {
	    $custom_dir = 'CV';

	    return [
            'path' => $dir_data[ 'basedir' ] . '/' . $custom_dir,
            'url' => $dir_data[ 'url' ] . '/' . $custom_dir,
            'subdir' => '/' . $custom_dir,
            'basedir' => $dir_data[ 'error' ],
            'error' => $dir_data[ 'error' ],
        ];
    }
}

