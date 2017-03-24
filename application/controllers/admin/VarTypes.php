<?php
/**
 * Created by PhpStorm.
 * User: LANICAMA
 * Date: 24/02/2016
 * Time: 05:33 PM
 */

class VarTypes extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->ion_auth->isLogin();
    }

    /**
     * CRUD configure  for table server_connection
     * @return html
     */
    public function index()
    {
        try{
            $crud = new grocery_CRUD();

            $crud->unset_delete();//grid

            $crud->set_table('var_type');
            $crud->set_subject('Types of Var for Reports');
            $output = $crud->render();
            $output->title_page = $this->lang->line('admin_var_type_title');
            $output->main_content =  'admin';
            $this->load->view('template/index',$output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

}