@props(['fout'])

@if (session('status'))
<div role="status" class="mb-6 rounded-lg border border-turquoise/30 bg-turquoise/10 px-4 py-3 text-sm font-medium text-deep-blue">
    {{ session('status') }}
</div>
@endif

@if ($errors->any())
<div role="alert" class="mb-6 rounded-lg border border-coral/30 bg-coral/10 px-4 py-3 text-sm font-medium text-deep-blue">
    {{ $fout }}
</div>
@endif
