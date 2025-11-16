<div class="container m-0 p-0">
         <div class="dt-container m-0 p-0">
            <table id="Student" class="table table-modern"
                data-dt
                data-title="Student List"
                data-index-url="{{ url('api/v1/students') }}"
                data-create-url="{{ url('admin/students/create') }}"
                data-edit-url="{{ route('admin.students.edit', ':id') }}"
                data-delete-url="{{ url('api/v1/students/destroy') }}/:id"
                data-show-url="{{ route('admin.students.show', ':id') }}"
                data-fields='["name", "roll", "is_active"]'
                data-headers='["Name", "Roll", "Active Status"]'
                data-export="true"
                data-colvis="true"
                data-csv="true"
                data-excel="true"
                data-pdf="true"
                data-print="true">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            
            dtHelpers.registerRenderer('Student', 'is_active', function(data, row) { 
                if(data == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            });

        });
    </script>