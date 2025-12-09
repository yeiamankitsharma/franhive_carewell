<?php

class AdminConsoleModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Load the database library
    }

    public function get_data()
    {
        // Write your select query here
        $query = $this->db->get('COURSES');
        return $query->result(); // Return the result
    }

    public function get_questions()
    {
        // Fetch questions from your database
        $this->db->order_by('QUESTION_ID', 'DESC');
        $query = $this->db->get('TEST_SERIES_QUESTIONS');
        return $query->result();
    }


    public function get_questions_in_test($test_id )
    {
        // Fetch questions from your database
        $query = "SELECT 
        TEST_SERIES_QUESTIONS.QUESTION_GROUP,TEST_SERIES_QUESTIONS.QUESTION_ID,TEST_SERIES_QUESTIONS.QUESTION_NAME,
        TEST_QUESTION_MAPPING.ID as `MAPPING_ID`,
        TEST_QUESTION_MAPPING.MAP_STATUS 
        FROM TEST_SERIES_QUESTIONS
        LEFT JOIN TEST_QUESTION_MAPPING ON TEST_QUESTION_MAPPING.QUESTION_ID = TEST_SERIES_QUESTIONS.QUESTION_ID AND TEST_QUESTION_MAPPING.TEST_ID =".$test_id ."
        ORDER BY QUESTION_ID DESC
                            ";

        $query = $this->db->query($query);
        return $query->result();
    }


    

    public function get_question_details($questionId)
    {
        // Fetch questions from your database
        $this->db->WHERE('QUESTION_ID', $questionId);

        $query = $this->db->get('TEST_SERIES_QUESTIONS');
        return $query->row_array();
    }


    public function save_question($data)
    {
        // Validate data before inserting
        if (is_array($data) && !empty($data)) {
            if ($this->db->insert('TEST_SERIES_QUESTIONS', $data)) {
                return true; // Successfully inserted
            } else {
                // Log or display the database error
                $error = $this->db->error(); // Get the error details
                log_message('error', 'Database Error: ' . $error['message']); // Log the error
                echo 'Database Error: ' . $error['message']; // Display the error (for debugging only)
                return false;
            }
        }
        echo 'Invalid data provided.'; // Display an error message for invalid data
        return false;
    }
    
    public function update_question($question_id, $data)
    {
        $this->db->where('QUESTION_ID', $question_id);
        $this->db->update('TEST_SERIES_QUESTIONS', $data);
    }
    


    public function delete_question($question_id)
    {
        // Delete the question from the database
        $this->db->where('QUESTION_ID', $question_id);
        $this->db->delete('TEST_SERIES_QUESTIONS'); // Change 'your table' to your actual table name
    }

    public function get_tests()
    {
        // Fetch questions from your database
        $this->db->where('IS_DEL !=', 1); // Use '!=' to exclude records where IS_DEL is equal to 1
        $this->db->order_by('TEST_ID', 'DESC');
        $query = $this->db->get('TEST_SERIES');
        return $query->result();
    }

    public function get_test_details($test_id)
    {
        // Fetch questions from your database
        $this->db->WHERE('TEST_ID', $test_id);

        $query = $this->db->get('TEST_SERIES');
        return $query->row_array();
    }


    public function get_tests_reports()
    {
        $this->db->select('TEST_USER_RESPONSE.*, TEST_SERIES.TEST_NAME, TEST_SERIES.TEST_TYPE,USERS.NAME');
        $this->db->from('TEST_USER_RESPONSE');
        $this->db->join('TEST_SERIES', 'TEST_SERIES.test_id = TEST_USER_RESPONSE.test_id', 'inner');
        $this->db->join('USERS', 'USERS.USER_ID = TEST_USER_RESPONSE.user_id', 'inner'); // Added join with USER table
        $this->db->order_by('TEST_USER_RESPONSE.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_users()
    {
        // Fetch questions from your database
        $this->db->order_by('USER_ID', 'DESC');
        $query = $this->db->get('USERS');
        return $query->result();
    }


    public function save_test($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $this->db->insert('TEST_SERIES', $data);
    }


    public function update_test_details($data, $test_id)
    {

        $this->db->where('TEST_ID', $test_id);
        $update_test_data = $this->db->update('TEST_SERIES', $data);

        if ($update_test_data) {
            return true;
        } else {
            return false; // Optionally handle the case where the update fails
        }
    }

    public function save_test_questing_mapping($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $add_qustn =  $this->db->insert('TEST_QUESTION_MAPPING', $data);
        $this->db->last_query();
        if ($add_qustn) {
            return true;
        }
    }

    public function save_test_user_mapping($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $add_qustn =  $this->db->insert('TEST_USER_ALLOCATION', $data);
        $this->db->last_query();
        if ($add_qustn) {
            return true;
        }
    }



    public function getTestResponses($test_id)
    {
        $query = "SELECT 
            utr.*, 
            ts.TEST_NAME, 
            ts.TEST_START_DATE, 
            ts.TEST_TYPE, 
            ts.TOTAL_QUESTIONS, 
            ts.TEST_DURATION,
            usr.EMAIL,
            usr.NAME,
            usr.PROFILE_PICTURE
        FROM TEST_USER_RESPONSE utr 
        INNER JOIN TEST_SERIES ts ON ts.TEST_ID = utr.test_id
        INNER JOIN USERS usr ON utr.USER_ID = usr.USER_ID
        WHERE utr.id = ? ";
    
        $query = $this->db->query($query, [$test_id]);
    
        if (!$query) {
            log_message('error', 'Database error: ' . $this->db->error()['message']); // Log error
            return false;
        }
    
        return $query->row_array();
    }
    

    public function get_userassigned_tests($user_id)
    {
        // Fetch questions from your database

        $query = "SELECT TEST_SERIES.*, TEST_USER_ALLOCATION.USER_ID,TEST_USER_ALLOCATION.MAPPING_ID,TEST_USER_ALLOCATION.IS_REMOVED
                    FROM TEST_SERIES 
                    LEFT JOIN TEST_USER_ALLOCATION on TEST_USER_ALLOCATION.TEST_ID = TEST_SERIES.TEST_ID AND TEST_USER_ALLOCATION.USER_ID = ".$user_id."
                  
                    ORDER BY TEST_SERIES.TEST_ID DESC
                    ";

        $query = $this->db->query($query);
        return $query->result();
    }

    public function update_user_test_eval($data)
    {

        $this->db->where('ID', $data['ID']);
        $update_eval_data = $this->db->update('TEST_USER_RESPONSE', $data);

        if ($update_eval_data) {
            return true;
        } else {
            return false; // Optionally handle the case where the update fails
        }
    }


    public function remove_user_test_mapping($mapping_id, $is_removed)
    {
        // Assuming $mapping_id is the ID of the mapping you want to remove

        $data = array(
            'is_removed' => $is_removed // Assuming 'is_removed' is the column you want to update
        );

        $this->db->where('MAPPING_ID', $mapping_id);
        $this->db->update('TEST_USER_ALLOCATION', $data);

        // Check if the update was successful
        $affected_rows = $this->db->affected_rows();

        if ($affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function delete_test($test_id)
    {
        // Update the is_del column in the database
        $this->db->where('TEST_ID', $test_id);
        $update_data = array('IS_DEL' => 1);
        $this->db->update('TEST_SERIES', $update_data);

        // Return true if the update was successful, false otherwise
        return $this->db->affected_rows() > 0;
    }
}
