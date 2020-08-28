@csrf
@if(auth()->user()->cab_id)
    <input type="hidden" name="nama_cabang" id="nama_cabang" value="{{ auth()->user()->cab_id }}">
@else
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="nama_cabang">Nama Cabang</label>
        <div class="col-sm-10">
            <select id="nama_cabang" name="nama_cabang" class="form-control">
                <option value="">=== Nama Cabang ===</option>
                @if($cabangs)
                    @foreach($cabangs as $key => $cabang)
                        <option value="{{ $cabang->cab_id }}">{{ $cabang->cab_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
@endif
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_pengguna">Nama Pengguna</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="alamat_email">Alamat Email / Username</label>
    <div class="col-sm-10">
        <input type="email" name="alamat_email" id="alamat_email" class="form-control">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="kata_sandi">Kata Sandi</label>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="password" class="form-control" name="kata_sandi" id="kata_sandi">
            <div class="input-group-append">
                <button onclick="$('#kata_sandi').attr('type')=='text' ? $('#kata_sandi').attr('type','password') : $('#kata_sandi').attr('type','text')" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
            </div>
        </div>
    </div>
    <label class="col-sm-2 col-form-label" for="ulangi_kata_sandi">Ulangi Kata Sandi</label>
    <div class="col-sm-4">
        <div class="input-group">
            <input type="password" class="form-control" name="ulangi_kata_sandi" id="ulangi_kata_sandi">
            <div class="input-group-append">
                <button onclick="$('#ulangi_kata_sandi').attr('type')=='text' ? $('#ulangi_kata_sandi').attr('type','password') : $('#ulangi_kata_sandi').attr('type','text')" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="level_pengguna">Level Pengguna</label>
    <div class="col-sm-4">
        <select name="level_pengguna" id="level_pengguna" class="form-control">
            @if($user_levels)
                @foreach($user_levels as $key => $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<script>
    $('#level_pengguna,#nama_cabang').select2();
    $('#ModalForm').attr({'action':'{{ url('radius-server/create') }}'});
</script>