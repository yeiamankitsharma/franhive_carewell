<?php

class User_test_model extends CI_Model {
    
     public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database(); // Load the database library

        // Check if the user session is set
        if (!$this->session->userdata('user') || !isset($this->session->userdata('user')['USER_ID'])) {
            // If not set, redirect to login
            redirect('login'); // Adjust 'login' to your actual login URL
        }
    }

    public function get_data() {
        // Write your select query here
        $this->db->select('*');
        $this->db->from('COURSES');
        $this->db->where('RECORD_STATUS', 0);
        $this->db->order_by('COURSE_ID', 'DESC');   
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result(); // Return the result
    }

    public function get_questions() {
        // Fetch questions from your database
        $query = $this->db->get('TEST_SERIES_QUESTIONS');
        return $query->result();
    }


    public function get_my_tests() {
        // Fetch the user ID from the session
      $user_id = $this->session->userdata('user')['USER_ID'];
    
        // Construct the SQL query using query binding to prevent SQL injection
        $qry = "
            SELECT 
                TEST_USER_ALLOCATION.TEST_ID,
                TEST_USER_ALLOCATION.MAPPING_ID,
                TEST_SERIES.TOTAL_QUESTIONS,
                TEST_SERIES.END_DATE, 
                TEST_SERIES.TEST_NAME,
                TEST_SERIES.TEST_START_DATE,
                TEST_USER_RESPONSE.IS_EVAL,
                TEST_USER_RESPONSE.IS_SUBMITTED,
                TEST_USER_RESPONSE.USER_RESPONSE
            FROM 
                TEST_USER_ALLOCATION
            LEFT JOIN 
                TEST_SERIES 
                ON TEST_SERIES.TEST_ID = TEST_USER_ALLOCATION.TEST_ID 
            LEFT JOIN 
                TEST_USER_RESPONSE 
                ON TEST_USER_RESPONSE.TEST_ID = TEST_USER_ALLOCATION.TEST_ID AND  TEST_USER_RESPONSE.USER_ID = ".$user_id ." 
            WHERE 
                TEST_USER_ALLOCATION.USER_ID = ? 
                AND TEST_SERIES.IS_DEL = 0
        ";
    
        // Execute the query with the user ID parameter
        $query = $this->db->query($qry, array($user_id));
        // echo $this->db->last_query(); die;
        // Return the results as an array
        return $query->result_array();
    }
    

    public function getTestDetails($test_id) {
      
        $query = "SELECT 
                TEST_SERIES.TOTAL_QUESTIONS,
                TEST_SERIES.TEST_NAME,
                TEST_SERIES.TEST_INSTRUCTIONS,
                TEST_TYPE,
                END_DATE,
                TEST_START_DATE
            FROM
                TEST_SERIES
            WHERE
                TEST_ID  =".$test_id."";
        $query = $this->db->query($query);
        return $query->row_array();
    }




    public function getTestQuestions($test_id) {
        // Fetch questions from your database
        $query = "select tsq.QUESTION_NAME, tsq.QUESTION_ID, 
        tsq.option_1,
        tsq.option_2,
        tsq.option_3,
        tsq.option_4
        
        from TEST_QUESTION_MAPPING tqm
        inner join TEST_SERIES_QUESTIONS tsq on tsq.QUESTION_ID = tqm.QUESTION_ID
        where TEST_ID =".$test_id."";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function saveUserResponse($user_id, $test_id, $user_response, $is_submitted) {
        // Define the data to be updated/inserted
        $data = array(
            'user_response' => $user_response,
            'is_submitted' => $is_submitted
        );
    
        // Check if a row with the same user_id and test_id exists
        $this->db->where('user_id', $user_id);
        $this->db->where('test_id', $test_id);
        $query = $this->db->get('TEST_USER_RESPONSE');
    
        if ($query->num_rows() > 0) {
            // Row exists, update it
            $this->db->where('user_id', $user_id);
            $this->db->where('test_id', $test_id);
            $this->db->update('TEST_USER_RESPONSE', $data);
        } else {
            // Row doesn't exist, insert a new one
            $data['user_id'] = $user_id;
            $data['test_id'] = $test_id;
            $this->db->insert('TEST_USER_RESPONSE', $data);
        }
    
        // Return true if an update or insert was successful
        return $this->db->affected_rows() > 0;
    }
    

    public function get_my_tests_reports($user_id) {        
        $this->db->select('TEST_USER_RESPONSE.*, TEST_SERIES.TEST_NAME,USERS.NAME');
        $this->db->from('TEST_USER_RESPONSE');
        $this->db->join('TEST_SERIES', 'TEST_SERIES.test_id = TEST_USER_RESPONSE.test_id', 'inner');
        $this->db->join('USERS', 'USERS.USER_ID = TEST_USER_RESPONSE.user_id', 'inner'); // Added join with USER table
        $this->db->where("TEST_USER_RESPONSE.USER_ID",$user_id);
        $this->db->order_by('TEST_USER_RESPONSE.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_my_courses() {
        // Fetch the user ID from the session
      $user_id = $this->session->userdata('user')['USER_ID'];
    
        // Construct the SQL query using query binding to prevent SQL injection
        $qry = "
           SELECT COURSES.*
            FROM USER_COURSE_MAPPING
            INNER JOIN COURSES on COURSES.COURSE_ID  = USER_COURSE_MAPPING.COURSE_ID
            WHERE 
                USER_COURSE_MAPPING.USER_ID = ? 
                AND COURSES.RECORD_STATUS = 0
        ";
    
        // Execute the query with the user ID parameter
        $query = $this->db->query($qry, array($user_id));
        // echo $this->db->last_query(); die;
        // Return the results as an array
        return $query->result_array();
    }

    public function get_user_by_email_or_mobile($email, $mobile) {
        return $this->db->where('EMAIL', $email)
                        ->or_where('PHONE', $mobile)
                        ->get('USERS')
                        ->row_array();
    }
    
    public function create_user($data) {
        $this->db->insert('USERS', $data);
        return $this->db->insert_id();
    }


    public function save_enrollment_form($data) {
        $insert = [
            'full_name' => $data['full_name'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'street_address' => $data['street_address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zip_code' => $data['zip_code'],
            'phone_number' => $data['phone_number'],
            'email_address' => $data['email_address'],

            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'emergency_contact_relationship' => $data['emergency_contact_relationship'],

            'program_id' => $data['program_id'],  // from select
            'training_month' => $data['program_start_month'],
            'training_year' => $data['training_year'],

            'total' => $data['total'],
            'deposit_amount' => $data['deposit_amount'],
            'for_training' => $data['for_training'],
            'deposit_paid_via' => $data['deposit_paid_via'],
            'paid_on' => $data['paid_on'],
            'balance_of' => $data['balance_of'],
            'due_date' => $data['due_date'],

            'agree_terms_1' => isset($data['agree_terms_1']) ? 1 : 0,
            'agree_terms_2' => isset($data['agree_terms_2']) ? 1 : 0,
            'agree_terms_3' => isset($data['agree_terms']) ? 1 : 0,

            'payment_option' => $data['payment_option'],
            'credit_card_number' => $data['credit_card_number'],
            'expiry_date' => $data['expiry_date'],
            'name_on_card' => $data['name_on_card'],
            'cvc' => $data['cvc'],

            'signature' => $data['signature'],
            'signature_date' => $data['signature_date'],
            'approved_by' => $data['approved_by'],
            'approval_date' => $data['approval_date']
        ];

        return $this->db->insert('ENROLL_AGREEMENT_DATA', $insert);
    }
    

    public function get_all_agreements() {
        // Fetch questions from your database
        $query = $this->db->get('ENROLL_AGREEMENT_DATA');
        return $query->result_array();
    }
    
}

?>