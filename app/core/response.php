<?php
namespace app\core;

class Response{

    const CONTENT_TYPE_VIEW = 1;
    const CONTENT_TYPE_JSON = 2;
    const CONTENT_TYPE_XML = 3;

    const CODE_ERROR = 500;
    const CODE_SUCCESS = 200;
    const CODE_REDIRECT = 301;

    const STATUS_ERROR = 'error';
    const STATUS_SUCCESS = 'success';
    const STATUS_REDIRECT = 'redirect';

    private $controller;
    private $action;
    private $view;
    public $modules;

    private $status;
    private $code;

    private $data;
    private $contentType;

    public function SetLayout($layout){
        $this->view->Set('layout', $layout);
    }

    public function SetTemplate($template){
        $this->view->Set('template', $template);
    }

    public function __construct($controller, $action){
        $this->view = new View();
        $this->modules = new ModulesManager();
        $this->contentType = self::CONTENT_TYPE_VIEW;
        $this->controller = $controller;
        $this->action = $action;
        $this->SetSuccess();
    }

    public function SetContentType($code){
        $this->contentType = $code;
    }

    public function SetContent($data){
        $this->data['content'] = $data;
    }

    public function SetSuccessFunc($data){
        $this->data['successFunc'] = $data;
    }

    public function SetErrorFunc($data){
        $this->data['errorFunc'] = $data;
    }

    public function SetError($data = null){
        $this->code = self::CODE_ERROR;
        $this->status = self::STATUS_ERROR;
        if($data){
            $this->SetContent($data);
        }
    }

    public function SetSuccess($data = null){
        $this->code = self::CODE_SUCCESS;
        $this->status = self::STATUS_SUCCESS;
        if($data){
            $this->SetContent($data);
        }
    }

    public function SetRedirect($url){
        $this->code = self::CODE_REDIRECT;
        $this->status = self::STATUS_REDIRECT;
        $this->data['url'] = $url;
    }

    // Get и Set для view
    public function Get($key){
        return $this->data[$key];
    }

    public function Set($key, $value){
        $this->data[$key] = $value;
    }

    public function Go(){
        $this->CheckError();
        switch ($this->contentType) {
            case self::CONTENT_TYPE_VIEW:
                if (!$this->view->HasLayout()) {
                    $this->view->Set('layout', Config::PATH_LAYOUT . 'mainLayout.php');
                }
                if (!$this->view->HasTeamplate()) {
                    $this->view->Set('template', Config::PATH_VIEW . $this->controller . Config::PATH_SEPARATOR . $this->action . '.php');
                }
                require_once($this->view->Get('layout'));
                break;
            case self::CONTENT_TYPE_JSON:
                $this->data['code'] = $this->code;
                $this->data['status'] = $this->status;
                echo json_encode($this->data);
                break;
            case self::CONTENT_TYPE_XML:
                echo 'XML not implement';
                break;
        }
        exit;
    }

    public function GenerateError($code = array()){
        if(!is_array($code)){
            $code = array($code);
        }
        $this->Redirect('error', 'index', $code);
    }

    public function NotFound(){
        $this->Redirect('error', 'index', array(404));
    }

    public function Redirect($controller = 'home', $action = 'index', $param = array()){
        $url = '/' . $controller . '/' . $action . '/';
        foreach($param as $val){
            if(!empty($val)){
                $url .= $val . '/';
            }
        }
        $this->RedirectUrl($url);
    }

    public function RedirectUrl($url = '/home/index/'){
        header('Location: ' . $url);
        exit();
    }

    private function CheckError(){
        if(ErrorList::Count()) {
            if (self::CONTENT_TYPE_VIEW == $this->contentType) {
                $this->GenerateError(ErrorList::ErrorReporting()); // редирект на страницу ошибки
            } else {
                $this->SetError();
                $this->SetContent(ErrorList::ErrorReporting()); // формирование ответа на ajax с ошибкой
            }
        }
    }

}