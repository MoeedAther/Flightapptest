<form action="{{ url('add') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="video">
    <button type="submit">Submit</button>
</form>
