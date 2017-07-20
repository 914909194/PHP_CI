<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class User_model extends CI_Model{
		public function user_insert($email,$uname,$pass,$pass2,$gender,$province){
			$time= date("Y-m-d");
			$arr=array(
				'ACCOUNT'=>$email,
				'NAME'=>$uname,
				'PASSWORD'=>$pass,
				'GENDER'=>$gender,
				'BIRTHDAY'=>$time,
				'PROVINCE'=>$province,
			);
			$query=$this->db->insert('t_users',$arr);
			return $query;
		}

		public function blog_content(){
			$this->db->select('*');
			$this->db->from('t_blogs');
			$this->db->order_by('BLOG_ID','desc');
			$this->db->join('t_blog_catalogs','t_blog_catalogs.CATALOG_ID=t_blogs.CATALOG_ID');
			$query=$this->db->get();
			$result=$query->result();
			return $result;
		}
		public function allContent(){
			$arr=array(
				'BLOG_ID'=>$_GET['id'],
				
			);

			$query=$this->db->get_where('t_blogs',$arr);
			$result=$query->row();
			return $result;
		}
		public function blog_catalog_all(){
			$this->db->select('*');
			$this->db->from('t_blog_catalogs');
			$query=$this->db->get();
			$result=$query->result();
			return $result;
		}
		public function sel_login($uname,$pass){
			$sql="select * from t_users where ACCOUNT='$uname' and PASSWORD='$pass'";
			$query=$this->db->query($sql);
			return $query->row();
		}
		public function get_name($uname,$pass){
			$arr=array(
				'ACCOUNT'=>$uname,
				'PASSWORD'=>$pass
			);
			
			$query=$this->db->get_where('t_users',$arr);
			return $query->row();
		}
		public function addBlog($catalog_id,$wt,$title,$content,$addTime,$is_Yours){
			$arr=array(
				'CATALOG_ID'=>$catalog_id,
				'WRITER'=>$wt,
				'TITLE'=>$title,
				'CONTENT'=>$content,
				'ADD_TIME'=>$addTime,
				'IS_YOURS'=>$is_Yours,
			);
			$query=$this->db->insert('t_blogs',$arr);
			return $query;

		}
		public function blog_soso($se){
			$sql="select * from t_blogs,t_blog_catalogs where t_blog_catalogs.CATALOG_ID=t_blogs.CATALOG_ID and t_blogs.TITLE like '%$se%' order by BLOG_ID desc";
			$query=$this->db->query($sql);
			return $query->result();

		}
	}



 ?>