<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>
<!-- Main content -->
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">

            <div class="row m-b-30">
                <div class="col-sm-12">
					<div class="form-group">
                    <label>{{$evento->nombre}}</label>
						</div>
					
					{!! Form::close() !!}
				</div>
            </div>
        </div>
    </div>
</div>
