<?php
define('vps_module_file_loaded','');

class Vps_Module extends Module {

		
	public function __construct($app,$name='') {
		if($name=='') $name='vps module';
		parent::__construct($app,$name);
		
		$this->extrasettings=False; # will be used for config		
		if($this->extrasettings) $type1="text";
		else $type1="hidden";
		
		

		$this->vps_settings=array( # to be used in settings
			array('iprange','textarea','default'=>$this->app->settings['vps']['iprange']),			
			array('netmask','default'=>$this->app->settings['vps']['netmask']),
			array('broadcast','default'=>$this->app->settings['vps']['broadcast']),
			array('gateway','default'=>$this->app->settings['vps']['gateway']),
			array('vps_image_templates','textarea','default'=>$this->app->settings['vps']['image_templates'],'readonly'=>'yes','righttext'=>'Use rescan below to update'),
			array('vps_images','lefttext'=>'Images that are<br>generated from templates','textarea','default'=>$this->app->settings['vps']['images'],'readonly'=>'yes','righttext'=>'Use rescan below to update'),
			array('vps_isos','lefttext'=>'ISO Images that can be used in CDs for loading','textarea','default'=>$this->app->settings['vps']['iso_images'],'readonly'=>'yes','righttext'=>'Use rescan below to update'),
			array('rams','lefttext'=>'Possible RAMs','textarea','default'=>$this->app->settings['vps']['rams']),
			array('cpus','lefttext'=>'Possible CPUs','textarea','default'=>$this->app->settings['vps']['cpus']),
			array('nameservers','lefttext'=>'Nameservers to be used in VPS\'s','textarea','default'=>$this->app->settings['vps']['nameservers']),
			array('virttype','radio','default'=>$this->app->settings['vps']['virttype'],'secenekler'=>array('kvm'=>'kvm','qemu'=>'qemu'))
			#array('singleserverip','default'=>$this->app->miscconfig['singleserverip'])
		);	
		$this->vps_settings_advanced=array( # to be used in settings
			array('iprange','textarea','default'=>$this->app->settings['vps']['iprange']),
			array('iprange2','textarea','default'=>$this->app->settings['vps']['iprange2'],'righttext'=>'Leave empty for single interface'),
			array('interfacetemplate','textarea','default'=>$this->app->settings['vps']['interfacetemplate'],'righttext'=>'Leave empty for default'),
			array('interfacetemplate2','textarea','default'=>$this->app->settings['vps']['interfacetemplate2'],'righttext'=>'Leave empty for single interface'),
			array('netmask','default'=>$this->app->settings['vps']['netmask']),
			array('netmask2',$type1,'default'=>$this->app->settings['vps']['netmask2'],'righttext'=>'Leave empty for single interface'),
			array('broadcast','default'=>$this->app->settings['vps']['broadcast']),
			array('broadcast2',$type1,'default'=>$this->app->settings['vps']['broadcast2'],'righttext'=>'Leave empty for single interface'),
			array('gateway','default'=>$this->app->settings['vps']['gateway']),
			array('gateway2',$type1,'default'=>$this->app->settings['vps']['gateway2'],'righttext'=>'Leave empty for single interface'),
			array('vps_image_templates','textarea','default'=>$this->app->settings['vps']['image_templates'],'readonly'=>'yes','righttext'=>'Use rescan below to update'),
			array('vps_images','lefttext'=>'Images that are<br>generated from templates','textarea','default'=>$this->app->settings['vps']['images'],'readonly'=>'yes','righttext'=>'Use rescan below to update'),
			array('vps_isos','lefttext'=>'ISO Images that can be used in CDs for loading','textarea','default'=>$this->app->settings['vps']['iso_images'],'readonly'=>'yes','righttext'=>'Use rescan below to update'),
			array('rams','lefttext'=>'Possible RAMs','textarea','default'=>$this->app->settings['vps']['rams']),
			array('cpus','lefttext'=>'Possible CPUs','textarea','default'=>$this->app->settings['vps']['cpus']),
			array('nameservers','lefttext'=>'Nameservers to be used in VPS\'s','textarea','default'=>$this->app->settings['vps']['nameservers'])
			#array('singleserverip','default'=>$this->app->miscconfig['singleserverip'])
		);		
	}
	
