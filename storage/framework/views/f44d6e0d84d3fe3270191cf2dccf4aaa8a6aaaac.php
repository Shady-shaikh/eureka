
<div class="form-body">
  <div class="row">
    <div class="col-md-12 col-12">
      <div class="form-group">
        <?php echo e(Form::label('category_name', 'Category Name *')); ?>

        <?php echo e(Form::text('category_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Category Name', 'required' => true,'id'=>'category_name'])); ?>

      </div>
    </div>
 
    <div class="col-md-3 col-6">
      <?php echo e(Form::label('visibility', 'Show / Hide')); ?>

      <fieldset>
        <div class="radio radio-success">
          <?php echo e(Form::radio('visibility','1',true,['id'=>'radioshow'])); ?>

          <?php echo e(Form::label('radioshow', 'Yes')); ?>

        </div>
      </fieldset>
      <fieldset>
        <div class="radio radio-danger">
          <?php echo e(Form::radio('visibility','0',false,['id'=>'radiohide'])); ?>

          <?php echo e(Form::label('radiohide', 'No')); ?>

        </div>
      </fieldset>
    </div>
    <div class="col-12 d-flex justify-content-start">
      <?php echo e(Form::submit('Save', array('class' => 'btn btn-primary mr-1 mb-1','id'=>'submit_category'))); ?>

      <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
    </div>
  </div>
</div><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/categories/_form.blade.php ENDPATH**/ ?>