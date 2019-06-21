<?php
namespace ExtensionsValley\Menumanager\Models;

use Illuminate\Database\Eloquent\Model;
use ExtensionsValley\Dashboard\Models\Extension;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menuitems extends Model
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_items';

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
    protected $fillable = ['menu_name', 'source', 'menu_type','parent_menu','ordering','is_new_tab','is_spa', 'status','created_by','updated_by'];


    public static function getMenuItems()
    {

        return self::Where('deleted_at', NULL)
            ->Where('status', 1)
            ->pluck('menu_name', 'id');
    }

    public static function getallMenus($position){

       $result = \DB::table('menu_types as T')
            ->leftjoin('menu_items as I','T.id' , '=' ,'I.menu_type')
            ->WhereNull('I.deleted_at')
            ->WhereNull('T.deleted_at')
            ->Where('T.status',1)
            ->Where('I.status',1)
            ->Where('T.position',$position)
            ->orderBy('ordering','ASC')
            ->get(['I.id','I.menu_name','I.source','I.parent_menu','I.is_new_tab']);
            return $result;
    }

    public static function getAllMenusWithType($position,$type){

       $result = \DB::table('menu_types as T')
            ->leftjoin('menu_items as I','T.id' , '=' ,'I.menu_type')
            ->WhereNull('I.deleted_at')
            ->WhereNull('T.deleted_at')
            ->Where('T.status',1)
            ->Where('I.status',1)
            ->Where('I.parent_menu',0)
            ->Where('I.menu_type',$type)
            ->orderBy('ordering','ASC')
            ->get(['I.id','I.menu_name','I.source','I.parent_menu','I.is_new_tab']);
            return $result;
    }

    public static function getChildItems($menu_id,$type){
         $result = \DB::table('menu_types as T')
            ->leftjoin('menu_items as I','T.id' , '=' ,'I.menu_type')
            ->WhereNull('I.deleted_at')
            ->WhereNull('T.deleted_at')
            ->Where('T.status',1)
            ->Where('I.status',1)
           ->Where('I.parent_menu',$menu_id)
           ->Where('I.menu_type',$type)
            ->orderBy('ordering','ASC')
            ->get(['I.id','I.menu_name','I.source','I.parent_menu','I.is_new_tab']);
            return $result;
    }

    public static function getParentMenus($menu_id = 0){
        if($menu_id == 0){
          return self::Where('deleted_at', NULL)
            ->Where('status', 1)
            ->where('parent_menu',0)
            ->pluck('menu_name', 'id');
        }else{
          return self::Where('deleted_at', NULL)
            ->Where('status', 1)
            ->where('id','<>',$menu_id)
            ->where('parent_menu',0)
            ->pluck('menu_name', 'id');
        }

    }

    //Prevent relation breaking
    public static function getRlationstatus($cid)
    {
       return 0;
    }





    public $page_title = "Manage Menu Items";

    public $table_name = "menu_items";

    public $acl_key = "extensionsvalley.Menumanager.menuitems";

    public $namespace = 'ExtensionsValley\Menumanager\Tables\MenuitemsTable';

    public $overrideview = "";

    public $model_name = 'ExtensionsValley\Menumanager\Models\Menuitems';

    public $listable = ['menu_name' => 'Name', 'menu_type' => 'Menu Type','ordering' => 'Order','status' => 'Status', 'created_at' => 'Sub Menus','is_new_tab' => 'Sub Menus'];

    public $parameter_array = [
        'acl_key' => 'extensionsvalley.menumanager.menuitems',
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

    public $routes = ['add_route' => 'extensionsvalley/menumanager/addmenuitems'
        , 'edit_route' => 'extensionsvalley/menumanager/editmenuitems'
        , 'view_route' => 'extensionsvalley/menumanager/viewmenuitems'
    ];

    public $advanced_filter = ['layout' => "Menumanager::admin.advancedfilters.menufilter"
        ,'filters' => [
            'filter_parent_menu' => 'filter_parent_menu'
            , 'filter_status' => 'filter_status'
            , 'filter_menu_type' => 'filter_menu_type'
            , 'filter_trashed' => 'filter_trashed'
        ]
    ];


    public function getQuery()
    {
        $search = \Input::get('customsearch');
        $filter_trashed = \Input::get('filter_trashed');
        $filter_status = \Input::has('filter_status') ? \Input::get('filter_status') : '-1';
        $filter_menu_type = \Input::get('filter_menu_type');
        $filter_parent_menu = \Input::get('filter_parent_menu');

        $menuitems = \DB::table('menu_items as I')
            ->leftjoin('menu_types AS T','T.id','=','I.menu_type')
            ->OrderBy('I.ordering','ASC')
            ->select('I.id', 'I.menu_name', 'T.title as menu_type','I.ordering','I.status', 'I.created_at','I.is_new_tab');

        if($filter_trashed == 1){
            $menuitems = $menuitems->where('I.deleted_at','<>', NULL);
        }else{
            $menuitems = $menuitems->where('I.deleted_at', NULL);
        }

        if($filter_parent_menu > 0){
            $menuitems = $menuitems->where('I.parent_menu',$filter_parent_menu);
        }else{
            $menuitems = $menuitems->where('I.parent_menu',0);
        }
        if ($filter_status != -1) {
            $menuitems = $menuitems->Where('I.status', $filter_status);
        }
        if ($filter_menu_type != 0) {
            $menuitems = $menuitems->Where('I.menu_type', $filter_menu_type);
        }

        return \DataTables::of($menuitems)
            ->editColumn('sl', '<input type="checkbox" name="cid[]" value="{{$id}}" class="cid_checkbox"/>')
            ->editColumn('status', '@if($status==1) <span class="glyphicon glyphicon-ok"> Published</span> @else <span class="glyphicon glyphicon-remove"> Unpublished</span> @endif')
            ->editColumn('created_at', '@if(ExtensionsValley\Menumanager\Models\Menuitems::whereNull("deleted_at")->Where("status",1)->Where("parent_menu",$id)->count() > 0)
                 <a href="?filter_parent_menu={{$id}}">
                  Items
                  ({{ExtensionsValley\Menumanager\Models\Menuitems::whereNull("deleted_at")->Where("status",1)->Where("parent_menu",$id)->count()}})
                 </a>
                 @else
                    ---
                 @endif')
            ->filter(function ($query) use ($search,$filter_parent_menu,$filter_status,$filter_menu_type,$filter_trashed) {
                $query->where('I.menu_name', 'like', $search . '%')
                    ->orwhere('T.title', 'like', $search . '%');

                if($filter_trashed == 1){
                    $query->where('I.deleted_at','<>', NULL);
                }else{
                    $query->where('I.deleted_at', NULL);
                }
                if ($filter_parent_menu > 0) {
                    $query->Where('I.parent_menu', $filter_parent_menu);
                }else{
                    $query->Where('I.parent_menu', 0);
                }
                if ($filter_status > 0) {
                    $query->Where('I.status', $filter_status);
                }
                if ($filter_menu_type > 0) {
                    $query->Where('I.menu_type', $filter_menu_type);
                }

            })
            ->make(true);
    }




}
