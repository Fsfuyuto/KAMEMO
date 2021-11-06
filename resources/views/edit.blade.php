@extends('layouts.app')

@section('javascript')
<script src="/js/confirm.js"></script>
@endsection


@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        Edit Memo
        <!-- これを入れてポストリクエストを作る -->
        <form id="delete-form" action="{{ route('destroy') }}" method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
            <i class="fas fa-trash mr-3" onclick="deleteHandle(event);"></i>
        </form>
    </div>
    <!-- {{-- route('store') と書くと→ /store --}} -->
    <!-- dataをサーバーに投げるのでFormタグを使う -->
    <form class="card-body my-card-body" action="{{ route('update') }}" method="POST">
    <!-- なりすまし防止->csrfトークンを入れる -->
        @csrf
        <!-- どのメモの🆔を更新するかコントローラーに教えてあげる -->
        <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}"/>
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力">{{$edit_memo[0]['content']}}</textarea>
        </div>
        @error('content')
            <div class="alert alert-danger">Please input your memo！</div>
        @enderror
        @foreach($tags as $tag)
            <div class="form-check form-check-inline mb-3">
                {{-- 3項演算子 → if文を1行で書く方法 {{ 条件 ? trueだったら : falseだったら }}--}}
                {{-- もし$include_tagsにループで回っているタグのidが含まれれば、ckeckedを書く --}}
            <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}" 
            {{ in_array($tag['id'], $include_tags) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name']}}</label>
            </div>
         @endforeach
            <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="Create New Tag" />
            <!-- typeをSubmitにすると保存ボタンを押したときにFormのアクションURL移動する -->
            <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
