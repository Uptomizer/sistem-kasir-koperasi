@if (session('success'))
<div id="toast-success"
     class="fixed top-5 right-5 bg-green-600 text-white px-4 py-3 rounded shadow z-50">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div id="toast-error"
     class="fixed top-5 right-5 bg-red-600 text-white px-4 py-3 rounded shadow z-50">
    {{ $errors->first() }}
</div>
@endif

<script>
setTimeout(() => {
    const success = document.getElementById('toast-success')
    const error = document.getElementById('toast-error')
    if (success) success.remove()
    if (error) error.remove()
}, 3500)
</script>
