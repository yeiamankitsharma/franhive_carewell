<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

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

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('User_Model');
        $this->load->model('Permission_Model');
        $this->load->model('LeadModel');
        $this->load->model('LeadTaskModel');
        $this->load->model('KnowledgeCenterModel');
        $this->load->model('Client_Model');
        $this->load->model('LandingPage_Model');
        $this->load->library('session');
    }

    public function index()
    {
        // ---------- keep your existing auth/permission flow ----------
        $permissions = $this->session->userdata('permissions');
    
        if (!$this->session->userdata('logged_in')) {
            redirect('login'); // same as before
        }
    
        if (!in_array('1', explode(',', $permissions))) {
            redirect('my-courses'); // same as before
        } else {
    
            // ---------- keep: fetch latest Welcome Note Text from BASIC_CONFIG ----------
            $welcomeConfig = $this->db->select('WELCOME_NOTE_TEXT')
                ->from('BASIC_CONFIG')
                ->order_by('ID', 'DESC')
                ->limit(1)
                ->get()
                ->row_array();
    
            $welcome_note_text = $welcomeConfig['WELCOME_NOTE_TEXT'] ?? null;
    
            // keep: store in session (even though we also pass to view)
            $this->session->set_userdata('welcome_note_text', $welcome_note_text);
    
            // ---------- NEW: subdomain detection (non-breaking) ----------
            $host      = $this->input->server('HTTP_HOST');
            $parts     = explode('.', (string)$host);
            $subdomain = (count($parts) >= 3) ? $parts[0] : '';
            if (!defined('SUBDOMAIN')) {
                // define only if not already defined elsewhere
                define('SUBDOMAIN', $subdomain);
            }



        $data['recently_leads'] = $this->LeadModel->get_recent_leads();
        // $data['recently_task'] = $this->LeadModel->get_recent_tasks();
    
        $recently_leads = $this->LeadModel->get_recent_leads();

        // recent tasks for dashboard table (still 10)
    $data['recently_task'] = $this->LeadModel->get_recent_tasks();

    // KPI: Tasks created in last 30 days
    $tasks_30d = $this->LeadModel->count_tasks_last_30_days();

        // Map to the view’s table structure (fill missing fields with “-” for now)
        $data['recent_leads'] =   $data['recently_leads'];
    
        // 2) KPI: New Leads (Last 30 Days)
        $leads_30d = $this->LeadModel->count_leads_last_30_days();

        $total_clients = $this->Client_Model->getTotalClients();

       
        $all_course_lesson = $this->KnowledgeCenterModel->get_all_training_course_lessons();

        $data['get_all_users'] = $this->User_Model->get_all_users();

        // echo "<pre>";
        // print_r( $data['recently_task']);die;

        // Top performers (compact list)
        $data['top_agents'] = [
            ['name' => ' EYD Admin',   'score' => 98, 'leads' => 54],
            ['name' => 'Reign Martinez',     'score' => 94, 'leads' => 49],
            ['name' => 'Maris Sorpresa', 'score' => 91, 'leads' => 44],
            ['name' => 'Barinderjeet Kaur',   'score' => 90, 'leads' => 42],
        ];

        $all_landing_pages = $this->LandingPage_Model->get_all_landingpages();


       
    
            // KPIs
            $data['kpis'] = [
                ['label' => 'Total Active Users',     'value' => count( $data['get_all_users']),  'icon' => 'ti-user',   'delta' => '+12%'],
                ['label' => 'Active Landing Pages',       'value' => count($all_landing_pages) ,  'icon' => 'ti-check-box',  'delta' => '-8%'],
                ['label' => 'Active Clients',        'value' => $total_clients, 'icon' => 'ti-briefcase',  'delta' => '+40%'],
                ['label' => 'Total Course Lessons', 'value' => count($all_course_lesson), 'icon' => 'ti-target',     'delta' => '+0.7%'],
            ];
    

            // $data['all_leads'] = $this->LeadModel->get_all_leads();

            // =================== Leads Funnel (Dynamic) ===================
            $leads = $this->LeadModel->get_all_leads();

            // Initialize counters
            $funnelCounts = [
                'Captured'  => 0,
                'Visited'   => 0,
                'Qualified' => 0,
                'Won'       => 0,
            ];

            // Map statuses to labels
            $statusMap = [
                '0'  => 'Captured',
                '-1' => 'Visited',
                '1'  => 'Qualified',
                '2'  => 'Won',
            ];

            // Count leads by LEAD_STATUS
            foreach ($leads as $lead) {
                $status = (string)($lead['LEAD_STATUS'] ?? '');
                if (isset($statusMap[$status])) {
                    $label = $statusMap[$status];
                    $funnelCounts[$label]++;
                }
            }

            // Build final array for chart
            $data['leads_funnel'] = [
                'labels' => array_keys($funnelCounts),
                'values' => array_values($funnelCounts),
            ];

    
            // Tasks status
            $data['tasks_status'] = [
                'labels' => ['Open', 'In Progress', 'Blocked', 'Completed', 'Overdue'],
                'values' => [58, 73, 11, 214, 9],
            ];
    
            // Clients growth (12 months)
            $data['clients_growth'] = [
                'months' => ['Dec','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov'],
                'active' => [280,302,318,333,351,362,374,389,395,402,407,412],
                'new'    => [22,25,21,19,26,18,20,24,17,15,13,12],
                'churn'  => [6,3,5,4,8,5,8,9,10,5,8,7],
            ];
    
            // Landing pages performance
            // $data['landing_pages'] = [
            //     'pages'       => ['/consultation','/ebook','/webinar','/demo','/offer'],
            //     'visits'      => [8200, 4600, 5200, 3900, 2800],
            //     'conversions' => [480, 365, 410, 295, 145],
            // ];
    
            // // Knowledge Center – views (7d)
            // $data['kc_views'] = [
            //     'days'  => ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'],
            //     'views' => [420, 388, 512, 601, 558, 640, 690],
            // ];
    
            // Test Management – avg score by test
            $data['tests'] = [
                'names'  => ['NLP Basics','Advanced NLP','Quiz A','Quiz B','Final Eval'],
                'scores' => [78, 67, 84, 72, 81],
            ];
    
            
    
            // Recent leads table
            // $data['recent_leads'] = [
            //     ['name'=>'Aditi Sharma', 'source'=>'Webinar',  'stage'=>'Qualified','owner'=>'Neil',    'created'=>'2025-11-05'],
            //     ['name'=>'Rohit Mehra',  'source'=>'LP: Demo', 'stage'=>'Proposal', 'owner'=>'Ren',     'created'=>'2025-11-05'],
            //     ['name'=>'Julia Thomas', 'source'=>'Ebook',    'stage'=>'Captured', 'owner'=>'Garrett', 'created'=>'2025-11-04'],
            //     ['name'=>'Karan Verma',  'source'=>'Referral', 'stage'=>'Won',      'owner'=>'Callie',  'created'=>'2025-11-03'],
            //     ['name'=>'Sneha Kapoor', 'source'=>'LP: Offer','stage'=>'Visited',  'owner'=>'Neil',    'created'=>'2025-11-03'],
            // ];
    
            // pass welcome note to view (unchanged behavior)
            $data['welcome_note_text'] = $welcome_note_text;
    
            // ---------- keep: render your existing/new dashboard view name ----------
            // NOTE: your view is spelled "dashbaord_new" in your snippet; keeping it unchanged.
            $this->load->view('dashbaord_new', $data);
        }
    }
    
    



    // public function addTest()
    // {
    // 	$this->load->view('admin_console');
    // }


    public function getAllQuestions()
    {

        $data['questions'] = $this->AdminConsoleModel->get_questions();

        $this->load->view('test_questions_listing', $data);
    }


    public function addQuestion()
{
    // Check if the form is submitted
    if ($this->input->post()) {

        // Get sanitized form data
        $questionData = [
            'QUESTION_NAME'   => $this->input->post('question_name', true),
            'QUESTION_MARKS'  => $this->input->post('question_marks', true),
            'QUESTION_TYPE'   => $this->input->post('question_type', true),
            'QUESTION_GROUP'  => $this->input->post('question_group', true),
            'option_1'        => $this->input->post('option_1', true),
            'option_2'        => $this->input->post('option_2', true),
            'option_3'        => $this->input->post('option_3', true),
            'option_4'        => $this->input->post('option_4', true),
            'is_correct_1'    => $this->input->post('is_correct_1') ? 1 : 0,
            'is_correct_2'    => $this->input->post('is_correct_2') ? 1 : 0,
            'is_correct_3'    => $this->input->post('is_correct_3') ? 1 : 0,
            'is_correct_4'    => $this->input->post('is_correct_4') ? 1 : 0,
        ];

        // Save the question using the model
        if ($this->AdminConsoleModel->save_question($questionData)) {
            $this->session->set_flashdata('success', 'Question added successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to add the question. Please try again.');
        }

        // Redirect to the question listing page
        redirect('questions'); // Change this URL as per your route
    }

    // Load the 'Add Question' view
    $this->load->view('add_question');
}



    public function editQuestion()
    {
        $question_id = $this->uri->segment(2);

        $data['questionData'] = $this->AdminConsoleModel->get_question_details($question_id);
        // echo "<pre>";
        // print_r( $questions_details);die;
        $this->load->view('edit_question_page', $data);
    }


    public function update_question()
{
    // Check if the form is submitted
    if ($this->input->post()) {
        // Get form data
        $question_id    = $this->input->post('question_id');
        $question_name  = $this->input->post('question_name');
        $question_marks = $this->input->post('question_marks');
        $question_type  = $this->input->post('question_type');

        // Options (default to empty for safety)
        $option_1 = $this->input->post('option_1') ?? '';
        $option_2 = $this->input->post('option_2') ?? '';
        $option_3 = $this->input->post('option_3') ?? '';
        $option_4 = $this->input->post('option_4') ?? '';

        // Correct option checkboxes (default 0 if unchecked)
        $is_correct_1 = $this->input->post('is_correct_1') ? 1 : 0;
        $is_correct_2 = $this->input->post('is_correct_2') ? 1 : 0;
        $is_correct_3 = $this->input->post('is_correct_3') ? 1 : 0;
        $is_correct_4 = $this->input->post('is_correct_4') ? 1 : 0;

        // If Question Type is NOT MCQ, clear options and correct flags
        if ($question_type != 1) {
            $option_1 = $option_2 = $option_3 = $option_4 = '';
            $is_correct_1 = $is_correct_2 = $is_correct_3 = $is_correct_4 = 0;
        }

        // Prepare data for DB
        $data = array(
            'QUESTION_NAME'   => $question_name,
            'QUESTION_MARKS'  => $question_marks,
            'QUESTION_TYPE'   => $question_type,
            'option_1'        => $option_1,
            'option_2'        => $option_2,
            'option_3'        => $option_3,
            'option_4'        => $option_4,
            'is_correct_1'    => $is_correct_1,
            'is_correct_2'    => $is_correct_2,
            'is_correct_3'    => $is_correct_3,
            'is_correct_4'    => $is_correct_4
        );

        // Update in DB
        $this->AdminConsoleModel->update_question($question_id, $data);

        // Redirect to the question listing page
        redirect('questions'); 
    } else {
        // Handle direct access without POST
        redirect('edit-question/' . $this->input->post('question_id')); 
    }
}

    


    public function delete_question($question_id)
    {

        // Check if the question ID is provided
        if ($question_id) {
            // Call the model function to delete the question
            $this->AdminConsoleModel->delete_question($question_id);

            // Redirect to the question listing page or show a success message
            redirect('questions'); // Change this URL as needed
        } else {
            // If the question ID is not provided, you may want to handle it accordingly
            // For example, redirect to the question listing page with an error message
            redirect('questions'); // Change this URL as needed
        }
    }

    public function save_question()
    {
        // Retrieve form data using $this->input->post('field_name');
        $question_name = $this->input->post('question_name');
        $total_marks = $this->input->post('question_marks');
        $question_type = $this->input->post('question_type');
        $question_group = $this->input->post('question_group');

        // Save the data to the database using your model
        $data = array(
            'QUESTION_NAME' => $question_name,
            'QUESTION_MARKS' => $total_marks,
            'QUESTION_TYPE' => $question_type,
            'QUESTION_GROUP' => $question_group
        );

        $this->AdminConsoleModel->save_question($data);

        redirect('questions');
    }



    public function getAllTests()
    {

        $data['tests'] = $this->AdminConsoleModel->get_tests();

        $this->load->view('test_listing', $data);
    }


    public function getAllTestsReports()
    {

        $data['tests'] = $this->AdminConsoleModel->get_tests_reports();

        // echo "<pre>";
        // print_r(  $data['tests'] );die;

        $this->load->view('test_reports_listings', $data);
    }


    public function getAllTestsAllocation()
    {
        $user_id = $this->uri->segment(2);
        $data['tests'] = $this->AdminConsoleModel->get_userassigned_tests($user_id);
        // $data['assigned_tests'] = $this->AdminConsoleModel->get_tests();

        // echo "pre";
        // print_r(  $data['tests'] );die;

        $this->load->view('test_allocation_listing', $data);
    }

    public function addTest()
    {
        $this->load->view('add_test');
    }

    public function editTest()
    {
        $test_id = $this->uri->segment(2);

        $data['testData'] = $this->AdminConsoleModel->get_test_details($test_id);
        // echo "<pre>";
        // print_r(   $data['testData']);die;

        $this->load->view('edit_test_page', $data);
    }


    public function save_test()
    {
        // Retrieve form data using $this->input->post('field_name');
        $test_name = $this->input->post('test_title');
        $total_marks = $this->input->post('total_marks');
        $total_questions = $this->input->post('total_questions');
        $question_type = $this->input->post('question_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $test_type = $this->input->post('test_type');
        $test_instructions = $this->input->post('test_instructions');



        // Save the data to the database using your model
        $data = array(
            'TEST_NAME' => $test_name,
            'TOTAL_QUESTIONS' => $total_questions,
            'TOTAL_MARKS' => $total_marks,
            'TEST_START_DATE' => $start_date,
            'END_DATE' => $end_date,
            'TEST_TYPE' => $test_type,
            'TEST_INSTRUCTIONS' => $test_instructions
        );

        $this->AdminConsoleModel->save_test($data);

        redirect('test');
    }

    public function update_test()
    {
        // Retrieve form data using $this->input->post('field_name');
        $test_name = $this->input->post('test_title');
        $total_marks = $this->input->post('total_marks');
        $total_questions = $this->input->post('total_questions');
        $question_type = $this->input->post('question_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $test_type = $this->input->post('test_type');
        $test_instructions = $this->input->post('test_instructions');




        // Save the data to the database using your model
        $data = array(
            'TEST_NAME' => $test_name,
            'TOTAL_QUESTIONS' => $total_questions,
            'TOTAL_MARKS' => $total_marks,
            'TEST_START_DATE' => $start_date,
            'END_DATE' => $end_date,
            'TEST_TYPE' => $test_type,
            'TEST_INSTRUCTIONS' => $test_instructions
        );
        // echo "<pre>";
        // print_r( $data);
        // die;
        $test_id =  $this->input->post('test_id');
        $this->AdminConsoleModel->update_test_details($data, $test_id);

        redirect('test');
    }

    public function questionsList()
    {

        $data['questions'] = $this->AdminConsoleModel->get_questions();

        $this->load->view('add-test-question', $data);
    }

    public function questionsListTest()
    {
        $test_id =  $this->uri->segment(2);
        $data['questions'] = $this->AdminConsoleModel->get_questions_in_test($test_id );

        $this->load->view('add-test-question', $data);
    }



    public function addQuestionTest()
    {

        $question_id = $this->input->post('question_id');
        $test_id = $this->input->post('test_id');

        // Save the data to the database using your model
        $data = array(
            'QUESTION_ID' => $question_id,
            'TEST_ID' => $test_id,
        );

        $add_question = $this->AdminConsoleModel->save_test_questing_mapping($data);

        if ($add_question) {
            echo "Added Question in test";
        }

        // redirect('test');
    }


    public function testAllocation()
    {

        $data['users'] = $this->AdminConsoleModel->get_all_users();
        // echo "<pre>";
        // print_r(  $data['users']);die;

        $this->load->view('test_user_allocation', $data);
    }

    public function testEval()
{
    $test_id = $this->uri->segment(2);
    $data['user_data'] = $this->AdminConsoleModel->getTestResponses($test_id);

    if (empty($data['user_data']) || !isset($data['user_data']['TEST_TYPE'])) {
        $data['user_response'] = $data['user_data'];
        $this->load->view('test_eval', $data);
        return;
    }

    $testType = (int) $data['user_data']['TEST_TYPE'];

    // === TYPE 1 Test ===
    if ($testType === 1) {
        $responses = !empty($data['user_data']['USER_RESPONSE'])
            ? json_decode($data['user_data']['USER_RESPONSE'], true)
            : [];

        $most_likely_counts = array_fill_keys(['option_1', 'option_2', 'option_3', 'option_4'], 0);
        $least_likely_counts = $most_likely_counts;

        if (!empty($responses) && is_array($responses)) {
            foreach ($responses as $response) {
                if (!empty($response['most_likely']) && isset($most_likely_counts[$response['most_likely']])) {
                    $most_likely_counts[$response['most_likely']]++;
                }
                if (!empty($response['least_likely']) && isset($least_likely_counts[$response['least_likely']])) {
                    $least_likely_counts[$response['least_likely']]++;
                }
            }
        }

        $data['options_json']       = json_encode(array_keys($most_likely_counts));
        $data['most_likely_json']   = json_encode(array_values($most_likely_counts));
        $data['least_likely_json']  = json_encode(array_values($least_likely_counts));
        $data['most_likely_counts'] = $most_likely_counts;
        $data['least_likely_counts'] = $least_likely_counts;
        $data['responses']          = $responses;

        $this->load->view('mcq_test_report', $data);
        return;
    }

    if ($testType === 2) {
        // Decode user responses
        $responses = json_decode($data['user_data']['USER_RESPONSE'], true);

        // echo "<pre>";
        // print_r( $responses );die;
    
        // Extract QUESTION_IDs from the responses
        $questionIds = [];
        foreach ($responses as $resp) {
            if (!empty($resp['question']['QUESTION_ID'])) {
                $questionIds[] = $resp['question']['QUESTION_ID'];
            }
        }
    
        if (!empty($questionIds)) {
            // Fetch questions from DB using these IDs
            $this->db->select('QUESTION_ID, QUESTION_NAME, QUESTION_MARKS');
            $this->db->where_in('QUESTION_ID', $questionIds);
            $query = $this->db->get('TEST_SERIES_QUESTIONS');
        
            if ($query && $query->num_rows() > 0) {
                $questions = $query->result_array();
                $totalQuestions = count($questions);
                $totalMarks = array_sum(array_column($questions, 'QUESTION_MARKS'));
        
                // ✅ Create an array indexed by QUESTION_ID for quick lookup in view
                $questionsById = [];
                foreach ($questions as $q) {
                    $questionsById[$q['QUESTION_ID']] = $q;
                }
            } else {
                $questions = [];
                $questionsById = [];
                $totalQuestions = 0;
                $totalMarks = 0;
            }
        } else {
            $questions = [];
            $questionsById = [];
            $totalQuestions = 0;
            $totalMarks = 0;
        }
        
        // Pass everything to the view
        $data['questions']        = $questions;
        $data['questions_by_id']  = $questionsById; // ✅ This fixes the view issue
        $data['total_questions']  = $totalQuestions;
        $data['total_marks']      = $totalMarks;
        $data['user_response']    = $data['user_data'];
        

        // echo "<pre>";
        // print_r( $data['user_response'] );die;
    
        $this->load->view('test_eval', $data);
        return;
    }
    


    // === TYPE 3 Test ===
    if ($testType === 3) {
        $responses = !empty($data['user_data']['USER_RESPONSE'])
            ? json_decode($data['user_data']['USER_RESPONSE'], true)
            : [];

        $questionIds = array_values(array_unique(
            array_map('intval', array_filter(array_column($responses, 'question_id')))
        ));

        $questionsById = [];
        if ($questionIds) {
            $this->db->select('QUESTION_ID, QUESTION_NAME, option_1, option_2, option_3, option_4,
                               is_correct_1, is_correct_2, is_correct_3, is_correct_4');
            $this->db->where_in('QUESTION_ID', $questionIds);
            foreach ($this->db->get('TEST_SERIES_QUESTIONS')->result_array() as $row) {
                $questionsById[(int)$row['QUESTION_ID']] = $row;
            }
        }

        $attempted = $correct = $wrong = 0;
        $analysis  = [];

        foreach ($responses as $r) {
            $qid = isset($r['question_id']) ? (int)$r['question_id'] : 0;
            if (!$qid || !isset($questionsById[$qid])) {
                continue;
            }

            $attempted++;
            $row = $questionsById[$qid];

            $selectedKey = $r['selected_option'] ?? '';
            $correctKeys = [];
            for ($i = 1; $i <= 4; $i++) {
                if (!empty($row["is_correct_$i"])) {
                    $correctKeys[] = "option_$i";
                }
            }

            $optText = function ($key) use ($row) {
                $i = (int)str_replace('option_', '', $key);
                return $row["option_$i"] ?? '';
            };

            $isCorrect = $selectedKey && in_array($selectedKey, $correctKeys, true);
            $isCorrect ? $correct++ : $wrong++;

            $analysis[] = [
                'question_id'     => $qid,
                'question_text'   => (string)$row['QUESTION_NAME'],
                'your_answer'     => $selectedKey ? $optText($selectedKey) : '',
                'your_answer_key' => $selectedKey,
                'correct_answer'  => implode(', ', array_map($optText, $correctKeys)),
                'correct_keys'    => $correctKeys,
                'is_correct'      => $isCorrect,
                'time_spent'      => isset($r['time_spent']) ? (int)$r['time_spent'] : 0,
            ];
        }

        $data['attempted']         = $attempted;
        $data['correct']           = $correct;
        $data['wrong']             = $wrong;
        $data['question_analysis'] = $analysis;

        $this->load->view('test_report_mcq', $data);
        return;
    }

    // === Other Test Types ===
    $data['user_response'] = $data['user_data'];
    $this->load->view('test_eval', $data);
}




    public function saveUserTestEval()
    {
        $data['MARKS_DATA'] = json_encode($this->input->post('marksData'));
        $data['MARKS_OBTAINED'] = $this->input->post('totalMarks');
        $data['TOTAL_MARKS']  = $this->input->post('totalTestMarks');
        $data['ID']  = $this->input->post('response_id');
        $data['IS_EVAL']  = 1;
        // $data['USER_ID']  = $this->input->post('userId');
        // $data['TEST_ID'] = $this->input->post('testId');

        // print_r($data);die;

        $save_test_eval_data = $this->AdminConsoleModel->update_user_test_eval($data);

        if ($save_test_eval_data) {
            echo "Done";
        }




        // Process the data as needed, update the database, etc.

        // You can return a response if needed
        // echo json_encode(['success' => true]);
    }


    public function addTestUser()
    {

        $test_id = $this->input->post('test_id');
        $user_id = $this->input->post('user_id');

        // Save the data to the database using your model
        $data = array(
            'TEST_ID' => $test_id,
            'USER_ID' => $user_id,
        );

        //  print_r($data);die;
        $add_test = $this->AdminConsoleModel->save_test_user_mapping($data);

        if ($add_test) {
            echo "Assigned test to user";
        }

        // redirect('test');
    }

    public function removeTestUser()
    {

        $mapping_id = $this->input->post('mapping_id');
        $user_id = $this->input->post('user_id');
        $is_removed = $this->input->post('is_removed');

        // Save the data to the database using your model
        $data = array(
            'MAPPING_ID' => $mapping_id,
            'USER_ID' => $user_id,
        );

        //  print_r($data);die;
        $remove_test = $this->AdminConsoleModel->remove_user_test_mapping($mapping_id, $is_removed);

        if ($remove_test) {
            echo "Removed test to user";
        }

        // redirect('test');
    }

    public function deleteTest()
    {
        // Get the test_id from the POST data
        $test_id = $this->input->post('test_id');

        // Call the model function to update the is_del column
        $result = $this->AdminConsoleModel->delete_test($test_id);

        // Send response back to the AJAX call
        echo json_encode($result);
    }


    public function myProfile()
    {
        if ($this->session->userdata('logged_in')) {
            // print_r($_SESSION);die;
            $email = $this->session->userdata('user')['EMAIL'];
            $data['user_data'] = $this->User_Model->get_user_by_email($email);
            $this->load->view('my_profile', $data);
        } else {
            redirect('/login');
        }
    }

    public function helpSupport()
    {

        $this->load->view('help_support');
    }

    public function userLogin()
    {

        if (!$this->session->userdata('logged_in')) {
            $this->load->view('login_page');
        } else {
            redirect('admin');
        }
    }

    public function login()
    {
        try {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
    
            $user = $this->User_Model->get_user_by_email($email);
    
            if (!$user || ($password != $user['PASSWORD'])) {
                throw new \Exception('Invalid email or password');
            }
    
            // Permissions
            $permissions = [];
            $permissionsData = $this->Permission_Model->get_user_permissions($user['USER_ID']);
            if (is_array($permissionsData) && !empty($permissionsData)) {
                $permissions = $permissionsData;
            }
    
            $permissionIDs = [];
            if (is_array($permissions) && !empty($permissions)) {
                foreach ($permissions as $permission) {
                    $permissionIDs[] = $permission['PERMISSION_ID'];
                }
            }
            $permissionIDs = array_unique($permissionIDs);
            $permission_mappings = $this->Permission_Model->getPermissions($permissionIDs);
    
            // Role permissions
            $rolepermissionsData = $this->Permission_Model->get_role_permissions($user['USERS_TYPE_ID']);
    
         
    
            // Session auth data
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('user', $user);
            $this->session->set_userdata('permissions', $rolepermissionsData['permission_ids'] ?? []);
            $this->session->set_flashdata('login_error', null);
    
            // (Optional) fix a possible typo from /dashbaord -> /dashboard
            redirect('/dashbaord');
        } catch (\Exception $e) {
            $this->session->set_flashdata('login_error', $e->getMessage());
            redirect('login');
        }
    }
    


    public function logout()
    {
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('permissions');
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        redirect('login');
    }



    public function register()
    {
        $email = $this->input->get('email');
        $username = $this->input->get('username');
        $password = $this->input->get('password');
        $confirm_password = $this->input->get('confirm_password');
        try {
            $db_insert = $this->User_Model->save($this->input);
            $user = $this->User_Model->find($db_insert);
            $permissions = $this->Permission_Model->get_user_permissions($db_insert);
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('user', $user);
            $this->session->set_userdata('permissions', $permissions);
            $this->session->set_flashdata('success_message', 'You are successfully registered!');
            redirect('dashboard');
        } catch (\Exception $e) {
            redirect('error');
        }
    }

    public function userRegister()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->load->view('register_page');
        } else {
            redirect('admin');
        }
    }

    public function underMaintenance()
    {
        $this->load->view('pop-up-modal');
    }

    public function setRedirectUrl()
    {
        $url = $this->input->post('url');
        $this->session->set_userdata('redirect_url', $url);
    }
}