	function vps(){
		#var_dump($this->app);
		
		$fields=array('_insert','op2','vpsname','sure','url','image','cdimage');
		foreach($fields as $f) global $$f;	
		$this->app->getVariable($fields);

		if($op2=='edit') { 
			#$this->app->output.="Editing not implemented."; 
			$isos=textarea_to_array($this->app->settings['vps']['iso_images'],array('')); # first element empty
			if(!$_insert){
				$params=array(
					array('cdimage','select','secenekler'=>$isos,'default'=>'','righttext'=>'Select a cd image to boot from cd')
					);

				$this->app->output.=inputform5($params);			
					
			} else {
				$this->app->output.="vpsname: $vpsname, CD imajı:".$cdimage." ile boot edilecek.";
				$this->app->executeQuery("update vps set cdimage='".mysql_real_escape_string($cdimage)."' where vpsname='".mysql_real_escape_string($vpsname)."'");
				$this->app->addDaemonOp('daemon_vps','shut_start_bootcd',$vpsname,'',__FUNCTION__.":$vpsname:shutoff");				
			}
			
			return True;
		} elseif($op2=='mountimage' or $op2=='dismountimage'){
			if(!$vpsname){
				$this->app->output.=inputform5('vpsname');
			} else {
				$this->app->addDaemonOp('daemon_vps',$op2,$vpsname,'',__FUNCTION__.":$vpsname:$op2");
			}
		} elseif($op2=='other'){
			if($url) {
				$this->app->addDaemonOp('daemon_vps','download_img',$url);
				$this->app->output.="Download will be started on server soon, image will be put in images folder, will be available as an vps image.(Image urls will be available at ehcp.net site)";
			} else {
				$params=array('url');		
				$this->app->output.="Download kvm/qemu img from url into server images:".inputform5($params);
				$this->app->output.="<br>Debug/Recovery: <br><a href='?op=vps&op2=mountimage'>Mount a vps hdd</a> (under /mnt/vps for looking/modifying contents)<br>";
				$this->app->output.="<a href='?op=vps&op2=dismountimage'>DisMount a vps hdd</a><br>";
				
			}
		} elseif ($op2=='delete' and $sure=='') {
			$this->app->output.="<big>Are you sure to delete VPS named ($vpsname) ? All data in it will be deleted and lost. <a href='?op=vps&vpsname=$vpsname&op2=delete&sure=yes'>Yes</a></big><br><br>"; 				
		} else {		
			if($op2=='delete') $this->app->executeQuery("update vps set state='deleting' where vpsname='$vps'");
			$this->app->listTable("",'vpstable');
			
			if($op2<>'') {		
				$this->app->addDaemonOp('daemon_vps',$op2,$vpsname,'',__FUNCTION__.":$vpsname:$op2");
			}
		}

		$this->app->showSimilarFunctions('vps');	
		return True;
	}


function vps_dismountimage(){
	$fields=array('_insert','vpsname');
	foreach($fields as $f) global $$f;	
	$this->app->getVariable($fields);
	
	if($_insert) {
		$this->app->addDaemonOp('daemon_vps','dismountimage',$vpsname,'',__FUNCTION__.":$vpsname:$op2");
	} else {
		$this->output.=inputform5('vpsname');
	}

}

function vps_mountimage(){
	$fields=array('_insert','vpsname');
	foreach($fields as $f) global $$f;	
	$this->app->getVariable($fields);
	
	if($_insert) {
		$this->app->addDaemonOp('daemon_vps','mountimage',$vpsname,'',__FUNCTION__.":$vpsname:$op2");
	} else {
		$this->output.=inputform5('vpsname');
	}

}

function add_vps(){	
	$fields=array('_insert','vpsname','description','ip','ip2','image_template','cdimage','ram','cpu','vncpassword');
	foreach($fields as $f) global $$f;	
	$this->app->getVariable($fields);
	
	
	if(!$this->vps_check_settings()) {		
		return $this->app->errorTextExit("vps settings are missing, or there is error. <a href='?op=settings&group=vps'>VPS Settings</a>");
	}
	

	$hostip=$this->app->miscconfig['dnsip'];
	
	if($_insert){
		$vpsname=str_replace(' ','',$vpsname);
		
		if(!$this->app->afterInputControls("addvps",
				array(
				"vpsname"=>$vpsname,
				"ip"=>$ip,
				"hostip"=>$hostip
				)
			)
		) return false;
		
		
		$this->app->output.="<br>VPS addition started. your command will be applied at server.";
		$this->app->executeQuery("insert into vps (panelusername,hostip,ip,ip2,broadcast,netmask,gateway,vpsname,description,image_template,cdimage,ram,cpu,state,vncpassword)values('".$this->app->activeuser."','$hostip','$ip','$ip2','".$this->app->settings['vps']['broadcast']."','".$this->app->settings['vps']['netmask']."','".$this->app->settings['vps']['gateway']."','$vpsname','$description','$image_template','$cdimage',$ram,$cpu,'non existent','$vncpassword')");
		$this->app->addDaemonOp('daemon_vps','add',$vpsname,'',__FUNCTION__.":$vpsname");
	} else {
		if(!$this->app->beforeInputControls("addvps",array())) return false;
		
		$ips=explode("\n",$this->app->settings['vps']['iprange']);
		$ips_=explode("\n",$this->app->settings['vps']['iprange2']);
		
		$ips2=array('dhcp-internal ip/net');
		$ips2_=array('dhcp-internal ip/net or none');
		
		foreach($ips as $ip1) {
			$ip1=trim($ip1);
			if($this->app->recordcount("vps","ip='$ip1'")==0) $ips2[$ip1]=$ip1; # add non used ips to new add dialog		
		}
		
		foreach($ips_ as $ip1) {
			$ip1=trim($ip1);
			if($this->app->recordcount("vps","ip2='$ip1'")==0) $ips2_[$ip1]=$ip1; # add non used ips to new add dialog		
		}		
		
		
		$vps_templates=textarea_to_array($this->app->settings['vps']['image_templates']);
		$rams=textarea_to_array($this->app->settings['vps']['rams']);
		$cpus=textarea_to_array($this->app->settings['vps']['cpus']);
		$isos=textarea_to_array($this->app->settings['vps']['iso_images'],array('')); # first element empty
		
		#$this->app->output.=print_r2($rams);
		
		
		$params=array(
			array('vpsname','righttext'=>'non-spaced simple name'),
			'description',
			array('image_template','select','secenekler'=>$vps_templates),
			array('cdimage','select','secenekler'=>$isos,'default'=>'','righttext'=>'Select a cd image to boot from cd; if selected, vps is booted directly.<br>If not selected, vps image is adjusted with IP choosen here.'),
			
			array('ip','select','secenekler'=>$ips2),
			array('ip2','select','secenekler'=>$ips2_),
			array('ram','select','secenekler'=>$rams),
			array('cpu','select','secenekler'=>$cpus),
			array('vncpassword','righttext'=>'For remote graphical connection, Suggested. leave empty to disable')
		);
		
		$this->app->output.=inputform5($params);		
	}
	$this->app->showSimilarFunctions('vps');
	
	return True;
} # end func


function copy_vps_image(){	
	$fields=array('_insert','vpsname','newfilename');
	foreach($fields as $f) global $$f;	
	$this->app->getVariable($fields);
	
	
	if(!$this->vps_check_settings()) {		
		return $this->app->errorTextExit("vps settings are missing, or there is error. <a href='?op=settings&group=vps'>VPS Settings</a>");
	}
	

	$hostip=$this->app->miscconfig['dnsip'];
	
	if($_insert){
		$vpsname=str_replace(' ','',$vpsname);
		$this->app->output.="<br>VPS image copy. your command will be applied at server.";
		$newfilename=str_replace('.img','',$newfilename); # kullanıcı yazarsa diye..
		$this->app->addDaemonOp('daemon_vps','copy_vps_image',$vpsname,$newfilename,__FUNCTION__.":$vpsname");
	} else {
	
		
		$params=array(
			array('vpsname','righttext'=>'non-spaced simple name'),
			array('newfilename','righttext'=>'New image file to write. img extension will be added.')
		);
		
		$this->app->output.=inputform5($params);		
	}
	$this->app->showSimilarFunctions('vps');
	
	return True;
} # end func


function copy_vps_image_daemon($vps,$newfilename) {
	passthru2("virsh suspend $vps");
	$srcimgfile="/vps/$vps.img";
	$newimage="/vps/images/$newfilename.img";
	passthru2("cp -v --sparse=always $srcimgfile $newimage");
	passthru2("virsh start $vps");
}


function vps_check_settings(){
	$set=array('iprange','netmask','broadcast','gateway','image_templates');
	#$this->app->print_r2($this->app->settings);
	
	foreach($set as $s) {		
		if($this->app->settings['vps'][$s]=='') {
			$this->app->output.=$this->app->settings['vps'][$s]." ($s) setting is missing.";
			return False;
		}
	}
	$ips=explode("\n",$this->app->settings['vps']['iprange']);
	foreach($ips as $ip) if($ip==$this->app->miscconfig['dnsip']) {
		$this->app->output.="One of vps ip's is same as host ip. this is wrong. remove host ip from ip range";
		return False;
	}
	
	return True;
} # end func

function vps_check_state_inhost($ip){
	if($ip<>$this->app->dnsip) {
		echo"\n vps check state on other hosts not supported yet.\n ip:$ip \n dnsip: {$this->app->dnsip} \n";
		return;
	}
	#$this->app->executeQuery("update vps set state='checking' where hostip='$ip'");

	exec("virsh list --all  | tr -s ' '| cut -f3 -d' ' | sed '1,2d'",$vpss);
	exec("virsh list --all  | tr -s ' '| cut -f4 -d' ' | sed '1,2d'",$statuss);
	
	$count=count($vpss);
	echo "VPS DURUMLARI: \n";
	for($i=0;$i<$count;$i++){		
		if($vpss[$i]<>'') {
			echo $vpss[$i].":".$statuss[$i]."\n";
			$this->app->executeQuery("update vps set state='".$statuss[$i]."' where vpsname='".$vpss[$i]."' and hostip='$ip'");			
		}
	}
	echo "end - VPS DURUMLARI: \n";
	
}

function vps_check_state(){
	$hosts=$this->app->query("select distinct(hostip) as hostip from vps ");
	foreach($hosts as $h) $this->vps_check_state_inhost($h['hostip']);
}
	

function daemon_add_vps($vps,$nocopy=False){
	
	/*
	 yeni bir vps oluşturma aşamaları:
1. örnek image dosyasını yeni dosyaya kopyala
2. yeni image mount et
3. içindeki interfaces dosyasını güncelle. 
4. sonra umount
5. yeni vps çalıştır. */
	
	$this->app->echoln2(__FUNCTION__.": started");
	$this->app->executeQuery("update vps set status='initializing' where vpsname='$vps'");
	$info=$this->app->query("select * from vps where vpsname='$vps'"); $info=$info[0];
	
	$this->app->echoln2(__FUNCTION__.": stage 1 - copy template img file");
	$srcdir="/vps/images";
	$srcimgfile=$srcdir.'/'.$info['image_template'];
	
	if(!file_exists($srcimgfile)) {
		echo "\n Source img file not found: $srcimgfile\n";
		return;
	}
	
	$fileinfo=pathinfo($srcimgfile);
	$ext=$fileinfo['extension'];	
	if(!($filetype=$this->get_file_type($srcimgfile))) return False;
	$this->vps_filetype=$filetype;
	
	$newimage="/vps/$vps.$ext";
	
	$format=$filetype;
	if(!$nocopy) { # bazı durumlarda, dosya zaten orada var oluyor, örneğin, domain'in yeniden oluşturulması durumu
		echo ("cp -v --sparse=always $srcimgfile $newimage");
		passthru2("cp -v --sparse=always $srcimgfile $newimage");
	}
	
	$ip=$info['ip'];
	$ip2=$info['ip2'];
	$hostip=$info['hostip'];
	$netmask=$info['netmask'];
	$broadcast=$info['broadcast'];
	$gateway=$info['gateway'];
	$ram=$info['ram'];
	$cpu=$info['cpu'];
	
if($info['cdimage']=='') { # if there is cd image, no IP adjust is done here. 
	$this->app->echoln2(__FUNCTION__.": stage 2 - mount new img file");
	if(!$this->daemon_vps_mountimage($newimage)) return False;
	$loopback=$this->vps_loopback;
	
	$intfile="/mnt/vps/etc/network/interfaces";
	$f=file_get_contents($intfile);
	if($f===False) {
		$this->app->echoln("Mounting of img file failed. Cannot add VPS, sory, check above logs. !");
		return;
	}
	
	$this->app->echoln2(__FUNCTION__.": stage 3 - change some file inside img, for new ip");


	$intfiledata="
auto lo
iface lo inet loopback
";


	if(trim($this->app->settings['vps']['interfacetemplate'])=='') {
		$intfiledata.="
auto eth0
iface eth0 inet static
address $ip
netmask $netmask
broadcast $broadcast
gateway $gateway
";
	} else {
	# eger bi template ile oluşturulacaksa, dual ethernet etc...
		$s=trim($this->app->settings['vps']['interfacetemplate']);
		$s=str_replace('{ip}',$ip,$s);
		$intfiledata.=$s;
	}

	# ikinci ethernet oluşturulması
	if(trim($this->app->settings['vps']['interfacetemplate2'])<>'') {
		$s=trim($this->app->settings['vps']['interfacetemplate2']);
		$s=str_replace('{ip2}',$ip2,$s);
		$intfiledata.="\n\n".$s;
	}


	echo "\nwriting $intfile:\n$intfiledata";

	$f=file_put_contents($intfile,$intfiledata);	
	if($f===False) {
		$this->app->echoln("Writing of interfaces file inside VPS img file failed. Cannot add VPS, sory, check above logs. !");
		return;
	}
	echo "\n (Debug) Contents of $intfile : \n".file_get_contents($intfile);
	
	echo "\n Writing hostname file.. \n";
	$f=file_put_contents("/mnt/vps/etc/hostname","ehcp-vps".$info['id']);
	echo "----------------- ls /mnt/vps/etc/\n";
	system("ls /mnt/vps/etc/");
	echo "-----------------\n";
	system("ls -l /mnt/vps/etc/resolv.conf");
	
	
	$nameservers=textarea_to_array($this->app->settings['vps']['nameservers']); 
	if(count($nameservers)>0) {
		$resolv="";
		foreach($nameservers as $name) $resolv="nameserver $name\n";
		unlink("/mnt/vps/etc/resolv.conf");

		# burasi ../ gibi link oldugunda, sıkıntı oluşuyor, aşağıdaki put_contents çalışmıyor.. 
		# o yüzden, önce unlink yapıyorum... 
		# /mnt/vps/etc/resolv.conf -> ../run/resolvconf/resolv.conf
		# PHP Warning:  file_put_contents(/mnt/vps/etc/resolv.conf): failed to open stream: 
		# No such file or directory in /var/www/new/ehcp/module.php on line 380
		
		$f=file_put_contents("/mnt/vps/etc/resolv.conf",$resolv);
	}
	
	$this->app->echoln2(__FUNCTION__.": stage 4 - Dismount img file");
	$this->daemon_vps_dismountimage($newimage);
}


	$this->app->echoln2(__FUNCTION__.": stage 5 - Add-run new VPS");

	if($info['vncpassword']=='') $vncoption='--graphics none';
	else $vncoption="--graphics vnc,password=".$info['vncpassword'];
	
	$br1=trim($this->app->executeProg3("ifconfig | grep br1"));
	if($br1<>'')$br1="--network bridge=br1";
	
	if($info['cdimage']<>'') {
		$boot="--boot cdrom";
		$cdrom="--cdrom ".$srcdir.'/'.$info['cdimage'];
	} else $boot="--boot hd";
	
	

$run="virt-install \
              --connect qemu:///system \
              --virt-type {$this->app->settings['vps']['virttype']} \
              --name $vps \
              --ram $ram \
              --vcpus $cpu \
              --disk path=$newimage,format=$format \
              $vncoption \
              --network bridge=br0 $br1 \
              $boot \
              $cdrom &";
              
	executeProg3($run,True);	
	$this->app->executeQuery("update vps set hdimage='$newimage',addvpscmd='".$this->app->escape($run)."' where vpsname='$vps' and ip='$ip' and hostip='$hostip'");
	sleep(1);
	executeProg3("killall qemu-nbd",True);
	
	$this->app->echoln2(__FUNCTION__.": finished");
}


function daemon_vps_dismountimage($imgfile){
	$this->app->echoln2(__FUNCTION__.": dismounting... ");
	
	$filetype=$this->vps_filetype;
	if(!$filetype){
		if(!($filetype=$this->get_file_type($imgfile))) return False;
		$this->vps_filetype=$filetype;
	}
	$loopback=$this->vps_loopback;
		

	executeProg3("umount /mnt/vps");
	switch($filetype) {
		case 'raw': 
			executeProg3("kpartx -dv $loopback",True);
			executeProg3("losetup -d  $loopback",True);
		break;
		
		case 'qcow2':	
			executeProg3("qemu-nbd -d /dev/nbd0",True);
			executeProg3("killall qemu-nbd",True);
		break;		
	}	
}	

function start_vps($vps){
	passthru2("virsh start $vps"); 
	passthru2("virsh resume $vps");
	passthru2("virsh autostart foo");
}

function shutoff_vps($vps){
	passthru2("virsh destroy $vps");
	passthru2("virsh autostart foo --disable");
}

function daemon_vps($params){
	$action=$params['action'];
	$vps=$params['info'];
	
	$this->app->requireCommandLine(__FUNCTION__);
	echo "\n ".__FUNCTION__." action: $action, vps: $vps \n";
	$info=$this->app->query("select * from vps where vpsname='$vps'"); 
	$info=$info[0];
	$ip=$info['ip'];
	$hostip=$info['hostip'];
	$hdimage=$info['hdimage'];
	
	switch($action){
		#case 'vps_check_state': $this->vps_check_state();break;
		case 'copy_vps_image': $this->copy_vps_image_daemon($vps,$params['info2']);break;
		case 'mountimage': $this->daemon_vps_mountimage($hdimage);break;
		case 'dismountimage': $this->daemon_vps_dismountimage($hdimage);break;
		case 'start': start_vps($vps); break;		
		case 'shutoff': shutoff_vps($vps); break;
		case 'pause': passthru2("virsh suspend $vps");break;
		case 'shut_start_bootcd':
			passthru2("virsh destroy $vps");
			passthru2("virsh undefine $vps");			
			$this->daemon_add_vps($vps,True); # cd ile boot edebilmek için, yeniden ekleniyor.. yoksa daha karışık. 
		break;
		case 'delete': 
			echo "\nvps $vps is being removed now:";
			passthru2("virsh destroy $vps");
			passthru2("virsh undefine $vps");			
			echo "\n removing vps img file: /vps/$vps.img or ($hdimage)";
			@unlink("/vps/$vps.img");
			@unlink("/vps/$vps.qcow2");
			@unlink($hdimage);
			
			$this->app->executeQuery("delete from vps where vpsname='$vps' and ip='$ip' and hostip='$hostip' limit 1");
		break;
		case 'add'	: $this->daemon_add_vps($vps);break;
		case 'rescanimages': $this->daemon_vps_rescanimages(); break;
		case 'download_img': 
			$this->app->download_file_from_url_extract($vps,'/vps/images','/vps/images');
			/*
			$filename=getLastPart($vps,'/');
			if(($filename=='') or($vps=='')) return False;
			$filename="/vps/images/$filename";
			if(!file_exists($filename)) passthru2("wget -O $filename $vps",True);*/
			
			
			
			$this->daemon_vps_rescanimages(); 			
		break;
		
		default: echo "\n".__FUNCTION__.": Undefined Op: $action\n";
	}
	
	passthru2("virsh list");
	$this->vps_check_state();
	
	return True;
}

function daemon_vps_mountimage($imgfile){
	
	$fileinfo=pathinfo($imgfile);
	$ext=$fileinfo['extension'];

	$filetype=$this->vps_filetype;
	if(!$filetype){
		if(!($filetype=$this->get_file_type($imgfile))) return False;
		$this->vps_filetype=$filetype;
	}
	
	echo "\n".__FUNCTION__.": imgfile: $imgfile , The image extension: $ext\n";
	
	executeProg3("umount /mnt/vps");
	
	if($filetype=='raw') {
		$loopback=executeProg3("losetup -f"); # get free loopback dev
		echo "\nloopback:($loopback) \n";
		$info=pathinfo($loopback);		
		$loopback_devname=$info['basename'];
		$partname='/dev/mapper/'.$loopback_devname.'p1';
		
		$this->vps_loopback=$loopback;
		
		executeProg3("losetup $loopback $imgfile",True);
		executeProg3("kpartx -av $loopback",True);
		executeProg3("mkdir /mnt/vps 2>/dev/null");		
		executeProg3("mount $partname /mnt/vps",True);
		
	}elseif($filetype=='qcow2'){
		echo "whoami:".exec('whoami')."\n\n";
		#echo executeProg3("modprobe nbd max_part=63",True);
		#echo executeProg3("qemu-nbd -c /dev/nbd0 $imgfile",True);
		echo exec("modprobe nbd max_part=63")."\n\n";sleep(2);
		echo exec("qemu-nbd -c /dev/nbd0 $imgfile")."\n\n";sleep(2);
		
		if(!file_exists('/dev/nbd0p1')){
			echo executeProg3("ls /dev/nb*");
			echo "\n passthru deniyor:\n";
			
			passthru("qemu-nbd -c /dev/nbd0 $imgfile");			
			echo executeProg3("ls /dev/nb*");
			
			if(!file_exists('/dev/nbd0p1')){
				echo "\n/dev/nbd0p1 bulunamadı, mount edilemiyor...\n";
				return False;
			}
		}
		executeProg3("mount /dev/nbd0p1 /mnt/vps",True);
	}else{
		echo "\n bilinmeyen image tipi ($filetype)\n";
		return False;
	}
	
	return True;
}

function get_file_type($f){
	if(!is_file($f)) {
		echo "\nBu bir dosya degil: ($f)\n";
		return False;
	}
	$ret=executeProg3("file -b $f");
	if(strstr($ret,'QEMU QCOW Image')) $type='qcow2';
	elseif(strstr($ret,'x86 boot sector; partition')) $type='raw';
	else {
		echo "\n File type cannot be determined:($f), file output: ($ret) \n";
		return False;
	}
	
	return $type;
}


function daemon_vps_rescanimages(){
	executeProg3("mkdir -p /vps/images 2>/dev/null"); # her ihtimale karşı, yoksa diye..
	executeProg3("mkdir -p /mnt/vps 2>/dev/null"); # her ihtimale karşı, yoksa diye..

	$vpsimages=$this->app->read_dir("/vps",'imgextension');
	$vpsimage_templates=$this->app->read_dir("/vps/images",'imgextension');

	if(count($vpsimage_templates)==0) { # hiç image yoksa, bitane hemen oluştur. 
		echo "\nHiç vps image bulunamadı, bu yüzden, bitane ben 10G'lık oluşturmaya çalışıyorum:\n";
		executeProg3("qemu-img create /vps/images/demo.img 10G");
		$vpsimage_templates=$this->app->read_dir("/vps/images",'imgextension');
	}

	$vps_isos=$this->app->read_dir("/vps/images",'isoextension');			
	echo "\nFound images,image templates,isos \n";
	
	print_r($vpsimages);
	print_r($vpsimage_templates);
	print_r($vps_isos);
	
	$this->app->setSettingsValue('vps','iso_images',implode("\n",$vps_isos));
	$this->app->setSettingsValue('vps','images',implode("\n",$vpsimages));
	$this->app->setSettingsValue('vps','image_templates',implode("\n",$vpsimage_templates));			
}

		
} # end class



?>
