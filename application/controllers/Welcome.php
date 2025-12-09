<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */


	 public function __construct() {
        parent::__construct();
        $this->load->model('User_test_model'); // Load your model
    }

	public function index()
	{
		$data = $this->User_test_model->get_data(); // Call the method in your model
		
		$this->load->view('user_dashboard');
	}



	public function myTest() {		
        
        $data['my_tests'] = $this->User_test_model->get_my_tests();
		
		// echo "<pre>";
		// print_r( $data['my_tests'] );die;

        $this->load->view('user_test_listing', $data);
    }
	public function myTestDashboard() {		
		$test_id = $this->uri->segment(2);
		$data['test_details'] = $this->User_test_model->getTestDetails($test_id);
		$data['test_questions'] = $this->User_test_model->getTestQuestions($test_id);
	
		// Get current date and time
		$current_datetime = date("d F Y h:i a");
	
		// Convert test dates to timestamps
		$test_start_datetime = strtotime($data['test_details']['TEST_START_DATE']);
		$test_end_datetime = strtotime($data['test_details']['END_DATE']);
		$current_timestamp = strtotime($current_datetime);
	
		// Determine if the test is upcoming or expired
		if ($current_timestamp < $test_start_datetime) {
			$data['error_msg'] = "The test is upcoming.";
		} elseif ($current_timestamp > $test_end_datetime) {
			$data['error_msg'] = "The test has expired.";
		} else {
			$data['error_msg'] = ""; // No error, test is ongoing
		}
	
		// If error message is set, print it and stop execution
		if (!empty($data['error_msg'])) {
			echo $data['error_msg'];
			exit;
		}
		// echo "<pre>";
		// print_r($data);die;
		// Load the appropriate view if no error
		if ($data['test_details']['TEST_TYPE'] == 1 || $data['test_details']['TEST_TYPE'] == 3) {
			$this->load->view('user_test_mcq_dashboard', $data);
		} else {
			$this->load->view('user_test_dashboard', $data);
		}
	}
	

	public function myTestDashboardMcq() {		

		$test_id =  $this->uri->segment(2);
		$data['test_details'] = $this->User_test_model->getTestDetails($test_id);
        $data['test_questions'] = $this->User_test_model->getTestQuestions($test_id);
		// echo "<pre>";
		// print_r(  $data['test_questions'] );die;
       
    }

	public function thankYouPage()
	{
		
		$this->load->view('thankyou_test_submit');
	}

	public function saveTestResponse(){

		$json_data = $this->input->raw_input_stream; // Get raw input stream
        $data = json_decode($json_data, true); // Decode JSON data into an associative array
		$user_data = $this->session->userdata('user');
		
		$test_id = $data['test_id'];
		$is_submitted = $data['is_submitted'];
		$responses = json_encode($data['responses'],true);
		
		// print_r($responses);die;
		$save_responses = $this->User_test_model->saveUserResponse($user_data['USER_ID'],$test_id ,$responses,$is_submitted);
		// var_dump($save_responses);die;
		if($save_responses)
		return true;
	}

	public function myTestReports() {	
		
		$user_data = $this->session->userdata('user');
        
        $data['my_test_reports'] = $this->User_test_model->get_my_tests_reports($user_data['USER_ID']);
		
		// echo "<pre>";
		// print_r( $data['my_test_reports'] );die;

        $this->load->view('user_reports_listings', $data);
    }


	public function myCourses() {		
        
        $data['my_courses'] = $this->User_test_model->get_my_courses();
		
		// echo "<pre>";
		// print_r( $data['my_tests'] );die;

        $this->load->view('user_course_listing', $data);
	}


	public function paymentAgreement() {		
			
		
		$data['all_courses'] = $this->User_test_model->get_data();
		
		// echo "<pre>";
		// print_r( $data['all_courses'] );die;

		$this->load->view('payment_agreement/add_agreement', $data);
	}

	public function enroll_data_submit() {
     
        $saved = $this->User_test_model->save_enrollment_form($this->input->post());

        echo $saved ? "Form submitted successfully!" : "Error saving form.";
    }



	public function paymentAgreementList() {		
        
        $data['all_agreements'] = $this->User_test_model->get_all_agreements();
		
		// echo "<pre>";
		// print_r( $data['all_agreements'] );die;

        $this->load->view('payment_agreement/payment_agreement_list', $data);
    }
}
