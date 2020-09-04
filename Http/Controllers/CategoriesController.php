<?php

namespace Modules\Categories\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Gate;

use App\Helpers\Functions;
use App\Models\Menu;
use App\Models\Language;
use App\Models\Translation;
use Modules\Categories\Models\Category;

use Modules\Categories\Http\Requests\CategoryRequest;

class CategoriesController extends Controller
{

    protected $menu_id;
    protected $menu_icon;
    protected $menu_name;
    protected $slug;
    protected $folder;
    protected $combine_filds;    
    
    public function __construct()
    {
        $slug = Functions::get_menuslug();
        $menu = Menu::where('slug',$slug)->first();
        $this->slug = $slug;        

        $this->folder = config('categories.folder');
        if($menu){
            $this->menu_id = is_null($menu->parent_id) ? $menu->id : $menu->parent_id;
            $this->menu_icon = $menu->icons;
            $this->menu_name = $menu->name;

            $keysFilds = explode(',',$menu->fields_active);
            $titlesFilds = explode(',',$menu->fields_title);
            $combineFilds = array_combine($keysFilds, $titlesFilds);
            $this->combine_filds = $combineFilds;
        }else{
            $this->menu_id = null;
        }
    }

    public function index(Request $request, Category $category)
    {  
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;

        if(!is_null($menu_id)){
            $categories = $category->where('menu_id', $menu_id)
            ->where(function ($query) use ($request) {            
                if(isset($request['parent']) && is_numeric($request['parent']) ){
                    $parent = (int)$request['parent'];
                    $query->where('parent_id', $parent);
                }else{
                    $query->whereNull('parent_id');
                }           
            })->orderBy('order', 'asc')->paginate(50);
            $total = $categories->total();
            $orders = \Functions::number_array($total);
            return view('Category::index', compact('categories', 'menu_icon', 'menu_name', 'slug', 'orders', 'combine_filds'));
        }else{
            abort(403, 'Página não encontrada');
        }
    }

    public function create(Category $category)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;

        $option_void = ['' => 'Selecione' ];
        $categories_list = $option_void+$category->combo_all();

        return view('Category::create', compact('menu_id', 'menu_icon', 'menu_name', 'slug', 'combine_filds', 'categories_list'));
    }

    public function store(CategoryRequest $request)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $data = $request->only(array_keys($request->rules()));
        if(isset($request->image))
            $data['image'] = $this->_uploadImage($request);
        Category::create($data);
        return redirect()->back()->with('success','Adicionado com sucesso!');
    }

    public function edit(Category $category)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $menu_id = $this->menu_id;
        $menu_icon = $this->menu_icon;
        $menu_name = $this->menu_name;
        $slug = $this->slug;
        $combine_filds = $this->combine_filds;
        $languages = Language::where('status', 'active')->orderBy('order', 'asc')->get();

        $categoryModel = new Category;
        $option_void = ['' => 'Selecione' ];
        $categories_list = $option_void+$categoryModel->combo_all();

        return view('Category::edit', compact('category', 'languages', 'menu_id', 'menu_icon', 'menu_name', 'slug', 'combine_filds', 'categories_list'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');

        $data = $request->only(array_keys($request->rules()));
        if(isset($request->image))
            $data['image'] = $this->_uploadImage($request, $category->image);
        $category->fill($data);
        $category->save();
        return redirect()->back()->with('success','Atualizado com sucesso');
    }

    public function destroy(Category $category)
    {
        if( Gate::denies("manager_{$this->slug}") ) 
            abort(403, 'Você não tem permissão para gerenciar esta página');
            
        Translation::where('parent_id', $category->id)->where('menu_id', $this->menu_id)->delete();
        $category->delete();              
        return redirect()->back()->with('success','Excluído com sucesso!');
    }

    public function status(Category $category)
    {
        $status = $category->status == 'active' ? 'inactive' : 'active';
        $category->status = $status;
        $category->save();
        return redirect()->back()->with('success','Status atualizado com sucesso');
    }

    public function featured(Category $category)
    {
        $featured = $category->featured == 'active' ? 'inactive' : 'active';
        $category->featured = $featured;
        $category->save();
        return redirect()->back()->with('success','Destaque atualizado com sucesso');
    }

    public function order(Request $request, Category $category)
    {
        $this_id = $category->id;
        $currentOrder = $request->order;
        $nextOrder = $currentOrder + 1;
        $previousOrder = $currentOrder - 1;
        $direction = ($category->order < $currentOrder ? 'up' : 'down');
        $menu_id = $this->menu_id;
        $parent_id = $category->parent_id;
        
        if($direction =='up'){
            $listModel = Category::where('menu_id', $menu_id)->where('parent_id', $parent_id)->where('order', '>=', $nextOrder)
            ->where('id', '<>', $this_id)
            ->orderBy('order', 'asc')->get();
            foreach($listModel as $categoryItem){
                $categoryItem->order = $categoryItem->order + 1;
                $categoryItem->save();
            }
            $category->order = $nextOrder;
            $category->save();
        }else{
            $listModel = Category::where('menu_id', $menu_id)->where('parent_id', $parent_id)->where('order', '<=', $previousOrder)
            ->where('id', '<>', $this_id)
            ->orderBy('order', 'asc')->get();
            foreach($listModel as $categoryItem){
                $categoryItem->order = $categoryItem->order - 1;
                $categoryItem->save();
            }
            $category->order = $previousOrder;
            $category->save();
        }
        $cont = 1;
        $listModel = Category::where('menu_id', $menu_id)->where('parent_id', $parent_id)->orderBy('order', 'asc')->get();
        foreach($listModel as $categoryItem){
            $categoryItem->order = $cont;
            $categoryItem->update();
            $cont++;
        }
        return redirect()->back()->with('success','Ordem atualizada com sucesso');

    }

    protected function _uploadImage(Request $request, $nameImage = null)
    {
        if(isset($request->image)){           
            $responseUpload = \Upload::imagePublic($request, 'image', $this->folder, null, $nameImage);
            if($responseUpload->original['success']){
                return $responseUpload->original['file'];
            }
            return null;
        }else{
            return null;
        }
    }

}