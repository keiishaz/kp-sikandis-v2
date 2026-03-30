<?php

return [
    'accepted'        => ':attribute harus diterima.',
    'accepted_if'     => ':attribute harus diterima ketika :other berisi :value.',
    'active_url'      => ':attribute bukan URL yang valid.',
    'after'           => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'  => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'           => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'      => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'       => ':attribute hanya boleh berisi huruf dan angka.',
    'array'           => ':attribute harus berisi sebuah array.',
    'before'          => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'         => [
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'file'    => ':attribute harus berukuran antara :min sampai :max kilobita.',
        'string'  => ':attribute harus berisi antara :min sampai :max karakter.',
        'array'   => ':attribute harus memiliki :min sampai :max anggota.',
    ],
    'boolean'         => ':attribute harus bernilai true atau false.',
    'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date'            => ':attribute bukan tanggal yang valid.',
    'date_equals'     => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'     => ':attribute tidak cocok dengan format :format.',
    'declined'        => ':attribute harus ditolak.',
    'declined_if'     => ':attribute harus ditolak ketika :other berisi :value.',
    'different'       => ':attribute dan :other harus berbeda.',
    'digits'          => ':attribute harus terdiri dari :digits angka.',
    'digits_between'  => ':attribute harus terdiri dari :min sampai :max angka.',
    'dimensions'      => ':attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'        => ':attribute memiliki nilai yang duplikat.',
    'doesnt_contain'  => ':attribute tidak boleh berisi salah satu dari nilai berikut: :values.',
    'doesnt_end_with' => ':attribute tidak boleh diakhiri dengan salah satu dari nilai berikut: :values.',
    'doesnt_start_with' => ':attribute tidak boleh diawali dengan salah satu dari nilai berikut: :values.',
    'email'           => ':attribute harus berupa alamat surel yang valid.',
    'ends_with'       => ':attribute harus diakhiri dengan salah satu dari: :values.',
    'enum'            => ':attribute yang dipilih tidak valid.',
    'exists'          => ':attribute yang dipilih tidak valid.',
    'file'            => ':attribute harus berupa sebuah berkas.',
    'filled'          => ':attribute harus memiliki nilai.',
    'gt'              => [
        'numeric' => ':attribute harus bernilai lebih besar dari :value.',
        'file'    => ':attribute harus berukuran lebih besar dari :value kilobita.',
        'string'  => ':attribute harus berisi lebih dari :value karakter.',
        'array'   => ':attribute harus memiliki lebih dari :value anggota.',
    ],
    'gte'             => [
        'numeric' => ':attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
        'string'  => ':attribute harus berisi lebih dari atau sama dengan :value karakter.',
        'array'   => ':attribute harus memiliki :value anggota atau lebih.',
    ],
    'image'           => ':attribute harus berupa gambar.',
    'in'              => ':attribute yang dipilih tidak valid.',
    'in_array'        => ':attribute tidak ada di dalam :other.',
    'integer'         => ':attribute harus berupa bilangan bulat.',
    'ip'              => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'            => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'            => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'            => ':attribute harus berupa JSON string yang valid.',
    'lowercase'       => ':attribute harus berupa huruf kecil.',
    'lt'              => [
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobita.',
        'string'  => ':attribute harus berisi kurang dari :value karakter.',
        'array'   => ':attribute harus memiliki kurang dari :value anggota.',
    ],
    'lte'             => [
        'numeric' => ':attribute harus bernilai kurang dari atau sama dengan :value.',
        'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'string'  => ':attribute harus berisi kurang dari atau sama dengan :value karakter.',
        'array'   => ':attribute harus tidak lebih dari :value anggota.',
    ],
    'max'             => [
        'numeric' => ':attribute tidak boleh bernilai lebih dari :max.',
        'file'    => ':attribute tidak boleh berukuran lebih dari :max kilobita.',
        'string'  => ':attribute tidak boleh berisi lebih dari :max karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :max anggota.',
    ],
    'max_digits'      => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes'           => ':attribute harus berupa berkas berjenis: :values.',
    'mimetypes'       => ':attribute harus berupa berkas berjenis: :values.',
    'min'             => [
        'numeric' => ':attribute harus bernilai minimal :min.',
        'file'    => ':attribute harus berukuran minimal :min kilobita.',
        'string'  => ':attribute harus berisi minimal :min karakter.',
        'array'   => ':attribute harus memiliki minimal :min anggota.',
    ],
    'min_digits'      => ':attribute harus memiliki minimal :min digit.',
    'missing'         => ':attribute harus tidak ada.',
    'missing_if'      => ':attribute harus tidak ada ketika :other adalah :value.',
    'missing_unless'  => ':attribute harus tidak ada kecuali :other adalah :value.',
    'missing_with'    => ':attribute harus tidak ada ketika :values ada.',
    'missing_with_all' => ':attribute harus tidak ada ketika :values ada.',
    'multiple_of'     => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'          => ':attribute yang dipilih tidak valid.',
    'not_regex'       => 'Format :attribute tidak valid.',
    'numeric'         => ':attribute harus berupa angka.',
    'password'        => [
        'letters'       => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed'         => ':attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung setidaknya satu angka.',
        'symbols'       => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present'         => ':attribute harus ada.',
    'present_if'      => ':attribute harus ada ketika :other adalah :value.',
    'present_unless'  => ':attribute harus ada kecuali :other adalah :value.',
    'present_with'    => ':attribute harus ada ketika :values ada.',
    'present_with_all' => ':attribute harus ada ketika :values ada.',
    'prohibited'      => ':attribute dilarang.',
    'prohibited_if'   => ':attribute dilarang ketika :other adalah :value.',
    'prohibited_unless' => ':attribute dilarang kecuali :other ada di :values.',
    'prohibits'       => ':attribute melarang :other untuk ada.',
    'regex'           => 'Format :attribute tidak valid.',
    'required'        => ':attribute wajib diisi.',
    'required_array_keys' => ':attribute harus mengandung entri untuk: :values.',
    'required_if'     => ':attribute wajib diisi ketika :other adalah :value.',
    'required_if_accepted' => ':attribute wajib diisi ketika :other diterima.',
    'required_unless' => ':attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'   => ':attribute wajib diisi ketika terdapat :values.',
    'required_with_all' => ':attribute wajib diisi ketika terdapat :values.',
    'required_without' => ':attribute wajib diisi ketika tidak terdapat :values.',
    'required_without_all' => ':attribute wajib diisi ketika tidak terdapat satupun dari :values.',
    'same'            => ':attribute dan :other harus sama.',
    'size'            => [
        'numeric' => ':attribute harus berukuran :size.',
        'file'    => ':attribute harus berukuran :size kilobita.',
        'string'  => ':attribute harus berukuran :size karakter.',
        'array'   => ':attribute harus memiliki :size anggota.',
    ],
    'starts_with'     => ':attribute harus diawali salah satu dari berikut: :values.',
    'string'          => ':attribute harus berupa string.',
    'timezone'        => ':attribute harus berupa zona waktu yang valid.',
    'unique'          => ':attribute sudah pernah terdaftar.',
    'uploaded'        => ':attribute gagal diunggah.',
    'uppercase'       => ':attribute harus berupa huruf besar.',
    'url'             => ':attribute harus berupa URL yang valid.',
    'uuid'            => ':attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'nomor_sk'      => 'Nomor SK',
        'tanggal_sk'    => 'Tanggal SK',
        'tanggal_mulai' => 'Tanggal Mulai',
        'nip'           => 'NIP',
        'pegawai_id'    => 'Pegawai',
        'nama_kendaraan' => 'Nama Kendaraan',
        'no_polisi'     => 'Nomor Polisi',
        'no_rangka'     => 'Nomor Rangka',
        'no_mesin'      => 'Nomor Mesin',
        'pajak'         => 'Tanggal Pajak',
        'tahun'         => 'Tahun',
        'status'        => 'Status',
        'nama_kategori' => 'Nama Kategori',
        'nama_unit'     => 'Nama Unit',
        'nama'          => 'Nama',
        'password'      => 'Kata Sandi',
        'username'      => 'Nama Pengguna',
    ],
];
