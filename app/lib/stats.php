<?php
    namespace app\lib;

    use \app\data\Calls;
    use \app\helpers\Validator;
    use \app\helpers\Html;
    use app\core\Response;

    /**
     * @group(ADMINISTRATOR,CLIENT)
     */
    class Stats extends \app\core\Page{
        private $validator;

        public function __construct($controller, $action, $meta)
        {
            parent::__construct($controller, $action, $meta);
            $this->validator = new Validator();
        }

        /**
         * @content(CONTENT_TYPE_JSON)
         */
        public function CommentCall(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $model = new Calls();
            $data = $model->GetComment($this->request->GetData(0));
            if($model->IsSuccess()){
                $this->response->SetSuccess($data);
                $this->response->SetSuccessFunc('UpdateComment');
            }
            else{
                $this->response->SetError('Данные не получены!');
            }
        }

        public function CommentCallPost()
        {
            $data = $this->request->GetData('UserData');
            $this->validator->Validate($data);

            if (!$this->validator->IsValid()) {
                $this->response->SetError($this->validator->ErrorReporting());
            } else {
                $data = Validator::CleanKey($data);
                $id = $data['id'];
                unset($data['id']);
                $model = new Calls();

                $model->Update($data, '`id` = ?', array($id))->Run();
                if ($model->IsSuccess()) {
                    $this->response->SetSuccess();
                    $this->response->SetSuccessFunc('SuccessOperation');
                } else {
                    $this->response->SetRedirect(Html::ActionPath('error', 'index', $model->ErrorReporting()));
                }
            }
        }

        /**
         * @content(CONTENT_TYPE_JSON)
         */
        public function InfoCall(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $model = new Calls();
            $data = $model->GetElementByField('id', $this->request->GetData(0));

            if($model->IsSuccess()){

                if(isset($data[0])){
                    $data = $data[0];
                }

                $html = '
                    <h2>'.$data['id'].'</h2>
                    <h3>'.$data['site'].'</h3>
                ';


                $this->response->SetSuccess($html);
                $this->response->SetSuccessFunc('InfoCall');
            }
            else{
                $this->response->SetError('Данные не получены!');
            }
        }
    }