<?php use \app\helpers\Html; ?>

<div id="modalUpdateProperties" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Свойства сайта</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('site', 'property'); ?> '" method="POST" class="ajax-form">

                </form>
            </div>
        </div>
    </div>
</div>