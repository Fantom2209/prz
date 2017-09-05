<?php
    use \app\core\Config;
?>
<html>
	<head>
		<title><?php echo $this->Get('title');?></title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.1/css/bootstrap-slider.min.css" rel="stylesheet">
        <link href="<?php echo Config::URL_CSS;?>custom.css" rel="stylesheet">
        <link href="<?php echo Config::URL_CSS;?>jquery-ui.min.css" rel="stylesheet">
        <script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
        <div>
            <?php
                $this->modules->menu;
            ?>
        </div>
        <div>
            <?php
                $this->modules->alerts;
            ?>
        </div>
        <div class="container">
		<?php
            require_once($this->view->Get('template'));
		?>
        </div>


        <div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Уверены?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default clear-active" data-dismiss="modal">Нет</button>
                        <a href="javascript:;" class="ajax-link btn btn-primary go" data-href="">Да</a>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.1/bootstrap-slider.min.js"></script>
        <script src="<?php echo Config::URL_JS; ?>jquery-ui.min.js"></script>
        <script src="<?php echo Config::URL_JS; ?>custom.js"></script>

    </body>
</html>