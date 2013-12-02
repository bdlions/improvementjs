<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth
*
* Author: Ben Edmunds
*		  ben.edmunds@gmail.com
*         @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Project_library
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;

	/**
	 * __construct
	 *
	 * @return void
	 * @author Ben
	 **/
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->config('ion_auth', TRUE);
		$this->ci->load->library('email');
		$this->ci->load->library('session');
		$this->ci->lang->load('ion_auth');
                $this->ci->load->model('ion_auth_model');
		$this->ci->load->model('project/project_model');
		$this->ci->load->helper('cookie');

		$this->ci->ion_auth_model->trigger_events('library_constructor');
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ci->project_model, $method) )
		{
			throw new Exception('Undefined method project_model::' . $method . '() called');
		}

		return call_user_func_array( array($this->ci->project_model, $method), $arguments);
	}


	
}