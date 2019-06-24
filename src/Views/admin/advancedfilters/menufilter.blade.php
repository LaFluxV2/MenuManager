<div class="col-md-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h5>Advanced Search Filter</h5>
        </div>
        <div class="x_content">
            {!!Form::open(array('url' => Request::url(), 'method' => 'get')) !!}

            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12 form-group">
                {!! Form::label('filter_parent_menu', 'Parent Menus') !!}
                {!! Form::select('filter_parent_menu', array('0'=>'--Select--') + ExtensionsValley\Menumanager\Models\Menuitems::getParentMenus(0)->toArray(), \Input::has('filter_parent_menu') ? \Input::get('filter_parent_menu') : '', [
                    'class'       => 'form-control input-sm select2',
              ]) !!}
            </div>

           <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12 form-group">
                {!! Form::label('filter_menu_type', 'Menu Type') !!}
                {!! Form::select('filter_menu_type', array('0'=>'--Select--') + ExtensionsValley\Menumanager\Models\Menutypes::getMenuTypes()->toArray(), \Input::has('filter_menu_type') ? \Input::get('filter_menu_type') : '', [
                    'class'       => 'form-control input-sm select2',
              ]) !!}
            </div>

            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12 form-group">
                {!! Form::label('filter_status', 'Menu Item Status') !!}
                {!! Form::select('filter_status', array('-1'=>'--Select--','0' => 'Unpublished','1' => 'Published')  ,\Input::has('filter_status') ? \Input::get('filter_status') : '' , [
                    'class'       => 'form-control js-example-responsive filter_status select2',
                ]) !!}
            </div>

            <div class="form-group pull-right">
                    <div class="col-md-12 col-sm-2 col-xs-2 ">
                        <br>
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-filter"></i>  Filter</button>
                            <a href="{{Request::url()}}" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Clear</a>
                    </div>
                </div>

            {!! Form::token() !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
