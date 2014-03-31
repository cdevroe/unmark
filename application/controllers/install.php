<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  private function check_admin(){
      $this->load->library('session');
      $user = $this->session->userdata('user');

      if (! isset($user['admin']) || empty($user['admin'])) {
          header('Location: /');
          exit;
      }
  }

  public function index()
  {

    // See if there is a users table
    // Also, be sure there _are_ users.
    if ( !$this->db->table_exists('users') ) { // No user table

        // There is no users table, run migrations to make
        // sure that database is up-to-date.
        $this->load->library('migration');

        if ( ! $this->migration->current() )
        {
          show_error($this->migration->error_string());
          exit;
        }

    }

    // Make sure there is at least one user
    $this->db->from('users');
    $number_of_users = $this->db->count_all_results();

    // if users exist, redirect to homepage, else register
    $redirect = ($number_of_users > 0) ? '/' : '/register';
    header('Location: ' . $redirect);
    exit;
  }

  public function setup()
  {
    if ($this->db->table_exists('users') === true) {
      header('Location: /');
      exit;
    }

    $this->load->view('setup');
  }

  // Used to update from one version to another.
  public function upgrade()
  {
    $this->check_admin();
    $this->load->library('migration');

    if ( ! $this->migration->current() )
    {
      show_error($this->migration->error_string());
      exit;
    }
    exit(sprintf(_('Upgraded. Please <a href="%s">return home</a>.'), '/'));

  }

 }