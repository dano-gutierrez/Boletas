<?php

class ctrLogin extends MX_Controller {

	function __construct () {
		parent::__construct();
		date_default_timezone_set('America/Mexico_City');
	}

	public function index () {
		$data['controller'] = Modules::run('tools/ctrdb/get_self');
		if ($this->session->userdata('id')) {
			redirect('home');
		} else {
			print $this->load->view('vwlogin', $data, TRUE);
		}
	}

	public function login () {
		$data = $this->security->xss_clean($this->input->post('data'));
		$params = array();
		parse_str($data, $params);
		$data_login = Modules::run('tools/ctrdb/get_data_single_row', 'id, nombre, username, escuelas_id, ciclos_id', 'usuarios_boletas',
			array(
				'username' => $params['username'],
				'pwd' => hash('sha512', $params['pwd']),
				'active' => 1
			)
		);

		if ($data_login) {
			if (array_key_exists('remember', $params)) {
				$sess['new_expiration'] = 60 * 60 * 24 * 1;//1 day(s)
				$this->session->sess_expiration = $sess['new_expiration'];
				$this->session->set_userdata($sess);
			}
			$this->session->set_userdata("data_temp", array());
			$this->session->set_userdata("current_test", 0);
			$this->session->set_userdata($data_login);
			Modules::run('tools/ctrdb/log_activity', 'login', $data_login['id']);
			print json_encode(
				array(
					"success" => TRUE,
					"type_msg" => "success",
					"title" => "Éxito",
					"msg" => "Sesión iniciada correctamente."
				)
			);
		} else {
			print json_encode(
				array(
					"success" => FALSE,
					"type_msg" => "danger",
					"title" => "Error",
					"msg" => "<h4>DATOS DE INGRESO INCORRECTOS</h4>Favor de verificar que el número de empleado y/o la contraseña sean válidos."
				)
			);
		}
	}

}

?>