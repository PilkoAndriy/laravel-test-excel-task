<div class="text-sm leading-5 text-gray-500">{{$subCategory->title}}</div>
@includeWhen(isset($subCategory->parent),'partials.product.subcategory', ['subCategory' => $subCategory->parent])

