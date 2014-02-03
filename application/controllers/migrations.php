<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CLI controller for running migrations without authentication
 * @author kip9
 *
 */
class Migrations extends Plain_Controller
{
    // NO API ROUTE

    public function __construct()
    {
        parent::__construct();
        parent::redirectIfNotCommandLine();
    }

    /**
     * Update DB to most current version
     */
    public function latest()
    {
        // Run migrations to make
        // sure that database is up-to-date.
        $this->load->library('migration');

        if ( ! $this->migration->current() )
        {
          show_error($this->migration->error_string());
          exit(-1);
        } else{
          echo "Migrations run successfully\n";
          exit(0);
        }
    }

    /**
     * Update DB to specified version
     */
    public function version($version)
    {
        if(!empty($version) && is_numeric($version)){
            // Run migrations to make
            // sure that database is up-to-date.
            $this->load->library('migration');

            if ( ! $this->migration->version($version) )
            {
                show_error($this->migration->error_string());
                exit(-1);
            } else{
                echo "Migrations run successfully for version $version\n";
                exit(0);
            }
        }
    }

}