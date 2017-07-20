<?php  defined('BASEPATH') OR exit('No direct script access allowed');
	class User extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->model('user_model');
			$this->load->helper('captcha');
		}
		public function reg(){
			$cap=$this->cap();
			$arr['data']=$cap;

			$this->load->view('reg.php',$arr);
		}
		public function login(){
			$this->load->view('login.php');
		}
		public function clogin(){
			// header('content-type:application/json;charset=utf8'); 
			header('Access-Control-Allow-Origin:*');
			header('Access-Control-Allow-Headers:X-Requested-With,Content-Type');
			$uname=$this->input->post('email');
			$pass=$this->input->post('pwd');
			$this->load->model("user_model");
			$result=$this->user_model->get_name($uname,$pass);
			if($result){
				$arr=array(
					'USER_ID'=>$result->USER_ID,
					'NAME'=>$result->NAME,
					'login_in'=>TRUE
					);
				$this->input->set_cookie('name',$result->NAME,time()+180);
				$this->session->set_userdata($arr);
				redirect("user/index_logined");
			}else{
				redirect("user/login");
			}

		}
		public function index_logined(){
			$this->load->model('user_model');
			$result=$this->user_model->blog_content();
			$arr['data']=$result;
			$result=$this->user_model->blog_catalog_all();
			$arr['data2']=$result;

			$this->load->view('index_logined.php',$arr);
		}
		public function viewPost(){
			// $blogid=$this->input->get('id');
			$result=$this->user_model->allContent();
			$arr['data']=$result;
			$this->load->view('viewPost.php',$arr);
		}
		public function index(){
			$this->load->view('index.php');
		}
		public function cap(){
			
			$vals = array(
			// $input=array('a','b','d','h','f');
				// $str="";
				// for($i=0;$i<4;$i++){
				// 	$val=array_random($input,1);
				// 	$str.=$input($val);
			// }
			    'word'      => rand(1000,9999),
			    'img_path'  => './captcha/',
			    'img_url'   => base_url().'captcha',
			    'font_path' => './path/to/fonts/texb.ttf',
			    'img_width' => '150',
			    'img_height'    => 30,
			    'expiration'    => 200,
			    'word_length'   => 8,
			    'font_size' => 16,
			    'img_id'    => 'Imageid',
			    'pool'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

			    // White background and border, black text and red grid
			    'colors'    => array(
			        'background' => array(255, 255, 255),
			        'border' => array(255, 255, 255),
			        'text' => array(0, 0, 0),
			        'grid' => array(255, 40, 40)
			    )
			);

			$cap = create_captcha($vals);
			$_SESSION['code']="";
			$_SESSION['code']=$cap['word'];
			return $cap;
			// echo $cap['image'];
		}
		public function do_reg(){
			$email=$this->input->post('email');
			$uname=$this->input->post('name');
			$pass=$this->input->post('pwd');
			$pass2=$this->input->post('pwd2');
			$gender=$this->input->post('gender');
			$province=$this->input->post('province');
			$verifyCode=$this->input->post('verifyCode');
			if($verifyCode==$_SESSION['code']){
				$query=$this->user_model->user_insert($email,$uname,$pass,$pass2,$gender,$province);
			}
			
			if($query){
				redirect("user/login");
			}else{
				redirect("user/reg");
			}
		}
		public function ajaxcheck(){
			header('Access-Control-Allow-Origin:*');
			header('Access-Control-Allow-Headers:X-Requested-With,Content-Type');
			$cap=$this->cap();
			echo $cap['filename'].'-'.$cap['word'];
		}
		public function checkcode(){
			$val=$this->input->post('var');
			if($val==$_SESSION['code']){
				echo "success";
			}else{
				echo "fail";
			}
		}
		public function newBlog(){
			$this->load->view('newBlog.php');
		}
		public function addBlog(){
			$catalog_id='3';
			$wt=$_SESSION['USER_ID'];
			$title=$this->input->post('title');
			$content=$this->input->post('content');
			$addTime=date("Y-m-d");
			$is_Yours=$this->input->post('type');
			$query=$this->user_model->addBlog($catalog_id,$wt,$title,$content,$addTime,$is_Yours);		
			if($query){
				redirect("user/index_logined");
			}else{
				redirect("user/newBlog");
			}
		}
		public function soso(){
			$se=$this->input->post('soso');
			$result=$this->user_model->blog_soso($se);
			$arr['data']=$result;
			$result=$this->user_model->blog_catalog_all();
			$arr['data2']=$result;
			$this->load->view('index_logined.php',$arr);
		}



	}

 ?>