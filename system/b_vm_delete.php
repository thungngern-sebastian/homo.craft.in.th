<?php
if(!empty($_P['cref']) && !empty($_P['chost']) && !empty($_SESSION["username"])){
    $pdo = _conn();
    $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
    if(!is_array($query) || @!isset($query['failed'])){
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
          unset($_SESSION["username"]);
        } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
          $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
          if($is_admin){
            $query = _que('SELECT * FROM hosts WHERE host = ?', [$_P['chost']]);
                  if(!is_array($query) || @!isset($query['failed'])){
                      $cloud = $query->fetch(PDO::FETCH_ASSOC);
                      if(!empty($cloud)){
                          require_once'../API/Xen.php';
                          $xen = new PsXenAPI($cloud['host'],$cloud['username'],$cloud['password']);
                          $key = PsXenAPI::apref($_P['cref']);
                          $data=$xen->rq('VM.get_record', [$key])['Value'];
                          if($data["power_state"] == 'Running'){
                              $xen->rq('VM.hard_shutdown', [$key]);
                          }
                          foreach ($data['VBDs'] as $vbd) {
                              $vbdx=$xen->rq('VBD.get_record', [$vbd])['Value'];
                              if($vbdx['type']=='Disk'){
                                  $xen->rq('VDI.destroy', [$vbdx['VDI']]);
                              }
                          }
                          $data = $xen->rq('VM.destroy', [$key]);
                          if($data['Status']!='Success'){
                              $data = ['msg' => 'ไม่สำเร็จ '.@$data['ErrorDescription'][0]];
                          } else {
                            _que('UPDATE ip_address SET available=0,uuid="" WHERE uuid=?;', [$_P['cref']]);
                              http_response_code(200);
                              $data = ['msg' => 'สำเร็จ','eval'=>'location.reload()'];
                          }
                      } else {
                          $data=['msg'=>'No host'];
  
                      }
                  } else {
                      $data=['msg'=>$query['msg']];
                  }
          }
        }
    } else {
        unset($_SESSION["username"]);
    }
}