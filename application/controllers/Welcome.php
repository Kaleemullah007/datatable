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
	public function index()
	{
		// $res = $this->db->where_not_in('admin_id', 1);
		// print_r($res);
		// die();
		$this->load->view('welcome_message');
	}

	function members($para1="",$para2="",$para3="",$para4="")
	{

		// echo "string";
		$this->load->model('Crud_model');
		// die();
		if (1 == FALSE) {
        	redirect(base_url().'admin/login', 'refresh');
		}
		else{
			$member_approval = $this->db->get_where('general_settings', array('type' => 'member_approval_by_admin'))->row()->value;
			$page_data['title'] = "Admin || ";
			if ($this->session->flashdata('alert') == "block") {
				$page_data['danger_alert'] = translate("you_have_successfully_blocked_this_member!");
			}
			elseif ($this->session->flashdata('alert') == "unblock") {
				$page_data['success_alert'] = translate("you_have_successfully_unlocked_this_member!");
			}
			elseif ($this->session->flashdata('alert') == "delete") {
				$page_data['success_alert'] = translate("this_member_is_moved_to_deleted_member_list!");
			}
			elseif ($this->session->flashdata('alert') == "failed_delete") {
				$page_data['danger_alert'] = translate("failed_to_delete_this_member!");
			}
			elseif ($this->session->flashdata('alert') == "member_approval") {
				$page_data['success_alert'] = translate("you_have_successfully_approved_this_member!");
			}
			if ($para2=="list_data") {
				if ($para1=="users") {
					if($member_approval == 'yes'){
						$columns = array(
                            0 =>'',
                            1 =>'member_profile_id',
							2 =>'first_name',
                            3 =>'status',
							4 =>'follower',
                            5 =>'reported_by',
                            6 =>'member_since',
                        );
					} else{
						$columns = array(
                            0 =>'',
                            1 =>'member_profile_id',
							2 =>'first_name',
							3 =>'follower',
                            4 =>'reported_by',
                            5 =>'member_since',
                        );
					}
		        	
		        }
		        elseif ($para1=="premium_members") {
		        	if($member_approval == 'yes'){
		        		$columns = array(
                           	0 =>'',
                            1 =>'member_profile_id',
							2 =>'first_name',
                            3 =>'status',
                            4 =>'follower',
							5 =>'reported_by',
                            6 =>'member_since',
                        );
		        	} else{
		        		$columns = array(
                           	0 =>'',
                            1 =>'member_profile_id',
							2 =>'first_name',
                            3 =>'follower',
							4 =>'reported_by',
                            5 =>'member_since',
                        );
		        	}
		        	
		        }

				$limit = $this->input->post('length');
		        $start = $this->input->post('start');
		        // $order = $columns[$this->input->post('order')[0]['column']];
		        // $dir = $this->input->post('order')[0]['dir'];
		        $order = 'member_id';
		        $dir = 'desc';

		        if ($para1=="users") {
		        	$member_type = 1;
		        }
		        elseif ($para1=="premium_members") {
		        	$member_type = 2;
		        }

		        $totalData = $this->Crud_model->allmembers_count($member_type);

		        $totalFiltered = $totalData;

		        if(empty($this->input->post('search')['value']))
		        {

		        	$members = $this->Crud_model->allmembers($member_type,$limit,$start,$order,$dir);
		        }
		        else {
		            $search = $this->input->post('search')['value'];

		            $members =  $this->Crud_model->members_search($member_type,$limit,$start,$search,$order,$dir);

		            $totalFiltered = $this->Crud_model->members_search_count($member_type,$search);
		        }

		        $data = array();
		        if(!empty($members))
		        {
		        	// if ($dir == 'asc') { $i = $start + 1; } elseif ($dir == 'desc') { $i = $totalFiltered - $start; }
		            foreach ($members as $member)
		            {
		            	$image = json_decode($member->profile_image, true);
		            	if (file_exists('uploads/profile_image/'.$image[0]['thumb'])) {
							$member_image="<img src='".base_url()."uploads/profile_image/".$image[0]['thumb']."' class='img-sm'>";
						}
						else {
							$member_image="<img src='".base_url()."uploads/profile_image/default_image.png' class='img-sm'>";
						}

						if ($member->is_blocked == "yes")
						{
							$block_button = "<button data-target='#block_modal' data-toggle='modal' class='btn btn-success btn-xs add-tooltip' data-toggle='tooltip' data-placement='top' title= '".translate('unblock')."' onclick='block(\"".$member->is_blocked."\", ".$member->member_id.")'><i class='fa fa-check'></i></button>
							";
						}
						elseif ($member->is_blocked == "no") {
							$block_button = "<button data-target='#block_modal' data-toggle='modal' class='btn btn-dark btn-xs add-tooltip' data-toggle='tooltip' data-placement='top' title='".translate('block')."' onclick='block(\"".$member->is_blocked."\", ".$member->member_id.")'><i class='fa fa-ban'></i></button>
							";
						}
						if ($member->is_closed == "yes")
						{
							$acnt_status_button = "<center><span class='badge badge-danger' style='width:60px'>".translate('closed')."</span></center>";
						}
						elseif ($member->is_closed == "no") {
							$acnt_status_button = "<center><span class='badge badge-success' style='width:60px'>".translate('Active')."</span></center>";
						}

						if ($member_approval == 'yes') {
							if ($member->status == "pending")
							{
								$status_button = "<button data-target='#status_modal' data-toggle='modal' class='btn btn-info btn-xs add-tooltip' data-toggle='tooltip' data-placement='top' title= '".translate('approve')."' onclick='status(\"".$member->status."\", ".$member->member_id.")'><i class='fa fa-hand-pointer-o'></i></button>
								";

							}
							elseif ($member->status == "approved") {
								$status_button = "<button  data-toggle='modal' class='btn btn-success btn-xs add-tooltip' data-toggle='tooltip' data-placement='top' title= '".translate('approved')."' ><i class='fa fa-thumbs-up'></i></button>
								";
							}
						}
						else
						{
							$status_button = '';
						}

						


		                $nestedData['image'] = $member_image;
						$nestedData['name'] = $member->first_name.' '.$member->last_name;
						if ($member->status == "pending")
						{
							$nestedData['status'] = "<button  data-toggle='modal' class='badge badge-info' >".translate('pending')."</button>
							";
						}
						elseif ($member->status == "approved") {
							$nestedData['status'] = "<button   class='badge badge-success' >".translate('approved')."</i></button>
							";
						}
		                $nestedData['member_id'] = $member->member_profile_id;
						$nestedData['follower'] = $member->follower;
		                $nestedData['profile_reported'] = $member->reported_by;
		               

		                if ($para1=="premium_members") {
		                	$package_info = $this->db->get_where('member', array('member_id' => $member->member_id))->row()->package_info;
                			$package_info = json_decode($package_info, true);
		                	$nestedData['package'] = $package_info[0]['current_package'];
		                }
		                $nestedData['member_since'] = date('d/m/Y h:i:s A', strtotime($member->member_since));
		                $nestedData['member_status'] = $acnt_status_button;
		                $nestedData['options'] = "<a href='".base_url()."admin/members/".$para1."/view_member/".$member->member_id."' id='demo-dt-view-btn' class='btn btn-primary btn-xs add-tooltip' data-toggle='tooltip' data-placement='top' title='".translate('view_profile')."' ><i class='fa fa-eye'></i></a>
							<a href='#' id='demo-dt-delete-btn' data-target='#package_modal' data-toggle='modal' class='btn btn-info btn-xs add-tooltip' data-toggle='tooltip' data-placement='top' title='".translate('packages')."' onclick='view_package(".$member->member_id.")'><i class='fa fa-object-ungroup'></i></a> ".$block_button."<button data-target='#delete_modal' data-toggle='modal' class='btn btn-danger btn-xs add-tooltip' data-toggle='tooltip' data-placement='top' title='".translate('delete_member')."' onclick='delete_member(".$member->member_id.")'><i class='fa fa-trash'></i></button>".$status_button."";

		                $data[] = $nestedData;
		                // if ($dir == 'asc') { $i++; } elseif ($dir == 'desc') { $i--; }
		            }
		        }

		        $json_data = array(
		                    "draw"            => intval($this->input->post('draw')),
		                    "recordsTotal"    => intval($totalData),
		                    "recordsFiltered" => intval($totalFiltered),
		                    "data"            => $data
		                    );
		        echo json_encode($json_data);
			}
			elseif ($para1=="users") {
				if ($para2=="") {
					$page_data['top'] = "members/index.php";
					$page_data['folder'] = "members";
					$page_data['file'] = "index.php";
					$page_data['bottom'] = "members/index.php";
					$page_data['get_users'] = $this->db->get_where("member", array("membership" => 1))->result();
					if ($this->session->flashdata('alert') == "edit") {
						$page_data['success_alert'] = translate("you_have_successfully_edited_the_profile!");
					}
					elseif ($this->session->flashdata('alert') == "upgrade") {
						$page_data['success_alert'] = translate("you_have_successfully_upgraded_the_member_package!");
					}
				}
				elseif ($para2=="view_member") {
					$page_data['top'] = "members/members.php";
					$page_data['folder'] = "members";
					$page_data['file'] = "view_member.php";
					$page_data['bottom'] = "members/members.php";
					$page_data['get_free_member_by_id'] = $this->db->get_where("member", array("membership" => 1, "member_id" => $para3))->result();
				}
				elseif ($para2=="edit_member") {
					$page_data['top'] 		= "members/members.php";
					$page_data['folder'] 	= "members";
					$page_data['file']	 	= "edit_member.php";
					$page_data['bottom'] 	= "members/members.php";
					$page_data['get_free_member_by_id'] = $this->db->get_where("member", array("membership" => 1, "member_id" => $para3))->result();
				}
				$page_data['member_type'] = "Free";
				$page_data['parameter'] = "users";
				$page_data['page_name'] = "users";
				$this->load->view('welcome_message', $page_data);
			}
			elseif ($para1=="premium_members") {
				if ($para2=="") {
					$page_data['top'] = "members/index.php";
					$page_data['folder'] = "members";
					$page_data['file'] = "index.php";
					$page_data['bottom'] = "members/index.php";
					$page_data['get_premium_members'] = $this->db->get_where("member", array("membership" => 2))->result();
					if ($this->session->flashdata('alert') == "edit") {
						$page_data['success_alert'] = translate("you_have_successfully_edited_the_profile!");
					}
					elseif ($this->session->flashdata('alert') == "upgrade") {
						$page_data['success_alert'] = translate("you_have_successfully_upgraded_the_member_package!");
					}
				}
				elseif ($para2=="view_member") {
					$page_data['top'] = "members/members.php";
					$page_data['folder'] = "members";
					$page_data['file'] = "view_member.php";
					$page_data['bottom'] = "members/members.php";
					$page_data['get_premium_member_by_id'] = $this->db->get_where("member", array("membership" => 2, "member_id" => $para3))->result();
				}
				elseif ($para2=="edit_member") {
					$page_data['top'] 		= "members/members.php";
					$page_data['folder'] 	= "members";
					$page_data['file']	 	= "edit_member.php";
					$page_data['bottom'] 	= "members/members.php";
					$page_data['get_premium_member_by_id'] = $this->db->get_where("member", array("membership" => 2, "member_id" => $para3))->result();
				}
				$page_data['member_type'] = "Premium";
				$page_data['parameter'] = "premium_members";
				$page_data['page_name'] = "premium_members";
				$this->load->view('back/index', $page_data);
			}
			elseif ($para1=="add_member") {
				if ($para2=="") {
					$page_data['top'] 		= "members/index.php";
					$page_data['folder'] 	= "members";
					$page_data['file']	 	= "add_member.php";
					$page_data['bottom'] 	= "members/index.php";
					$page_data['page_name'] = "add_member";
					if ($this->session->flashdata('alert') == "add") {
						$page_data['success_alert'] = translate("you_have_successfully_added_a_member!!");
					}
					elseif ($this->session->flashdata('alert') == "add_fail") {
						$page_data['danger_alert'] = translate("member_registration_failed!");
					}


					$this->load->view('back/index', $page_data);
				}
				elseif ($para2=="do_add") {

					$this->form_validation->set_rules('fname', 'First Name', 'required');
					$this->form_validation->set_rules('lname', 'Last Name', 'required');
					$this->form_validation->set_rules('gender', 'Gender', 'required');
					$this->form_validation->set_rules('on_behalf', 'On Behalf', 'required');
					$this->form_validation->set_rules('plan', 'Plan', 'required');
		            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[member.email]',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
	                $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');
		            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required');
		            $this->form_validation->set_rules('password', 'Password', 'required');
		            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');

		            if ($this->form_validation->run() == FALSE) {
	                    $page_data['top'] 		= "members/index.php";
						$page_data['folder'] 	= "members";
						$page_data['file']	 	= "add_member.php";
						$page_data['bottom'] 	= "members/index.php";
						$page_data['page_name'] = "add_member";
						$page_data['form_error'] = "yes";
		                $page_data['form_contents'] = $this->input->post();
		                $this->session->set_flashdata('alert', 'add_fail');
		                $this->load->view('back/index', $page_data);
		            }
		            else {

	                    // ------------------------------------Profile Image------------------------------------ //
	                    $profile_image[] = array('profile_image'    =>  'default.jpg',
	                                                'thumb'         =>  'default_thumb.jpg'
	                                        );
	                    $profile_image = json_encode($profile_image);
	                    // ------------------------------------Profile Image------------------------------------ //

	                    // ------------------------------------Basic Info------------------------------------ //
	                    $basic_info[] = array('age'                 => '',
	                                        'marital_status'        => '',
	                                        'number_of_children'    => '',
	                                        'area'                  => '',
	                                        'on_behalf'             => $this->input->post('on_behalf')
	                                        );
	                    $basic_info = json_encode($basic_info);
	                    // ------------------------------------Basic Info------------------------------------ //

	                    // ------------------------------------Present Address------------------------------------ //
	                    $present_address[] = array('country'        => '',
	                                        'city'                  => '',
	                                        'state'                 => '',
	                                        'postal_code'           => ''
	                                        );
	                    $present_address = json_encode($present_address);
	                    // ------------------------------------Present Address------------------------------------ //

	                    // ------------------------------------Education & Career------------------------------------ //
	                    $education_and_career[] = array('highest_education' => '',
	                                        'occupation'                    => '',
	                                        'annual_income'                 => ''
	                                        );
	                    $education_and_career = json_encode($education_and_career);
	                    // ------------------------------------Education & Career------------------------------------ //

	                    // ------------------------------------ Physical Attributes------------------------------------ //
	                    $physical_attributes[] = array('weight'     => '',
	                                        'eye_color'             => '',
	                                        'hair_color'            => '',
	                                        'complexion'            => '',
	                                        'blood_group'           => '',
	                                        'body_type'             => '',
	                                        'body_art'              => '',
	                                        'any_disability'        => ''
	                                        );
	                    $physical_attributes = json_encode($physical_attributes);
	                    // ------------------------------------ Physical Attributes------------------------------------ //

	                    // ------------------------------------ Language------------------------------------ //
	                    $language[] = array('mother_tongue'         => '',
	                                        'language'              => '',
	                                        'speak'                 => '',
	                                        'read'                  => ''
	                                        );
	                    $language = json_encode($language);
	                    // ------------------------------------ Language------------------------------------ //

	                    // ------------------------------------Hobbies & Interest------------------------------------ //
	                    $hobbies_and_interest[] = array('hobby'     => '',
	                                        'interest'              => '',
	                                        'music'                 => '',
	                                        'books'                 => '',
	                                        'movie'                 => '',
	                                        'tv_show'               => '',
	                                        'sports_show'           => '',
	                                        'fitness_activity'      => '',
	                                        'cuisine'               => '',
	                                        'dress_style'           => ''
	                                        );
	                    $hobbies_and_interest = json_encode($hobbies_and_interest);
	                    // ------------------------------------Hobbies & Interest------------------------------------ //

	                    // ------------------------------------ Personal Attitude & Behavior------------------------------------ //
	                    $personal_attitude_and_behavior[] = array('affection'   => '',
	                                        'humor'                 => '',
	                                        'political_view'        => '',
	                                        'religious_service'     => ''
	                                        );
	                    $personal_attitude_and_behavior = json_encode($personal_attitude_and_behavior);
	                    // ------------------------------------ Personal Attitude & Behavior------------------------------------ //

	                    // ------------------------------------Residency Information------------------------------------ //
	                    $residency_information[] = array('birth_country'    => '',
	                                        'residency_country'     => '',
	                                        'citizenship_country'   => '',
	                                        'grow_up_country'       => '',
	                                        'immigration_status'    => ''
	                                        );
	                    $residency_information = json_encode($residency_information);
	                    // ------------------------------------Residency Information------------------------------------ //

	                    // ------------------------------------Spiritual and Social Background------------------------------------ //
	                    $spiritual_and_social_background[] = array('religion'   => '',
	                                        'caste'                 => '',
	                                        'sub_caste'             => '',
	                                        'ethnicity'             => '',
	                                        'u_manglik'             => '',
	                                        'personal_value'        => '',
	                                        'family_value'          => '',
	                                        'community_value'       => '',
                                    		'family_status'          =>  ''
	                                        );
	                    $spiritual_and_social_background = json_encode($spiritual_and_social_background);
	                    // ------------------------------------Spiritual and Social Background------------------------------------ //

	                    // ------------------------------------ Life Style------------------------------------ //
	                    $life_style[] = array('diet'                => '',
	                                        'drink'                 => '',
	                                        'smoke'                 => '',
	                                        'living_with'           => ''
	                                        );
	                    $life_style = json_encode($life_style);
	                    // ------------------------------------ Life Style------------------------------------ //

	                    // ------------------------------------ Astronomic Information------------------------------------ //
	                    $astronomic_information[] = array('sun_sign'    => '',
	                                        'moon_sign'                 => '',
	                                        'time_of_birth'             => '',
	                                        'city_of_birth'             => ''
	                                        );
	                    $astronomic_information = json_encode($astronomic_information);
	                    // ------------------------------------ Astronomic Information------------------------------------ //

	                    // ------------------------------------Permanent Address------------------------------------ //
	                    $permanent_address[] = array('permanent_country'    => '',
	                                        'permanent_city'                => '',
	                                        'permanent_state'               => '',
	                                        'permanent_postal_code'         => ''
	                                        );
	                    $permanent_address = json_encode($permanent_address);
	                    // ------------------------------------Permanent Address------------------------------------ //

	                    // ------------------------------------Family Information------------------------------------ //
	                    $family_info[] = array('father'             => '',
	                                        'mother'                => '',
	                                        'brother_sister'        => ''
	                                        );
	                    $family_info = json_encode($family_info);
	                    // ------------------------------------Family Information------------------------------------ //

	                    // --------------------------------- Additional Personal Details--------------------------------- //
	                    $additional_personal_details[] = array('home_district'  => '',
	                                        'family_residence'              => '',
	                                        'fathers_occupation'            => '',
	                                        'special_circumstances'         => ''
	                                        );
	                    $additional_personal_details = json_encode($additional_personal_details);
	                    // --------------------------------- Additional Personal Details--------------------------------- //

	                    // ------------------------------------ Partner Expectation------------------------------------ //
	                    $partner_expectation[] = array('general_requirement'    => '',
	                                        'partner_age'                       => '',
	                                        'partner_height'                    => '',
	                                        'partner_weight'                    => '',
	                                        'partner_marital_status'            => '',
	                                        'with_children_acceptables'         => '',
	                                        'partner_country_of_residence'      => '',
	                                        'partner_religion'                  => '',
	                                        'partner_caste'                     => '',
	                                        'partner_subcaste'                  => '',
	                                        'partner_complexion'                => '',
	                                        'partner_education'                 => '',
	                                        'partner_profession'                => '',
	                                        'partner_drinking_habits'           => '',
	                                        'partner_smoking_habits'            => '',
	                                        'partner_diet'                      => '',
	                                        'partner_body_type'                 => '',
	                                        'partner_personal_value'            => '',
	                                        'manglik'                           => '',
	                                        'partner_any_disability'            => '',
	                                        'partner_mother_tongue'             => '',
	                                        'partner_family_value'              => '',
	                                        'prefered_country'                  => '',
	                                        'prefered_state'                    => '',
	                                        'prefered_status'                   => ''
	                                        );
	                    $partner_expectation = json_encode($partner_expectation);
	                    // ------------------------------------ Partner Expectation------------------------------------ //

	                    // ------------------------------------Privacy Status------------------------------------ //
	                    $privacy_status[] = array(
	                                        'present_address'                 => 'no',
	                                        'education_and_career'            => 'no',
	                                        'physical_attributes'             => 'no',
	                                        'language'                        => 'no',
	                                        'hobbies_and_interest'            => 'no',
	                                        'personal_attitude_and_behavior'  => 'no',
	                                        'residency_information'           => 'no',
	                                        'spiritual_and_social_background' => 'no',
	                                        'life_style'                      => 'no',
	                                        'astronomic_information'          => 'no',
	                                        'permanent_address'               => 'no',
	                                        'family_info'                     => 'no',
	                                        'additional_personal_details'     => 'no',
	                                        'partner_expectation'             => 'yes'
	                                        );
	                    $privacy_status = json_encode($privacy_status);
	                    // ------------------------------------Privacy Status------------------------------------ //

	                    // ------------------------------------Pic Privacy Status------------------------------------ //
                        $pic_privacy[] = array(
                                            'profile_pic_show'        => 'all',
                                            'gallery_show'            => 'premium'

                                            );
                        $data_pic_privacy = json_encode($pic_privacy);
                        // ------------------------------------Pic Privacy Status------------------------------------ //

	                    // --------------------------------- Additional Personal Details--------------------------------- //
	                    $package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', $this->input->post('plan')),
	                                            'package_price'     => $this->Crud_model->get_type_name_by_id('plan', $this->input->post('plan'), 'amount'),
	                                            'payment_type'      => 'None',
	                                        );
	                    $package_info = json_encode($package_info);
	                    // --------------------------------- Additional Personal Details--------------------------------- //


	                        $data['status'] = 'approved';
	                        $data['first_name'] = $this->input->post('fname');
	                        $data['last_name'] = $this->input->post('lname');
	                        $data['gender'] = $this->input->post('gender');
	                        $data['email'] = $this->input->post('email');
	                        $data['date_of_birth'] = strtotime($this->input->post('date_of_birth'));
	                        $data['height'] = 0.00;
	                        $data['mobile'] = $this->input->post('mobile');
	                        $data['password'] = sha1($this->input->post('password'));
	                        $data['profile_image'] = $profile_image;
	                        $data['introduction'] = '';
	                        $data['basic_info'] = $basic_info;
	                        $data['present_address'] = $present_address;
	                        $data['family_info'] = $family_info;
	                        $data['education_and_career'] = $education_and_career;
	                        $data['physical_attributes'] = $physical_attributes;
	                        $data['language'] = $language;
	                        $data['hobbies_and_interest'] = $hobbies_and_interest;
	                        $data['personal_attitude_and_behavior'] = $personal_attitude_and_behavior;
	                        $data['residency_information'] = $residency_information;
	                        $data['spiritual_and_social_background'] = $spiritual_and_social_background;
	                        $data['life_style'] = $life_style;
	                        $data['astronomic_information'] = $astronomic_information;
	                        $data['permanent_address'] = $permanent_address;
	                        $data['additional_personal_details'] = $additional_personal_details;
	                        $data['partner_expectation'] = $partner_expectation;
	                        $data['interest'] = '[]';
	                        $data['short_list'] = '[]';
	                        $data['followed'] = '[]';
	                        $data['ignored'] = '[]';
	                        $data['ignored_by'] = '[]';
	                        $data['gallery'] = '[]';
	                        $data['happy_story'] = '[]';
	                        $data['package_info'] = $package_info;
	                        $data['payments_info'] = '[]';
	                        $data['interested_by'] = '[]';
	                        $data['follower'] = 0;
	                        $data['notifications'] = '[]';
	                        $plan = $this->input->post('plan');
	                        if ($plan == 1) {
	                        	$data['membership'] = 1;
	                        }
	                        else {
	                        	$data['membership'] = 2;
	                        }
	                        $data['profile_status'] = 1;
	                        $data['is_closed'] = 'no';
	                        $data['member_since'] = date("Y-m-d H:i:s");
	                        $data['express_interest'] = $this->db->get_where('plan', array('plan_id'=> $plan))->row()->express_interest;
	                        $data['direct_messages'] = $this->db->get_where('plan', array('plan_id'=> $plan))->row()->direct_messages;
	                        $data['photo_gallery'] = $this->db->get_where('plan', array('plan_id'=> $plan))->row()->photo_gallery;
	                        $data['profile_completion'] = 0;
	                        $data['is_blocked'] = 'no';
	                        $data['privacy_status'] = $privacy_status;
	                        $data['pic_privacy'] = $data_pic_privacy;
							$data['report_profile'] = '[]';

	                        $this->db->insert('member', $data);
	                        $insert_id = $this->db->insert_id();
                            $member_profile_id = strtoupper(substr(hash('sha512', rand()), 0, 8)).$insert_id;

                            $this->db->where('member_id', $insert_id);
                            $this->db->update('member', array('member_profile_id' => $member_profile_id));
	                        recache();

	                        // $msg = 'done';
	                        if ($this->Email_model->account_opening('member', $data['email'], $this->input->post('password')) == false) {
	                            //$msg = 'done_but_not_sent';
	                        } else {
	                            //$msg = 'done_and_sent';
	                        }
	                        // $msg = 'done';
	                        if ($this->Email_model->member_registration_email_to_admin($insert_id) == false) {
	                            //$msg = 'done_but_not_sent';
	                        } else {
	                            //$msg = 'done_and_sent';
	                        }

	                        $this->session->set_flashdata('alert', 'add');
	                        redirect(base_url().'admin/members/add_member', 'refresh');

		            }
				}
			}
			elseif ($para1=="update_member") {
				$this->form_validation->set_rules('introduction', 'Introduction', 'required');

				$this->form_validation->set_rules('first_name', 'First Name', 'required');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required');
				$this->form_validation->set_rules('gender', 'Gender', 'required');
	            $this->form_validation->set_rules('on_behalf', 'On Behalf', 'required');
	            if ($this->input->post('old_email') != $this->input->post('email')) {
	                $this->form_validation->set_rules('email', 'Email', 'required|is_unique[member.email]',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
	            }
	            if ($this->input->post('old_mobile') != $this->input->post('mobile')) {
	                $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[member.mobile]',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
	            }
	            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
				$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');

				if ($this->db->get_where('frontend_settings', array('type' => 'present_address'))->row()->value == "yes")
				{
					$this->form_validation->set_rules('country', 'Country', 'required');
		            $this->form_validation->set_rules('state', 'State', 'required');
				}

	            if ($this->db->get_where('frontend_settings', array('type' => 'education_and_career'))->row()->value == "yes")
				{
		            $this->form_validation->set_rules('highest_education', 'Highest Education', 'required');
		            $this->form_validation->set_rules('occupation', 'Occupation', 'required');
				}

				if ($this->db->get_where('frontend_settings', array('type' => 'language'))->row()->value == "yes")
				{
		            $this->form_validation->set_rules('mother_tongue', 'Mother Tongue', 'required');
				}

				if ($this->db->get_where('frontend_settings', array('type' => 'residency_information'))->row()->value == "yes")
				{
		            $this->form_validation->set_rules('birth_country', 'Birth Country', 'required');
		            $this->form_validation->set_rules('citizenship_country', 'Citizenship Country', 'required');
				}

				if ($this->db->get_where('frontend_settings', array('type' => 'spiritual_and_social_background'))->row()->value == "yes")
				{
		            $this->form_validation->set_rules('religion', 'Religion', 'required');
				}

				if ($this->db->get_where('frontend_settings', array('type' => 'permanent_address'))->row()->value == "yes")
				{
		            $this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required');
		            $this->form_validation->set_rules('permanent_state', 'Permanent State', 'required');
				}

	            if ($this->form_validation->run() == FALSE) {
	            	$page_data['top'] 		= "members/index.php";
					$page_data['folder'] 	= "members";
					$page_data['file']	 	= "edit_member.php";
					$page_data['bottom'] 	= "members/index.php";
					$page_data['page_name'] = "edit_member";
					$page_data['form_error'] = "yes";
	                $page_data['form_contents'] = $this->input->post();
	                $this->session->set_flashdata('alert', 'edit_fail');
	                if ($para3 == 'premium_members') {
	                	$page_data['get_premium_member_by_id'] = $this->db->get_where("member", array("membership" => 2, "member_id" => $para2))->result();
		                $page_data['member_type'] = "Premium";
						$page_data['parameter'] = "premium_members";
						$page_data['page_name'] = "premium_members";
	                }
	                elseif ($para3 == 'users') {
	                	$page_data['get_free_member_by_id'] = $this->db->get_where("member", array("membership" => 1, "member_id" => $para2))->result();
		                $page_data['member_type'] = "Free";
						$page_data['parameter'] = "users";
						$page_data['page_name'] = "users";
	                }

	                $this->load->view('welcome_message', $page_data);
	            }
	            else {
	            	$data['first_name'] = $this->input->post('first_name');
	            	$data['last_name'] = $this->input->post('last_name');
	            	$data['gender'] = $this->input->post('gender');
	            	$data['email'] = $this->input->post('email');
	                $data['mobile'] = $this->input->post('mobile');
	                $data['date_of_birth'] = strtotime($this->input->post('date_of_birth'));
	                $data['height'] = $this->input->post('height');
	            	$data['introduction'] = $this->input->post('introduction');

	            	// ------------------------------------Basic Info------------------------------------ //
	            	$basic_info[] = array(
	    								'marital_status'		=>	$this->input->post('marital_status'),
	    								'number_of_children'	=>	$this->input->post('number_of_children'),
	    								'area'					=>	$this->input->post('area'),
	                                    'on_behalf'             =>  $this->input->post('on_behalf')
				                        );
	            	$data['basic_info'] = json_encode($basic_info);
	            	// ------------------------------------Basic Info------------------------------------ //

	            	// ------------------------------------Present Address------------------------------------ //
	            	$present_address[] = array('country'		=>  $this->input->post('country'),
	    								'city'					=>	$this->input->post('city'),
	    								'state'					=>	$this->input->post('state'),
	    								'postal_code'			=>	$this->input->post('postal_code')
				                        );
	            	$data['present_address'] = json_encode($present_address);
	            	// ------------------------------------Present Address------------------------------------ //

	            	// ------------------------------------Education & Career------------------------------------ //
	            	$education_and_career[] = array('highest_education'	=>  $this->input->post('highest_education'),
	    								'occupation'					=>	$this->input->post('occupation'),
	    								'annual_income'					=>	$this->input->post('annual_income')
				                        );
	            	$data['education_and_career'] = json_encode($education_and_career);
	            	// ------------------------------------Education & Career------------------------------------ //

	            	// ------------------------------------ Physical Attributes------------------------------------ //
	            	$physical_attributes[] = array('weight'     =>	$this->input->post('weight'),
	    								'eye_color'				=>	$this->input->post('eye_color'),
	    								'hair_color'			=>	$this->input->post('hair_color'),
	    								'complexion'			=>	$this->input->post('complexion'),
	    								'blood_group'			=>	$this->input->post('blood_group'),
	    								'body_type'				=>	$this->input->post('body_type'),
	    								'body_art'				=>	$this->input->post('body_art'),
	    								'any_disability'		=>	$this->input->post('any_disability')
				                        );
	            	$data['physical_attributes'] = json_encode($physical_attributes);
	            	// ------------------------------------ Physical Attributes------------------------------------ //

	            	// ------------------------------------ Language------------------------------------ //
	            	$language[] = array('mother_tongue'			=>  $this->input->post('mother_tongue'),
	    								'language'				=>	$this->input->post('language'),
	    								'speak'					=>	$this->input->post('speak'),
	    								'read'					=>	$this->input->post('read')
				                        );
	            	$data['language'] = json_encode($language);
	            	// ------------------------------------ Language------------------------------------ //

	            	// ------------------------------------Hobbies & Interest------------------------------------ //
	            	$hobbies_and_interest[] = array('hobby'	    =>  $this->input->post('hobby'),
	            						'interest'				=>  $this->input->post('interest'),
	    								'music'					=>	$this->input->post('music'),
	    								'books'					=>	$this->input->post('books'),
	    								'movie'					=>	$this->input->post('movie'),
	    								'tv_show'				=>	$this->input->post('tv_show'),
	    								'sports_show'			=>	$this->input->post('sports_show'),
	    								'fitness_activity'		=>	$this->input->post('fitness_activity'),
	    								'cuisine'				=>	$this->input->post('cuisine'),
	    								'dress_style'			=>	$this->input->post('dress_style')
				                        );
	            	$data['hobbies_and_interest'] = json_encode($hobbies_and_interest);
	            	// ------------------------------------Hobbies & Interest------------------------------------ //

	            	// ------------------------------------ Personal Attitude & Behavior------------------------------------ //
	            	$personal_attitude_and_behavior[] = array('affection'	=>  $this->input->post('affection'),
	                    								'humor'             =>	$this->input->post('humor'),
	                    								'political_view'    =>	$this->input->post('political_view'),
	                    								'religious_service' =>	$this->input->post('religious_service')
	                			                        );
	            	$data['personal_attitude_and_behavior'] = json_encode($personal_attitude_and_behavior);
	            	// ------------------------------------ Personal Attitude & Behavior------------------------------------ //

	            	// ------------------------------------Residency Information------------------------------------ //
	            	$residency_information[] = array('birth_country'	=>  $this->input->post('birth_country'),
	    								'residency_country'		=>	$this->input->post('residency_country'),
	    								'citizenship_country'	=>	$this->input->post('citizenship_country'),
	    								'grow_up_country'		=>	$this->input->post('grow_up_country'),
	    								'immigration_status'	=>	$this->input->post('immigration_status')
				                        );
	            	$data['residency_information'] = json_encode($residency_information);
	            	// ------------------------------------Residency Information------------------------------------ //

	            	// ------------------------------------Spiritual and Social Background------------------------------------ //
	            	$spiritual_and_social_background[] = array('religion'	=>  $this->input->post('religion'),
	    								'caste'					=>	$this->input->post('caste'),
	    								'sub_caste'				=>	$this->input->post('sub_caste'),
	    								'ethnicity'				=>	$this->input->post('ethnicity'),
	    								'personal_value'		=>	$this->input->post('personal_value'),
	    								'family_value'			=>	$this->input->post('family_value'),
	                                    'u_manglik'             =>  $this->input->post('u_manglik'),
	    								'community_value'		=>	$this->input->post('community_value'),
	                                    'family_status'         =>  $this->input->post('family_status')
				                        );
	            	$data['spiritual_and_social_background'] = json_encode($spiritual_and_social_background);
	            	// ------------------------------------Spiritual and Social Background------------------------------------ //

	            	// ------------------------------------ Life Style------------------------------------ //
	            	$life_style[] = array('diet'				=>  $this->input->post('diet'),
	    								'drink'					=>	$this->input->post('drink'),
	    								'smoke'					=>	$this->input->post('smoke'),
	    								'living_with'			=>	$this->input->post('living_with')
				                        );
	            	$data['life_style'] = json_encode($life_style);
	            	// ------------------------------------ Life Style------------------------------------ //

	            	// ------------------------------------ Astronomic Information------------------------------------ //
	            	$astronomic_information[] = array('sun_sign'	=>  $this->input->post('sun_sign'),
	    								'moon_sign'					=>	$this->input->post('moon_sign'),
	    								'time_of_birth'				=>	$this->input->post('time_of_birth'),
	    								'city_of_birth'				=>	$this->input->post('city_of_birth')
				                        );
	            	$data['astronomic_information'] = json_encode($astronomic_information);
	            	// ------------------------------------ Astronomic Information------------------------------------ //

	            	// ------------------------------------Permanent Address------------------------------------ //
	            	$permanent_address[] = array('permanent_country'	=>  $this->input->post('permanent_country'),
	    								'permanent_city'				=>	$this->input->post('permanent_city'),
	    								'permanent_state'				=>	$this->input->post('permanent_state'),
	    								'permanent_postal_code'			=>	$this->input->post('permanent_postal_code')
				                        );
	            	$data['permanent_address'] = json_encode($permanent_address);
	            	// ------------------------------------Permanent Address------------------------------------ //

	            	// ------------------------------------Family Information------------------------------------ //
	            	$family_info[] = array('father'				=>  $this->input->post('father'),
	    								'mother'				=>	$this->input->post('mother'),
	    								'brother_sister'		=>	$this->input->post('brother_sister')
				                        );
	            	$data['family_info'] = json_encode($family_info);
	            	// ------------------------------------Family Information------------------------------------ //

	            	// ------------------------------------ Additional Personal Details------------------------------------ //
	            	$additional_personal_details[] = array('home_district'	=>  $this->input->post('home_district'),
	    								'family_residence'				=>	$this->input->post('family_residence'),
	    								'fathers_occupation'			=>	$this->input->post('fathers_occupation'),
	    								'special_circumstances'			=>	$this->input->post('special_circumstances')
				                        );
	            	$data['additional_personal_details'] = json_encode($additional_personal_details);
	            	// ------------------------------------ Additional Personal Details------------------------------------ //

	            	// ------------------------------------ Partner Expectation------------------------------------ //
	            	$partner_expectation[] = array('general_requirement'	=>  $this->input->post('general_requirement'),
	    								'partner_age'						=>	$this->input->post('partner_age'),
	    								'partner_height'					=>	$this->input->post('partner_height'),
	    								'partner_weight'					=>	$this->input->post('partner_weight'),
	    								'partner_marital_status'			=>	$this->input->post('partner_marital_status'),
	    								'with_children_acceptables'			=>	$this->input->post('with_children_acceptables'),
	    								'partner_country_of_residence'		=>	$this->input->post('partner_country_of_residence'),
	    								'partner_religion'					=>	$this->input->post('partner_religion'),
	    								'partner_caste'						=>	$this->input->post('partner_caste'),
	    								'partner_complexion'				=>	$this->input->post('partner_complexion'),
	    								'partner_education'                 =>	$this->input->post('partner_education'),
	    								'partner_profession'				=>	$this->input->post('partner_profession'),
	    								'partner_drinking_habits'			=>	$this->input->post('partner_drinking_habits'),
	    								'partner_smoking_habits'			=>	$this->input->post('partner_smoking_habits'),
	    								'partner_diet'						=>	$this->input->post('partner_diet'),
	    								'partner_body_type'					=>	$this->input->post('partner_body_type'),
	    								'partner_personal_value'			=>	$this->input->post('partner_personal_value'),
	    								'manglik'							=>	$this->input->post('manglik'),
	    								'partner_any_disability'			=>	$this->input->post('partner_any_disability'),
	    								'partner_mother_tongue'				=>	$this->input->post('partner_mother_tongue'),
	    								'partner_family_value'				=>	$this->input->post('partner_family_value'),
	    								'prefered_country'					=>	$this->input->post('prefered_country'),
	    								'prefered_state'					=>	$this->input->post('prefered_state'),
	    								'prefered_status'					=>	$this->input->post('prefered_status')
				                        );
	            	$data['partner_expectation'] = json_encode($partner_expectation);
	            	// ------------------------------------ Partner Expectation------------------------------------ //
	            	// Profile Image 
	            	if ($_FILES['profile_image']['name'] !== '') {
		                $path = $_FILES['profile_image']['name'];
		                $ext = '.' . pathinfo($path, PATHINFO_EXTENSION);
		                if ($ext==".jpg" || $ext==".JPG" || $ext==".jpeg" || $ext==".JPEG" || $ext==".png" || $ext==".PNG") {
		                    $this->Crud_model->file_up("profile_image", "profile", $para2, '', '', $ext);
		                    $images[] = array('profile_image' => 'profile_' . $para2 . $ext, 'thumb' => 'profile_' . $para2 . '_thumb' . $ext);
		                    $data['profile_image'] = json_encode($images);
		                }
		            }

	                $this->db->where('member_id', $para2);
	                $result = $this->db->update('member', $data);
	                recache();
	                if ($result) {
	                    $this->session->set_flashdata('alert', 'edit');
	                    redirect(base_url().'admin/members/'. $para3, 'refresh');
	                }
	            }
			}
			elseif ($para1=="upgrade_member_package") {
				$up_member_id = $this->input->post('up_member_id');
				$plan_id = $this->input->post('plan');
				$member_type = $this->input->post('member_type');

				$prev_express_interest =  $this->db->get_where('member', array('member_id' => $up_member_id))->row()->express_interest;
                $prev_direct_messages = $this->db->get_where('member', array('member_id' => $up_member_id))->row()->direct_messages;
                $prev_photo_gallery = $this->db->get_where('member', array('member_id' => $up_member_id))->row()->photo_gallery;

                if ($plan_id == '1') {
                	$data['membership'] = 1;
                } else {
                	$data['membership'] = 2;
                }

                $data['express_interest'] = $prev_express_interest + $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->express_interest;
                $data['direct_messages'] = $prev_direct_messages + $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->direct_messages;
                $data['photo_gallery'] = $prev_photo_gallery + $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->photo_gallery;

                $package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', $plan_id),
                                'package_price'     => $this->Crud_model->get_type_name_by_id('plan', $plan_id, 'amount'),
                                'payment_type'      => 'By Admin',
                            );
                 $data['package_info'] = json_encode($package_info);

                $this->db->where('member_id', $up_member_id);
                $result = $this->db->update('member', $data);
                recache();
                if ($result) {
                    $this->session->set_flashdata('alert', 'upgrade');
                    redirect(base_url().'admin/members/'. $member_type, 'refresh');
                }
			}
		}
	}
}
