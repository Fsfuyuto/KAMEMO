function deleteHandle(event){
    // 一旦フォームをストップ
  event.preventDefault();
  if(window.confirm('Do you really want to delete？')){
    // 削除OKならformを再開
    document.getElementById('delete-form').submit();
	}else{
		alert('Cancelled');
	}
}