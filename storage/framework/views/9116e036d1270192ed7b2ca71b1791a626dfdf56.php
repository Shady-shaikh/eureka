<div class="form-body">
    <div class="row">
      <div class="col-md-12 col-12">
        <div class="form-group">
          <?php echo e(Form::label('name', 'Combi Type Name *')); ?>

          <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Combi Type Name', 'required' => true])); ?>

        </div>
      </div>
      
      <div class="col-12 d-flex justify-content-start">
        <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_combi">Submit</button>
        <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
      </div>
    </div>
  </div><?php /**PATH C:\wamp64\www\eureka\resources\views/backend/products/combi_form.blade.php ENDPATH**/ ?>