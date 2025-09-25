<style>
   .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin: 10px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: sans-serif;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .alert-danger {
    background-color: #ffebee;
    color: #c62828;
    border-right: 4px solid #c62828;
    }

    .alert-success {
    background-color: #e8f5e9;
    color: #2e7d32;
    border-right: 4px solid #2e7d32;
    }

    .close-btn {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: inherit;
    margin-left: 15px;
    }
</style>

@if(isset($errors))
@error('*')
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@enderror
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

