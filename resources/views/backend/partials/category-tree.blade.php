{{-- যেকোনো view-তে --}}
{{--@include('partials.category-tree', ['categories' => $tree])--}}
{{-- যেকোনো model- --}}
{{--$tree = Category::where('parent_id', $category->id)->with('allChildren')->get();--}}
<ul>
    @foreach($categories as $category)
        <li>
            {{ $category->name }}
            @if($category->children->count())
                @include('backend.partials.category-tree', ['categories' => $category->children])
            @endif
        </li>
    @endforeach
</ul>