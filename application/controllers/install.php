<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->helper(array('url','form'));

    $this->load->database(); // Load Database for all methods

  }

  public function index()
  {

    // Step one: See if there is a database
  	$data['install_complete'] = $this->database_install();

  	$this->load->view( 'install', $data );

  }

  public function database_install()
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
    $this->load->library('plain_session', '', 'session');
    
    // Make sure there is at least one user
    $this->db->from('users');
    $number_of_users = $this->db->count_all_results();

    if ( $number_of_users > 0 ) { // There is at least one user
        return true;
    } else {
        return false;
    }

    return false;
  }

  // Used to update from one version to another.
  public function upgrade()
  { 
      $this->load->library('plain_session', '', 'session');
      // Logged in user id
      $userId = $this->session->userdata('userid');
      if (!empty($userId)) {
          $userFromDb = $this->db->get_where('users',array('user_id' => $userId));
          if($userFromDb->num_rows() > 0){
              if($this->is_admin($userFromDb->row())){
                  // Admin user logged in - run migrations if needed
                  $this->load->library('migration');
              
                  if ( ! $this->migration->current() )
                  {
                    show_error($this->migration->error_string());
                    exit;
                  }
                  exit('Upgraded. Please <a href="/">return home</a>.');
              }
          }
        exit('You need to be logged in as admin to upgrade');
      } else {
          // No logged user - redirect
          redirect('/');
      }
  }
  
  /**
   * Checks if given user is an admin
   * TODO Move function to more proper place (model? helper?)
   * @param unknown $user
   */
  public function is_admin($user)
  {
      // TODO Add admin flag in DB to handle this
      if(property_exists($user, 'admin')) {
          return $user->admin;
      } else {
        return $user->user_id == 1;
      }      
  }

 }