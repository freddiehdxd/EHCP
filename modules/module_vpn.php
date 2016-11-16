<?php
define('vpn_module_file_loaded','');

class Vpn_Module extends Module {

	public $vpntable=array(
			'tablename'=>'vpnusers',
			'listfields'=>array('id','panelusername','vpnusername','type','datetime','name','email'),
			'insertfields'=>array('vpnusername','password','name','email'),
			'linkimages'=>array('images/delete1.jpg'),
			'linkfiles'=>array('?op=del_vpn'),
			'linkfield'=>'id'
	);

	public function __construct($app,$name='') {
		if($name=='') $name='vpn module';
		parent::__construct($app,$name);
	}

	# pptpd kullanarak vpn yapan modul olacak. 
	# http://silverlinux.blogspot.com.tr/2012/05/how-to-pptp-vpn-on-ubuntu-1204-pptpd.html
	# 
	# aslında sadece, /etc/ppp/chap-secrets dosyasını yazacak olacam bitecek.. 
	# [Username] [Service] [Password] [Allowed IP Address]
	# sampleusername pptpd samplepassword *
	# kurulumu ayrıca veya burada olacak, bilmiyorum. 
	# 
	# Module todo: 
	# run_op -> should be here, not main file, 
	# check_tables should be here, not main file, (checked upon login)
	# 

	public function add_vpn(){
		$tb=$this->vpntable;

		foreach($tb['insertfields'] as $insertfield) {
			if(is_array($insertfield)) $insertfield=$insertfield[0];
			global $$insertfield;
		}

		$ret=$this->app->getVariable($tb['insertfields']);

		#if(!$this->beforeInputControls("addpaneluser")) return false;


		if(!$vpnusername){
			#$this->output.="Adding panel user:<br>".inputform4($action, array('','',"maxdomains","maxemails","maxpanelusers",'maxftpusers','Max Mysql Databases',"Quota (Mb)"),array("panelusername","password","maxdomains","maxemails","maxpanelusers",'maxftpusers','maxdbs',"quota"),$deger,array("op"),array("addpaneluser"));
			$this->app->output.="Adding vpn user:<br>"
				.inputform5ForTableConfig($tb,array(array('op','hidden','default'=>__FUNCTION__)));
				# i tried to remove all old function inputform5, i switched to a newer one of inputform5, left old statements commented to checkout and to understand.
				#inputform4($action, $tb['insertfieldlabels'],$tb['insertfields'],$deger,array("op"),array("addpaneluser"));
		}else {
			#if(!$this->afterInputControls("addpaneluser",array('panelusername'=>$panelusername))) return false;
			$this->app->output.="Adding user:<br>";
			$success=$this->add_vpn_user_direct($vpnusername,$password,$name,$email);
			$success=$success && $this->app->addDaemonOp('daemon_vpn','sync_vpn','xx');
			$this->app->ok_err_text($success,"Added vpn user successfully.",'failed to add panel user');
		}
		$this->app->showSimilarFunctions('vpn');
		return $success;

	}

	public function add_vpn_user_direct($vpnusername,$password,$name,$email){
		return $this->app->executequery("insert into vpnusers (panelusername,vpnusername,`password`,type,datetime,name,email) values ('{$this->app->activeuser}','$vpnusername','$password','pptp',now(),'$name','$email')");
	}

	public function del_vpn(){
		$alanlar=array('id');
		foreach($alanlar as $al) global $$al;
		$degerler=$this->app->getVariable($alanlar);

		$success=$this->app->executequery("delete from vpnusers where id=$id and panelusername='{$this->app->activeuser}'");
		$success=$success && $this->app->addDaemonOp('daemon_vpn','sync_vpn','xx');
		$this->app->output.="Vpn user deleted !";
		$this->app->showSimilarFunctions("vpn");	
		return True;
	}

	public function list_vpn(){
		$this->app->listTable2('',$this->vpntable,"panelusername='{$this->app->activeuser}'");
		$this->app->showSimilarFunctions("vpn");	
	}

	public function daemon_vps($params){
		$action=$params['action'];
		$vps=$params['info'];
		
		$this->app->requireCommandLine(__FUNCTION__);
		switch($action){
			case 'sync_vpn'	: $this->sync_vpn();
		}

	}

	public function sync_vpn(){
		# to be coded ; https://help.ubuntu.com/community/PPTPServer
		print "vpn being synced... \n";
		$ret=$this->app->query("select * from vpnusers");
		$icerik="";
		foreach ($ret as $vpn) {
			$icerik.=$vpn['vpnusername']."	pptpd 	".$vpn['password']."	*";
		}

		$f=file_put_contents("/etc/ppp/chap-secrets", $icerik);
		passthru("service pptpd restart");
		passthru("sysctl net.ipv4.ip_forward=1"); # forward ip packets

		/*
		/etc/ppp/chap-secrets
			test    pptpd   1234	*
		/etc/pptpd.conf

		pptpd kullanılıyor, kullanıcılar da /etc/ppp/chap-secrets içine yazılıyor. dbden okuyup, oraya yazılacak. 

		*/

	}


		
} # end class



?>
