@extends('dashboards.manageAccess')


@section('permission-data')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Role : {{ $role->name }} 
                            <a href="{{ route('roles.index') }}" class="btn btn-danger float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('roles.give-permission',$role->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="" class="form-label">Permissions : </label>
                                @error('permission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="row">
                                    @foreach ($groupedPermissions as $module => $permissions)
                                        <div class="mb-2 col-3">

                                            <!-- Parent -->
                                            <label>
                                                <!-- Checkbox (ONLY for checking) -->
                                                <input type="checkbox" class="parent-checkbox" data-module="{{ $module }}">
                                                
                                                <!-- Text (ONLY for toggle) -->
                                                <span class="toggle-group" data-module="{{ $module }}" style="cursor:pointer;">
                                                    <strong>{{ ucfirst($module) }}</strong>
                                                </span>
                                            </label>

                                            <!-- Children -->
                                            <div id="group-{{ $module }}" style="display:none; margin-left:20px;">
                                                
                                                @foreach ($permissions as $permission)
                                                    <div>
                                                        <label>
                                                            <input 
                                                                type="checkbox" 
                                                                name="permission[]" 
                                                                value="{{ $permission->name }}"
                                                                class="child-checkbox child-{{ $module }}"
                                                                data-module="{{ $module }}"
                                                                {{ in_array($permission->id , $rolePermissions) ? 'checked' : '' }}
                                                            >
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach

                                            </div>

                                        </div>

                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


   <script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ Function: update parent state
    function updateParent(module) {
        let children = document.querySelectorAll('.child-' + module);
        let parent = document.querySelector('.parent-checkbox[data-module="' + module + '"]');

        if (!parent || children.length === 0) return;

        let total = children.length;
        let checked = Array.from(children).filter(c => c.checked).length;

        if (checked === total) {
            parent.checked = true;
        } else {
            parent.checked = false;
        }
    }

    // ✅ INIT: set parent state + auto open if needed
    document.querySelectorAll('.parent-checkbox').forEach(parent => {
        let module = parent.dataset.module;
        let children = document.querySelectorAll('.child-' + module);

        updateParent(module);
    });

    // ✅ Parent checkbox click
    document.querySelectorAll('.parent-checkbox').forEach(parent => {
        parent.addEventListener('change', function () {
            let module = this.dataset.module;
            let children = document.querySelectorAll('.child-' + module);

            children.forEach(child => {
                if (!child.disabled) {
                    child.checked = this.checked;
                }
            });

            updateParent(module);
        });
    });

    // ✅ Child change
    document.querySelectorAll('.child-checkbox').forEach(child => {
        child.addEventListener('change', function () {
            let module = this.dataset.module || this.className.split('child-')[1];
            updateParent(module);
        });
    });

    // ✅ Toggle ONLY UI
    document.querySelectorAll('.toggle-group').forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();

            let module = this.dataset.module;
            let group = document.getElementById('group-' + module);

            group.style.display = group.style.display === 'none' ? 'block' : 'none';
        });
    });

});
</script>
@endsection