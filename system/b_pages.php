<?php
$path=__DIR__ . '/../data/help/';
if(!empty($_P["type"]) && !empty($_P["page"]) && !empty($_SESSION["username"])){
  $pdo = _conn();
  $query = _que('SELECT * FROM customer where id = ?', [$_SESSION["username"]]);
  if(!is_array($query) || @!isset($query['failed'])){
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if(empty($user)){
      unset($_SESSION["username"]);
    } elseif(empty($user['2fa']) || (!empty($user['2fa']) && !empty($_SESSION['2fa']))) {
      $is_admin=($user['admin'] == 1 && !empty($user['admin']))?true:false;
      if($is_admin){

        if($_P['page'] == 'home'){
          $pages=[];
          $query = _que('SELECT * FROM pages WHERE `page`="home"');
          if(!is_array($query) || @!isset($query['failed'])){
            $pages = $query->fetchAll(PDO::FETCH_ASSOC);
          }
          $page = json_decode($pages[0]['json']);
          if($_P['type'] == 'zone1'){
            $json = json_encode(
              array_replace(
                (array)array(
                  'title' => $_P['title'],
                  'desc' => $_P['desc'],
                  'i1_title' => $_P['i1_title'],
                  'i1_desc1' => $_P['i1_desc1'],
                  'i1_desc2' => $_P['i1_desc2'],
                  'i2_title' => $_P['i2_title'],
                  'i2_desc1' => $_P['i2_desc1'],
                  'i2_desc2' => $_P['i2_desc2'],
                  'i3_title' => $_P['i3_title'],
                  'i3_desc1' => $_P['i3_desc1'],
                  'i3_desc2' => $_P['i3_desc2'],
                  'i4_title' => $_P['i4_title'],
                  'i4_desc1' => $_P['i4_desc1'],
                  'i4_desc2' => $_P['i4_desc2']
                )
              )
            );
            
            $query = _que('UPDATE pages SET `json` = ? WHERE `page` = "home"', 
            [json_encode(array_replace((array)$page,array('zone1'=>$json)))]);
            
            if(!is_array($query) || @!isset($query['failed'])){
              http_response_code(200);
              $data = ['msg' => 'Done!'];
            } else {
              $data = ['msg' => $query['msg']];
            }
          }

          if($_P['type'] == 'zone2'){
            $json = json_encode(
              array_replace(
                (array)array(
                  'title' => $_P['title'],
                  'desc1' => $_P['desc1'],
                  'desc2' => $_P['desc2']
                )
              )
            );
            
            $query = _que('UPDATE pages SET `json` = ? WHERE `page` = "home"', 
            [json_encode(array_replace((array)$page,array('zone2'=>$json)))]);
            if(!is_array($query) || @!isset($query['failed'])){
              http_response_code(200);
              $data = ['msg' => 'Done!'];
            } else {
              $data = ['msg' => $query['msg']];
            }
          }

          if($_P['type'] == 'plan1'){
            $json = json_encode(
              array_replace(
                (array)array(
                  'title' => $_P['title'],
                  'price' => $_P['price'],
                  'note' => $_P['note'],
                  'cpu' => $_P['cpu'],
                  'ram' => $_P['ram'],
                  'disk' => $_P['disk'],
                  'support' => $_P['support']
                )
              )
            );
            
            $query = _que('UPDATE pages SET `json` = ? WHERE `page` = "home"', 
            [json_encode(array_replace((array)$page,array('plan1'=>$json)))]);
            if(!is_array($query) || @!isset($query['failed'])){
              http_response_code(200);
              $data = ['msg' => 'Done!'];
            } else {
              $data = ['msg' => $query['msg']];
            }
          }

          if($_P['type'] == 'plan2'){
            $json = json_encode(
              array_replace(
                (array)array(
                  'title' => $_P['title'],
                  'price' => $_P['price'],
                  'note' => $_P['note'],
                  'cpu' => $_P['cpu'],
                  'ram' => $_P['ram'],
                  'disk' => $_P['disk'],
                  'support' => $_P['support']
                )
              )
            );
            
            $query = _que('UPDATE pages SET `json` = ? WHERE `page` = "home"', 
            [json_encode(array_replace((array)$page,array('plan2'=>$json)))]);
            if(!is_array($query) || @!isset($query['failed'])){
              http_response_code(200);
              $data = ['msg' => 'Done!'];
            } else {
              $data = ['msg' => $query['msg']];
            }
          }

          if($_P['type'] == 'plan3'){
            $json = json_encode(
              array_replace(
                (array)array(
                  'title' => $_P['title'],
                  'price' => $_P['price'],
                  'note' => $_P['note'],
                  'net' => $_P['net'],
                  'ip' => $_P['ip'],
                  'power' => $_P['power'],
                  'support' => $_P['support']
                )
              )
            );
            
            $query = _que('UPDATE pages SET `json` = ? WHERE `page` = "home"', 
            [json_encode(array_replace((array)$page,array('plan3'=>$json)))]);
            if(!is_array($query) || @!isset($query['failed'])){
              http_response_code(200);
              $data = ['msg' => 'Done!'];
            } else {
              $data = ['msg' => $query['msg']];
            }
          }
        }

      }
    }
  } else {
    unset($_SESSION["username"]);
  }
    
} else {
  $data=['msg'=>'data incorrect'];
}