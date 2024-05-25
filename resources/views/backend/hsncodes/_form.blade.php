<div class="form-body">
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <fieldset class="form-group">
                {{ Form::label('hsncode_desc', 'HSN Code *') }}
                {{ Form::number('hsncode_desc', null, ['class' => 'form-control char-textarea', 'placeholder' => 'Enter Code', 'required' => true]) }}
            </fieldset>
        </div>

        <div class="col-md-12 col-12">
            <div class="form-group">
                {{ Form::label('hsncode_name', 'HSNCodes Name *') }}
                {{ Form::text('hsncode_name', null, ['class' => 'form-control', 'placeholder' => 'Enter HSNCodes Name', 'required' => true]) }}
            </div>
        </div>

        <div class="col-12 d-flex justify-content-start">
            <button type="submit" class="btn btn-primary mr-1 mb-1" id="submit_hsncode">Submit</button>
            <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>
        </div>
    </div>
</div>
