<?php echo $header; ?>
    <h3 class="float-start mt-3 mb-3"><?php echo __('extend.create_variable'); ?></h3>
    <form id="form-add" method="post" action="<?php echo Uri::to('admin/extend/variables/add'); ?>"
          novalidate>
        <input name="token" type="hidden" value="<?php echo $token; ?>">

        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-name"><?php echo __('extend.name'); ?></label>
            <?php echo Form::text('key', Input::previous('key'), [
                'class' => 'form-control',
                'id' => 'label-name'
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('extend.name_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-value"><?php echo __('extend.value'); ?></label>
            <?php echo Form::textarea('value', Input::previous('value'), ['cols' => 20,
                'class' => 'form-control',
                'id' => 'label-value'
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('extend.value_explain'); ?></small>
        <div class="sticky-sm-bottom bg-body row">
            <div class="col px-0 d-grid gap-2">
                <?php echo Form::button(__('global.save'), [
                    'type' => 'submit',
                    'form' => 'form-add',
                    'class' => 'btn btn-success m-2'
                ]); ?>
            </div>
            <div class="col px-0 d-grid gap-2">
                <?php echo Html::link('admin/extend/variables/', __('global.cancel'), [
                    'class' => 'btn btn-link btn-block fw-bold text-muted text-decoration-none m-2'
                ]); ?>
            </div>
        </div>
    </form>
<?php echo $footer; ?>