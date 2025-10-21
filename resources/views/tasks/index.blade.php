<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Management') }}
            </h2>

            <button id="btnOpenAddModal"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow hover:bg-blue-700 transition">
                + Tambah Task
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-300 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table id="taskTable"
                        class="min-w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                        <thead class="bg-gray-100 text-gray-900 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-4 py-3 border-b text-center">No</th>
                                <th class="px-4 py-3 border-b">Judul</th>
                                <th class="px-4 py-3 border-b">Deskripsi</th>
                                <th class="px-4 py-3 border-b">Status</th>
                                <th class="px-4 py-3 border-b">User</th>
                                <th class="px-4 py-3 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('tasks.modals.tasksModal')

    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#taskTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('tasks.index') }}',
                    order: [
                        [1, 'desc']
                    ],
                    columns: [{
                            data: null,
                            name: 'no',
                            className: 'text-center',
                            orderable: true,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data) {
                                let color = {
                                    'to-do': 'bg-gray-300 text-gray-800',
                                    'in-progress': 'bg-blue-200 text-blue-800',
                                    'done': 'bg-green-200 text-green-800'
                                } [data] || 'bg-gray-100';
                                return `<span class="px-2 py-1 rounded text-xs ${color}">${data.replace('-', ' ')}</span>`;
                            }
                        },
                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'id',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(id, type, row) {
                                if (row.can_edit) {
                                    return `
                            <button type="button"
                                class="px-3 py-1 bg-yellow-500 text-white text-xs rounded-md hover:bg-yellow-600 transition btn-edit"
                                data-id="${row.id}"
                                data-title="${row.title}"
                                data-description="${row.description ?? ''}"
                                data-status="${row.status}"
                                data-user_id="${row.user_id}">
                                Edit
                            </button>
                            <button class="px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 transition deleteTask"
                                data-id="${id}">
                                Delete
                            </button>`;
                                }
                                return `<span class="text-gray-400 italic">No Access</span>`;
                            }
                        }
                    ]
                });

                $('#btnOpenAddModal').on('click', function() {
                    $('#taskForm')[0].reset();
                    $('#task_id').val('');
                    $('#taskForm').attr('data-mode', 'create');
                    $('#modalTitle').text('Tambah Task');
                    $('#taskModal').removeClass('hidden');
                });

                $(document).on('click', '.btn-edit', function() {
                    $('#taskForm')[0].reset();

                    $('#task_id').val($(this).data('id'));
                    $('#title').val($(this).data('title'));
                    $('#description').val($(this).data('description'));
                    $('#status').val($(this).data('status'));
                    $('#user_id').val($(this).data('user_id'));

                    $('#taskForm').attr('data-mode', 'edit');
                    $('#modalTitle').text('Edit Task');
                    $('#taskModal').removeClass('hidden');
                });

                $('#btnCloseModal').on('click', function() {
                    $('#taskModal').addClass('hidden');
                });

                $('#taskForm').on('submit', function(e) {
                    e.preventDefault();
                    let mode = $(this).attr('data-mode');
                    let id = $('#task_id').val();
                    let url = (mode === 'edit') ? `/tasks/${id}` : `{{ route('tasks.store') }}`;
                    let method = (mode === 'edit') ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        method: method,
                        data: $(this).serialize(),
                        success: function(res) {
                            $('#taskModal').addClass('hidden');
                            table.ajax.reload(null, false);
                            Swal.fire('Berhasil!', res.message, 'success');
                        },
                        error: function(xhr) {
                            let msg = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                });

                $(document).on('click', '.deleteTask', function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Task ini akan dihapus permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/tasks/${id}`,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    table.ajax.reload(null, false);
                                    Swal.fire('Berhasil!', res.message, 'success');
                                },
                                error: function() {
                                    Swal.fire('Error', 'Gagal menghapus task.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>
