@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Create New Memo</div>
    <!-- {{-- route('store') と書くと→ /store --}} -->
    <!-- dataをサーバーに投げるのでFormタグを使う -->
    <form class="card-body my-card-body" action="{{ route('store') }}" method="POST">
    <!-- なりすまし防止->csrfトークンを入れる -->
        @csrf
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="Input New Memo"></textarea>
        </div>
        @error('content')
            <div class="alert alert-danger">Please input your memo！</div>
        @enderror
    @foreach($tags as $tag)
        <div class="form-check form-check-inline mb-3">
          <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}">
          <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name']}}</label>
        </div>
    @endforeach
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="Create New Tag">
        <!-- typeをSubmitにすると保存ボタンを押したときにFormのアクションURL移動する -->
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
