<h1><?php echo $this->Get('code');?></h1>

<div>
    <?php echo $this->Get('msg');?> <a href="<?php echo \app\helpers\Html::ActionPath('home', 'index');?>">На главную</a>
</div>