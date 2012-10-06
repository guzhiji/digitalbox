<?php

require 'core1.lib.php';
LoadIBC1Class('DBConnProvider', 'sql');
/*
  LoadIBC1Class("CatalogModelManager", "datamodel.catalog");
  $m=new CatalogModelManager();
  if (!$m->IsInstalled())
  $m->Install();
  $m->Create("catalogtest", array("localhost:3306", "root", "", "digitalbox3_test"));
 */
/*
  LoadIBC1Class('CatalogItemEditor', 'datamodel.catalog');
  $editor=new CatalogItemEditor('catalogtest');
  $editor->Create();
  $editor->SetName('catalog 2');
  $editor->Save(0);
  echo $editor->GetID();
 */
/*
  LoadIBC1Class('CatalogListReader','datamodel.catalog');
  $reader=new CatalogListReader('catalogtest');
  try{
  var_dump($reader->GetCatalog(3));
  }catch(Exception $ex){
  echo 'not found';
  }
  echo "<hr />\n";
  $reader->LoadCatalog(2);
  //$reader->LoadList();
  $reader->MoveFirst();
  while($item=$reader->GetEach()){
  var_dump($item);
  echo "<hr />\n";
  }
  $reader->CloseService();
 */
/*
  LoadIBC1Class('CatalogItemEditor', 'datamodel.catalog');
  $editor=new CatalogItemEditor('catalogtest');
  $editor->Open(1);
  $editor->SetName('catalog 1');
  try{
  $editor->Save();
  } catch (Exception $ex){
  echo $ex->getMessage();
  }
  $editor->CloseService();
 */
/*
  LoadIBC1Class('ContentItemEditor', 'datamodel.catalog');
  $editor=new ContentItemEditor('catalogtest');
  $editor->Create();
  $editor->SetName('content 2');
  $id=0;
  try{
  $editor->Save(1);
  $id=$editor->GetID();
  echo "succeeded\n";
  }catch(Exception $ex){
  echo 'failed:'.$ex->getMessage()."\n";
  }
  $editor->CloseService();
  echo "<hr />\n";
  if($id>0){
  LoadIBC1Class('ContentItemReader', 'datamodel.catalog');
  $reader=new ContentItemReader('catalogtest');
  if($reader->Open($id)){
  echo $reader->GetID()."\n";
  echo $reader->GetName()."\n";
  }
  $reader->CloseService();
  }
 */
/*
LoadIBC1Class('ContentItemEditor', 'datamodel.catalog');
$editor = new ContentItemEditor('catalogtest');
$editor->Open(1);
$editor->SetName('content b');
try {
    $editor->Save();
    echo "succeeded\n";
} catch (Exception $ex) {
    echo 'failed:' . $ex->getMessage() . "\n";
}
$editor->CloseService();
LoadIBC1Class('ContentListReader', 'datamodel.catalog');
$reader = new ContentListReader('catalogtest');
$reader->SetCatalog(1);
$reader->LoadList();
$reader->MoveFirst();
while ($item = $reader->GetEach()) {
    var_dump($item);
    echo "<hr />\n";
}
$reader->CloseService();
*/
/*
LoadIBC1Class('UserModelManager', 'datamodel.user');
$m=new UserModelManager();
try{
    $m->Create(
        'usertest',
        array(
            'host'=>'localhost:3306',
            'user'=>'root',
            'pwd'=>'',
            'dbname'=>'digitalbox3_test'
        ),
        array(
            'level 1',
            'level 2',
            'level 3'
        ),
        array(
            'uid'=>'guzhiji',
            'pwd'=>'19900827',
            'repeat'=>'19900827'
        )
    );
    echo "succeeded\n";
} catch(Exception $ex) {
    echo $ex->getMessage()."\n";
}
*/
/*
LoadIBC1Class('UserPassport', 'datamodel.user');
$up=new UserPassport('usertest');
try{
    $up->Login('guzhiji','19900827');
    echo "login!\n";
    echo "welcome ".$up->GetUID()."\n";
} catch(Exception $ex) {
    echo $ex->getMessage()."\n";
}
$up->Logout();
if(!$up->IsOnline()) echo "logout!\n";
$up->CloseService();
*/
/*
LoadIBC1Class('UserInfoReader', 'datamodel.user');
$r=new UserInfoReader('usertest');
try{
    $r->Open('guzhiji');
    echo $r->GetUID()."\n";
}catch(ServiceException $ex){
    echo $ex->getMessage()."\n";
}
if($r->CheckPWD('19900827')) echo 'password: correct!';
$r->CloseService();
*/
/*
LoadIBC1Class('UserInfoEditor','datamodel.user');
$e=new UserInfoEditor('usertest');
try{
    $e->Open('guzhiji', '19900827');
    $e->SetNickName('Zhiji Gu');
    $e->Save();
    echo "nick name changed\n";
}catch(ServiceException $ex){
    switch($ex->getCode()){
        case UserInfoEditor::E_UNAUTHORIZED:
            echo 'open failed:'.$ex->getMessage()."\n";
            break;
        default:
            echo 'unexpected error:'.$ex->getMessage()."\n";
            break;
    }
}
$e->CloseService();
*/
/*
define('SERVICENAME', 'usertest');
LoadIBC1Class('UserPassport', 'datamodel.user');
$up=new UserPassport(SERVICENAME);
try{
    $up->Login('guzhiji','19900827');
    echo "login!\n";
    echo "welcome ".$up->GetUID()."\n";
} catch(Exception $ex) {
    echo $ex->getMessage()."\n";
}
LoadIBC1Class('UserInfoEditor','datamodel.user');
$e=new UserInfoEditor(SERVICENAME);
try{
    $e->OpenWithPassport('guzhiji', $up);
    $e->SetNickName('Zhiji Gu');
    $e->Save();
    echo "nick name changed\n";
}catch(ServiceException $ex){
    switch($ex->getCode()){
        case UserInfoEditor::E_UNAUTHORIZED:
            echo 'open failed:'.$ex->getMessage()."\n";
            break;
        default:
            echo 'unexpected error:'.$ex->getMessage()."\n";
            break;
    }
}
$e->CloseService();
$up->Logout();
if(!$up->IsOnline()) echo "logout!\n";
$up->CloseService();
*/
/*
LoadIBC1Class('UserInfoEditor','datamodel.user');
$e=new UserInfoEditor('usertest');
try{
    $e->Create('gzj2');
    $e->SetPWD('19900827', '19900827');
    $e->SetNickName('Zhiji Gu');
    $e->Save();
    echo "user created\n";
}catch(ServiceException $ex){
    switch($ex->getCode()){
        case UserInfoEditor::E_EXISTS:
            echo 'username not available:'.$ex->getMessage()."\n";
            break;
        case UserInfoEditor::E_INVALID:
            echo 'invalid:'.$ex->getMessage()."\n";
            break;
        default:
            echo 'unexpected error:'.$ex->getMessage()."\n";
            break;
    }
}
$e->CloseService();
*/
/*
LoadIBC1Class('UserListReader', 'datamodel.user');
$list=new UserListReader('usertest');
$list->SetUserAdmin(1);
$list->SetOnline(1);
$list->LoadList();
$list->MoveFirst();
while($user=$list->GetEach()){
    var_dump($user);
    echo "<hr />\n";
}
$list->CloseService();
*/

