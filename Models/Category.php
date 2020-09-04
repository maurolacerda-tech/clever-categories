<?php

namespace Modules\Categories\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','image','summary','status','featured','order','body','seo_title','meta_description','meta_keywords','menu_id','parent_id'];

    const STATUS = [
        'active' => 'Ativo',
        'inactive' => 'Inativo'
    ];

    const FEATURED = [
        'active' => 'Ativo',
        'inactive' => 'Inativo'
    ];

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function getCountChildAttribute()
    {
        $id = $this->id;
        $count = Category::where('parent_id', $id)->count();
        return $count;        
    }

    public function child2($in='')
    {
        if(!empty($in))
            return $this->hasMany(self::class, 'parent_id', 'id')->whereIn('id', explode(',',$in))->orderBy('order', 'asc')->get();
        else
            return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('order', 'asc')->get();
    }

    public function combo_all($in='')
    {
        if(!empty($in))
            $categories = $this->whereNull('parent_id')->whereIn('id', explode(',',$in))->get();
        else
            $categories = $this->whereNull('parent_id')->get();

        $pai = [];
        foreach($categories as $category){
            $filho = [];
            $categoryId = $category->id;
            $categoryName = $category->name;
            $pai[$categoryId] = $categoryName;
            
            foreach ($category->child2($in) as $categoryFilho) {
                $categorySubName = $categoryFilho->name;
                $pai[$categoryFilho->id] = $categoryName.' » '.$categorySubName;
                foreach ($categoryFilho->child2($in) as $categoryFilhoFilho) {
                    $categorySubSubName = $categoryFilhoFilho->name;
                    $pai[$categoryFilhoFilho->id] = $categoryName.' » '.$categorySubName.' » '.$categorySubSubName;
                    foreach ($categoryFilhoFilho->child2($in) as $categoryFilhoFilhoFilho) {
                        $categorySubSubSubName = $categoryFilhoFilhoFilho->name;
                        $pai[$categoryFilhoFilhoFilho->id] = $categoryName.' » '.$categorySubName.' » '.$categorySubSubName.' » '.$categorySubSubSubName;
                    }
                }
            }
        }
        return $pai;
    }

}