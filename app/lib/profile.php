<?php
    namespace app\lib;

    use \app\core\Config;
    use app\core\UsersManager;
    use app\data\Calls;
    use app\data\Emails;
    use app\data\Properties;
    use \app\data\Users;
    use \app\lib\modules\Menu;
    use \app\data\Sites;
    use \app\data\Branches;

    use \app\helpers\Pagination;


    use \app\helpers\Develop;

    /**
     * @group(ADMINISTRATOR,CLIENT)
     */
    class Profile extends \app\core\Page{

        public function __construct($controller, $action, $meta)
        {
            parent::__construct($controller, $action, $meta);

            $this->response->Set('AccountName', $this->userManager->Get('UserName'));

            $user = new Users();

            $user_info = $user->GetElementByField('id', $this->userManager->Get('UserId'));
            $user_info = isset($user_info[0])?$user_info[0]:$user_info;
            $menu = $this->response->modules->Add('menu');
            $menu->Init(Menu::TYPE_CLIENT_MAIN);
            $menu->SetBrand('Личный кабинет');

            $menu->Set('Paid', $user_info['paid']);
            $menu->Set('IsAuth', $this->userManager->IsAuthorized());
            $menu->Set('AccountName', $this->userManager->Get('UserName'));
            $menu->Set('IsAdmin',$this->userManager->InRole(array(UsersManager::GROUP_ADMINISTRATOR)));
            $menu->Set('IsActive', $this->userManager->IsActive());
            if($this->userManager->IsSuperUser()){
                $menu->Set('IsSuperUser', $this->userManager->IsSuperUser());
                $menu->Set('SuperAccountName', $this->userManager->Get('BaseName'));
            }

        }

        public function Index(){
            $site = new Sites();
            $result = $site->GetElementByField('id_user', $this->userManager->Get('UserId'));
            $properties = $site->GetProperties('yes');

            $mp = array();
            foreach($properties as $item){
                $item['dop'] = $site->DecodeParams($item['dop']);
                $mp[$item['group']][] = $item;
            }

            $widget = $this->response->modules->Add('widget');
            $widget->SetEditMode();

            $widget->Set('propertiesEmpty', count($properties) == 0);
            $widget->Set('Properties',$mp);
            $widget->Set('IsAdmin',$this->userManager->InRole(array(UsersManager::GROUP_ADMINISTRATOR)));
            $widget->Set('IsSuperUser', $this->userManager->IsSuperUser());

            $this->response->Set('sitesEmpty',count($result) == 0);
            $this->response->Set('Data',$result);
            $this->response->Set('UserId', $this->userManager->Get('UserId'));
            $this->response->Set('title','Личный кабинет');
        }

        public function Calls(){ //todo рефакторинг
            $model = new Properties();
            $property = $model->GetPropertyInfoByCodeName('accessList');
            $model->Clear();
            $model->SetTable('PropertiesValue');
            $id = $property['id'];
            $email = 'admin@gmail.com';

            $data = $model->GetElementByField('property_id', $id);
            $list = array();
            foreach($data as $item){
                $p = str_replace(array("\t", "\r","\n"), array(' ', '', PHP_EOL), trim($item['value']));
                $p = explode(PHP_EOL, trim($p));
                if(in_array($email, $p)){
                    $list[] = $item['site_id'];
                }
            }

            $model = new Sites();
            $r = $model->Select(array('id'))->Where('id_user = ?', array($this->userManager->Get('UserId')))->Build()->Run(true)->GetAll();

            $calls = new Calls();

            $page = $this->request->GetData('page');

            //filter
            $filter = $this->response->modules->Add('filter');
            $filter->Init($this->request, 'calls');
            $calls->PrepareMeta($r,$list, $filter->Get('dbMeta'));

            $filter->Set('page',$page ? $page : 1);
            $filter->Set('sites',$calls->GetSiteList());

            //pagination
            $countItems = $calls->GetCountCalls();
            $p = Pagination::Build($countItems, $page ? $page : 1, Config::PAGINATION_COUNT_LEFT, Config::PAGINATION_COUNT_RIGHT, $filter->GetParamList());

            $result = $calls->GetCalls(array('start' => $p['start'], 'count' => Config::PAGINATION_COUNT_ON_PAGE));
            $this->response->Set('count', $countItems);
            $this->response->Set('empty', count($result) === 0);
            $this->response->Set('data', $result);
            $this->response->Set('pagination', $p['html']);
        }

        public function Emails(){
            $model = new Properties();
            $property = $model->GetPropertyInfoByCodeName('accessList');
            $model->Clear();
            $model->SetTable('PropertiesValue');
            $id = $property['id'];
            $email = 'admin@gmail.com';

            $data = $model->GetElementByField('property_id', $id);
            $list = array();
            foreach($data as $item){
                $p = str_replace(array("\t", "\r","\n"), array(' ', '', PHP_EOL), trim($item['value']));
                $p = explode(PHP_EOL, trim($p));
                if(in_array($email, $p)){
                    $list[] = $item['site_id'];
                }
            }

            $model = new Sites();
            $r = $model->Select(array('id'))->Where('id_user = ?', array($this->userManager->Get('UserId')))->Build()->Run(true)->GetAll();

            /*$this->response->Set('sites',$r);
            $this->response->Set('dopSites',$list);*/

            $stat = new Emails();

            $page = $this->request->GetData('page');

            //filter
            $filter = $this->response->modules->Add('filter');
            $filter->Init($this->request, 'emails');
            $stat->PrepareMeta($r,$list, $filter->Get('dbMeta'));

            $filter->Set('page',$page ? $page : 1);
            $filter->Set('sites',$stat->GetSiteList());

            //pagination
            $countItems = $stat->GetCountEmails();
            $p = Pagination::Build($countItems, $page ? $page : 1, Config::PAGINATION_COUNT_LEFT, Config::PAGINATION_COUNT_RIGHT, $filter->GetParamList());

            $result = $stat->GetEmails(array('start' => $p['start'], 'count' => Config::PAGINATION_COUNT_ON_PAGE));
            $this->response->Set('count', $countItems);
            $this->response->Set('empty', count($result) === 0);
            $this->response->Set('data', $result);
            $this->response->Set('pagination', $p['html']);
        }

        public function Branches(){
            $model = new Branches();
            $user = $this->userManager->Get('UserId');
            $branchList = $model->GetBranchesByUser($user);
            $this->response->Set('empty', count($branchList) === 0);
            $this->response->Set('data', $branchList);
            $this->response->Set('UserId', $user);
            //Develop::VarDamp();
        }
    }