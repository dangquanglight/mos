<?php	$file = getcwd() . "/" . APPPATH . "controllers/MY_controller.php";			include $file;	class Home extends Fsadfzerrwez_home{		function Home(){			parent::__construct();					}						function index(){			$this->check_login();						$data = array();			$title = $this->lang->line('home');			$this->load_page('home', $title, $data);					}				function home_details($date_type){					$data = array();			$company_id = get_company_id();			$this->load->model('site');						$data['data'] = $this->site->get_consumtion($company_id, $date_type);			$this->load->view($this->home_directory . 'home-details', $data);		}				function introduce(){					if (is_login()){				//$data = array();
				//$data['back-url'] = get_back_url(base_url(""), MODULE_HOME);				//die();				//redirect('');								$this->index();			}			else{				$this->load->model('user');				//CHECK LOGIN HERE				$redirect_url = urldecode($this->input->get('redirect_url'));				$user_id = get_user_id();				if ($user_id){									if ($this->view_name == "login"){//check current url						$redirect_url = base_url();					}					redirect($redirect_url);					return;				}				$result = null;				$err_msg = "";							if (is_post()){													$result = false;						$email = $this->input->post('email');					if (!$email){						$email = $this->input->post('email_login');					}					$password = $this->input->post('password');					if (!$password){						$password = $this->input->post('password_login');					}					$remember = $this->input->post('remember');					if (!$remember){						$remember = $this->input->post('remember_login');					}										if ($email && $password){						$user = $this->user->get_user_by_email_password($email, $password);						if ($user){							if($user->status && $user->role_status){															//NOT ACTIVATE								if (!$user->activated){																	redirect(base_url('need-activate-account'));								}								else{									$result = true;									set_login_data($user);																if ($remember){										$time = time();										//add subfix 5 and postfix 5 characters										$rem_data = "email=" . urlencode($email) . "&hash=" . substr($time, 0, 5) . md5(urlencode($password)) . substr($time, -5);										//2 weeks										setcookie($this->cookie_auth_name, $rem_data, $time + 14 * 24 * 60 * 60);									}									else{										setcookie($this->cookie_auth_name, "", -1);									}									if (!$redirect_url){										$redirect_url = base_url();									}									redirect($redirect_url);								}							}							else{								redirect(base_url('account-disabled'));							}						}						else{							$err_msg = $this->lang->line('login-fail');						}					}					else{						$err_msg = $this->lang->line('login-fail');					}				}													$data['redirect_url'] = $redirect_url;					$data['result'] = $result;							$data['err_msg'] = $err_msg;				$this->show_cms(1, $data);			}							}				function show_cms($id, $data = null){			if ($data == null){				$data = array();			}			$this->load->model('cms');			$cms = $this->cms->get_id(1);			$title = $this->lang->line('home');			if ($cms){				$lang = get_language();				if ($lang->iso == 'en'){					$title = $cms->title_en;				}				else if ($lang->iso == 'fi'){					$title = $cms->title_fi;				}				else if ($lang->iso == 'ci'){					$title = $cms->title_ci;				}			}			$data['cms'] = $cms;			$this->load_page('show-cms', $title, $data);		}	}?>