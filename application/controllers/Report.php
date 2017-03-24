<?php

class Report extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model( $this->config->item('rpt_models') . 'report_m');
		$this->validateUserReport();
//		$this->ion_auth->isLogin();
	}

	public function show($id){
		$this->report_m->loadReport($id);
		session_write_close();
		$data = $this->report_m->dataGrid($id);
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	public function grid($id){
		$this->report_m->loadReport($id);
		$table = $this->report_m->bodyGrid($id);
		$report = $this->report_m->getReportData();
		$breadcrumb = $this->getBreadCrumb($report);
		$views = $this->config->item('rpt_template');
		$template = is_null($report->template) ? $views . 'index' : $report->template;
		$data = array(
			'title_page' => $report->title,
			'main_content' => $views . 'grid',
            'table' => $table,
			'report' => $report,
            'breadcrumb' => $breadcrumb,
            'data_url' => site_url('report/show/'.$id),
			'custom_js_files' => array(base_url('assets/js/report/main.js')),
		);
		$this->load->view($template, $data);
	}


    public function download($id){
		$this->report_m->loadReport($id);
		session_write_close();
        $params = array('model' => $this->report_m, 'idReport' => $id);
        $this->load->library('Large_Download', $params);
        return $this->large_download->download();
    }

    private function validateUserReport(){
        $this->load->library('CI_URI');
        $idReport = $this->uri->segment(3, null);
        $report = $this->report_m->find($idReport);
//        $this->ion_auth->validateUserProject($_SESSION['user_id'], $report->idProject);
    }

	private function getBreadCrumb($report){
		return array(
			array(
				'title'=> $this->lang->line('home'),
				'link'=> site_url()
			),array(
				'title'=> $report->project,
				'link'=> site_url('project/name/'.url_title($report->slug))
			), array(
				'title' => $report->title
			));
	}
}