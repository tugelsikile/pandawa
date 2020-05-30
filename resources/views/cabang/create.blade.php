@csrf
<div class="form-group">
    <label for="cab_name" class="col-sm-2 control-label">Nama Cabang</label>
    <div class="col-sm-10">
        <input name="cab_name" id="cab_name" type="text" class="cab_name form-control">
    </div>
</div>
<script>
    $('#ModalForm').attr({'action':'{{ url('admin-cabang/create') }}'});
</script>