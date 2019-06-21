<?php
namespace ExtensionsValley\Menumanager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menutypes extends Model
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_types';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'position', 'is_all_page', 'status','created_by','updated_by'];


    public static function getMenuTypes()
    {

        return self::Where('deleted_at', NULL)
            ->Where('status', 1)
            ->pluck('title', 'id');
    }


    //Prevent relation breaking
    public static function getRlationstatus($cid)
    {
       $count = \DB::table('menu_items')
            ->WhereNull('deleted_at')
            ->WhereIn('menu_type', $cid)
            ->count();

        if ($count > 0) {
            return 1;
        } else {
            return 0;
        }

    }


    public $page_title = "Manage Menu Types";

    public $table_name = "menu_types";

    public $acl_key = "extensionsvalley.Menumanager.menutypes";

    public $namespace = 'ExtensionsValley\Menumanager\Tables\MenutypesTable';

    public $overrideview = "";

    public $model_name = 'ExtensionsValley\Menumanager\Models\Menutypes';

    public $listable = ['title' => 'Title', 'Position' => 'position','status' => 'Status', 'created_at' => 'Date'];

    public $parameter_array = [
        'acl_key' => 'extensionsvalley.menumanager.menutypes',
    ];

    public $show_toolbar = ['view' => 'Show'
        , 'add' => 'Add'
        , 'edit' => 'Edit'
        , 'publish' => 'Publish'
        , 'unpublish' => 'Unpublish'
        , 'trash' => 'Trash'
        , 'restore' => 'Restore'
        , 'forcedelete' => 'Force Delete'
    ];

    public $routes = ['add_route' => 'extensionsvalley/menumanager/addmenutypes'
        , 'edit_route' => 'extensionsvalley/menumanager/editmenutypes'
        , 'view_route' => 'extensionsvalley/menumanager/viewmenutypes'
    ];

    public $advanced_filter = ['layout' => ""
        ,'filters' => [
            'filter_trashed' => 'filter_trashed'
        ]
    ];


    public function getQuery()
    {
        $filter_trashed = \Input::get('filter_trashed');
        $groups = \DB::table('menu_types')
            ->select('id', 'title', 'position','status', 'created_at');

        if($filter_trashed == 1){
            $groups = $groups->where('deleted_at','<>', NULL);
        }else{
            $groups = $groups->where('deleted_at', NULL);
        }

        return \DataTables::of($groups)
            ->editColumn('sl', '<input type="checkbox" name="cid[]" value="{{$id}}" class="cid_checkbox"/>')
            ->editColumn('status', '@if($status==1) <span class="glyphicon glyphicon-ok"> Published</span> @else <span class="glyphicon glyphicon-remove"> Unpublished</span> @endif')
            ->editColumn('created_at', '{{date("M-j-Y",strtotime($created_at))}}')
            ->rawColumns(['sl', 'status'])
            ->make(true);
    }


}
