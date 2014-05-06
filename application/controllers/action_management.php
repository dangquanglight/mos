<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Action_management extends GEH_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'actions_model',
            'action_condition_model',
            'device_model',
            'device_state_model',
            'device_setpoint_model'
        ));
    }

    public $action_management_view = 'action_management/';

    public function index()
    {
        if ($this->input->post()) {
            // Go to page add new action with controlled device id GET value
            redirect(add_new_action_url($this->input->post('action_type')) . '&row_device_id=' . $this->input->post('controlled_device'));
        }

        $data['actions_list'] = $this->prepare_action_list_info($this->actions_model->get_list());
        $data['controlled_devices_list'] = $this->device_model->get_by_device_state(DEVICE_STATE_CONTROLLED);
        $extend_data['content_view'] = $this->load->view($this->action_management_view . 'index', $data, TRUE);

        $this->load_frontend_template($extend_data, 'ACTION MANAGEMENT');
    }

    private function prepare_action_list_info($data)
    {
        foreach ($data as &$item) {
            // Action type
            if ($item['action_type'] == ACTION_TYPE_SCHEDULE)
                $item['action_type'] = 'Schedule';
            else
                $item['action_type'] = 'Event';
            // Action status
            if ($item['status'] == ACTION_ENABLE)
                $item['status'] = 'Enable';
            else
                $item['status'] = 'Disalbe';
        }

        return $data;
    }

    public function modify()
    {
        // Insert / Update data into database
        if ($this->input->post()) { //var_dump($this->input->post()); die();
            // Flash data successful
            $add_new_success_message = 'Add new action successful!';
            $edit_success_message = 'Edit action successful!';

            // Get exception day from - to
            if ($this->input->post('exception_type')) {
                $exception_from = $this->input->post('exception_type') == EXCEPTION_TYPE_DAY ?
                    date('Y-m-d', strtotime($this->input->post('exception_day'))) :
                    date('Y-m-d', strtotime($this->input->post('exception_from')));

                $exception_to = $this->input->post('exception_type') == EXCEPTION_TYPE_DURATION ?
                    date('Y-m-d', strtotime($this->input->post('exception_to'))) : NULL;
            } else {
                $exception_from = $exception_to = NULL;
            }

            // Action type Schedule
            if ((isset($_GET['action_type']) and $_GET['action_type'] == 'schedule') or (isset($_POST['action_type']) and $_POST['action_type'] == 'schedule')) {
                if ($this->input->post('schedule_day')) {
                    // Create string schedule days
                    $schedule_days = implode(',', $this->input->post('schedule_day'));
                } else {
                    $schedule_days = NULL;
                }

                $data = array(
                    'device_id' => $this->input->post('action_device_id'),
                    'status' => intval($this->input->post('action_status')),
                    'action_type' => ACTION_TYPE_SCHEDULE,
                    'action_setpoint' => floatval($this->input->post('action_setpoint')),
                    'schedule_days' => $schedule_days,
                    'schedule_start' => $this->input->post('time_start'),
                    'schedule_end' => $this->input->post('time_end'),
                    'exception_type' => $this->input->post('exception_type'),
                    'exception_from' => $exception_from,
                    'exception_to' => $exception_to,
                    'exception_setpoint' => floatval($this->input->post('exception_setpoint')),
                    'created_date' => time()
                );

                // Edit action
                if (isset($_GET['id']) and (is_numeric($_GET['id']) and intval($_GET['id'] > 0))) {
                    if ($this->actions_model->update($_GET['id'], $data)) {
                        $this->session->set_flashdata($this->flash_success_session, $edit_success_message);
                        redirect(action_management_controller_url());
                    }
                }

                // Add new action
                else if (isset($_GET['action_type']) and ($_GET['action_type'] == 'schedule' or $_GET['action_type'] == 'event')) {
                    if ($this->actions_model->insert($data)) {
                        $this->session->set_flashdata($this->flash_success_session, $add_new_success_message);
                        redirect(action_management_controller_url());
                    }
                }
            }

            // Action type event
            else if ((isset($_GET['action_type']) and $_GET['action_type'] == 'event') or (isset($_POST['action_type']) and $_POST['action_type'] == 'event')) {
                $data = array(
                    'device_id' => $this->input->post('action_device_id'),
                    'status' => intval($this->input->post('action_status')),
                    'action_type' => ACTION_TYPE_EVENT,
                    'action_setpoint' => floatval($this->input->post('action_setpoint')),
                    'exception_type' => $this->input->post('exception_type'),
                    'exception_from' => $exception_from,
                    'exception_to' => $exception_to,
                    'exception_setpoint' => floatval($this->input->post('exception_setpoint')),
                    'created_date' => time()
                ); //var_dump($data); die();

                // Edit action
                if (isset($_GET['id']) and (is_numeric($_GET['id']) and intval($_GET['id'] > 0))) {
                    if ($this->actions_model->update($_GET['id'], $data)) {
                        $flag = FALSE;

                        // Remove all action condition and insert the new one to database
                        // Find all condions of this action and delete it first
                        $conditions = $this->action_condition_model->get_by_action_id($_GET['id']);
                        if ($conditions) {
                            foreach ($conditions as $condition) {
                                $this->action_condition_model->delete($condition['id']);
                            }
                        }

                        for ($i = 1; $i <= $this->input->post('count_condition'); $i++) {
                            $data = array(
                                'action_id' => $_GET['id'],
                                'row_device_id' => $this->input->post('input_device_' . $i),
                                'operator' => $this->input->post('operator_' . $i),
                                'condition_setpoint' => $this->input->post('condition_setpoint_' . $i)
                            );
                            if ($this->action_condition_model->insert($data))
                                $flag = TRUE;
                        }

                        // Go to action management page if has no insertion fail
                        if ($flag) {
                            $this->session->set_flashdata($this->flash_success_session, $edit_success_message);
                            redirect(action_management_controller_url());
                        }
                    }
                }

                // Add new action
                else if (isset($_GET['action_type']) and ($_GET['action_type'] == 'schedule' or $_GET['action_type'] == 'event')) {
                    if ($action_id = $this->actions_model->insert($data)) {
                        $flag = FALSE;

                        for ($i = 1; $i <= $this->input->post('count_condition'); $i++) {
                            $data = array(
                                'action_id' => $action_id,
                                'row_device_id' => $this->input->post('input_device_' . $i),
                                'operator' => $this->input->post('operator_' . $i),
                                'condition_setpoint' => $this->input->post('condition_setpoint_' . $i)
                            );
                            if ($this->action_condition_model->insert($data))
                                $flag = TRUE;
                        }

                        // Go to action management page if has no insertion fail
                        if ($flag) {
                            $this->session->set_flashdata($this->flash_success_session, $add_new_success_message);
                            redirect(action_management_controller_url());
                        }
                    }
                }
            }
        }

        // Get state id by state name: Input
        $state = $this->device_state_model->get_by_name(DEVICE_STATE_INPUT);
        // Get list input devices
        $input_devices = $this->device_model->get_list_by_state_id($state['id']);
        $data['input_devices_list'] = $input_devices;

        // Case: edit action
        if (isset($_GET['id']) and (is_numeric($_GET['id']) and intval($_GET['id'] > 0))) {
            // Get action detail information
            $action = $this->actions_model->get_by_id($_GET['id']);
            $data['action'] = $this->prepare_action_info($action);

            // Get device detail information
            $device = $this->device_model->get_by_row_id($action['device_id']);
            $data['device'] = $device;

            // Action type: schedule
            if ($action['action_type'] == ACTION_TYPE_SCHEDULE) {
                $data['action_type'] = 'schedule';
                $extend_data['content_view'] = $this->load->view($this->action_management_view . 'edit_action_schedule',
                    $data, TRUE);

            } // Action type: event
            else if ($action['action_type'] == ACTION_TYPE_EVENT) {
                $action_conditions = $this->action_condition_model->get_by_action_id($action['id']);
                $data['action_conditions'] = $action_conditions; //var_dump($action_conditions); die();

                //Remove input device of conditions from list input device
                $temp_input_device = $input_devices;
                foreach ($temp_input_device as $key => &$value) {
                    foreach ($action_conditions as $item) {
                        if ($item['row_device_id'] == $value['row_device_id']) {
                            unset($temp_input_device[$key]);
                        }
                    }
                }
                $data['new_input_devices'] = $temp_input_device;
                $data['action_type'] = 'event';

                $extend_data['content_view'] = $this->load->view($this->action_management_view . 'edit_action_event',
                    $data, TRUE);
            }

            $this->load_frontend_template($extend_data, 'EDIT ACTION');
        } // Case: add new action
        else if (isset($_GET['action_type']) and ($_GET['action_type'] == 'schedule' or $_GET['action_type'] == 'event')) {
            $action_type = $_GET['action_type'];
            $device = $this->device_model->get_by_row_id($_GET['row_device_id']);
            $data['device'] = $device;
            $data['device_setpoints'] = $this->device_setpoint_model->get_by_device_row_id($device['id']);

            // Action type: schedule
            if ($action_type == 'schedule') {
                $extend_data['content_view'] = $this->load->view($this->action_management_view . 'add_action_schedule',
                    $data, TRUE);
            } // Action type: event
            else if ($action_type == 'event') {
                $extend_data['content_view'] = $this->load->view($this->action_management_view . 'add_action_event',
                    $data, TRUE);
            }

            $this->load_frontend_template($extend_data, 'ADD NEW ACTION');
        } else
            redirect(action_management_controller_url());
    }

    public function delete()
    {
        if ($this->input->get('id')) {
            $action_id = $this->input->get('id');
            // Find all condions of this action and delete it first
            $conditions = $this->action_condition_model->get_by_action_id($action_id);
            if ($conditions) {
                foreach ($conditions as $condition) {
                    $this->action_condition_model->delete($condition['id']);
                }
            }

            // Delete action after delete all its condition
            if ($this->actions_model->delete($action_id)) {
                $this->session->set_flashdata($this->flash_success_session, 'Action has been removed successful!');
                redirect(action_management_controller_url());
            }
        }
    }

    private function prepare_action_info($data)
    {
        // Action schedule day
        if ($data['action_type'] == ACTION_TYPE_SCHEDULE) {
            $data['schedule_days'] = explode(",", $data['schedule_days']);
        }

        return $data;
    }

}

/* End of file action_management.php */
/* Location: ./application/controllers/action_management.php */