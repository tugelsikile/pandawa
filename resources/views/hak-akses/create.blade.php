@csrf

<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="nama_hak_akses">Nama Hak Akses</label>
    <div class="col-sm-10">
        <input type="text" value="" name="nama_hak_akses" id="nama_hak_akses" class="form-control">
    </div>
</div>
<div class="form-group">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">Halaman</th>
            <th rowspan="2">Fungsi</th>
            <th colspan="4">Hak Akses</th>
        </tr>
        <tr>
            <th width="50px"><abbr onclick="$('.R_opt').bootstrapToggle('toggle')" title="Membaca data">R</abbr></th>
            <th width="50px"><abbr onclick="$('.C_opt').bootstrapToggle('toggle')" title="Membuat data">C</abbr></th>
            <th width="50px"><abbr onclick="$('.U_opt').bootstrapToggle('toggle')" title="Mengedit data">U</abbr></th>
            <th width="50px"><abbr onclick="$('.D_opt').bootstrapToggle('toggle')" title="Menghapus data">D</abbr></th>
        </tr>
        </thead>
        <tbody>
        @if($controllers->count())
            @foreach($controllers as $controller)
                <tr>
                    <td colspan="6">{{ $controller->ctrl_name }}</td>
                </tr>
                @if($controller->functions->count())
                    @foreach($controller->functions as $function)
                        <tr>
                            <td></td>
                            <td>{{ $function->func_name }}</td>
                            <td><input type="checkbox" class="R_opt" name="R_opt[{{ $function->func_id }}]"></td>
                            <td><input type="checkbox" class="C_opt" name="C_opt[{{ $function->func_id }}]"></td>
                            <td><input type="checkbox" class="U_opt" name="U_opt[{{ $function->func_id }}]"></td>
                            <td><input type="checkbox" class="D_opt" name="D_opt[{{ $function->func_id }}]"></td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<script>
    $('.R_opt,.C_opt,.U_opt,.D_opt').bootstrapToggle({
        on      : 'Allow',
        off     : 'Forbid',
        size    : 'small',
        onstyle : 'success',
        offstyle: 'danger'
    });
</script>