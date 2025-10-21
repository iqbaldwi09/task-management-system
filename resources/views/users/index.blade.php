<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>

            <button id="btnOpenAddModal"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700 transition">
                + Tambah User
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- DataTable --}}
                <div class="overflow-x-auto">
                    <table id="userTable"
                        class="min-w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                        <thead class="bg-gray-100 text-gray-900 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-4 py-3 border-b text-center">No</th>
                                <th class="px-4 py-3 border-b">Nama</th>
                                <th class="px-4 py-3 border-b">Email</th>
                                <th class="px-4 py-3 border-b text-center">Role</th>
                                <th class="px-4 py-3 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @include('users.modals.usersModal')

    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#userTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('users.index') }}",
                    order: [
                        [1, 'asc']
                    ],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'role',
                            name: 'role',
                            className: 'text-center',
                            render: function(role) {
                                const color = role === 'admin' ? 'bg-blue-100 text-blue-700' :
                                    'bg-gray-100 text-gray-700';
                                return `<span class="px-2 py-1 rounded text-xs ${color}">${role}</span>`;
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data) {
                                return `
                        <button class="px-3 py-1 bg-yellow-500 text-white text-xs rounded-md hover:bg-yellow-600 transition btn-edit"
                            data-id="${data.id}" data-name="${data.name}" data-email="${data.email}" data-role="${data.role}">
                            Edit
                        </button>
                        <button class="px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 transition btn-delete"
                            data-id="${data.id}">
                            Hapus
                        </button>
                    `;
                            }
                        }
                    ]
                });

                $('#btnOpenAddModal').on('click', function() {
                    $('#userForm')[0].reset();
                    $('#user_id').val('');
                    $('#userForm').attr('data-mode', 'create');
                    $('#passwordContainer').show();
                    $('#modalTitle').text('Tambah User');
                    $('#userModal').removeClass('hidden');
                });

                $('#btnCloseUserModal').on('click', function() {
                    $('#userModal').addClass('hidden');
                });

                $(document).on('click', '.btn-edit', function() {
                    $('#userForm')[0].reset();

                    $('#user_id').val($(this).data('id'));
                    $('#name').val($(this).data('name'));
                    $('#email').val($(this).data('email'));
                    $('#role').val($(this).data('role'));
                    $('#passwordContainer').remove();

                    $('#userForm').attr('data-mode', 'edit');
                    $('#modalTitle').text('Edit User');
                    $('#userModal').removeClass('hidden');
                });

                $('#userForm').on('submit', function(e) {
                    e.preventDefault();

                    let mode = $(this).attr('data-mode');
                    let id = $('#user_id').val();
                    let url = (mode === 'edit') ? `/users/${id}` : `{{ route('users.store') }}`;

                    let data = $(this).serializeArray();

                    if (mode === 'edit') {
                        data.push({
                            name: '_method',
                            value: 'PUT'
                        }); 
                    }

                    $.ajax({
                        url: url,
                        method: 'POST', 
                        data: $.param(data), 
                        success: function(res) {
                            $('#userModal').addClass('hidden');
                            table.ajax.reload();
                            Swal.fire('Berhasil!', res.message, 'success');
                        },
                        error: function(xhr) {
                            let msg = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                            Swal.fire('Error', msg, 'error');
                            console.log(xhr);
                        }
                    });
                });

                $(document).on('click', '.btn-delete', function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Data user akan dihapus permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e3342f',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/users/${id}`,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    table.ajax.reload();
                                    Swal.fire('Berhasil!', res.message, 'success');
                                },
                                error: function() {
                                    Swal.fire('Gagal!',
                                        'Terjadi kesalahan saat menghapus user.',
                                        'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>
