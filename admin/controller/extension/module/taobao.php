<?php
class ControllerExtensionModuleTaobao extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/taobao');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_taobao', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/taobao', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/taobao', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_taobao_status'])) {
			$data['module_taobao_status'] = $this->request->post['module_taobao_status'];
		} else {
			$data['module_taobao_status'] = $this->config->get('module_taobao_status');
		}

		if (isset($this->request->post['module_taobao_appkey'])) {
			$data['module_taobao_appkey'] = $this->request->post['module_taobao_appkey'];
		} else {
			$data['module_taobao_appkey'] = $this->config->get('module_taobao_appkey');
		}

		if (isset($this->request->post['module_taobao_appsecret'])) {
			$data['module_taobao_appsecret'] = $this->request->post['module_taobao_appsecret'];
		} else {
			$data['module_taobao_appsecret'] = $this->config->get('module_taobao_appsecret');
		}

		if (isset($this->request->post['module_taobao_userid'])) {
			$data['module_taobao_userid'] = $this->request->post['module_taobao_userid'];
		} else {
			$data['module_taobao_userid'] = $this->config->get('module_taobao_userid');
		}

		if (isset($this->request->post['module_taobao_zone'])) {
			$data['module_taobao_zone'] = $this->request->post['module_taobao_zone'];
		} else {
			$data['module_taobao_zone'] = $this->config->get('module_taobao_zone');
		}

		if (isset($this->request->post['module_taobao_size'])) {
			$data['module_taobao_size'] = $this->request->post['module_taobao_size'];
		} else if($this->config->get('module_taobao_size')){
			$data['module_taobao_size'] = $this->config->get('module_taobao_size');
		} else {
			$data['module_taobao_size'] = '20';
		}

		if (isset($this->request->post['module_taobao_page'])) {
			$data['module_taobao_page'] = $this->request->post['module_taobao_page'];
		} else if($this->config->get('module_taobao_page')){
			$data['module_taobao_page'] = $this->config->get('module_taobao_page');
		} else {
			$data['module_taobao_page'] = '1';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/taobao', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/taobao')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}