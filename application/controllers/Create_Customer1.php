<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Create_Customer extends CI_Controller {
 public function __construct() 
       {
            parent::__construct();

            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->load->helper("url");
            $this->load->library('session');
            $this->load->library('PHPExcel');
            $this->load->library('pagination');
            if (!$this->session->userdata('is_logged_in'))
            {
            $this->ctrl_logout();
            redirect(base_url(), 'refresh');
            }
       }
       public function index()
		{
			$this->load->view('create_customer');
		}
                public function ctrl_logout() {
        $this->session->sess_destroy();
    }
		public function check_email() 
		{ 
		
			$m="";
			$email = $_POST['email_data'];        
			$this->load->model('Create_Customer_data');      
			$email_add = $this->Create_Customer_data->db_chk_email($email);
                       $rowcount = $email_add->num_rows();
			if ($rowcount == 0)
				{
					$m=1;
					echo $m;			
				} 
			else 
				{
					$m=0;
					echo $m;      
				}
				
		}
                public function check_phone() 
		{ 
		
			$m="";
			$phone = $_POST['phone_data'];        
			$this->load->model('Create_Customer_data');      
			$mobile_add = $this->Create_Customer_data->db_chk_phone($phone);
                         $rowcount = $mobile_add->num_rows();
			if ($rowcount == 0)
				{
					$m=1;
					echo $m;			
				} 
			else 
				{
					$m=0;
					echo $m;      
				}
				
		}
                function check_name() 
        {       
            $code = $_POST['code_data'];
            $this->load->model('Create_Customer_data');
            $code_data = $this->Create_Customer_data->db_chk_name($code);
            $rowcount = $code_data->num_rows();
			if ($rowcount == 0)
				{
					$m=1;
					echo $m;			
				} 
			else 
				{
					$m=0;
					echo $m;      
				}
			
        }
                function customer_save_data()
                {
                    
                        $this->load->model('Create_Customer_data');
                        $last_inserted_id = $this->Create_Customer_data->db_save_customer_data();      
                        $this->create_folder_function($last_inserted_id, "Customer");
                        $this->global_save_folder_image($last_inserted_id, "Customer", "customer_image", $_FILES["image"]["name"], "customer_txt");
                        $final_data = array
                                (
                           'redirect_path' => base_url("Create_Customer")
                             );
                        echo json_encode($final_data);          
                }
                public function get_state_data() 
                {
                        $this->load->model('Create_Customer_data');
                        $getCountry = $this->Create_Customer_data->getState_Data();
                        foreach ($getCountry->result() as $row)
                            {
                            $data1[] = $row;
                            }
                        echo json_encode($data1);
                }
                public function get_city() 
                {
                        $city = $_POST['city'];
                        $this->load->model('Create_Customer_data');
                        $get_state = $this->Create_Customer_data->getCity($city);
                        foreach ($get_state->result() as $row) 
                            {
                            $data1[] = $row;
                            }
                        echo json_encode($data1);
                }
                 function create_folder_function($last_inserted_id, $EventKey) 
                    {
                        $last_id = $last_inserted_id;
                        $Event = $EventKey;
                        $this->load->model('Create_Customer_data');
                        $base_path = $this->Create_Customer_data->db_retrieve_base_path();
                        $base_path1 = $base_path->path;
                        $middle_path = $this->Create_Customer_data->db_retrieve_base_path1($Event);
                       // print_r($middle_path);
                        foreach ($middle_path->result() as $row) 
                            {
                                $db_col = $row->folder_path;
                                $file_path = str_replace('kkkzzz', "$last_id", $db_col);
                                $mode = "0777";
                    // $dirpath ="Image_file_container/Customer/19/image";
               		  $dirpath = $base_path1 . "/" . $file_path . "/";
                                is_dir($dirpath) || mkdir($dirpath,0777, true);
                            }
                    }
                    function create_index_file($base_path2,$base_path3)
                            {
                                if (file_exists($base_path2 . "/" . $base_path3)) 
                                        {
                                            $myfile = fopen($base_path2 . "/" . $base_path3, 'r');
                                            $userName = fgets($myfile);
                                            $myfile1 = fopen($base_path2 . "/" . $base_path3, "w") or die("Unable to open file!");
                                            $txt = ++$userName;
                                            fwrite($myfile1, $txt);
                                            fclose($myfile);
                                            fclose($myfile1);
                                            return $txt;
                                        } 
                                else
                                    {
                                            $myfile1 = fopen($base_path2 . "/" . $base_path3, 'w');
                                            $txt = 0;
                                            $txt1 = ++$txt;
                                            fwrite($myfile1, $txt1);
                                            fclose($myfile1);
                                            return $txt1;
                                    }
                         }
                    function global_save_folder_image($last_inserted_id, $event, $address_path, $arr, $text) 
                      {
       // print_r($arr);
                            $msg = "";
                            $this->load->model('Create_Customer_data');
                            $base_path = $this->Create_Customer_data->db_retrieve_base_path();
                            $base_path2 = $base_path->index_file_path;
                            $base_path3 = $base_path->index_file_name;
                            $path = $this->Create_Customer_data->db_retrieve_base_path_image($address_path, $event);
                            $path1 = $path->folder_path;
                            $path2 = str_replace('kkkzzz', "$last_inserted_id", $path1);
                            $text_path = $this->Create_Customer_data->db_retrieve_base_path_text($event, $text);
                            $text_path1 = $text_path->folder_path;
                            $text_path2 = str_replace('kkkzzz', "$last_inserted_id", $text_path1);
                            $dirpath = $base_path2 . "/" . $path2;
                            $dirpath1 = $base_path2 . "/" . $text_path2;
                            $total = count(array_filter($arr));
                            if ($total > 0) {
                                $counter = 0;
                                for ($i = 0; $i < count($arr); $i++) {
                                    $_FILES["image"]["size"][$i];
                                    $allowedExts = array("gif", "jpeg", "jpg", "png");
                                    if ($_FILES["image"]["name"][$i] <> "") {
                                        $ext1 = ((explode(".", $_FILES["image"]["name"][$i])));
                                        $extension = end($ext1);
                                        if (($_FILES["image"]["size"][$i] < 40000000) && in_array($extension, $allowedExts)) {
                                            $txt = $this->create_index_file($base_path2, $base_path3);
                                            $date = date('m.d.Y.hia');
                                            $tmp_name[$i] = $_FILES["image"]["tmp_name"][$i];
                                            $name1[$i] = $_FILES["image"]["name"][$i];
                                            $chars = $txt . "_" . $date . "_";
                                            $names[$i] = $chars . "_" . $last_inserted_id . "." . $name1[$i];
                                            $str[$i] = substr($name1[$i], 0, strpos($name1[$i], '.'));
                                            $name2[$i] = $chars . "_" . $last_inserted_id . "." . $str[$i] . ".txt";
                                            move_uploaded_file($tmp_name[$i], "$dirpath/$names[$i]");
                                            $img_txt[$i] = $_POST['img_txt'][$i];
                                            $ourFileName = "$dirpath1/$name2[$i]";
                                            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
                                            fwrite($ourFileHandle, $img_txt[$i]);
                                        }
                                    }
                                    $counter++;
                                    echo $msg = ($counter == 0) ? ("Files uploaded Successfully") : ($msg);
                                }
                            } else {
                                $txt = $this->create_index_file($base_path2, $base_path3);
                                $date = date('m.d.Y.hia');
                                $chars = $txt . "_" . $date . "_";
                                $dir = "$base_path2/$event";
                                $dh = opendir($dir);
                                $name1 = "Hydrangeas";
                                while (false !== ($name1 = readdir($dh))) {
                                    $files[] = $name1;
                                }
                                $images = preg_grep('/\.jpg$/i', $files);
                                $names = $chars . "_" . $last_inserted_id . "." . $name1;
                                $imagePath = "$base_path2/Hydrangeas.jpg";
                                $newPath = "$dirpath/";
                                $ext = $chars . $last_inserted_id . ".Hydrangeas" . '.jpg';
                                $ext1 = $chars . $last_inserted_id . ".Hydrangeas" . '.txt';
                                $newName = $newPath . $ext;
                                //copy($imagePath , $newName);
                                $ourFileName = "$dirpath1/$ext1";
                                $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
                            }
                      }
               
    
}