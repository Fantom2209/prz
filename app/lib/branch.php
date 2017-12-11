<?php

    namespace app\lib;

    use app\core\Config;
    use app\core\Response;
    use app\core\UsersManager;
    use app\data\Users;
    use \app\helpers\Validator;
    use \app\core\ErrorInfo;
    use \app\helpers\Html;
    use \app\data\Sites;
    use \app\data\Properties;
    use \app\lib\modules\Widget;
    use \app\helpers\Uploder;
    use \app\data\Branches;

    /**
     * @group(ADMINISTRATOR,CLIENT)
     */
    class Branch extends \app\core\Page{

        private $validator;

        public function __construct($controller, $action, $meta)
        {
            parent::__construct($controller, $action, $meta);
            $this->validator = new Validator();
        }


        public function AddPost(){
            $data = $this->request->GetData('UserData');
            $this->validator->Validate($data);

            if(!$this->validator->IsValid()){
                $this->response->SetError($this->validator->ErrorReporting());
            }
            else{
                $data = Validator::CleanKey($data);
                $model = new Branches();

                $model->Insert($data)->Run();
                if($model->IsSuccess()){
                    $item = $model->GetBranches($model->GetLastId());
                    $item = isset($item[0]) ? $item[0] : array();

                    $html = Html::Snipet('BranchLine', array(
                        $item['name'], $item['time_zone'],
                        Html::ActionPath('branch', 'update', array($item['id'])),
                        Html::ActionPath('site', 'property', array($item['id'])),
                        Html::ActionPath('site', 'delete', array($item['id'])),
                    ));

                    $this->response->SetSuccess($html);
                    $this->response->SetSuccessFunc('AddLineTop');
                }
                else{
                    $this->response->SetRedirect(Html::ActionPath('error','index', $model->ErrorReporting()));
                }
            }
        }


        /**
         * @content(CONTENT_TYPE_JSON)
         */
        public function Update(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $model = new Branches();
            $data = $model->GetBranches($this->request->GetData(0));
            if($model->IsSuccess()){
                $this->response->SetSuccess($data);
                $this->response->SetSuccessFunc('UpdateBranch');
            }
            else{
                $this->response->SetError('Данные не получены!');
            }
        }

        public function UpdatePost()
        {
            $data = $this->request->GetData('UserData');
            $this->validator->Validate($data);

            if (!$this->validator->IsValid()) {
                $this->response->SetError($this->validator->ErrorReporting());
            } else {
                $data = Validator::CleanKey($data);
                $id = $data['id'];
                unset($data['id']);
                $model = new Branches();


                $model->Update($data, '`id` = ?', array($id))->Run();
                if ($model->IsSuccess()) {
                    $item = $model->GetBranches($id);
                    $item = isset($item[0]) ? $item[0] : array();

                    $html = Html::Snipet('BranchLine', array(
                        $item['name'], $item['time_zone'],
                        Html::ActionPath('branch', 'update', array($item['id'])),
                        Html::ActionPath('site', 'property', array($item['id'])),
                        Html::ActionPath('site', 'delete', array($item['id'])),
                    ));

                    $this->response->SetSuccess($html);
                    $this->response->SetSuccessFunc('UpdateLine');
                } else {
                    $this->response->SetRedirect(Html::ActionPath('error', 'index', $model->ErrorReporting()));
                }
            }
        }

        /**
         * @content(CONTENT_TYPE_JSON)
         */
        public function Delete(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $model = new Branches();
            $model->Delete()->Where('`id` = ?', array($this->request->GetData(0)))->Build()->Run();
            if($model->IsSuccess()){
                $this->response->SetSuccess();
                $this->response->SetSuccessFunc('DeleteLine');
            }
            else{
                $this->response->SetRedirect(Html::ActionPath('error','index', $model->ErrorReporting()));
            }
        }
    }