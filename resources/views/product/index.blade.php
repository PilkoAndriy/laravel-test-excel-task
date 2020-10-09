@extends('layouts.app')

@section('content')
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    @if(session()->has('excel.export.error'))
                    @foreach(session()->pull('excel.export.error') as $sessionKey => $sessionValue)
                            <div class="bg-red-500 text-white font-bold rounded-t px-2 py-2">
                                {{"Failed rows on key {$sessionKey}: {$sessionValue}"}}
                            </div>

                    @endforeach
                    @endif
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('Name')}}
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('Article')}}
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('Description')}}
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('Price')}}
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('Guarantee')}}
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('InStock')}}
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('Brand')}}
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{__('Category')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{$product->name}}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{$product->article}}
                                </td>
                                <td class="px-6 py-4 text-sm leading-5 text-gray-500">
                                    {{$product->description}}
                                </td>
                                <td class="px-6 py-4 text-sm leading-5 text-gray-500">
                                    {{number_format($product->price, 2)}}
                                </td>
                                <td class="px-6 py-4 text-sm leading-5 text-gray-500">
                                    {{$product->guarantee??__('Without guarantee')}}
                                </td>
                                <td class="px-6 py-4 text-sm leading-5 text-gray-500">
                                    {{$product->in_stock?__('In stock'):__('missing')}}
                                </td>
                                <td class="px-6 py-4 text-right text-sm leading-5 font-medium">
                                    <a href="#"
                                       class="text-indigo-600 hover:text-indigo-900">{{$product->brand->title}}</a>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="text-sm leading-5 text-gray-900">{{$product->category->title}}</div>
                                        @includeWhen(isset($product->category->parent), 'partials.product.subcategory', ['subCategory' => $product->category->parent])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(count($products))
                        <div class="mx-auto p-10">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
