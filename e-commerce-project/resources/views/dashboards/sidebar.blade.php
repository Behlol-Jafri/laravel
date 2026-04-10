<aside class="">
    <ul class="list-unstyled p-3">
        @if (!Auth::user()->hasRole('User'))
        @can('view user')
            <li>
             <a href="{{ route('users') }}" class="text-decoration-none text-black">
                <span><i class="fas fa-users me-2"></i>Users</span>
            </a>
        </li>
        @endcan
        @endif
        @can('view post')
        <li>
             <a href="{{ route('posts.index') }}" class="text-decoration-none text-black">
                <span><i class="fas fa-tags me-2"></i>Posts</span>
            </a>
        </li>
        @endcan
        @can('view category')
        <li>
             <a href="{{ route('category.index') }}" class="text-decoration-none text-black">
                <span><i class="fas fa-tags me-2"></i>Categories</span>
            </a>
        </li>
        @endcan
        @can('view product')
        <li>
             <a href="{{ route('product.index') }}" class="text-decoration-none text-black">
                <span><i class="fas fa-tags me-2"></i>Products</span>
            </a>
        </li>
        @endcan
        @if (!Auth::user()->hasRole('User'))
        @canany(['view role','view user'])
        <li>
            <a href="{{ route('manage.access') }}" class="text-decoration-none text-black">
                <span><i class="fas fa-key me-2"></i>Manage Access</span>
            </a>
        </li>
        @endcanany
        @endif
    </ul>
</aside>