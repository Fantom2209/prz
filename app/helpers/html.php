<?php
namespace app\helpers;

use app\core\Config;

class Html
{
    private static $html;

    public static function ActionLink($controller = 'home', $action = 'index')
    {

    }

    public static function ActionPath($controller = 'home', $action = 'index', $param = array())
    {
        $url = '/' . $controller . '/' . $action . '/';
        foreach ($param as $key => $item) {
            if (!is_int($key)) {
                $url .= $key . ':';
            }
            $url .= $item . '/';
        }
        return $url;
    }

    private static function PrepareSnipet($data)
    {
        $patterns = array();
        foreach ($data as $key => $item) {
            $patterns[] = '{{' . ($key + 1) . '}}';
        }
        return str_replace($patterns, $data, self::$html);
    }

    public static function Snipet($name, $data)
    {
        $name = 'Snipet_' . $name;
        self::{$name}();
        return self::PrepareSnipet($data);
    }

    private static function Snipet_IMG(){
        self::$html = '<img src="{{1}}" alt="{{2}}" width="{{3}}">';
    }

    private static function Snipet_UserLine()
    {
        self::$html = '
            <tr class="{{1}}">
                <td><span>{{2}}</span></td>
                <td><span>{{3}}</span></td>
                <td><span>{{4}}</span></td>
                <td>{{5}}</td>
                <td><a href="#modalUpdateUser" data-toggle="modal" class="ajax-link link-line-action" data-href="{{6}}">Редактировать</a></td>
                <td><a href="{{7}}">{{8}}</a></td>
                <td><a href="{{9}}">Войти</a></td>
            </tr>
        ';
    }

    private static function Snipet_SiteLine()
    {
        self::$html = '
            <tr class="{{7}}">
                <td><span>{{1}}</span></td>
                <td><span>{{2}}</span></td>
                <td><span>{{3}}</span></td>
                <td><a href="#modalUpdateSite" data-toggle="modal" class="ajax-link link-line-action" data-href="{{4}}">Редактировать</a></td>
                <td><a href="#modalUpdateProperties" data-toggle="modal" class="ajax-link" data-href="{{5}}" >Свойства</a></td>
                <td><a href="#modal-confirm" data-toggle="modal" class="link-line-action confirm" data-href="{{6}}">Удалить</a></td>
                <td><a href="#" class="ajax-link link-line-action" data-href="{{8}}">{{9}}</a></td>
            </tr>
        ';
    }

    private static function Snipet_PropertyLine()
    {
        self::$html = '
            <tr>
                <td>{{1}}</td>
                <td>{{2}}</td>
                <td><a href="#modalUpdateProperty" data-toggle="modal" class="ajax-link link-line-action" data-href="{{3}}">Редактировать</a></td>
                <td><a href="#modal-confirm" data-toggle="modal" class="link-line-action confirm" data-href="{{4}}">Удалить</a></td>
            </tr>
        ';
    }

    private static function Snipet_FieldString()
    {
        self::$html = '
            <div class="form-group">
                <label>{{1}}:</label>
                <input class="form-control" type="text" name="UserData[{{2}}{{3}}]" value="{{4}}">
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldParam()
    {
        self::$html = '
            <div class="form-group input-group">
                <span class="input-group-addon param-name">{{1}}:</span>
                <input type="text" class="form-control" name="Params[{{2}}{{1}}]" value="{{3}}">
                <span class="input-group-btn">
                    <button class="btn btn-danger param-delete-btn" type="button">X</button>
                </span>
            </div>
        ';
    }

    private static function Snipet_FieldNumber()
    {
        self::$html = '
            <div class="form-group">
                <label>{{1}}:</label>
                <input class="form-control" type="text" name="UserData[{{2}}{{3}}]" value="{{4}}">
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldPhone()
    {
        self::$html = '
                <div class="form-group">
                    <label>{{1}}:</label>
                    <input class="form-control" type="text" name="UserData[{{2}}{{3}}]" value="{{4}}">
                    <div class="error-box"></div>
                </div>
        ';
    }

    private static function Snipet_FieldRange()
    {
        self::$html = '
            <div class="form-group">
                <label>{{1}}:</label>
                <input class="number-range" id=\'field_{{3}}\' data-slider-id=\'field_{{3}}\' type="text" data-slider-min="{{4}}" data-slider-max="{{5}}" data-slider-step="{{6}}" data-slider-value="{{7}}" name="UserData[{{2}}{{3}}]"/>
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldDate()
    {
        self::$html = '
            <div class="form-group">
                <label>{{1}}:</label>
                <input class="form-control dp" type="text" name="UserData[{{2}}{{3}}]" value="{{4}}">
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldImage()
    {
        self::$html = '
            <div class="form-group">
                <label>{{1}}:</label>
                <input class="form-control" type="file" name="{{2}}{{3}}">
                <div class="error-box"></div>
                <div class="text-center" style="padding-top:5px;">
                    {{4}}
                </div>
            </div>
        ';
    }

    private static function Snipet_FieldMultiline()
    {
        self::$html = '
            <div class="form-group">
                <label>{{1}}:</label>
                <textarea class="form-control" name="UserData[{{2}}{{3}}]">{{4}}</textarea>
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldCheckbox()
    {
        self::$html = '
            <div class="form-group {{4}}">
                <label>{{1}}:</label>
                <input type="checkbox" id="field_{{2}}" name="CheckBoxList[{{2}}]"{{3}}>
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldColor()
    {
        self::$html = '
            <div class="form-group {{5}}">
                <label>{{1}}:</label>
                <input class="form-control" type="color" name="UserData[{{2}}{{3}}]" value="{{4}}">
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldSelect()
    {
        self::$html = '
            <div class="form-group {{8}}">
                <label>{{1}}:</label>
                <select class="form-control" id="field_{{2}}" name="UserData[{{2}}]">
                {{3}}
                </select>
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_FieldSelectInput()
    {
        self::$html = '
            <div class="form-group">
                <label>{{1}}:</label>
                <select class="form-control with-input" name="SelectList[{{2}}]">
                {{3}}
                </select>
                <input style="margin-top:5px;" type="text" name="InputList[{{7}}{{2}}]" class="form-control{{6}}" value="{{4}}" data-active="{{5}}">
                <div class="error-box"></div>
            </div>
        ';
    }

    private static function Snipet_AccordionPanel(){
        self::$html = '
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#{{1}}" href="#{{2}}">{{3}}</a>
                    </h4>
                </div>
                <div id="{{2}}" class="panel-collapse collapse{{4}}">
                    <div class="panel-body">
                        {{5}}
                    </div>
                </div>
            </div>
        ';
    }

    private static function Snipet_TestTest(){
        self::$html = '
            <h2>Тестовый снипет</h2>
        ';
    }

}