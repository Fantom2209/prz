<?php
namespace app\lib\modules;
use \app\data\Menu as DMenu;
use \app\core\Config;

class Menu extends \app\core\ViewModule {

    const TYPE_GUEST_MAIN = 1;
    const TYPE_CLIENT_MAIN = 2;
    const TYPE_ADMIN_MAIN = 3;
    const TYPE_DEVELOPER_MAIN = 4;

    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new DMenu();
        $this->SetBrand(Config::DEFAULT_BRAND);
    }

    public function Init($type){
        $name = '';
        switch($type){
            case self::TYPE_GUEST_MAIN :
                $name = 'guest_main';
                $this->Set('Type', 'Guest');
                break;
            case self::TYPE_CLIENT_MAIN :
                $name = 'client_main';
                $this->Set('Type', 'Client');
                break;
            case self::TYPE_ADMIN_MAIN :
                $name = 'administrator_main';
                $this->Set('Type', 'Admin');
                break;
            case self::TYPE_DEVELOPER_MAIN :

                break;
        }
        $items = $this->model->GetItems($name);
        $this->Set('items', $this->BuildMenu($items));
    }

    public function SetBrand($text){
        $this->Set('brand', $text);
    }

    private function BuildMenu($data){
        $result['root'] = array();

        foreach($data as $item){
            $i = $item['parent'] != '0' ? $item['parent'] : 'root';
            $result[$i][] = $item;
        }

        return $result;
    }
}