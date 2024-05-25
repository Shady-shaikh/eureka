<div class="form-body">
  <div class="row">
    <div class="col-md-12 col-12">
      <div class="form-group">
        {{ Form::label('name', 'Variant Name *') }}
        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Variant Name', 'required' => true]) }}
      </div>
    </div>
    {{-- <div class="col-lg-12 col-md-12">
      <fieldset class="form-group">
        {{ Form::label('brand_desc', 'Brand Description *') }}
          {{ Form::textarea('brand_desc', null, ['class' => 'form-control char-textarea', 'placeholder' => 'Enter Description', 'rows'=>3]) }}
      </fieldset>
    </div> --}}
    <div class="col-12 d-flex justify-content-start">
      <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_variant">Submit</button>
      <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
    </div>
  </div>
</div>