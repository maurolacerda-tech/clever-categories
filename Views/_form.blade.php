{{ Form::hidden('menu_id', $menu_id) }}

@isset ($combine_filds['image'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('image', $combine_filds['image']) }}
            <input type="file" name="image" class="dropify" @if(isset($category->image)) data-default-file="{{ url("storage/categories/".$category->image) }}"@endif data-height="150" data-max-file-size="2M" data-allowed-file-extensions="jpg png jpeg"  />
        </div>
    </div>
</div>
@endisset


@isset ($combine_filds['parent_id'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('parent_id', 'Parent', ['class' => 'form-label']) }}
            {{Form::select('parent_id', $categories_list,null,['class' => $errors->has('parent_id') ?  'form-control is-invalid' : 'form-control'])}}
            @include('admin.partials._help_block',['field' => 'parent_id'])
        </div>
    </div>
</div>
@endisset

@isset ($combine_filds['name'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('name', $combine_filds['name']) }}
            {{ Form::text('name', null, ['class' => $errors->has('name') ?  'form-control is-invalid' : 'form-control', 'onkeyup' => "slugGenerate(this,'Modules\\\Categories\\\Models\\\Category')"]) }}
            @include('admin.partials._help_block',['field' => 'name'])
        </div>
    </div>
</div>
@endisset


@isset ($combine_filds['summary'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('summary', $combine_filds['summary']) }}
            {{ Form::textarea('summary', null, ['class' => $errors->has('summary') ?  'form-control is-invalid' : 'form-control']) }}
            @include('admin.partials._help_block',['field' => 'summary'])
        </div>
    </div>
</div>
@endisset

@isset ($combine_filds['body'])
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            {{ Form::label('body', $combine_filds['body']) }}
            {{ Form::textarea('body', null, ['class' => $errors->has('body') ?  'form-control is-invalid html-editor' : 'form-control html-editor']) }}
            @include('admin.partials._help_block',['field' => 'body'])
        </div>
    </div>
</div>
@endisset

@if (isset($combine_filds['slug']) || isset($combine_filds['seo_title']) || isset($combine_filds['meta_description']) || isset($combine_filds['meta_keywords']) )
    <h6>Meta Tags</h6>
    <div class="row">
        @isset ($combine_filds['slug'])
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('slug', $combine_filds['slug'], ['class' => 'form-label']) }} 
                {{ Form::text('slug', null, ['class' => $errors->has('slug') ?  'form-control is-invalid' : 'form-control']) }}                            
                @include('admin.partials._help_block',['field' => 'slug'])
            </div>
        </div>
        @endisset

        @isset ($combine_filds['seo_title'])
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('seo_title', $combine_filds['seo_title']) }} 
                {{ Form::text('seo_title', null, ['class' => $errors->has('seo_title') ?  'form-control is-invalid' : 'form-control']) }}
                @include('admin.partials._help_block',['field' => 'seo_title'])
            </div>
        </div>
        @endisset

    </div>

    @isset ($combine_filds['meta_description'])
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {{ Form::label('meta_description', $combine_filds['meta_description']) }} 
                {{ Form::text('meta_description', null, ['class' => $errors->has('meta_description') ?  'form-control is-invalid' : 'form-control']) }}
                @include('admin.partials._help_block',['field' => 'meta_description'])
            </div>
        </div>
    </div>
    @endisset

    @isset ($combine_filds['meta_keywords'])
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                {{ Form::label('meta_keywords', $combine_filds['meta_keywords']) }} <p>Para adicionar o próximo item use a tecla <code>enter</code> ou <code>,</code> </p>
                {{ Form::text('meta_keywords', null, ['class' => $errors->has('meta_keywords') ?  'form-control tags is-invalid ' : 'form-control tags']) }}
                @include('admin.partials._help_block',['field' => 'meta_keywords'])
            </div>
        </div>
    </div>
    @endisset
@endif

